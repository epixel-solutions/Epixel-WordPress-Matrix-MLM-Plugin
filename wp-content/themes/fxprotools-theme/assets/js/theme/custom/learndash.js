$(document).on('.fx-course-navigation ul li a, .fx-table-lessons a', function(){
	var lesson_id = $(this).data('previous-lesson-id');
	var lesson_link = $(this).attr('href');

	if( lesson_id > 1){
		$.ajax({
			url: fx.ajax_url,
			type : 'post',
			data : {
				action : 'lms_lesson_complete',
				lesson_id : lesson_id
			},
			success : function( response ) {
				if(response == '1'){
					window.location = lesson_link;
				}
				else{
					popup_alert("Course Lesson","Please finish the previous lesson first.");
				}
			}
		});
		return false;
	}
});