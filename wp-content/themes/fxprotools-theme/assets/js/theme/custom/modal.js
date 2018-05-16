var Modal = function(){
	var ajax_url = fx;
	function isJson(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	function ajaxGetWebinar(){
		var ajaxCall = $.ajax({
		  method: "GET",
		  url: ajax_url.ajax_url,
		  data: { 'action': 'get_webinars' }
		});
		return ajaxCall;
	}
	function ajaxRegisterWebinar(body){
		var ajaxCall = $.ajax({
		  method: "POST",
		  url: ajax_url.ajax_url,
		  data: { 'action': 'register_webinars', 'post_data' : body }
		});
		return ajaxCall;
	}
	return {
		init:function(){
			$('.webinar-modal-lg').on('shown.bs.modal', function (e) {
				$('.ajax-webinars').html('<p>Getting Webinars, please wait...</p>');
				$('.webinar-register-now').hide();
				ajaxGetWebinar().done(function(data){
					
					if( isJson(data) ){
						data = jQuery.parseJSON(data);
						if( data.status == 'no-webinar' ){
							$('.ajax-webinars').html('<p>' + data.msg + '</p>');
						}
						//console.log(data);
					}else{
						//console.log(data);
						$('.ajax-webinars').html(data);
					}
					
					//$('.webinar-register-now').show();
				});
			})
			$('.webinar-modal-lg').on('hidden.bs.modal', function (e) {
				$('.ajax-webinars').html('');
			})
			$('.reserve-your-seat').click(function(e){
				e.preventDefault();
				$('.webinar-modal-lg').modal('show');	
			});
		},
		registerNow:function(){
			//$('.webinar-register-now').on('click', function(e){
			$(document).on('click', '.webinar-register-now', function(e){
				e.preventDefault();
				$('.ajax-webinar-lists').hide();
				$('.ajax-webinars-msg').html('<p>Registering to webinar please wait</p>');
				var post_data = $(".register-webinar").serialize();
				ajaxRegisterWebinar(post_data).done(function(data){
					//console.log(data);
					$('.ajax-webinars-msg').html('');
					if( isJson(data) ){
						data = jQuery.parseJSON(data);
						if( data.status == 'error' ){
							console.log(data);
							$.each( data.msg, function( key, value ) {
								$('.ajax-webinars-msg').append('<p>'+value+'</p>');
							});
						}else{
							console.log(data);
							$('.ajax-webinars-msg').html('<p>'+data.msg+'</p>');
							$.each( data.webinar_ret, function( key, value ) {
								$('.' + key + '-info').html('');
								$('.' + key + '-info').html('<p>'+value.msg+'</p>');
							});
						}
					}
					$('.ajax-webinar-lists').show();
				});
			});
		}
	};
}();

var ModalRegisterNow = function(){
	
}();
jQuery(document).ready( function($) {
	Modal.init();
	Modal.registerNow();
});