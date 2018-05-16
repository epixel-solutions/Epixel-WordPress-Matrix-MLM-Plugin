jQuery(document).ready(function($){
	//temporary selector
	//
	$('.btn-pause').on('click', function(){
		$.ajax({
			url: fx.ajax_url,
			type : 'post',
			data : {
				action : 'fx_customer_pause_account',
				subscription_id: $(this).data('subscription-id')
			},
			success : function( response ) {
				if(response.status == 'success'){
					window.location = fx.logout_url;
				}
				else{
					alert("Pause subscription fail. Please contact support.");
				}
			}
		});
		return false;
	});
});