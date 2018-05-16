jQuery(document).ready(function() {
	/*
 	 * --------------------------------------------------
	 * Downline members
 	 * --------------------------------------------------
	*/
	 if (jQuery('#afl-widgets-afl-downline-members-panel').length) {
	 	eps_dashboard_panel_grid(
	 		'#afl-widgets-afl-downline-members-panel',
	 		'afl_user_downlines_count'
	 	);
	 }

	/*
 	 * --------------------------------------------------
	 * E-wallet
 	 * --------------------------------------------------
	*/
	 if (jQuery('#afl-widgets-afl-e-wallet').length) {
	 	eps_dashboard_panel_grid(
	 		'#afl-widgets-afl-e-wallet',
	 		'afl_user_e_wallet'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * income and Credits
 	 * --------------------------------------------------
	*/
	 if (jQuery('#afl-widgets-afl-total-credits').length) {
	 	eps_dashboard_panel_grid(
	 		'#afl-widgets-afl-total-credits',
	 		'afl_user_total_credits'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * Expense and Debits
 	 * --------------------------------------------------
	*/
	 if (jQuery('#afl-widgets-afl-total-debits').length) {
	 	eps_dashboard_panel_grid(
	 		'#afl-widgets-afl-total-debits',
	 		'afl_user_total_debits'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * e-wallet sum
 	 * --------------------------------------------------
	*/
	 if (jQuery('#afl-widgets-afl-ewallet-sum-panel').length) {
	 	eps_dashboard_panel_grid_full_width(
	 		'#afl-widgets-afl-ewallet-sum-panel',
	 		'afl_user_e_wallet_sum'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * B-wallet income
 	 * --------------------------------------------------
	*/
	 if (jQuery('#block-afl-widgets-afl-bwallet-income').length) {
	 	eps_business_panel_blocks(
	 		'#block-afl-widgets-afl-bwallet-income',
	 		'afl_b_wallet_income'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * B-wallet expense
 	 * --------------------------------------------------
	*/
	 if (jQuery('#block-afl-widgets-afl-bwallet-expenses').length) {
	 	eps_business_panel_blocks(
	 		'#block-afl-widgets-afl-bwallet-expenses',
	 		'afl_b_wallet_expense'
	 	);
	 }

	/*
 	 * --------------------------------------------------
	 * B-wallet balance
 	 * --------------------------------------------------
	*/
	 if (jQuery('#block-afl-widgets-afl-bwallet-balance').length) {
	 	eps_business_panel_blocks(
	 		'#block-afl-widgets-afl-bwallet-balance',
	 		'afl_b_wallet_balance'
	 	);
	 }

	/*
 	 * --------------------------------------------------
	 * User rank
 	 * --------------------------------------------------
	*/
	 if (jQuery('#block-afl-widgets-afl-member-rank').length) {
	 	eps_render_template(
	 		'#block-afl-widgets-afl-member-rank',
	 		'afl_member_rank'
	 	);
	 }

	/*
 	 * --------------------------------------------------
	 * E-wallet transactions chart
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-e-wallet-transaction-chart').length) {
	 	eps_high_charts(
	 		'#afl-widgets-afl-e-wallet-transaction-chart',
	 		'afl_e_wallet_transaction_chart'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * E-wallet summary
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-e-wallet-summary').length) {
	 	eps_render_template(
	 		'#afl-widgets-afl-e-wallet-summary',
	 		'afl_e_wallet_summary'
	 	);
	 }

	/*
 	 * --------------------------------------------------
	 * B-wallet transactions chart
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-b-wallet-transaction-chart').length) {
	 	eps_high_charts(
	 		'#afl-widgets-afl-b-wallet-transaction-chart',
	 		'afl_b_wallet_transactions_chart'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * B-wallet report
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-b-wallet-report').length) {
	 	eps_high_charts(
	 		'#afl-widgets-afl-b-wallet-report',
	 		'afl_b_wallet_report_chart'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * Downlines level users count
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-dashboard-level-user-counts').length) {
	 	eps_level_users_count(
	 		'#afl-widgets-afl-dashboard-level-user-counts',
	 		'afl_each_level_user_count'
	 	);
	 }
	 /*
 	 * --------------------------------------------------
	 * Downlines chart
 	 * --------------------------------------------------
	*/
		if (jQuery('#afl-widgets-afl-dashboard-downline-chart').length) {
	 	eps_high_charts(
	 		'#afl-widgets-afl-dashboard-downline-chart',
	 		'afl_downline_members_chart'
	 	);
	 }
	/*
 	 * --------------------------------------------------
	 * Other events
 	 * --------------------------------------------------
	*/
	 // eps_affiliates_dashboard_js_events();

});


/*
 * --------------------------------------------------
 * Here comes all the functions that needed to show
 * the widget content
 * --------------------------------------------------
*/
 function eps_dashboard_panel_grid (obj,ajax_url) {
 		jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(data){
	   		jsonDatas = JSON.parse(data);
	   		if (jsonDatas) {
					var html_tag = theme_panelGridAjax(jsonDatas);
					jQuery(obj).html(html_tag);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
 }
/*
 * --------------------------------------------------
 * Panel grid full width
 * --------------------------------------------------
*/
  function eps_dashboard_panel_grid_full_width (obj,ajax_url) {
  	jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(data){
	   		jsonDatas = JSON.parse(data);
	   		if (jsonDatas) {
					var html_tag = theme_panelGridGold(jsonDatas);
					jQuery(obj).html(html_tag);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
  }

/*
 * --------------------------------------------------
 * business  header blocks
 * --------------------------------------------------
*/
	function eps_business_panel_blocks (obj,ajax_url) {
		var html_tag = '';
		jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(data){
	   		jsonDatas = JSON.parse(data);
	   		if (jsonDatas) {
					var html_tag = theme_business_blocks(jsonDatas);
					jQuery(obj).html(html_tag);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
	}
/*
 * --------------------------------------------------
 * render a php template file
 * --------------------------------------------------
*/
	function eps_render_template (obj,ajax_url) {
		var html_tag = '';
		jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(data){
	   		if (data) {
					jQuery(obj).html(data);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
	}
/*
 * --------------------------------------------------
 * High charts
 * --------------------------------------------------
*/
	function eps_high_charts (obj,ajax_url) {
		var html_tag = '';
		jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(jsonDatas){
	   		if (jsonDatas) {
	   			jsonDatas = JSON.parse(jsonDatas);
	   			var html_tag = theme_high_charts(jsonDatas);
					jQuery(obj).html(html_tag);
					jQuery(obj).children('.afl-widget-chart').children('.chart').highcharts(jsonDatas.text);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
	}
/*
 * -----------------------------------------------
 * Users downines user count with each level
 * -----------------------------------------------
*/

 function eps_level_users_count (obj,ajax_url) {
 	var html_tag = '';
		jQuery.ajax({
	   	type :'POST',
	   	data : {
	   		action:ajax_url,
	   	},
	   	url:ajax_object.ajaxurl,
	   	success: function(jsonDatas){
	   		if (jsonDatas) {
	   			jsonDatas = JSON.parse(jsonDatas);
	   			var html_tag = theme_level_user_counts(jsonDatas);
					jQuery(obj).html(html_tag);
	   		} else {
	   			var html_tag = theme_panel_error();
					jQuery(obj).html(html_tag);
	   		}
	   	},
	   	error: function(xhr, textStatus, errorThrown){
	       var html_tag = theme_panel_error();
				jQuery(obj).html(html_tag);
	    }
	  });
 }

/*
 * --------------------------------------------------
 * Panel grid theme
 * --------------------------------------------------
*/
 function theme_panelGridAjax (jsonData, v_type='') {
 	 var html_tag = '<div class="block panel padder-v item afl-widget-panel '+jsonData.box_color+'">';
    html_tag += '<a href="'+jsonData.link+'">';
    html_tag += '<div class="h1 font-thin '+jsonData.valu_text_color+'">'+jsonData.text;
    if(typeof jsonData.currency_text != 'undefined'){
      html_tag += '<span class="text-xs m-l-xs">'+jsonData.currency_text+'</span>';
    }
    html_tag +='</div>';
    html_tag += '<div class="text-muted text-xs '+jsonData.text_color+'">'+jsonData.title+'</div>';
    html_tag += '</a>';
    // html_tag += '<div class="top dropdown">';
    // html_tag += '<i class="fa fa-gear m-l-sm m-r-sm '+jsonData.icon_color+' dropdown-toggle" data-toggle="dropdown"></i>';
    // html_tag += '<ul class="dropdown-menu">';
    // html_tag += '<li><a href="#" type="y" '+((v_type=='y')?'class="active"':'class="visible-type"')+'>'+'This Year'+'</a></li>';
    // html_tag += '<li><a href="#" type="m" '+((v_type=='m')?'class="active"':'class="visible-type"')+'>'+'This Month'+'</a></li>';
    // html_tag += '<li><a href="#" type="d" '+((v_type=='d')?'class="active"':'class="visible-type"')+'>'+'Today'+'</a></li>';
    // html_tag += '<li><a href="#" type="t" '+((v_type=='t')?'class="active"':'class="visible-type"')+'>'+'Overall'+'</a></li>';
    // html_tag += '</ul>';
    // html_tag += '</div>';

    html_tag += '</div>';
    return html_tag;
 }
/*
 * -----------------------------------------------
 * Theme Error
 * -----------------------------------------------
*/
 function theme_panel_error (text='') {
 	var html_tag = '<div class="block panel padder-v item bg-danger text-center">';
    html_tag += '<div class="h1 font-thin h1 text-white"><i class="glyphicon glyphicon-warning-sign"></i></div>';
    html_tag += '<div class="text-muted text-xs text-muted">'+((text) ? text : 'some error occurred');+'</div>';
    html_tag += '</div>';
    return html_tag;
 }
/*
 * -----------------------------------------------
 * Panel grid Gold
 * -----------------------------------------------
*/
  function theme_panelGridGold (jsonData) {
  	 var html_tag = '<div class="r item hbox no-border '+jsonData.box_color+'">';
    html_tag += '<div class="col w-xs v-middle hidden-md">';
    html_tag += '<i class="fa m-l-sm m-r-sm '+jsonData.icon_color+' '+jsonData.icon_size+'"></i>';

    html_tag += '</div>';
    html_tag += '<div class="col dk padder-v r-r">';
    html_tag += '<a href="'+jsonData.link+'">';
    html_tag += '<div class="font-thin h1 '+jsonData.valu_text_color+'">'+jsonData.text;
    if(typeof jsonData.currency_text != 'undefined'){
      html_tag += '<span class="text-xs m-l-xs">'+jsonData.currency_text+'</span>';
    }
    html_tag += '</div>';
    html_tag += '<div class="text-muted text-xs '+jsonData.text_color+'">'+jsonData.title+'</div>';
    html_tag += '</a>';
    html_tag += '</div>';
    html_tag += '</div>';
    return html_tag;
  }
/*
 * -----------------------------------------------
 * Business overview blocks
 * -----------------------------------------------
*/
 function theme_business_blocks (jsonData) {
 	var html_tag ='<div class="inline m-r text-left ">';
 	html_tag += '<div class="m-b-xs font-thin text-lg '+jsonData.text_color+'">';
 	html_tag += '<p class="text-base m-b-none '+jsonData.title_color+'">'+jsonData.title+'</p>';
 	html_tag += jsonData.text;
 	html_tag +='</div>';
 	html_tag +='</div>';

 	return html_tag;
 }
/*
 * -----------------------------------------------
 * High charts
 * -----------------------------------------------
*/
 function theme_high_charts (jsonData, v_type = 'y') {

 	 var html_tag = '<div class="panel wrapper afl-widget-chart">';
    html_tag += '<div class="clearfix">';
    html_tag += '<h4 class="font-thin m-t-none m-b '+jsonData.title_color+' pull-left">'
    html_tag += jsonData.title;
    html_tag += '</h4>';
    // html_tag += '<div class="pull-left dropdown">';
    // html_tag += '<i class="fa fa-gear m-l-sm m-r-sm '+jsonData.icon_color+' dropdown-toggle" data-toggle="dropdown"></i>';
    // html_tag += '<ul class="dropdown-menu">';
    // if(jsonData.show_chart=='pie'){
    //   html_tag += '<li><a href="#" type="y" '+((v_type=='y')?'class="active"':'class="visible-type"')+'>'+('This Year')+'</a></li>';
    //   html_tag += '<li><a href="#" type="m" '+((v_type=='m')?'class="active"':'class="visible-type"')+'>'+('This Month')+'</a></li>';
    //   html_tag += '<li><a href="#" type="d" '+((v_type=='d')?'class="active"':'class="visible-type"')+'>'+('Today')+'</a></li>';
    //   html_tag += '<li><a href="#" type="t" '+((v_type=='t')?'class="active"':'class="visible-type"')+'>'+('Overall')+'</a></li>';
    // }else{
    //   html_tag += '<li><a href="#" type="y" '+((v_type=='y')?'class="active"':'class="visible-type"')+'>'+('Yearly')+'</a></li>';
    //   html_tag += '<li><a href="#" type="m" '+((v_type=='m')?'class="active"':'class="visible-type"')+'>'+('Monthly')+'</a></li>';
    //   html_tag += '<li><a href="#" type="d" '+((v_type=='d' || v_type=='t')?'class="active"':'class="visible-type"')+'>'+('Day')+'</a></li>';
    // }
    // html_tag += '</ul>';
    // html_tag += '</div>';
    html_tag += '</div>';
    html_tag += '<div  ui-refresh="'+jsonData.show_chart+'" style="'+jsonData.chart_style+'" class="chart"></div>';
    html_tag += '</div>';
    return html_tag;
 }
/*
 * -----------------------------------------------
 * User downlines users based on level
 * -----------------------------------------------
*/
 function theme_level_user_counts (jsonData) {
 		var cls = '';
    var html_tag = '<div class="block panel item afl-user-count-panel clearfix">';
    var arr_count = jsonData.length;
    var ar_count_half = arr_count/2;
    var ar_half_round = Math.round(arr_count/2);

    pan_width = 100/ar_half_round;

    if (arr_count <= 5) {
      pan_width = 100/(arr_count);
    }
    
    jQuery.each(jsonData, function(key, value){
      var color_timer = key%2;
      cls = '';
      if (ar_half_round % 2 == 0 && key >= ar_count_half && arr_count > 5) {
         if (color_timer == 1) {
            cls = 'bg-info';
          }
      }
      else
         if (color_timer == 0) {
         cls = 'bg-info';
      }
        html_tag += '<div class="padder-v '+cls+'" style="width:'+pan_width+'%;float:left;text-align:center;"> L '+value.level;
        html_tag += '<a href="#">';
        html_tag += '<div class="font-thick">'+value.count+'/'+value.total;
        html_tag +='</div>';
        html_tag += '</a>';
        html_tag += '</div>';
    });
    
    if (cls == 'bg-info') {
      cls = '';
    }
    else {
      cls = 'bg-info';
    }

    if (arr_count % 2 == 1) {
      html_tag += '<div class="padder-v '+cls+' " style="width:'+pan_width+'%;float:left;text-align:center;">""';
        html_tag += '<a href="#">';
        html_tag += '<div class="font-thick">NILL';
        html_tag +='</div>';
        html_tag += '</a>';
        html_tag += '</div>';
    }
    html_tag += '</div>';
    return html_tag;
 }