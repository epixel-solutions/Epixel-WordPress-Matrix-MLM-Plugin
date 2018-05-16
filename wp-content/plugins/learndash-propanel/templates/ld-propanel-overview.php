<?php
/**
 * Learndash ProPanel Activity Overview
 */
?>
<div class="clearfix propanel-admin-row center">

	<div class="col-1-2 propanel-stat">
		<div class="stat-inner">
			<h2 class="stat-label">
				<?php 
					$user_list_href = '';
					if ( current_user_can('list_users') ) { 
						$user_list_href = admin_url('users.php');
					} else if ( current_user_can('group_leader' ) ) {
						$user_list_href = admin_url('admin.php?page=group_admin_page');
					}	
					if ( !empty( $user_list_href ) ) {
						?><a href="<?php echo esc_url( $user_list_href ); ?>"><?php
					}
				?>
				<?php esc_html_e( 'Total Students', 'ld_propanel' ); ?>
				<?php if ( !empty( $user_list_href ) ) { ?>
					</a>
				<?php } ?>
			</h2>
			<strong class="stat"><?php 
				if ( current_user_can('list_users') ) { 
					//echo learndash_students_enrolled_count(); 
					$total_user_count = ld_propanel_get_users_count();
					echo $total_user_count;
										
				} else if ( current_user_can( 'group_leader' ) ) {
					echo count(learndash_get_group_leader_groups_users());
				}
				?></strong>
		</div>
	</div>

	<div class="col-1-2 propanel-stat">
		<div class="stat-inner">
			<h2 class="stat-label">
				<?php 
					$user_list_href = '';
					if ( current_user_can('edit_courses') ) { 
						$user_list_href = admin_url('edit.php?post_type=sfwd-courses');
					} else if ( current_user_can('group_leader' ) ) {
						$user_list_href = admin_url('admin.php?page=group_admin_page');
					}	
					if ( !empty( $user_list_href ) ) {
						?><a href="<?php echo esc_url( $user_list_href ); ?>"><?php
					}
				?>
				<?php echo sprintf( _x( '%s', 'Courses', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'courses' ) ); ?>
				<?php if ( !empty( $user_list_href ) ) { ?>
					</a>
				<?php } ?>
			</h2>
			<strong class="stat"><?php 
				if ( current_user_can('edit_courses') ) { 
					echo learndash_get_courses_count(); 
				} else if ( current_user_can( 'group_leader' ) ) {
					echo count(learndash_get_group_leader_groups_courses());
				}
				?></strong>
		</div>
	</div>
	
	<div class="col-1-2 propanel-stat">
		<div class="stat-inner">
			<h2 class="stat-label"><a href="<?php echo esc_url( learndash_admin_get_assignments_pending_listing_link() ); ?>"><?php esc_html_e( 'Assignments Pending', 'ld_propanel' ); ?></a></h2>
			<strong class="stat"><a href="<?php echo esc_url( learndash_admin_get_assignments_pending_listing_link() ); ?>"><?php echo learndash_get_assignments_pending_count(); ?></a></strong>
		</div>
	</div>
	
	<div class="col-1-2 propanel-stat">
		<div class="stat-inner">
			<h2 class="stat-label"><a href="<?php echo esc_url( learndash_admin_get_essays_pending_listing_link() ); ?>"><?php esc_html_e( 'Essays Pending', 'ld_propanel' ); ?></a></h2>
			<strong class="stat"><a href="<?php echo esc_url( learndash_admin_get_essays_pending_listing_link() ); ?>"><?php echo learndash_get_essays_pending_count(); ?></a></strong>
		</div>
	</div>
		
</div>