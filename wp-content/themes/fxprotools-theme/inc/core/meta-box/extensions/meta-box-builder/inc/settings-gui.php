<?php require MBB_INC_DIR . 'components/tabs.php'; ?>

<div id="settings-gui" ng-app="Builder">

    <div class="menu-settings" ng-controller="BuilderController" ng-init="init()">

        <table class="table">
            <thead>
                <tr>
                    <th><?php _e( 'General', 'meta-box-builder' ); ?></th>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php _e('Priority', 'meta-box-builder'); ?></td>
                    <td>
                        <ul class="builder-grid">
                            <li class="builder-col">
                                <label>
                                    <input type="radio" ng-model="meta.priority" name="priority"
                                           value="high"> <?php _e('High', 'meta-box-builder'); ?>
                                </label>
                            </li>
                            <li class="builder-col">
                                <label>
                                    <input type="radio" ng-model="meta.priority" name="priority"
                                           value="low"> <?php _e('Low', 'meta-box-builder'); ?>
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Context', 'meta-box-builder'); ?></td>
                    <td>
                        <ul class="builder-grid">
                            <li class="builder-col">
                                <label>
                                    <input type="radio" ng-model="meta.context" name="context"
                                           value="normal"> <?php _e('Normal', 'meta-box-builder'); ?>
                                </label>
                            </li>
                            <li class="builder-col">
                                <label>
                                    <input type="radio" ng-model="meta.context" name="context"
                                           value="advanced"> <?php _e('Advanced', 'meta-box-builder'); ?>
                                </label>
                            </li>
                            <li class="builder-col">
                                <label>
                                    <input type="radio" ng-model="meta.context" name="context"
                                           value="side"> <?php _e('Side', 'meta-box-builder'); ?>
                                </label>
                            </li>
                        </ul>

                    </td>
                </tr>

                <tr>
                    <td><?php _e('Post types', 'meta-box-builder'); ?></td>
                    <td><select id="select-post-types" multiple="multiple" ng-model="meta.pages"
                                ng-options="post_type as post_type for post_type in post_types"></select></td>
                </tr>

                <tr>
                    <td>
                        <label for="metabox-auto-save"><?php _e('Autosave', 'meta-box-builder'); ?></label>
                    </td>
                    <td>
                        <input id="metabox-auto-save" ng-true-value="'true'" ng-false-value="'false'" type="checkbox"
                               ng-model="meta.autosave"/>
                    </td>
                </tr>

                <tr ng-show="tabExists">
                    <td>
                        <label for="meta-box-tabs-style"><?php _e('Tabs', 'meta-box-builder'); ?></label>
                    </td>
                    <td>
                        <label><?php _e( 'Style', 'meta-box-builder' ); ?>
                            <select ng-model="meta.tab_style">
                                <option value="default"><?php _e( 'default', 'meta-box-builder' ); ?></option>
                                <option value="box"><?php _e( 'box', 'meta-box-builder' ); ?></option>
                                <option value="left"><?php _e( 'left', 'meta-box-builder' ); ?></option>
                            </select>
                        </label>

                        <label><?php _e( 'Wrapper', 'meta-box-builder' ); ?>
                            <input id="meta-box-tabs-wrapper" type="checkbox" ng-model="meta.tab_wrapper" ng-true-value="'true'"
                               ng-false-value="'false'"/>
                        </label>
                    </td>
                </tr>

            </tbody>

            <thead>
                <tr>
                    <th><?php _e( 'Custom Attributes', 'meta-box-builder' ); ?></th>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="2">
                        <table style="max-width: 690px" ng-show="meta.attrs.length > 0">
                            <tr>
                                <td><?php _e( 'Key', 'meta-box-builder' ); ?></td>
                                <td><?php _e( 'Value', 'meta-box-builder' ); ?></td>
                                <td></td>
                            </tr>
                            <tr ng-repeat="attr in meta.attrs track by $index">
                                <td class="col-xs-5" width="45%">
                                    <input ng-keydown="navigate($event, active.id, $index, 'key')"
                                           ng-enter="addMetaBoxAttribute()" focus-on="metabox_key_{{$index}}"
                                           type="text" class="form-control col-sm-6 input-sm" ng-model="attr.key"
                                           placeholder="Enter key"/>
                                </td>

                                <td class="col-xs-6" width="45%">
                                    <textarea style="width: 100%" type="text" class="form-control col-sm-6 input-sm"
                                              ng-model="attr.value" placeholder="Enter value"></textarea>
                                </td>

                                <td class="col-xs-1" width="5%">
                                    <button type="button" class="button" ng-click="removeMetaBoxAttribute($index);">
                                        <span class="dashicons dashicons-trash"></span></button>
                                </td>
                            </tr>
                        </table>
                        <button type="button" class="button" ng-click="addMetaBoxAttribute();"><?php _e( 'Add Custom Attribute', 'meta-box-builder' ); ?>
                        </button>
                    </td>
                </tr>
            </tbody>

            <thead>
                <tr>
                    <th><?php _e( 'Conditional Logic', 'meta-box-builder' ); ?></th>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <section class="builder-conditional-logic" ng-show="meta.logic">
                            <label>
                                <?php _e( 'This conditional logic applies for current Meta Box, for fields conditional logic, please
                                go to each field and set.', 'meta-box-builder' ); ?>
                            </label><br>

                            <select ng-model="meta.logic.visibility">
                                <option value="visible"><?php _e( 'Visible', 'meta-box-builder' ); ?></option>
                                <option value="hidden"><?php _e( 'Hidden', 'meta-box-builder' ); ?></option>
                            </select>

                            <code><?php _e( 'when', 'meta-box-builder' ); ?></code>

                            <select ng-model="meta.logic.relation">
                                <option value="and"><?php _e( 'All', 'meta-box-builder' ); ?></option>
                                <option value="or"><?php _e( 'Any', 'meta-box-builder' ); ?></option>
                                <select>

                                    <code><?php _e( 'of these conditions match', 'meta-box-builder' ); ?></code>

                                    <table class="table" style="max-width: 690px">
                                        <tr>
                                            <td><?php _e( 'Fields', 'meta-box-builder' ); ?></td>
                                            <td><?php _e( 'Is', 'meta-box-builder' ); ?></td>
                                            <td><?php _e( 'Value', 'meta-box-builder' ); ?></td>
                                            <td></td>
                                        </tr>
                                        <tr ng-repeat="item in meta.logic.when track by $index">
                                            <td width="35%">
                                                <input type="text" ng-model="meta.logic.when[$index][0]"
                                                       list="available_fields" placeholder="Select or enter a field...">
                                            </td>
                                            <td width="15%">
                                                <select ng-model="meta.logic.when[$index][1]">
                                                    <option value="=">=</option>
                                                    <option value=">">></option>
                                                    <option value="<">&lt;</option>
                                                    <option value=">=">>=</option>
                                                    <option value="<=">&lt;=</option>
                                                    <option value="!=">!=</option>
                                                    <option value="contains">contains</option>
                                                    <option value="not contains">not contains</option>
                                                    <option value="starts with">starts with</option>
                                                    <option value="not starts with">not starts with</option>
                                                    <option value="ends with">ends with</option>
                                                    <option value="not ends with">not ends with</option>
                                                    <option value="between">between</option>
                                                    <option value="not between">not between</option>
                                                    <option value="in">in</option>
                                                    <option value="not in">not in</option>
                                                    <option value="match">match</option>
                                                    <option value="not match">not match</option>
                                                </select>
                                            </td>
                                            <td width="35%">
                                                <input type="text" ng-model="meta.logic.when[$index][2]"
                                                       placeholder="Enter a value...">
                                            </td>
                                            <td width="5%">
                                                <button type="button" class="button"
                                                        ng-click="removeConditionalLogic($index, 'meta');">
                                                    <span class="dashicons dashicons-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                        </section>
                        <button type="button" class="button" ng-click="addConditionalLogic('meta');"><?php _e( 'Add Conditional Logic', 'meta-box-builder' ); ?>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php Meta_Box_Show_Hide_Template::show(); ?>
        <?php Meta_Box_Include_Exclude_Template::show(); ?>

        <input type="hidden" name="excerpt" value="{{meta}}"/>
        <input type="hidden" name="tab" value="settings">
    </div><!--.menu-settings-->
</div>

<div class="publishing-action">
    <input type="submit" id="bind_submit" name="save_metabox" class="button button-primary menu-save"
           value="<?php _e('Save Changes', 'meta-box-builder'); ?>">
</div><!-- END .publishing-action -->