<?php
if ( $uid ) {
	$holding_tank_nodes = _get_holding_tank_node($uid);
	$count = count($holding_tank_nodes);

	//get user from the holding tank based on the uid
	$holding_data = _get_holding_tank_data( $uid ,'unilevel');
	//if ( !empty($holding_data)) : 
		?>
	<div class="holding-toggle-wrapper">
		<div class="row">
			<div class="col-md-6 holding-tank-info-block">
				<p>Team Info<p>
				<p>Team/Leg ID<p>
				<p>Team/Leg slot<p>
				<p>Team Direct upline<p>
				<p>Team Direct downline<p>
			</div>
			<div class="col-md-6 holding-tank-info-block">
			<p>Sponsor Info</p>
			<p>Sponsor Name</p>
			<p>Sponsor MLM ID</p>
			</div>
		</div>

		<div class="row holding-tank-toggle-border">
			<div class="col-md-12">
				<div class="holding-user">
				  <div class="hv-item-child">
	          <div class="hv-item">
		          <div class="">
		             <div class="person">
		             		<span class="toggle-left-arrow"><i class="fa fa-caret-left fa-5x"></i></span>
		             			<div class="holding-toggle-user-image">
		                		<img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/no-user.png'; ?>" alt="">
		                	</div>
		             		<span class="toggle-right-arrow"><i class="fa fa-caret-right fa-5x"></i></span>
								</div>

								<div class="">
									<span>Mickey Mouse</span>
		              <p>User rank : </p>
		              <span><i class="fa fa-envelope"></i>Customer-email@eps.com</span>
		              <span><i class="fa fa-mobile"></i>+14-025164845</span>
		              <span><i class="fa fa-building-o"></i>City:sanfrancisco</span>
								</div>
								<div class="sliding-options">
									<div class="row">
										<div class="col-md-3 block-previous-user">0</div>
										<div class="col-md-3 block-save-user">0</div>
										<div class="col-md-3 block-next-user">0</div>
									</div>
								</div>
		          </div>
	          </div>
	        </div>
	      </div>
			</div>
		</div>

		<div class="row holding-tank-toggle-border">
			<div class="holding-toggle-holding-users-list">
				<div class="col-md-12">
					<?php if ( $holding_tank_nodes ) : ?>
						<section class="holding-tank-warpper">
							<div class="holding-tank-wrapper">
								<div class="holding-tank-profiles">
									<ul class="row">
										<?php foreach ($holding_tank_nodes as $key => $value) : ?>
												<li class="col-md-2 col-sm-3" data-user-id = "<?=$value->uid;?>">
										      <div class="person">
						                <img src="http://woocommerce-plugin/wp-content/plugins/eps-affiliates/assets/images/avathar.png" alt="">
							              <p class="name"><?= $value->display_name; ?></p>
					                  <span class=""><?= $value->day_remains;?> Day remains</span>
						              </div>
										    </li>
										<?php endforeach; ?>
									</ul>
								 </div>
							</div>
						</section>
					<?php  else : ?>
								No users currently in your Holding Tank

					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>
<style type="text/css">
	/* .holding-toggle-wrapper{text-align:center;} */
	.holding-user{text-align:center;}
	.holding-toggle-wrapper{margin: auto; max-width: 1024px!important}
	.toggle-left-arrow, .toggle-right-arrow{cursor: pointer;}
	.holding-tank-info-block{border: 2px solid #ccc;min-height: 160px;}
	.holding-tank-toggle-border{border: 2px solid #ccc;}
	.holding-user,.holding-toggle-holding-users-list{padding: 25px;}

	.toggle-left-arrow{float: left;}
	.holding-toggle-user-image{display: inline-block;}
	.toggle-right-arrow{float: right;}

	.block-previous-user, .block-save-user, .block-next-user{border: 2px solid #ccc;margin: 0px 5px;}
</style>
<?php //endif; 
}