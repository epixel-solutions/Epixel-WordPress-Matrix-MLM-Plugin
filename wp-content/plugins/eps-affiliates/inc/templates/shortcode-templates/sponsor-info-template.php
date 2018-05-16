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
			      		<li class="profile-header">Sponsor Info:</li>
			      		<li>Sponsor Name : 		<?php echo !empty($sponsor->display_name) ? $sponsor->display_name : 'Not Available';?> </li>
			      		<li>Sponsor MLM ID : 	<?php echo !empty($sponsor_node->remote_user_mlmid) ? $sponsor_node->remote_user_mlmid : 'Not Available';?> </li>
			      		<li>Sponsor Email : 	<?php echo !empty($sponsor->user_email) ? $sponsor->user_email : 'Not Available';?></li>
			      	</ul>
			      </div>
			      <div class="col-md-6">
			      	<a href="<?php bloginfo('url');	?>/team/direct-upline/" class="button-link">View Direct Upline</a>
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