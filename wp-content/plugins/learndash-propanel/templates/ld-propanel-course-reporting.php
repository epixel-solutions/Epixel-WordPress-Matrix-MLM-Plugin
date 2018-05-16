<?php
/**
 * Learndash ProPanel Courses User Progress Reporting
 *
 * Available variables:
 * @var $users array of WP_User objects
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
	<p class="search-wrap"><input class="tablesorter-search" type="search" placeholder="Search" data-column="all"></p>
</div>

<table id="table" class="tablesorter">
	<thead>
	<tr>
		<th width="<?php echo $this->get_course_users_column_width( $container_type, 'checkbox' ); ?>" class="sorter-checkbox checkbox-col" data-sorter="false"></th>

		<?php if ( 'full' == $container_type ) : ?>
		<th width="<?php echo $this->get_course_users_column_width( $container_type, 'user_id' ); ?>"align="left" data-sorter="false"><?php esc_html_e( 'User ID', 'ld_propanel' ); ?></th>
		<?php endif; ?>

		<th width="<?php echo $this->get_course_users_column_width( $container_type, 'user' ); ?>" align="left" class="user-col"><?php esc_html_e( 'User', 'ld_propanel' ); ?></th>

		<th width="<?php echo $this->get_course_users_column_width( $container_type, 'progress' ); ?>" align="left" data-sorter="false" class="progress-col"><?php esc_html_e( 'Progress', 'ld_propanel' ); ?></th>

		<?php if ( 'full' == $container_type ) : ?>
		<th width="<?php echo $this->get_course_users_column_width( $container_type, 'last_update' ); ?>" align="left"  data-sorter="false"><?php esc_html_e( 'Completed On', 'ld_propanel' ); ?></th>
		<?php endif; ?>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<div class="pager bottom">
	<p class="ld-propanel-reporting-pager-info">
		<button class="button button-simple first" title="<?php esc_attr_e( 'First Page', 'ld_propanel' ); ?>">&laquo;</button>
		<button class="button button-simple prev">&lsaquo;</button>
		<span><?php _e('page', 'ld_propanel') ?> <span class="pagedisplay"></span></span>
		<button class="button button-simple next">&rsaquo;</button>
		<button class="button button-simple last" title="<?php esc_attr_e( 'Last Page', 'ld_propanel' ); ?>">&raquo;</button>
	</p>
	<p class="search-wrap"><input class="tablesorter-search" type="search" placeholder="<?php _e('Search', 'ls_propanel') ?>" data-column="all"></p>
</div>