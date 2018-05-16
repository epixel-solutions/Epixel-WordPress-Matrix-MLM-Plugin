<?php 
function afl_payout_in_remittance(){
	afl_eps_page_header();
	afl_content_wrapper_begin();
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'active_payouts';  

		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  echo '<li class="'.(($active_tab == 'active_payouts') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout-in-remittance&tab=active_payouts" >Active Payouts-Ready For Payment</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'canceled_payouts') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout-in-remittance&tab=canceled_payouts" >Pay Later / Cancelled</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'payout_history') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-payout-in-remittance&tab=payout_history" >Payouts History</a>  
		          </li>';
		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'active_payouts':					
	  		case 'canceled_payouts':
	  		case 'payout_history':
	  			afl_payout_in_remittance_datatable();
	  		break;

	  		default :	 
	  			afl_payout_in_remittance_datatable();
	  			break;
		  }
		  afl_content_wrapper_end();
 }

function afl_payout_in_remittance_datatable(){
		new Afl_enque_scripts('common');
	

	$affiliates_table = new Eps_payout_in_remitance_datatable();
	?>
			<div class="wrap">
			<?php
			/**
			 * Manage payouts eps-affiliates
			 *
			 * Use this hook to add content to this section of AffiliateWP.
			 */
				do_action( 'eps_affiliates_page_top' );

				?>
				<form id="eps-affiliates-filter" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php $affiliates_table->search_box( __( 'Search', 'eps-affiliates' ), 'eps-affiliates' ); ?>

					<input type="hidden" name="page" value="affiliate-eps-payout-in-remittance" />

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

