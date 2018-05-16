<?php
/**
 * Learndash ProPanel Users Course Progress Reporting
 *
 * Available variables:
 * @var $courses WP_Query Query of LearnDash Courses
 */
?>

<div class="pager top">
	<p class="ld-propanel-reporting-pager-info">
		<button class="button button-simple first" title="<?php esc_attr_e( 'First Page', 'ld_propanel' ); ?>">&laquo;</button>
		<button class="button button-simple prev" >&lsaquo;</button>
		<span><?php _e('page', 'ld_propanel') ?> <span class="pagedisplay"></span></span>
		<button class="button button-simple next">&rsaquo;</button>
		<button class="button button-simple last" title="<?php esc_attr_e( 'Last Page', 'ld_propanel' ); ?>">&raquo;</button>
	</p>	
	<p class="search-wrap"><input class="tablesorter-search" type="search" placeholder="<?php _e('Search', 'ld_propanel') ?>" data-column="all"></p>
</div>

<table id="table" class="tablesorter">
	<thead>
	<tr>
		<?php if ( 'full' == $container_type ) : ?>
			<th width="<?php echo $this->get_user_courses_column_width( $container_type, 'course_id' ); ?>" align="left" width="70%" data-sorter="false"><?php echo sprintf( _x( '%s ID', 'Course ID', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></th>
		<?php endif; ?>

		<th width="<?php echo $this->get_user_courses_column_width( $container_type, 'course' ); ?>" align="left" width="70%" data-sorter="false"><?php echo sprintf( _x('%s', 'Course', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></th>

		<th width="<?php echo $this->get_user_courses_column_width( $container_type, 'progress' ); ?>" align="left" width="30%" data-sorter="false"><?php esc_html_e( 'Progress', 'ld_propanel' ); ?></th>

		<?php if ( 'full' == $container_type ) : ?>
			<th width="<?php echo $this->get_user_courses_column_width( $container_type, 'last_update' ); ?>" align="left" width="70%" data-sorter="false"><?php esc_html_e( 'Completed On', 'ld_propanel' ); ?></th>
		<?php endif; ?>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<div class="pager bottom">
	<p class="ld-propanel-reporting-pager-info">
		<button class="button button-simple first" title="<?php esc_attr_e( 'First Page', 'ld_propanel' ); ?>">&laquo;</button>
		<button class="button button-simple prev" >&lsaquo;</button>
		<span><?php _e('page', 'ld_propanel') ?> <span class="pagedisplay"></span></span>
		<button class="button button-simple next">&rsaquo;</button>
		<button class="button button-simple last" title="<?php esc_attr_e( 'Last Page', 'ld_propanel' ); ?>">&raquo;</button>
	</p>	
	<p class="search-wrap"><input class="tablesorter-search" type="search" placeholder="<?php _e('Search', 'ld_propanel') ?>" data-column="all"></p>
</div>