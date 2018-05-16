<?php
$user_id = get_current_user_id();
$course_id = get_the_ID();
$course = get_post( $course_id ); 
$lessons = get_lessons_by_course_id( $course_id );
$course_progress = get_user_progress();
$course_prerequisites = learndash_get_course_prerequisites( $course_id );
?>

<?php get_header(); ?>

	<nav class="navbar fx-navbar-sub">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<ul id="38" class="fx-nav-options xpto3">
						<li class="dashboard icon icon-products "><a href="<?php bloginfo('url'); ?>/basic-training"><span class="number">#</span><span class="text">Basic Training</span></a></li>
						<li class="current-menu-item"><a title="1." href="javascript:void(0)"><span class="number">1.</span><span class="text"><?php echo get_the_title(); ?></span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>

	<?php if( !is_course_prerequities_completed($course_id) ): ?>
		<?php get_template_part('inc/templates/course/prerequisites'); ?>
	<?php elseif( !sfwd_lms_has_access_fn($course_id) ): ?>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="fx-header-title">
						<h1><?php the_title();?></h1>
						<p><?php echo rwmb_meta('subtitle');?></p>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
					<div class="learndash_join_button">
						<form method="post">
							<input type="hidden" value="<?php echo $course_id;?>" name="course_id" />
							<input type="hidden" name="course_join" value="<?php echo wp_create_nonce( 'course_join_'. $user_id .'_'. $course_id  );?>" />
							<input type="submit" value="Start This Course" class="btn btn-success block" style="width:100%;" />
						</form>
					</div>
					<br/>
				</div>
				<div class="clearfix"></div>
				<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
					<div class="panel panel-default fx-course-outline xs-fx-course">
						<div class="panel-body">
							<div class="content">
								<?php echo get_mb_pto1( 'video_embed', 'pto3' ); ?>
								<h3>Course Description</h3>
								<?php echo do_shortcode(get_post_field('post_content', $course_id)); ?>
							</div>
							<hr/>
							<h5 class="text-bold">Course Progress</h5>

							<?php get_template_part('inc/templates/course/progressbar'); ?>

							<hr/>
							<h5 class="text-bold">Course Lessons</h5>
							<div class="table-responsive">
								<table class="table table-bordered fx-table-lessons">
									<thead>
										<tr>
											<th>Lessons</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
									<?php if( $lessons ) : ?>
										<?php $count = 0;  foreach($lessons as $post): setup_postdata($post); $count++; ?>
											<?php $is_complete = get_course_lesson_progress($course_id, get_the_ID());?>
											<tr>
												<td class="text-center number"><?php echo $count; ?></td>
												<td>
													<a href="<?php the_permalink();?>"><?php the_title();?></a>
													<div class="status pull-right">
														<i class="fa <?php echo  $is_complete ?  'fa-check text-success' : '';?>"></i>
													</div>
												</td>
											</tr>
										<?php endforeach; wp_reset_query();?>
									<?php endif;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	<?php else: ?>
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
							<h5 class="text-bold">Course Navigation</h5>

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
								<h1><?php the_title();?></h1>
								<p><?php echo rwmb_meta('subtitle');?></p>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="fx-video-container"></div>
							<br/>
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default fx-course-outline xs-fx-course">
								<div class="panel-body">
									<h3>Course Description</h3>
									<div class="content">
										<?php echo get_mb_pto1( 'video_embed', 'pto3' ); ?>
										<?php echo wpautop($course->post_content); ?>
									</div>
								</div>
							</div>
							<!--
							<a href="#" class="btn btn-danger block">Upgrade For Access - $197</a>
							<br/
							>-->
							<div class="panel panel-default fx-course-outline">
								<div class="panel-body">
									<h5 class="text-bold">Course Lessons</h5>
									<div class="table-responsive">
										<table class="table table-bordered fx-table-lessons">
											<thead>
												<tr>
													<th>Lessons</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<?php if( $lessons ) : ?>
													<?php $count = 0;  foreach($lessons as $key => $post): setup_postdata($post); $count++; ?>
														<?php $is_complete = learndash_is_lesson_complete($user_id, $post->ID);?>
														<tr>
															<td class="text-center number"><?php echo $count; ?></td>
															<td>
																<a href="<?php the_permalink();?>" data-previous-lesson-id="<?php echo $lessons[$key - 1]->ID;?>"><?php the_title();?></a>
																<div class="status pull-right">
																	<i class="fa <?php echo  $is_complete ?  'fa-check text-success' : '';?>"></i>
																</div>
															</td>
														</tr>
													<?php endforeach; wp_reset_query();?>
												<?php endif;?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	<?php endif; ?>

<?php get_footer(); ?>