<?php $menu = mbb_get_builder_menu();
	require MBB_INC_DIR . 'components/tabs.php';
?>

<div class="builder-gui nav-menus-php" ng-app="Builder">

	<div id="nav-menus-frame" ng-controller="BuilderController" ng-init="init()">

		<div id="menu-settings-column" class="metabox-holder">
			<div id="side-sortables" class="accordion-container">
				<ul class="outer-border">
					<?php foreach ( $menu as $block => $fields ):
						$open = ( $block === 'Text' ) ? 'open' : '';
						?>
						<li class="control-section accordion-section <?php echo $open ?>">
							<h3 class="accordion-section-title hndle" tabindex="0"><?php echo $block ?></h3>
							<span class="screen-reader-text"><?php _e( 'Press return or enter to expand', 'meta-box-builder' ); ?></span>

							<div class="accordion-section-content">
								<div class="inside">
									<?php
									$i = 1;
									foreach ( $fields as $key => $value ): $i ++; ?>
										<button type="button" class="button-secondary" ng-click="addField('<?php echo $key ?>');"><?php echo $value ?></button>
										<?php
										if ( $i % 2 )
											echo '<p></p>';
									endforeach; ?>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div id="menu-management-liquid">
			<div id="menu-management">
				<div class="menu-edit">
					<div id="nav-menu-header">
						<div class="major-publishing-actions">
							<label class="menu-name-label howto open-label" for="menu-name"><?php _e( 'Meta Box Title', 'meta-box-builder' ); ?></label>
							<input name="post_title" ng-model="meta.title" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" placeholder="<?php _e( 'Enter name here', 'meta-box-builder' ); ?>">
							<div class="publishing-action">
								<input type="submit" id="bind_submit" name="save_metabox" class="button button-primary menu-save" value="<?php _e( 'Save Meta Box', 'meta-box-builder' ); ?>">
							</div><!-- END .publishing-action -->
						</div><!-- END .major-publishing-actions -->
					</div>

					<div id="post-body">
						<div id="post-body-content">

							<h3><?php _e( 'Meta Box Fields', 'meta-box-builder' ); ?></h3>
							<p ng-show="meta.fields.length == 0">
								<?php _e( 'Add meta box fields from the column on the left.', 'meta-box-builder' ); ?>
							</p>
							<p ng-show="meta.fields.length > 0">
								<?php _e( 'Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.', 'meta-box-builder' ); ?>
							</p>

							<tg-dynamic-directive ng-model="meta" tg-dynamic-directive-view="getView"></tg-dynamic-directive>

							<script type="text/ng-template" id="nestable_item.html">
								<section ng-if="ngModelItem.type">
									<dl class="menu-item-bar {{ngModelItem.type}}">
										<dt class="menu-item-handle">
											<span class="item-title">
												<span class="menu-item-title" ng-show="ngModelItem.type != 'tab'">{{ngModelItem.name}}</span>
												<span class="menu-item-title" ng-show="ngModelItem.type == 'tab'">
													<i class="wp-menu-image dashicons-before {{ngModelItem.icon}}"></i> {{ngModelItem.label}}
												</span>
											</span>
											<span class="item-controls">
												<span class="item-type">{{ngModelItem.type}}</span>
												<a class="item-edit" ng-click="toggleEdit(ngModelItem, $event)"><?php _e( 'Edit', 'meta-box-builder' ); ?></a>
											</span>
										</dt>
									</dl>

									<div class="menu-item-settings" ng-show="ngModelItem==active">
										<div class="field-edit-content" ng-include src="ngModelItem.type + '.edit.html'" role="tabpanel"></div>
										<div class="menu-item-actions description-wide submitbox">
											<a href="#" role="button" class="submitdelete item-delete deletion" ng-click="removeField(ngModelItem)"><?php _e( 'Remove', 'meta-box-builder' ); ?></a>
											<span class="meta-sep hide-if-no-js"> | </span>
											<a href="#" role="button" class="submitcancel item-cancel" ng-click="unEdit($event)"><?php _e( 'Cancel', 'meta-box-builder' ); ?></a>
											<a ng-show="ngModelItem.type != 'tab' && ngModelItem.type != 'group'" href="#" role="button" class="submitduplicate item-cancel" ng-click="cloneField(ngModelItem)"><?php _e( 'Duplicate', 'meta-box-builder' ); ?></a>
										</div>
									</div>
								</section>

								<ul class="apps-container menu ui-sortable" ui-sortable="sortableOptions" ng-model="ngModelItem.fields">
									<li style="display: none; visibility: hidden;" ng-if="field.type=='group'"></li>
									<li class="menu-item builder-field {{field.type}} {{field.id==active.id}}" ng-repeat="field in ngModelItem.fields track by field.id+$index">
										<tg-dynamic-directive ng-model="field" tg-dynamic-directive-view="getView">
										</tg-dynamic-directive>
									</li>
								</ul>
							</script>

						</div><!--#post-body-content-->
					</div><!--#post-body-->

					<div id="nav-menu-footer">
						<div class="major-publishing-actions">
              <span class="delete-action">
                <a class="submitdelete deletion menu-delete" href="<?php echo get_delete_post_link(); ?> "><?php _e( 'Delete Meta Box', 'meta-box-builder' ); ?></a>
              </span><!-- END .delete-action -->
							<div class="publishing-action">
								<input type="submit" id="bind_submit" name="save_metabox" class="button button-primary menu-save" value="<?php _e( 'Save Meta Box', 'meta-box-builder' ); ?>">
							</div><!-- END .publishing-action -->
						</div><!-- END .major-publishing-actions -->
					</div><!--#nav-menu-footer-->

				</div>
			</div>
		</div>

		<?php
		// Generate script content for each template
		foreach ( $menu as $block => $fields ):
			foreach ( $fields as $k => $v ) : ?>

				<script type="text/ng-template" id="<?php echo $k ?>.edit.html">
					<?php mbb_get_field_edit_content( $k ); ?>
				</script>

			<?php endforeach; endforeach; ?>

		<datalist id="available_fields">
			<option ng-repeat="field in available_fields track by $index" value="{{field}}">
		</datalist>

		<input type="hidden" name="excerpt" value="{{meta}}" />

    </div><!-- Builder Controller all code should here-->

</div><!--.builder-gui-->
