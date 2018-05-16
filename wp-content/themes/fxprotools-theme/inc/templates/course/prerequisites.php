<?php
global $course_prerequisites;
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">	
			<div class="fx-course-prerequisites">	
				<h3>To take this course, you need to complete the following course first:</h3>
				<?php 
					$post_links = ''; 
					if ( !empty( $course_prerequisites ) ){
							foreach( $course_prerequisites as $id => $status ) {
							if ( $status === false ) {
								if ( !empty( $post_links ) ) $post_links .= ', ';
								$post_links .= '<a href="'. get_the_permalink( $id ) .'">'. get_the_title( $id ) .'</a>';
							}
						}
					}
				?>
				<div class="post-link">	
					<?php if ( !empty( $post_links ) ) echo $post_links; ?>
				</div>
			</div>
		</div>
	</div>
</div>
