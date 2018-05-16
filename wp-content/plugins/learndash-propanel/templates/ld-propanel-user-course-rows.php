<?php
/**
 * Rows of Courses for a selected User
 */
?>
<?php if ( 'course_id' == $header ) : ?>
	<?php echo $activity->post_id; ?>
<?php endif; ?>

<?php if ( 'course' == $header ) : ?>
	<strong title="<?php echo sprintf( _x( '%s ID:', 'Course ID:', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?> <?php echo $activity->post_id; ?>" class="display-name"><?php echo esc_html( $activity->post_title ); ?></strong>
<?php endif; ?>

<?php if ( 'progress' == $header ) : ?>
	<div class="progress-bar" title="<?php echo sprintf( __("%d of %d steps completed", 'ld_propanel'), LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity ), LearnDash_ProPanel_Activity::get_activity_steps_total( $activity ) ) ?>">
		<?php 
			if ( is_null( $activity->activity_status ) ) {
				$progress_percent = 0;
				$progress_label = __('Not Started', 'ld_propanel' );
			} else if ( $activity->activity_status == false ) {
				$progress_percent = round( 100 * ( intval( LearnDash_ProPanel_Activity::get_activity_steps_completed( $activity ) ) / intval( LearnDash_ProPanel_Activity::get_activity_steps_total( $activity ) ) ) );
				$progress_label = $progress_percent .'%';
			} else if ( $activity->activity_status == true ) { 
				$progress_percent = 100;
				$progress_label = $progress_percent.'%';
			}
		?>
		<span class="actual-progress" style="width: <?php echo $progress_percent; ?>%;"></span>
	</div>	
	<strong class="progress-amount"><?php echo $progress_label; ?></strong>
<?php endif; ?>

<?php if ( 'last_update' == $header ) : ?>
	<?php echo learndash_adjust_date_time_display( $activity->course_completed_on ); ?>
<?php endif; ?>
