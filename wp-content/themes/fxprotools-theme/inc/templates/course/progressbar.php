<?php
global $course_id;
$progress = learndash_course_progress(array( 'course_id' => $course_id, 'array' => true) ); 
$percentage = isset( $progress['percentage'] ) ? $progress['percentage'] : 0;
?>

<div class="progress">
 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage ;?>%">
		<?php echo $percentage ;?>%
 	</div>
</div>