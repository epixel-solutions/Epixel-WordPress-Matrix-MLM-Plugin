<?php
global $post;
$user_id = get_current_user_id();
$lesson_id = get_the_ID();
$lesson = get_post($lesson_id);
$course = get_lesson_parent_course( $lesson_id );
$course_id = $course->ID;
$lessons = get_lessons_by_course_id( $course->ID );
$course_progress = get_user_progress();
$previous_lesson = array_search($lesson, $lessons) > 1 ? $lessons[intval(array_search($lesson, $lessons)-1)] : '';
$next_lesson = array_search($lesson, $lessons) < sizeof($lessons) ? $lessons[intval(array_search($lesson, $lessons)+1)] : '';
$course_video = Learndash_Course_Video::get_instance();
$lesson_settings = learndash_get_setting( $post );
$video = $course_video->add_video_to_content( '', $post, $lesson_settings );
$progression_enabled = is_lesson_progression_enabled($course_id);
?>

<?php get_header(); ?>

	<?php //print_r($course); //get_template_part('inc/templates/nav-products'); ?>

	<nav class="navbar fx-navbar-sub">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<ul id="38" class="fx-nav-options xpto3">
						<li class="dashboard icon icon-products "><a href="<?php bloginfo('url'); ?>/basic-training"><span class="number">#</span><span class="text">Basic Training</span></a></li>
						<li><a title="1." href="<?php echo get_the_permalink($course_id); ?>"><span class="number">1.</span><span class="text"><?php echo $course->post_title; ?></span></a></li>
						<li class="current-menu-item"><a title="2." href="javascript:void(0)"><span class="number">2.</span><span class="text"><?php echo get_the_title(); ?></span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>

	<?php if ( !is_object($previous_lesson) || ( $progression_enabled && learndash_is_lesson_complete($user_id, $previous_lesson->ID)) ): ?>
	
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body">
							<h5 class="text-bold">Course Progress</h5>
							<?php get_template_part('inc/templates/course/progressbar'); ?>
						</div>
					</div>
					<div class="panel panel-default fx-course-navigation">
						<div class="panel-body">
							<h5 class="text-bold">Lesson Navigation</h5>
							<?php if( $lessons ) : ?>
								<ul>
								<?php $count = 0;  foreach($lessons as $key => $post): setup_postdata($post); $count++; ?>
									<?php $is_complete = learndash_is_lesson_complete($user_id, $post->ID);?>
									<li class="<?php echo  $is_complete ?  'completed' : '';?>" ><a href="<?php the_permalink();?>" data-previous-lesson-id="<?php echo $lessons[$key - 1]->ID;?>"><?php the_title();?></a></li>
								<?php endforeach;  wp_reset_query(); ?>
								</ul>
							<?php endif;?>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-9">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="fx-header-title">
								<h3>Lesson #<?php echo intval(array_search($lesson, $lessons)) + 1;?></h3>
								<h1><?php the_title();?></h1>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="fx-video-container">
								<?php echo $video; ?>
							</div>
							<br/>
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default fx-course-outline xs-fx-course">
								<div class="panel-body">
									<div class="content">
										<?php echo get_mb_pto1( 'video_embed', 'pto3' ); ?>
										<?php echo do_shortcode(get_post_field('post_content', $lesson_id)); ?>
									</div>
									<br>
									<?php if( !learndash_is_lesson_complete($user_id, $lesson->ID) ): ?>
										<?php $disabled_complete = forced_lesson_time(); ?>
										<div class="mark-complete">
											<form id="sfwd-mark-complete" method="post" action="">
												<input type="hidden" value="<?php echo $lesson_id;?>" name="post" />
												<input type="hidden" value="<?php echo wp_create_nonce( 'sfwd_mark_complete_'. $user_id .'_'. $lesson_id );?>" name="sfwd_mark_complete" />
												<input <?php $disabled_complete ? $disabled_complete : '';?> type="submit" value="Mark Complete" class="btn btn-success block" style="width:100%;" id="learndash_mark_complete_button"/>
												<span id="learndash_timer" class="hidden"></span>
											</form>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="fx-adjacent-lessons">
								<?php if ( is_object($previous_lesson) ): ?>
									<a href="<?php echo get_permalink($previous_lesson->ID);?>" class="prev-link" rel="prev"><span class="meta-nav">←</span> Previous Lesson</a>
								<?php endif;?>

								<?php if ( is_object($next_lesson) && learndash_is_lesson_complete($user_id, $lesson->ID) ): ?>
									<a href="<?php echo get_permalink($next_lesson->ID);?>" class="prev-link" rel="prev"><span class="meta-nav">→</span> Next Lesson</a>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>

	<?php else: ?>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<h4>You must finish the previous lesson first: <a href="<?php echo get_permalink($previous_lesson->ID);?>"><?php echo $previous_lesson->post_title;?></a></h4>
				</div>
			</div>
		</div>
	<?php endif;?>
<?php get_footer(); ?>