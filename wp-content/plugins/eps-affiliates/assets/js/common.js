jQuery(document).ready(function(){
  $.getScript( "/wp-content/plugins/eps-affiliates/assets/plugins/jquery-ui/jquery-ui.min.js" )
    .done(function( script, textStatus ) {
      console.log( textStatus );
      jQuery( ".date_time_picker" ).datepicker();
      jQuery('[data-toggle="tooltip"]').tooltip();   
    })
    .fail(function( jqxhr, settings, exception ) {
      $( "div.log" ).text( "Triggered ajaxError handler." );
  });

    $.getScript( "/wp-content/plugins/eps-affiliates/assets/js/bootstrap-typeahead.js" )
    .done(function( script, textStatus ) {
      console.log( textStatus );
    })
    .fail(function( jqxhr, settings, exception ) {
      $( "div.log" ).text( "Triggered ajaxError handler." );
  });
  jQuery('body').addClass('eps');
});

jQuery(function () {
     
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "rtl": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": 300,
      "hideDuration": 2000,
      "timeOut": 5000,
      "extendedTimeOut": 1000,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  
    jQuery('.navbar-toggle').click(function () {
        jQuery('.navbar-nav').toggleClass('slide-in');
        jQuery('.side-body').toggleClass('body-slide-in');
        jQuery('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //jQuery('.absolute-wrapper').toggleClass('slide-in');
        
    });
    
     jQuery('.auto_complete').on('keyup click',function(){
  		var autoArray = [];
    	var path 			 = jQuery(this).attr('data-path');
    	var search_key = jQuery(this).val();
    	if (path != '#' && search_key!=undefined) {
    		jQuery.ajax({
			   	type :'POST',
			   	data : {
			   		action:path,
            tree_mode : jQuery('#tree-mode').val(),
			   	},
			   	url:ajax_object.ajaxurl,
			   	success: function(data){
							var arr = JSON.parse(data);
							var i 	= 0;
							var data_array = [];
							jQuery('.auto_complete').typeahead({
                source: arr,
              });
			   	}
			  });
    	}
    });
/*
 * -------------------------------------------
* Data tables for user downlines
 * -------------------------------------------
*/
  if (jQuery('.custom-data-tables').length) {

      var table; 
      table = jQuery(".custom-data-tables").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_downlines_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3], 
          "orderable": false, 
        }], 
      }); 
  }
/*
 * -------------------------------------------
* Data tables for user refred members
 * -------------------------------------------
*/
  if (jQuery('.refered-members').length) {

      var table; 
      table = jQuery(".refered-members").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_refered_downlines_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for ewallet summary
* -------------------------------------------
*/
if (jQuery('.custom-ewallet-summary-table').length) {
      var table; 
      table = jQuery(".custom-ewallet-summary-table").DataTable({
      "bFilter" : false, 
      "bInfo": false,
      "searching": false,
      "paging": false,
      "pageLength": 50,
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_summary_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }

/*
* -------------------------------------------
* Data tables for ewallet transaction
* -------------------------------------------
*/
if (jQuery('.custom-ewallet-all-trans-table').length) {
      var table; 
      table = jQuery(".custom-ewallet-all-trans-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_all_transaction_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for ewallet Income report 
* -------------------------------------------
*/
if (jQuery('.custom-ewallet-income-table').length) {
      var table; 
      table = jQuery(".custom-ewallet-income-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_income_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for ewallet Expense report 
* -------------------------------------------
*/
if (jQuery('.custom-ewallet-expense-table').length) {
      var table; 
      table = jQuery(".custom-ewallet-expense-table").DataTable({
       "processing": true, 
       "serverSide": true,
       "pageLength": 50, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_expense_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }

/*
* -------------------------------------------
* Data tables for business transaction summary  
* ------------------------------------------- 
*/
if (jQuery('.custom-business-summary-table').length) {
      var table; 
      table = jQuery(".custom-business-summary-table").DataTable({
      "bFilter" : false, 
      "bInfo": false,
      "searching": false,
      "paging": false,
      "pageLength": 50,
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_summary_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }
  /*
* -------------------------------------------
* Data tables for business All transaction
* -------------------------------------------
*/
if (jQuery('.custom-business-all-trans-table').length) {
      var table; 
      table = jQuery(".custom-business-all-trans-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_all_transaction_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }


/*
* -------------------------------------------
* Data tables for business income report
* -------------------------------------------
*/
if (jQuery('.custom-business-income-history-table').length) {
      var table; 
      table = jQuery(".custom-business-income-history-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_income_history_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for business expense report
* -------------------------------------------
*/
if (jQuery('.custom-business-expense-history-table').length) {
      var table; 
      table = jQuery(".custom-business-expense-history-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "pageLength": 50,
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_expense_history_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }
/*
  * -------------------------------------------
  * On click Holding tank user
  * -------------------------------------------
*/
   jQuery('.holding-tank-profiles li').click(function(){
    jQuery('#seleted-user-id').val(jQuery(this).attr('data-user-id'));
    jQuery('.progress').css('width','0px');
    jQuery('#holding-tank-change-model').modal('show');
   });

   jQuery('#place-user').click(function() {
    if (jQuery('#choose-parent').val() == '') {
      jQuery('.notification').html('please choose the parent');
      jQuery('.notification').css('color', 'red');
    } else {
      //load the availbale free spaces
         var parent     = jQuery('#choose-parent').val();
         var sponsor    = jQuery('#current-user-id').val();
         var user_id    = jQuery('#seleted-user-id').val();
         var tree_mode  = jQuery('#tree-mode').val();

         if (user_id != '' && sponsor!= '' && parent!='' ){
          var position = jQuery('input[name="free_space"]:checked').attr('id');
          if (position) {
            jQuery.ajax({
              type :'POST',
              data : {
                action:'afl_place_user_from_tank',
                user_id:user_id,
                sponsor:sponsor,
                parent:parent,
                position :position, 
                tree_mode :tree_mode, 
              },
              url:ajax_object.ajaxurl,
              beforeSend:function(){
                  for(var i = 1; i <=100 ; i++){
                    jQuery('.progress').css('width',i+'%');
                  }
              },
              complete:function(){
                  jQuery('.progress').css('width','100%');

              },
              success: function(data){
                var data = JSON.parse(data);
                jQuery('.progress').css('width','100%');
                if (data['status'] == 1) {
                  jQuery('.notification').html('Member Placed successfully');
                   setTimeout(function() { window.location.reload(true); }, 500 );
                }
              }
            });
          } else {
            jQuery('.notification').html('Unable to select a position.You cannot place a member without the position.');
            jQuery('.notification').css('color', 'red');
          }
          
         }
    }
   });

  jQuery('#choose-parent').change(function(){
    if (jQuery('#choose-parent').val() !=''){
      jQuery.ajax({
        type :'POST',
        data : {
          action:'afl_get_available_free_space',
          sponsor : jQuery('#current-user-id').val(),
          uid     : jQuery('#seleted-user-id').val(),
          parent  : jQuery('#choose-parent').val(),
          tree_mode : jQuery('#tree-mode').val(),

        },
        url:ajax_object.ajaxurl,
        success: function(data){
            jQuery('#available-free-spaces').html(data);
        }
      });
    }
  });

  jQuery('div.pricingTable').on('click', function(){
    jQuery(this).parent().parent().find('div.pricingTable').removeClass('selected');
    jQuery(this).addClass('selected');
    jQuery(this).find('input[type="radio"]').prop("checked", true);
    
  });
/*
 * -------------------------------------------------------------
 * On clicking the auto placement button
 * get the uid
 * user automatically place under a user
 * -------------------------------------------------------------
*/  
 jQuery('#auto-place-user').click(function (){
    var sponsor = jQuery('#current-user-id').val();
    var uid     = jQuery('#seleted-user-id').val();
    var choose_sponsor  = jQuery('#choose-parent').val();
    
    if ( choose_sponsor != 0 && choose_sponsor ) {
      sponsor = choose_sponsor.match(/\((\d+)\)/)[1];
    }
    
    jQuery.ajax({
      type :'POST',
      data : {
        action:'afl_auto_place_user_ajax',
        sponsor : sponsor,
        uid     : jQuery('#seleted-user-id').val(),
        tree_mode : jQuery('#tree-mode').val(),
      },
      url:ajax_object.ajaxurl,
      beforeSend:function(){
          for(var i = 1; i <=100 ; i++){
            jQuery('.progress').css('width',i+'%');
          }
      },
      complete:function(){
        jQuery('.progress').css('width','100%');

      },
      success: function(data){
        jQuery('.progress').css('width','100%');
        jQuery('.notification').html('Completed');

        setTimeout(function() { window.location.reload(true); }, 500 );
      }
    });

 });


//document ends here
});
  
/*
 * -------------------------------------------------------------
 * Expand genealogy tree on click  expense
 * -------------------------------------------------------------
*/
function expandMatrixTree(obj) {
  jQuery(obj).find('i').toggleClass('fa-times-circle fa-plus-circle');
    var jQueryuid = jQuery(obj).attr('data-user-id');

    if(jQuery(obj).find('i').hasClass('fa-plus-circle')){
      jQuery('.append-child-'+jQueryuid).html('');
      jQuery(obj).parent().parent().removeClass('hv-item-parent');

    } else{
      jQuery(obj).parent().parent().addClass('hv-item-parent');

      if (jQueryuid != undefined) {
        jQuery.ajax({
          type :'POST',
          data : {
            action:'afl_user_expand_genealogy',
            uid:jQueryuid,
          },
          url:ajax_object.ajaxurl,
          success: function(data){
            if (data.length) {
              jQuery(data).hide().appendTo('.append-child-'+jQueryuid).fadeIn(1000);
              // jQuery('.append-child-'+jQueryuid).append(data).fadeIn('slow');
            }
          }
        });
      }
    }
}
/*
 * -------------------------------------------------------------
 * Expand genealogy tree on click  expense
 * -------------------------------------------------------------
*/
function expandUnilevelTree(obj) {
  jQuery(obj).find('i').toggleClass('fa-times-circle fa-plus-circle');
    var jQueryuid = jQuery(obj).attr('data-user-id');

    if(jQuery(obj).find('i').hasClass('fa-plus-circle')){
      jQuery('.append-child-'+jQueryuid).html('');
      jQuery(obj).parent().parent().removeClass('hv-item-parent');

    } else{
      jQuery(obj).parent().parent().addClass('hv-item-parent');

      if (jQueryuid != undefined) {
        jQuery.ajax({
          type :'POST',
          data : {
            action:'afl_unilevel_user_expand_genealogy',
            uid:jQueryuid,
          },
          url:ajax_object.ajaxurl,
          success: function(data){
            if (data.length) {
              jQuery(data).hide().appendTo('.append-child-'+jQueryuid).fadeIn(1000);
              // jQuery('.append-child-'+jQueryuid).append(data).fadeIn('slow');
            }
          }
        });
      }
    }
}


/*
 * -------------------------------------------------------------
 * Expand genealogy tree on click  expense
 * -------------------------------------------------------------
*/
  function expandToggleMatrixTree(obj) {
    jQuery(obj).find('i').toggleClass('fa-times-circle fa-plus-circle');
      var jQueryuid = jQuery(obj).attr('data-user-id');

      if(jQuery(obj).find('i').hasClass('fa-plus-circle')){
        jQuery('.append-child-'+jQueryuid).html('');
        jQuery(obj).parent().parent().removeClass('hv-item-parent');

      } else{
        jQuery(obj).parent().parent().addClass('hv-item-parent');

        if (jQueryuid != undefined) {
          jQuery.ajax({
            type :'POST',
            data : {
              action:'afl_user_expand_toggle_genealogy',
              uid:jQueryuid,
            },
            url:ajax_object.ajaxurl,
            success: function(data){
              if (data.length) {
                jQuery(data).hide().appendTo('.append-child-'+jQueryuid).fadeIn(1000);
                // jQuery('.append-child-'+jQueryuid).append(data).fadeIn('slow');
              }
            }
          });
        }
      }
  }
/*
 * -------------------------------------------------------------
 * Expand unilevel genealogy tree on click  expense
 * -------------------------------------------------------------
*/
  function expandToggleUnilevelTree(obj) {
    jQuery(obj).find('i').toggleClass('fa-times-circle fa-plus-circle');
      var jQueryuid = jQuery(obj).attr('data-user-id');

      if(jQuery(obj).find('i').hasClass('fa-plus-circle')){
        jQuery('.append-child-'+jQueryuid).html('');
        jQuery(obj).parent().parent().removeClass('hv-item-parent');

      } else{
        jQuery(obj).parent().parent().addClass('hv-item-parent');

        if (jQueryuid != undefined) {
          jQuery.ajax({
            type :'POST',
            data : {
              action:'afl_unilevel_user_expand_toggle_genealogy',
              uid:jQueryuid,
            },
            url:ajax_object.ajaxurl,
            success: function(data){
              if (data.length) {
                jQuery(data).hide().appendTo('.append-child-'+jQueryuid).fadeIn(1000);
                // jQuery('.append-child-'+jQueryuid).append(data).fadeIn('slow');
              }
            }
          });
        }
      }
  }
/*
 * ----------------------------------------------------------------
 * Add error class
 * ----------------------------------------------------------------
*/

 function inform_error (id = '') {
  jQuery('#'+id).addClass('required error');
  jQuery('#'+id).parent('div').addClass('has-error');
 }

/*
 * -------------------------------------------------------------
 * Increment the progress bar
 * -------------------------------------------------------------
*/
  function progressBarIncrement () {
    var width   = jQuery('.progress-bar').css('width');
    parentWidth = jQuery('.progress-bar').offsetParent().width(),
    
    
    percent = Math.round(100 * parseInt(width) / parseInt(parentWidth));
    percent = percent + 4;
     if ( width == undefined)
      percent = 1;
    
    if (percent <= 98) {
       jQuery('#message').html('authenticating API....');
       jQuery("#progress").html('<div class="progress-bar" role="progressbar"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>');
       jQuery('.progress-bar').css('transition-duration','300ms');
       jQuery('.progress-bar').css( 'width' ,percent+'%');
    }
  }
/*
 * -------------------------------------------------------------
 * Toggle holding tank node to left
 * -------------------------------------------------------------
*/
  function _toggle_holding_node_left(object){
    console.log('sss');
    var toggle_left_id = jQuery(object).attr('data-toggle-uid');
    var sponsor        = jQuery('#sponsor').val(); 
    var tree           = jQuery('#tree').val();
    jQuery.ajax({
      type :'POST',
      data : {
        action:'afl_user_holding_genealogy_toggle_left',
        uid:toggle_left_id,
        sponsor:sponsor,
        tree:tree

      },
      url:ajax_object.ajaxurl,
      success: function(data){
         data = JSON.parse(data);
        if (data!=null) {
         jQuery('.toggle-save-placement-button').attr('data-toggle-uid',data.uid)
         html_tag = _theme_toggle_holding_genealogy_user(data);
         jQuery(object).parent('.toggle-user-placement-toggle-area').html(html_tag);
        }
      }
    });
  }
/*
 * -------------------------------------------------------------
 * Toggle holding tank node to right
 * -------------------------------------------------------------
*/
  function _toggle_holding_node_right(object){
    var toggle_right_id = jQuery(object).attr('data-toggle-uid');
    var sponsor         = jQuery('#sponsor').val(); 
    var tree            = jQuery('#tree').val();
    
      jQuery.ajax({
        type :'POST',
        data : {
          action:'afl_user_holding_genealogy_toggle_right',
          uid:toggle_right_id,
          sponsor:sponsor,
          tree:tree

        },
        url:ajax_object.ajaxurl,
        success: function(data){
            data = JSON.parse(data);
          if (data!=null) {
            jQuery('.toggle-save-placement-button').attr('data-toggle-uid',data.uid)
            html_tag = _theme_toggle_holding_genealogy_user(data);
            jQuery(object).parent('.toggle-user-placement-toggle-area').html(html_tag);
          }
        }
      });
  }
/*
 * -------------------------------------------------------------
 * Place user toggel genealogy selected position
 * -------------------------------------------------------------
*/
  function _toggle_holding_node_place(object){
    var place_holding_uid       = jQuery(object).attr('data-toggle-uid');
    var place_holding_parent    = jQuery(object).attr('data-toggle-parent');
    var place_holding_position  = jQuery(object).attr('data-toggle-position');
    var tree_mode               = jQuery('#tree').val(); 
    var sponsor                 = jQuery('#sponsor').val(); 
    if ( place_holding_uid == 0) {
      alert('Please choose a holding user.');
      return false;
    }
    jQuery.confirm({
      title: 'Confirm',
      content: 'Really you want to place the holding user to this parent?',
      icon: 'fa fa-question-circle',
      animation: 'scale',
      closeAnimation: 'scale',
      opacity: 0.5,
      buttons: {
        'confirm': {
            text: 'Proceed',
            btnClass: 'btn-blue',
            action: function () {
              jQuery.ajax({
                type :'POST',
                data : {
                  action:'afl_place_user_from_tank',
                  user_id:place_holding_uid,
                  sponsor:sponsor,
                  parent:place_holding_parent,
                  position :place_holding_position, 
                  tree_mode :tree_mode, 
                },
                url:ajax_object.ajaxurl,
                success: function(data){
                  var data = JSON.parse(data);
                  if (data['status'] == 1) {
                    jQuery.alert('Success. the member has been placed successfully.');
                  }
                   setTimeout(function() { window.location.reload(true); }, 500 );
                }
              });
            }
        },
        cancel: function () {
        },
      }
    });

  }

/* -------------------------------------------------------------------------------------- */
/*
 * ------------------------------------------------------------
 * Theme 
 * ------------------------------------------------------------
*/
  function _theme_toggle_holding_genealogy_user (json_data) {
      var html_tag = '';
      html_tag += '<span class="toggle-left-arrow" data-toggle-uid="'+json_data.uid+'" onclick="_toggle_holding_node_left(this)">';
      html_tag += '<i class="fa fa-caret-left fa-5x"></i>';
      html_tag += '</span>';
      html_tag += '<div class="holding-toggle-user-image">';
      html_tag += '<img src="'+json_data.image_url+'">';
      html_tag += '</div>';
      html_tag += '<span class="toggle-right-arrow" data-toggle-uid="'+json_data.uid+'" onclick="_toggle_holding_node_right(this)">';
      html_tag += '<i class="fa fa-caret-right fa-5x"></i>';
      html_tag += '</span>';
      html_tag += '<p>';
      html_tag += json_data.user_login;
      html_tag += '</p>';
      html_tag += '</div>';
      return html_tag;
  }
/* -------------------------------------------------------------------------------------- */