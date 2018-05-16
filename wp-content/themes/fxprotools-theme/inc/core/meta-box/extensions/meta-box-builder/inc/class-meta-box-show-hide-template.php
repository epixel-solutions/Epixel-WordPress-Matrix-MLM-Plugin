<?php

class Meta_Box_Show_Hide_Template
{
	public static function show()
	{
		if ( ! self::visible() )
			return;
		?>
		<div class="meta-box-sortables">
      <div class="postbox closed">
        <div class="handlediv" title="Click to toggle"> <br></div>
          <h3 class="hndle ui-sortable-handle">Show / Hide <span class="label">Extension</span></h3>
          <div class="inside">
            <dl class="extension" id="meta-box-show-hide">
              <dt></dt>
              <dd>

                <dl>
                  <dt class="one-half"><label for="meta-showhide-type">Show / Hide</label></dt>
                  <dd class="one-half">
                    <select id="meta-showhide-type" ng-model="meta.showhide.type" ng-init="meta.showhide.type = meta.showhide.type || 'off'">
                      <option value="off">Off (Always Show)</option>
                      <option value="show">Show</option>
                      <option value="hide">Hide</option>
                    </select>
                  </dd>
                </dl>
                
                <div ng-hide="meta.showhide.type=='off' || meta.showhide.type==''">

                <dl>
                  <dt class="one-half"><label for="meta-showhide-relation">Relation</label></dt>
                  <dd class="one-half">
                    <select id="meta-showhide-relation" ng-model="meta.showhide.relation" ng-init="meta.showhide.relation = meta.showhide.relation || 'OR'">
                      <option value="AND">AND</option>
                      <option value="OR">OR</option>
                    </select>
                  </dd>
                </dl>
                <div class="clear clearfix"></div>
                <?php $templates = mbb_get_page_templates(); 
                if ( ! empty( $templates ) ):
                ?>
                <dl>
                  <dt class"one-half"><label for="meta-showhide-template">Page Template</label></dt>
                  <dd>
                  <select id="meta-showhide-template" ng-model="meta.showhide.template" class="form-control" multiple>
                    <?php foreach ( $templates as $file => $name ) : ?>
                    <option value="<?php echo $file ?>"><?php echo $name ?></option>
                    <?php endforeach; ?>
                  </select>
                  </dd>
                </dl>
                <?php endif; ?>
                
                <?php $post_formats = mbb_get_post_formats(); 
                if ( ! empty( $post_formats ) ) :
                ?>
                <dl>
                  <dt class"one-half"><label for="meta-showhide-post_format">Post Format</label></dt>
                  <dd>
                    <select id="meta-showhide-post_format" ng-model="meta.showhide.post_format" class="form-control" multiple>
                      <?php foreach ( $post_formats as $format ) : ?>
                      <option value="<?php echo $format ?>"><?php echo str_title( $format ); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </dd>
                </dl>
                <?php endif; ?>
                
                <?php $term_taxonomies = mbb_get_terms(); 
                  if ( ! empty( $term_taxonomies ) ): 
                    foreach ( $term_taxonomies as $name => $terms ) :
                  ?>
                  <dl>
                    <dt class"one-half"><label for="meta-showhide-<?php echo $name ?>"><?php echo str_title( $name ); ?></label></dt>
                    <dd>
                      <select id="meta-showhide-<?php echo $name ?>" ng-model="meta.showhide.<?php echo $name ?>" class="form-control" multiple>
                        <?php foreach ( $terms as $id => $term_name ) : ?>
                        <option value="<?php echo $id ?>"><?php echo $term_name; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </dd>
                  </dl>
                <?php endforeach ; endif; ?>
                </div>

              </dd>
            </dl>
          </div>
        </div><!--.postbox-closed-->
      </div><!--.meta-box-sortables-->
    <?php
	}

	public static function visible()
	{
		return class_exists( 'RWMB_Show_Hide' );
	}
}