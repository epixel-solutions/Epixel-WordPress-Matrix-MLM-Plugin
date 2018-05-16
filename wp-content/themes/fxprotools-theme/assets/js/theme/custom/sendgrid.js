jQuery(document).ready(function($){
	
	$('.fx-sendgrid').submit(function(e){
		$.ajax({
			url: fx.ajax_url,
			type : 'post',
			data : {
				action : 'fx_sendgrid_capture_email',
				email : $('.fx-sendgrid').find('input[name="email"]').val(),
				funnel_id : $('.fx-sendgrid').find('input[name="funnel_id"]').val(),
				affiliate_user_id: $('.fx-sendgrid').find('input[name="affiliate_user_id"]').val(),
				name : $('.fx-sendgrid').find('input[name="name"]').val(), 
				contact : $('.fx-sendgrid').find('input[name="contact"]').val(), 
			},
			success : function( response ) {
				response = JSON.parse( response );
				if(response.status == "OK"){
					var redirect_to = $('.fx-sendgrid').find('input[name="redirect_to"]').val();
					window.location.href =  redirect_to ;
				}
				else{
					alert("Sending email information failed.");
				}
			}
		});
		return false;
		e.preventDefault();
	});
});