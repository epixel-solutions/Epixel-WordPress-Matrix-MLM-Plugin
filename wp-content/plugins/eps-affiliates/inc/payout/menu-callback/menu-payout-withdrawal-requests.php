<?php 

function afl_payout_withdrawal_requests () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
	
 	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'active_requestes';  

		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  echo '<li class="'.(($active_tab == 'active_requestes') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout&tab=active_requestes" >Active withdrawal Requests</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'approved_requests') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout&tab=approved_requests" >Approved Requests</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'rejected_requests') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout&tab=rejected_requests" >Rejected Requests</a>  
		          </li>';
		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'active_requestes':
	  		case 'approved_requests':
	  		case 'rejected_requests':
	  		default :
	  			afl_payout_withdrawal_request_active();
	  		break;
		  }
	afl_content_wrapper_end();

 }


function afl_payout_withdrawal_request_active(){	
		new Afl_enque_scripts('common');
	
	$affiliates_table = new Eps_withdraw_request_data_table();
	?>
			<div class="wrap">
			<?php
			/**
			 * Manage Members pf eps-affiliates
			 *
			 * Use this hook to add content to this section of AffiliateWP.
			 */
				do_action( 'eps_affiliates_page_top' );

				?>
				<form id="eps-affiliates-filter" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php $affiliates_table->search_box( __( 'Search', 'eps-affiliates' ), 'eps-affiliates' ); ?>

					<input type="hidden" name="page" value="affiliate-eps-payout" />

					<?php //$affiliates_table->views() ?>
					<?php $affiliates_table->prepare_items() ?>
					<?php $affiliates_table->display() ?>
				</form>
				<?php
				/**
				 * Fires at the bottom of the admin affiliates page.
				 *
				 * Use this hook to add content to this section of AffiliateWP.
				 */
				do_action( 'eps_affiliates_page_bottom' );
				?>
			</div>
	<?php }

