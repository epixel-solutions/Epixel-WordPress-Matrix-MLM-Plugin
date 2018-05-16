<?php
/**
 * Rows of Users for a selected Course
 */
?>
<?php if ( 'checkbox' == $header ) : ?>
	<input type="checkbox" data-user-id="<?php echo $activity->user_id; ?>">
<?php endif; ?>

<?php if ( 'user' == $header ) : ?>
	<?php
		if ( current_user_can( 'edit_user', $activity->user_id ) ) { 
			$user_link = get_edit_user_link( $activity->user_id ) ."#ld_course_info";
		} else {
			$user_link = "#";
		}
	?>
	
	<strong title="User ID: <?php echo $activity->user_id; ?>" class="display-name"><?php echo esc_html( $activity->user_display_name ); ?></strong>
	<p class="user-login"><a href="<?php echo $user_link; ?>" title="<?php echo esc_attr( $activity->user_display_name ); ?>"><?php echo esc_html( $activity->user_display_name ); ?></a></p>
	<p class="user-email"><a href="mailto:<?php echo esc_attr( $activity->user_email ); ?>" title="<?php printf( esc_attr__( 'Compose a new mail to %s', 'ld_propanel' ), $activity->user_email ); ?>"><?php echo esc_html( $activity->user_email ); ?></a></p>
<?php endif; ?>

<?php if ( 'user_id' == $header ) : ?>
	<?php echo $activity->user_id; ?>
<?php endif; ?>

<?php if ( 'progress' == $header ) : ?>
	<div class="progress-bar" title="<?php echo sprintf( __("%d of %d steps completed", 'ld_propanel'), LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity ), LearnDash_ProPanel_Activity::get_activity_steps_total( $activity ) ) ?>">
		<?php 
			$progress_label_style = '';
			if ( is_null( $activity->activity_status ) ) {
				$progress_percent = 0;
				$progress_label = __('Not Started', 'ld_propanel' );
				$progress_label_style = 'font-size: 16px;';
			} else if ( $activity->activity_status == false ) {
				$steps_completed = LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity );
				$steps_total = LearnDash_ProPanel_Activity::get_activity_steps_total( $activity );
				$progress_percent = round( 100 * ( intval( $steps_completed ) / intval( $steps_total ) ) );
				$progress_label = $progress_percent .'%';
				$progress_label_style = '';
			} else if ( $activity->activity_status == true ) {
				$progress_percent = 100;
				$progress_label = $progress_percent .'%';
			}
		?>
		<span class="actual-progress" style="width: <?php echo $progress_percent; ?>%;"></span>
	</div>
	<strong class="progress-amount" style="<?php echo $progress_label_style ?>"><?php echo $progress_label; ?></strong>
<?php endif; ?>

<?php if ( 'last_update' == $header ) : ?>
	<?php echo learndash_adjust_date_time_display( $activity->course_completed_on ); ?>
<?php endif; ?>

