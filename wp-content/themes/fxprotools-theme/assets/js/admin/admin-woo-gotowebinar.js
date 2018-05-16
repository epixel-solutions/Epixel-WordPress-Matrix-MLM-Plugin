var WooGotoWebinar = function(){
	
	return {
		init:function(){
			
		},
	};
}();

jQuery(document).ready( function($) {
	//console.log('here');
	$('#apyc_woo_gotowebinar_appointment').hide();
	var product_type_dropdown = $('#product-type');
	function showThis(){
		//for Price tab
		$('.product_data_tabs .general_tab').addClass('show_if_variable_bulk').show();
		$('#general_product_data .pricing').addClass('show_if_variable_bulk').show();
		//for Inventory tab
		$('.inventory_options').addClass('show_if_variable_bulk').show();
		$('#inventory_product_data ._manage_stock_field').addClass('show_if_variable_bulk').show();
		$('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_variable_bulk').show();
		$('#inventory_product_data ._sold_individually_field').addClass('show_if_variable_bulk').show();
		$('#apyc_woo_gotowebinar_appointment').show();
	}
	if( product_type_dropdown.val() == 'apyc_woo_gotowebinar_appointment' ){
		showThis();
	}
	product_type_dropdown.change(function(){
		var _val = $(this).val();
		if( _val == 'apyc_woo_gotowebinar_appointment' ){
			showThis();
			$('#' + _val).show();
			console.log(_val);
		}else{
			$('#' + _val).hide();
		}
	});
});