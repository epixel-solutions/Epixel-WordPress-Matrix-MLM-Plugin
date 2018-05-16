jQuery(document).ready(function($){
	var page_templates = $('#page_template').html();
	var page_parents = $('#parent_id').html();
	$('#pto1_page_template').html('').append(page_templates);
	$('#pto1_parent_id').html('').append(page_parents);

	$('.rwmb-tab-panel-pto1_attributes select').on('change',function(){
		var selected = $(this).find('option:selected').val();
		var target = $(this).attr('id').replace('pto1_', '');
		$('#' + target).find('option[value="'+ selected +'"]').prop('selected','selected');
	});
});