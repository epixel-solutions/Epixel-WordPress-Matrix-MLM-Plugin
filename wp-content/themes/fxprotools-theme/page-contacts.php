<?php
$product_id = 2920; //business package
$product = wc_get_product( $product_id );					
$page_counter = 1;
?>
<?php get_header(); ?>

<?php  
$list = array (
    array('vin', 'bbb', 'ccc', 'dddd'),
    array('123', '456', '789'),
    array('"aaa"', '"bbb"')
);

$fp = fopen('file.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
?>

	<?php get_template_part('inc/templates/nav-marketing'); ?>

	<?php if ( is_user_fx_distributor() || current_user_can('administrator')  ): ?>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="fx-header-title">
						<h1>Contacts</h1>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="text-right m-b-md">
								<a href="#" id="export_contacts" class="btn btn-default"><i class="fa fa-download"></i> Export Contacts</a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="fx-search-contact">
						<div class="panel panel-default">
							<div class="panel-body">
								<form id="contact-search-form" action="" method="GET">
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-search"></i></div>
										<input type="text" class="form-control" name="search" placeholder="Search by name or e-mail">
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="contact-status"></div>
					<div class="contacts-container">
					<!-- insert contact list here via ajax -->
					</div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<?php get_template_part('inc/templates/no-access'); ?>
	<?php endif; ?>
<?php get_footer(); ?>

<script type="text/javascript">
	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};

	function get_contacts(){
		$.ajax({
	        url: "<?php echo get_option('home'); ?>/wp-admin/admin-ajax.php",
	        method: 'POST',
	        data: {
	            'action':'ajax_contacts',
	            'page_num' : page_num,
	            'query_offset_multi' : query_offset_multi,
	            'search_string ': search_string
	        },
	        beforeSend: function(){
	        	$("#contact-search-form input").prop("disabled", true);
	        	$('.contact-status').html('');
	        	$('.contact-status').append('<div class="col-md-6 col-md-offset-3"><div class="progress"><div class="progress-bar progress-bar-danger progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Your contacts are loading</div></div></div>');
	        },
	        success:function(data) {
	        	var json = JSON.parse(data);
	        	console.log(json);
	            $.ajax({
			        url: "<?php echo get_option('home'); ?>/wp-admin/admin-ajax.php",
			        method: "POST",
			        data: {
			            'action':'format_contacts',
			            'contacts' : json.contacts,
			            'ref_count' : json.ref_count,
			            'ref_count_search' : json.ref_count_search,
			            'query_offset' : json.query_offset,
			            'query_offset_multi' : query_offset_multi,
			            'search_term' : search_string,
			            'page_num' : page_num
			        },
			        beforeSend: function(){
			        	$('.contact-status').html('');
	        			$('.contact-status').append('<div class="col-md-6 col-md-offset-3"><div class="progress"><div class="progress-bar progress-bar-danger progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Preparing your contacts</div></div></div>');
			        },
			        success:function(data) {
			        	$('.contacts-container').html('');
			            $('.contacts-container').prepend(data);
			            $("#contact-search-form input").prop("disabled", false);
			            $('.contact-status').html('');
			        },
			        error: function(errorThrown){
			            console.log(errorThrown);
			        }
			    }); 
	        },
	        error: function(errorThrown){
	            console.log(errorThrown);
	        }
	    }); 
	}

	var search_string = null;
	var page_num = 1;
	var query_offset_multi = 10;
	var query_offset = 1;	
	if(getUrlParameter('i')){
		page_num = getUrlParameter('i');
	}
	if(getUrlParameter('search')){
		search_string = getUrlParameter('search');
	}

	$(document).ready(function(){
		get_contacts();
		$('#contact-search-form').submit(function(e){
			e.preventDefault();
			search_string = $('input[name="search"]').val();
			page_num = 1;
			$('.contacts-container').html('');
			get_contacts();
		});
		$('body').on('submit','#contact-pagination',function(e){
			e.preventDefault();
			page_num = $('input[name="i"]').val();
			search_string = $('#contact-search-form input[name="search"]').val();
			$('.contacts-container').html('');
			get_contacts();
		});
		$('body').on('click','#contact-pagination a',function(e){
			e.preventDefault();
			page_num = $(this).attr('data-page');
			//alert(page_num);
			search_string = $('#contact-search-form input[name="search"]').val();
			$('.contacts-container').html('');
			get_contacts();
		});

		//csv
		$('body').on('click','#export_contacts',function(e){
			e.preventDefault();
			$.ajax({
		        url: "<?php echo get_option('home'); ?>/wp-admin/admin-ajax.php ?>",
		        method: "POST",
		        data: {

		        },
		        beforeSend: function(){
		        },
		        success:function(data) {
		        	console.log(data);
		        },
		        error: function(errorThrown){
		            console.log(errorThrown);
		        }
		    }); 
		});
	});
</script>