<?php
/*
 * ------------------------------------------
 * Get the sponsor details
 * ------------------------------------------
*/
	$sponsor 	= array();
	$uid 			= get_uid();
	$node 		= afl_genealogy_node($uid,'matrix');
	if ( $node ) {
		if ( !empty( $node->referrer_uid )) {
			$sponsor 					= afl_user_data( $node->referrer_uid );
			$sponsor_node 	 	= afl_genealogy_node($node->referrer_uid);
		}
	//}
	// $sponsor 			= afl_user_data( 162 );
	// $sponsor_node = afl_genealogy_node(162);
 ?>
		<style type="text/css">
			.profile-header{
				font-size: 15px;font-weight: bold;
			}
			.affiliate-genealogy-details .profile-info{list-style: none;}
			.button-link {
				padding: 10px 15px;
				background: #4479BA;
				color: #FFF;
			}
		</style>
		<div class="affiliate-genealogy-details">
			<div class="panel panel-default">
		    <div class="panel-body">
			    <div class="row">
			      <div class="col-md-6">
			      	<ul class="profile-info">
			      		<li class="profile-header">Matrix Info:</li>
			      		<?php 
			      			$legs_gv_sum 	= 0;
 									$legs_gv 			= _get_user_direct_legs_gv_with_position($node->uid);
 									$legs_gv_sum 	= _get_user_direct_legs_gv_with_position($node->uid,TRUE);
			      		?>

			      		<li>Global Volume : <?php echo $legs_gv_sum;?> </li>
			      		<li>Team/ Leg #1 :<?php echo !empty($legs_gv[1]) ? $legs_gv[1]['sales'] : 0; ?> </li>
			      		<li>Team/ Leg #2 :<?php echo !empty($legs_gv[2]) ? $legs_gv[2]['sales'] : 0; ?> </li>
			      		<li>Team/ Leg #3 :<?php echo !empty($legs_gv[3]) ? $legs_gv[3]['sales'] : 0; ?> </li>
			      	</ul>
			      </div>
			      <div class="col-md-6">
				      <div class="row">
				      	<div class="col-md-6">
				      		<div class="group-btn-link">
				      			<a href="<?php bloginfo('url'); ?>/team/referred-members/" class="button-link"><?= __('Distributors').' : '.apply_filters('afl_my_distributors_count',$node->uid); ?></a>
				      		</div>
				      	</div>
				      	<div class="col-md-6">
				      		<div class="group-btn-link">
				      			<a href="<?php bloginfo('url'); ?>/team/referred-members/" class="button-link"><?= __('Customers').' : '.apply_filters('afl_my_customers_count',$node->uid); ?></a>
				      		</div>
				      	</div>
				      	<div class="col-md-12">
					    	<a href="<?php bloginfo('url'); ?>/team/matrix-tree/" class="button-link view-team-tree"><?= __('View Matrix Tree'); ?></a>
					    </div>
				      </div>
			      </div>
			    </div>
			  </div>
		  </div>
		</div>
<?php 
} else { ?>
	<div class="panel panel-default">
		<div class="panel-body">
			Unable to view 
		</div>
	</div>
<?php }