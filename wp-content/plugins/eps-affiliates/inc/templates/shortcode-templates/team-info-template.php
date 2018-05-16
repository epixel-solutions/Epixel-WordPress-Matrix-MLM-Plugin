<?php
/*
 * ------------------------------------------
 * Get the sponsor details
 * ------------------------------------------
*/
	$sponsor 	= array();
	$uid 			= get_uid();
	$node 		= afl_genealogy_node($uid);
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
			.affiliate-sponsor-details .profile-info{list-style: none;}
			.button-link {
				padding: 10px 15px;
				background: #4479BA;
				color: #FFF;
			}
		</style>
		<div class="affiliate-sponsor-details">
			<div class="panel panel-default">
		    <div class="panel-body">
			    <div class="row">
			      <div class="col-md-6">
			      	<ul class="profile-info">
			      		<li class="profile-header">Unilevel Info:</li>
			      		<li>Team/Leg ID : <?php echo !empty($node->display_name) ? $node->display_name : 'Not Available';?> </li>
			      		<li>Team/Leg Volume:<?=  apply_filters('afl_distributor_team_volume',$node->uid); ?> </li>
			      		<li>Personal Volume : 	<?= apply_filters('afl_distributor_personal_volume',$node->uid);?></li>
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
					    	<a href="<?php bloginfo('url'); ?>/team/unilevel-tree-view/" class="button-link view-team-tree"><?= __('View Unilevel Tree'); ?></a>
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