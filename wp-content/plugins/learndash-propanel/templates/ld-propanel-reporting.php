<?php
/**
 * Learndash ProPanel Reporting
 */
?>

<div class="reporting-wrap">

	<div class="table-actions-wrap">

		<div class="section-toggles clearfix">
			<a href="#table-filters" title="<?php esc_attr_e( 'Filters', 'ld_propanel' ); ?>" class="button section-toggle"><?php esc_html_e( 'Filters', 'ld_propanel' ); ?></a>
			<a href="#email" title="<?php esc_attr_e( 'Email', 'ld_propanel' ); ?>" class="button section-toggle email-toggle"><?php esc_html_e( 'Email', 'ld_propanel' ); ?></a>
			
			<a class="button full-page" href="<?php echo admin_url( '?page=propanel-reporting' ); ?>"><?php esc_html_e( 'Full Page', 'ld_propanel' ); ?></a>
			<a class="button dashboard-page" href="<?php echo admin_url( '/' ); ?>"><?php esc_html_e( 'Dashboard', 'ld_propanel' ); ?></a>
		</div>

		<div class="reporting-actions toggle-section" id="table-filters">
			<a href="#" title="<?php esc_attr_e( 'Close', 'ld_propanel' ); ?>" class="close"><?php esc_html_e( 'Close', 'ld_propanel' ); ?></a>

			<?php _e( 'Filter By:', 'ld_propanel' ); ?><br />
			<div class="filter-selection filter-section-type" style="display: inline-block;">
				<select id="ld_propanel_filter_type_select" class="filter-type-select" data-placeholder="<?php esc_html_e( 'Select Type', 'ld_propanel' ); ?>">
					<option></option>
					<option value="user"><?php esc_html_e( 'User', 'ld_propanel' ); ?></option>
					<option value="course"><?php echo sprintf( _x( '%s', 'Course', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></option>
				</select>
			</div>

			<div class="select2-selection filter-section-users-courses"  style="display: inline-block;">
				<select class="user select2" data-ajax--cache="true">
					<option value=""><?php esc_html_e( 'Select User', 'ld_propanel' ); ?></option>
				</select>
				<select class="course select2" data-ajax--cache="true">
					<option value=""><?php echo sprintf( _x( 'Select %s', 'Select Course', 'ld_propanel' ), LearnDash_Custom_Label::get_label( 'course' ) ); ?></option>
				</select>
			</div>

			<div class="filter-selection filter-section-status" style="display: inline-block;">
				<select class="course course-status" data-placeholder="<?php esc_html_e( 'Statuses', 'ld_propanel' ); ?>">
					<option></option>
					<option value="all"><?php esc_html_e( 'All Statuses', 'ld_propanel' ); ?></option>
					<option value="not-started"><?php esc_html_e( 'Not Started', 'ld_propanel' ); ?></option>
					<option value="in-progress"><?php esc_html_e( 'In Progress', 'ld_propanel' ); ?></option>
					<option value="completed"><?php esc_html_e( 'Completed', 'ld_propanel' ); ?></option>
				</select>
			</div>

			<p>
				<?php esc_html_e( 'Per Page:', 'ld_propanel' ); ?>
				<?php
					$per_page_array = ld_propanel_get_pager_values(); 
					if ( !empty( $per_page_array ) ) {
						?><select id="ld-propanel-pagesize" class="pagesize"><?php
						$selected_per_page = 0;
						foreach( $per_page_array as $per_page ) {
							if ( empty( $selected_per_page ) ) $selected_per_page = $per_page;
							?><option <?php selected( $selected_per_page, $per_page ) ?> value="<?php echo abs( intval( $per_page ) ) ?>"><?php echo abs( intval( $per_page ) ) ?></option><?php
						}
						?></select><?php
					}
				?>
			</p>

			<p><button type="button" class="button button-primary filter"><?php esc_html_e( 'Filter', 'ld_propanel' ); ?></button>  <button type="button" class="button reset"><?php esc_html_e( 'Reset', 'ld_propanel' ); ?></button> 
				
				<button class="button button-primary download-reporting" data-template="download-reporting" data-nonce="<?php echo wp_create_nonce( 'learndash-data-reports-user-courses-'. get_current_user_id() ); ?>" data-slug="user-courses" type="button"><?php esc_html_e( 'Download', 'ld_propanel' ); ?><span class="status"></span></button> 
				
				<?php /* ?><a class="button full-page" href="<?php echo admin_url( '?page=propanel-reporting' ); ?>"><?php esc_html_e( 'Full Page', 'ld_propanel' ); ?></a><?php */ ?></p>


		</div>

		<div class="email toggle-section" id="email">
			<div class="no-results">
				<strong class="note"><?php esc_html_e( 'Please select user(s) to send an email to.', 'ld_propanel' ); ?></strong>
			</div>
			<div class="results" style="display:none;">
				<a href="#" title="<?php esc_attr_e( 'Close', 'ld_propanel' ); ?>" class="close"><?php esc_attr_e( 'Close', 'ld_propanel' ); ?></a>
				<input type="text" class="subject" placeholder="Subject">
				<textarea rows="10" class="message" placeholder="<?php _e('Your Message', 'ld_propanel') ?>"></textarea>
				<button id="propanel-send-email" class="button button-primary" disabled><?php printf( esc_html__( 'Send - Selected (%s)', 'ld_propanel' ), '<span>0</span>' ); ?></button>
				<span class="sending" style="display:none;">
					<?php esc_html_e( 'Sending...', 'ld_propanel' ); ?>
					<img src="<?php echo admin_url( 'images/spinner.gif' ); ?>">
				</span>
				<span class="sent" style="display:none;">
					<?php esc_html_e( 'Sent!', 'ld_propanel' ); ?>
				</span>
			</div>
		</div>

	</div>
</div>