<?php 
   /* get all the downlines of this user */
   if (isset($_POST['uid']))  {
     $uid = $_POST['uid'];
     $query = array();
     $query['#select'] = _table_name('afl_user_downlines');
     $query['#join']  = array(
        'wp_users' => array(
          '#condition' => '`wp_users`.`ID`=`'._table_name('afl_user_downlines').'`.`downline_user_id`'
        )
      );
     $query['#where'] = array(
        '`'._table_name('afl_user_downlines').'`.`uid`='.$uid.'',
        '`'._table_name('afl_user_downlines').'`.`level`=1',
      );
     $query['#order_by'] = array(
        '`level`' => 'ASC'
      );
      $downlines = db_select($query, 'get_results');

      $tree = array();
      //get the downlines levels
      $levels = array();
      foreach ($downlines as $key => $row) {
        $tree[$row->downline_user_id] = $row;
        $level[$row->relative_position] = $row->downline_user_id;
      }
      $parent = afl_genealogy_node($uid);
      $plan_width = afl_variable_get('matrix_plan_width',3);
  if (!empty($parent)) :
  ?>

  <div class="hv-item-children">
  <?php 
  for ($i = 1; $i <= $plan_width; $i++) : 

    if (isset($level[$i])) : ?>
      <div class="hv-item-child">

          <div class="hv-item">
                <div class="">
                  <div class="person">
                      <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                      <p class="name">
                        <?= $tree[$level[$i]]->user_login.' ('.$tree[$level[$i]]->ID.')'; ?>
                      </p>
                    <span class="expand-tree" data-user-id ="<?= $level[$i];?>" onclick="expandToggleMatrixTree(this)">
                      <i class="fa fa-plus-circle fa-2x"></i>
                    </span>
                  </div>
                </div>
              <!-- check he has downlines -->
              <div class="append-child-<?= $level[$i];?>">
                
              </div>
              

          </div>
      </div>
    <?php else :  ?>
        <div class="hv-item-child">

          <div class="hv-item">
                <div class="">
                  <div class="person">
                      <div class="col-md-12">
                        <div class="holding-user">
                          <div class="">
                             <div class="person">
                                <input type="hidden" name="sponsor" id="sponsor" value="<?php echo get_uid(); ?>">
                                <div class="toggle-user-placement-toggle-area">
                                  
                                  <span class="toggle-left-arrow" data-toggle-uid="0" onclick="_toggle_holding_node_left(this)">
                                    <i class="fa fa-caret-left fa-5x"></i>
                                  </span>
                                  
                                  <div class="holding-toggle-user-image">
                                    <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/no-user.png';?>" alt="">
                                    <p>No user</p>
                                  </div>
                                  
                                  <span class="toggle-right-arrow" onclick="_toggle_holding_node_right(this)">
                                    <i class="fa fa-caret-right fa-5x"></i>
                                  </span>
                                </div>
                                  <div>
                                   <button class="toggle-save-placement-button" data-toggle-uid="0"  data-toggle-position='<?php echo $i; ?>' data-toggle-parent='<?php echo $parent->user_login.'('.$parent->uid.')'; ?>' onclick="_toggle_holding_node_place(this)">Save Placement</button>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </div>    
                   </div>
                </div>
          </div>
      </div>
    <?php endif; ?>
  <?php endfor;  ?>
  </div>

<?php
endif;
 } 
 die();
