<?php 
   /* get all the downlines of this user */
   if (isset($_POST['uid']))  {
     $uid = $_POST['uid'];
     $table_name = _table_name('afl_user_downlines');
     $query = array();
     $query['#select'] = $table_name;
     $query['#join']  = array(
        'wp_users' => array(
          '#condition' => '`wp_users`.`ID`=`'._table_name('afl_user_downlines').'`.`downline_user_id`'
        ),
        _table_name('afl_user_genealogy') => array(
          '#condition' => '`'._table_name('afl_user_genealogy').'`.`uid`=`'._table_name('afl_user_downlines').'`.`downline_user_id`'
        ),
      );
    $query['#fields']  = array(
      _table_name('users') => array(
        'display_name',
        'user_login',
        'ID'
      ),
      _table_name('afl_user_downlines') => array(
        'downline_user_id',
        'uid',
        'relative_position',
        'level',
      ),
      _table_name('afl_user_genealogy') => array(
        'parent_uid',
        'status'
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
    // pr($downlines);
    $tree = array();
    //get the downlines levels
    $levels = array();
    $positions = array();
    foreach ($downlines as $key => $row) {
      $tree[$row->downline_user_id] = $row;
      $level[$row->relative_position] = $row->downline_user_id;
      $positions[$row->parent_uid][$row->relative_position] = $row->downline_user_id;
    }
    $parent = afl_genealogy_node($uid);
    
    $this_user_downlines =  isset($positions[$uid])  ? $positions[$uid] : array();
    ksort($this_user_downlines);
    
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
                      <?php 

                        if ( $tree[$level[$i]]->status == 0  ){ ?>
                          <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/block.png'; ?>" alt="">
                      <?php  } else { ?>
                          <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                      <?php }
                      ?>
                      <p class="name">
                        <?= $tree[$level[$i]]->user_login.' ('.$tree[$level[$i]]->ID.')'; ?>
                      </p>
                    <span class="expand-tree" data-user-id ="<?= $level[$i];?>" onclick="expandMatrixTree(this)">
                      <i class="fa fa-plus-circle fa-2x"></i>
                    </span>
                  </div>
                </div>
              <!-- check he has downlines -->
              <div class="append-child-<?= $level[$i];?>">
                
              </div>
              

          </div>
      </div>
    <?php else : ?>
        <div class="hv-item-child">

          <div class="hv-item">
                <div class="">
                  <div class="person">
                      <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/no-user.png'; ?>" alt="">
                      <p>No user</p>
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
