<?php 
 /*
  * -----------------------------------------------------------------
  * This is the basic template for all the dashboard content
  * Here only give the basic structure, and all the content is loaded 
  * using the ajax
  * -----------------------------------------------------------------
 */
?>

<?php echo afl_eps_page_header(); ?>

<?php echo afl_content_wrapper_begin(); ?>
<div class="panel wrapper">
<div class="row ">

	<div class="col-md-5">
    <div class="row text-center">

	  	<div id="afl-widgets-afl-downline-members-panel" class="block col-md-6">
			</div>

			<div id="afl-widgets-afl-e-wallet" class="block col-md-6">
			</div>

			<div id="afl-widgets-afl-total-credits" class="block col-md-6">
			</div>

			<div id="afl-widgets-afl-total-debits" class="block col-md-6">
			</div>

			<div id="afl-widgets-afl-ewallet-sum-panel" class="block col-md-12">
			</div>

		</div>
	</div>
	<div class="col-md-7">
			
			<div id="afl-widgets-afl-dashboard-downline-chart" class="block col-md-12">
			</div>

			<div id="afl-widgets-afl-dashboard-level-user-counts" class="block col-md-12">
			</div>
	</div>
</div>
</div>

<div class="panel wrapper">
	<div class="row ">
		<div class="col-md-6">
			<div id="afl-widgets-afl-e-wallet-transaction-chart">
			</div>
		</div>
		<div class="col-md-6">
			<div id="afl-widgets-afl-e-wallet-summary" >
			</div>
		</div>
	</div>
</div>

<?php if (eps_is_admin())  : ?>
<div class="panel wrapper">
	<div class="row ">
		<div class="col-md-6">
			<div id="afl-widgets-afl-b-wallet-transaction-chart">
			</div>
		</div>
		<div class="col-md-6">
			<div id="afl-widgets-afl-b-wallet-report" >
			</div>
		</div>
	</div>
</div>
<?php endif; ?>




<?php echo afl_content_wrapper_end();