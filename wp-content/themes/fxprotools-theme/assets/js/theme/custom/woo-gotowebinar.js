var PublicGotoWebinar = function(){
	var ajax_url = fx;
	function isJson(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	function _datePickerOnSelect(dateText, inst){
		// var date = $(this).val();
		// console.log(dateText);
        //console.log(inst);
        //console.log(inst.selectedDay);
		$('.selected_date').val(inst.selectedDay);
		var selected_month = (inst.selectedMonth + 1);
		$('.selected_month').val(selected_month);
		$('.selected_year').val(inst.selectedYear);
		$('.ajax-woowebinar-time-rage').html('<p> Getting time, please wait...</p>');
		_ajaxGetTime(inst).done(function(data){
			//console.log(data);
			$('.ajax-woowebinar-time-rage').html(data);
		});
	}
	
	function _ajaxGetTime(dateFromDP){
		var time_from = '';
		var time_from_meridiem = '';
		var range_time_from = '';
		var time_to = '';
		var time_to_meridiem = '';
		var range_time_to = '';
		
		if(woo_webinar.hasOwnProperty('_woogotowebinar_range_time_from')){
			time_from = woo_webinar._woogotowebinar_range_time_from;
		}
		if(woo_webinar.hasOwnProperty('_woogotowebinar_range_time_from_meridiem')){
			time_from_meridiem = woo_webinar._woogotowebinar_range_time_from_meridiem;
		}
		range_time_from = time_from + ":00" + " " + time_from_meridiem.toUpperCase();
		
		if(woo_webinar.hasOwnProperty('_woogotowebinar_range_time_to')){
			time_to = woo_webinar._woogotowebinar_range_time_to;
		}
		if(woo_webinar.hasOwnProperty('_woogotowebinar_range_time_to_meridiem')){
			time_to_meridiem = woo_webinar._woogotowebinar_range_time_to_meridiem;
		}
		range_time_to = time_to + ":00" + " " + time_to_meridiem.toUpperCase();
		//console.log(range_time_from);
		//console.log(range_time_to);
		var ajaxCall = $.ajax({
		  method: "GET",
		  url: ajax_url.ajax_url,
		  data: { 
			'action': 'get_timerange_woowebinar',
			'selectedDate': dateFromDP.selectedDay,
			'selectedMonth': dateFromDP.selectedMonth,
			'selectedYear': dateFromDP.selectedYear,
			'range_time_from':range_time_from,
			'range_time_to':range_time_to
		  }
		});
		return ajaxCall;
	}
	
	return {
		init:function(){
			
		},
		time_click:function(){
			$( document ).on('click', '.webinar_time', function(e){
				e.preventDefault();
				var select_time = $(this).data('time');
				$('.selected_time').val(select_time);
				$('.webinar_single_add_to_cart_button').removeAttr('disabled');
				console.log(select_time);
			});
		},
		date_picker:function(){
			var maxDateNum = '';
			var maxDateDay = '';
			var maxDate = '';
			if(woo_webinar.hasOwnProperty('_woogotowebinar_scheduling_window_num')){
				maxDateNum = "+" + woo_webinar._woogotowebinar_scheduling_window_num;
			}
			if(woo_webinar.hasOwnProperty('_woogotowebinar_scheduling_window_date')){
				if( woo_webinar._woogotowebinar_scheduling_window_date == 'month' ){
					maxDateDay = 'M';
				}
				if( woo_webinar._woogotowebinar_scheduling_window_date == 'day' ){
					maxDateDay = 'D';
				}
				if( woo_webinar._woogotowebinar_scheduling_window_date == 'year' ){
					maxDateDay = 'Y';
				}
			}
			var _arg = {
				minDate: 0,
				maxDate: maxDateNum + maxDateDay,
				onSelect:function(dateText, inst){
					_datePickerOnSelect(dateText, inst);
				}
			}
			//console.log(_arg);
			$('#product-datepicker').datepicker(_arg);
		},
	};
}();

jQuery(document).ready( function($) {
	$('.webinar_single_add_to_cart_button').attr('disabled', 'disabled');
	PublicGotoWebinar.date_picker();
	PublicGotoWebinar.time_click();
});