<?php 
	global $count; 
	$course_id = get_the_ID();
	$progress = get_user_progress();
	$description = rwmb_meta('short_description');

	if( isset($progress[$course_id]) ){
		$sash = 'Active';
	}
	else{
		$price = get_course_price_by_id( $course_id );
		$sash = $price == 0 ? 'Free' : '$' . $price;
	}

?>

<li class="list-item">
	<div class="left">
		<div class="box">
			<span class="sash"><?php echo $sash ;?></span>
			<span class="number"><?php echo $count;?></span>
		</div>
	</div>
	<div class="right">
		<div class="row">
			<div class="col-md-12">
				<span class="title"><?php the_title();?></span>
			</div>
			<div class="col-md-10">
				<p><?php echo wp_trim_words($description, 35); ?></p>	
			</div>
			<div class="col-md-2">
				<a href="<?php the_permalink(); ?>" class="btn btn-default block">Learn More</a>
			</div>
			<div class="col-md-12">
				<?php get_template_part('inc/templates/course/progressbar'); ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</li>