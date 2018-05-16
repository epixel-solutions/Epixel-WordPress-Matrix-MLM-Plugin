// -------------------------------------
// Custom Admin Scripts For Custom Pages
// -------------------------------------
jQuery(document).ready(function($){

	$('[data-toggle="tooltip"]').tooltip();

	$('#table-customers').DataTable({
		processing: true,
		// serverSide: true,
		ajax: {
			url: wpAjax.ajaxUrl,
			type: 'POST',
			data: { action: 'get_customers' },
		},
		columns: [
			{ data: 'profile_id', orderable: false },
			{ 
				data: 'billing_info',
				orderable: false,
				render: function(data, type, full, meta){
					return data.first_name + ' ' + data.last_name;
				}
			},
			{ data: 'email', orderable: false },
			{ data: 'description', orderable: false },
			{
				data: 'profile_id',
				orderable: false,
				render: function(data, type, full, meta){
					return '<div class="btn-group"> \
								<button type="button" class="btn btn-default view-lead" data-source="info_customer" data-id="'+data+'" data-toggle="modal" data-target="#view-lead"> \
									<span class="glyphicon glyphicon-pencil"></span> \
								</button> \
							</div>';
				}
			}
		],
	});



});

// Events
$(document).on('hidden.bs.modal', '.view-lead-modal', function(){
	$('.modal-loading').hide();
	$('.modal-loading').html('');
	$('.modal-body-content').removeClass('active');
});

// $(document).on('show.bs.modal', '.modal', function () {
// 	// var zIndex = 1040 + (10 * $('.modal:visible').length);
// 	// $(this).css('z-index', zIndex);
// 	// setTimeout(function() {
// 	// 	$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
// 	// }, 0);
// });

$(document).on('click', '.view-lead', function(){
	var formSource =  $(this).data('source'),
		modalTarget = $(this).data('target');
	$.ajax({
		url: wpAjax.ajaxUrl,
		method: 'POST',
		dataType: 'json',
		data: {
			id: $(this).data('id'),
			source: formSource,
			action: 'view_lead'
		},
		beforeSend: function(){
			$(modalTarget+' .modal-loading').html('Loading Information ..');
			$(modalTarget+' .modal-loading').show();
		},
		success: function(r){
			// Show/Hide content
			$('.modal-body-content').removeClass('active');
			$('.modal-body-content[data-source="'+formSource+'"]').addClass('active');

			if(formSource == 'info_customer'){
				$('#view-lead .modal-title').text('Customer Info');
			}

			setTimeout(function(){
				$(modalTarget+' .modal-dialog').removeClass('modal-sm');
				$(modalTarget+' .modal-dialog').addClass('modal-lg');
				$(modalTarget+' .modal-loading').fadeOut();
				$('.modal-header, .modal-body-content, .modal-footer').show();
			}, 1500);
		},
	});
});