$(function(){
 
var syncTaskPointer = null;
var requestsQueue = [];
var requestResolveCallbackQueue = [];
var successes = 0;
var failures 	= 0;
var total_count	= 0;
var i	= 0;
/*
 * --------------------------------------------------------------
 * On click event of the access api button ,call remote url
 * and get the data
 * --------------------------------------------------------------
*/
  var timer;
  $('.access-api').click(function (event){
    event.preventDefault();
    var users = _get_remote_users();
  });




/*
 * -------------------------------------------------------------
 * Get the remote users from the API url callback
 * -------------------------------------------------------------
*/
 function _get_remote_users() {
  var timer;
  var users =  $.ajax({
      type :'POST',
      data : {
        action:'api_embedd_remote_user_access',
      },

      url:ajax_object.ajaxurl,
      beforeSend:function(){
        timer = window.setInterval(progressBarIncrement, 1000);
      },
      success: function(data) {
        fetch_data  = jQuery.parseJSON(data);
        total_count = data.count;

       
        window.clearInterval(timer);
        $("#progress").html('<div class="progress-bar" role="progressbar"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>');
        $('.progress-bar').css('transition-duration','300ms');
        $('.progress-bar').css( 'width' ,'100%');
        $('#message').html('Processing');
        _upload_details_to_processing_queue(data);
        $('#message').html('Completed');
         // setTimeout(function() { window.location.reload(true); }, 500 );

      }
    });
 }
/*
 * -------------------------------------------------------------
 * Upload the details to the processing queue
 * -------------------------------------------------------------
*/
 function _upload_details_to_processing_queue (data) {
 	data = jQuery.parseJSON(data);
 	total_count = data.count;

 	var i = 0;
 	$.each(jQuery.parseJSON(data.users), function(index, value){
 		i++;

		$.ajax({
      type :'POST',
      async:false,
      data : {
        action:'api_upload_users_to_queue',
        data:value,
        id:index
      },

      url:ajax_object.ajaxurl,
      beforeSend:function(){
        timer = window.setInterval(progressBarIncrement, 1000);
      },
      success: function(data) {
       percent = parseInt((i/total_count)*100);
       $("#progress").html('<div class="progress-bar" role="progressbar"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>');
       $('.progress-bar').css('transition-duration','300ms');
       $('.progress-bar').css( 'width' ,percent+'%');


      }
    });

   
  });
 }
/*
 * -------------------------------------------------------------
 * Get the users from the json
 * Loop through the users
 * Create user to the 
 * -------------------------------------------------------------
*/
function _embedd_remote_user_to_current_system (data) {
  data = jQuery.parseJSON(data);
  total_count = data.count;

  i = 0;
  $("#progress").html('<div class="progress-bar" role="progressbar"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>');
  $('.progress-bar').css('transition-duration','300ms');
  $('#message').html('Importing users...........');
  
  var flag      = 0;
  var successes = 0;
  var failures  = 0;

  $.each(jQuery.parseJSON(data.users), function(index, value){
    flag = 1;
    ajaxSync(value);
    i++;
    
  });
}




function nativeAjax (value) {

	// console.log(total_count);
	// console.log(i);
	//this is  actual ajax function 
	//which will return a promise
	
	//after finishing the ajax call you call the .next() function of syncRunner
	//you need to put it in the suceess callback or in the .then of the promise
var data = $.ajax({
      type :'POST',
     	// async:false,
      data : {
        action:'api_embedd_remote_user_to_system',
        data:value,
      },
      url:ajax_object.ajaxurl,
      success:function(responseData){
      	if ( responseData == 1 ) 
	      	successes = successes + 1;
	    	else
	      	failures  = failures + 1;

	    	percent = parseInt((i/total_count)*100);
	    		setTimeout(function(){
				   	$('#message').html('Creating '+i+'/'+total_count);
				   	// $('.progress-bar').css( 'transition' ,'all 1000ms ease-out;');
				   	$('.progress-bar').css({
				   				width : percent+'%',
				   	});
			   	});
	    	

	      if(requestResolveCallbackQueue){
	       (requestResolveCallbackQueue.shift())(responseData);
	      }
	      syncTaskPointer.next();
      }
    })
}

	function* syncRunner(){
		while(requestsQueue.length>0){
			yield nativeAjax(requestsQueue.shift());	

		}

		//set the pointer to null
		syncTaskPointer = null;
		console.log("all resolved");
	};

	ajaxSync = function (requestObj) {
		requestsQueue.push(requestObj);
		if(!syncTaskPointer){
			syncTaskPointer = syncRunner();
			syncTaskPointer.next();
		}
		return new Promise(function (resolve, reject) {
			var responseFlagFunc = function (data) {
				resolve(data);
			}
			requestResolveCallbackQueue.push(responseFlagFunc);
        // console.log(requestResolveCallbackQueue);

		});
	}
});