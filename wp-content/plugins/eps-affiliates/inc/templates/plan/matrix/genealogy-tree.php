  <?php 
   /* get all the downlines of this user */
   $uid = get_current_user_id();
   $uid = 37;
   $query = array();
   $query['#select'] = 'wp_afl_user_downlines';
   $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_user_downlines`.`downline_user_id`'
      )
    );
   $query['#where'] = array(
      '`wp_afl_user_downlines`.`uid`='.$uid.'',
      '`wp_afl_user_downlines`.`level`=1',
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
    <!--Genealogy Hierarchy-->
    <section class="genealogy-hierarchy">
      <div class="hv-container">
            <div class="hv-wrapper">

                <!-- Parent -->
                <div class="hv-item">

                    <div class="hv-item-parent">
                        <div class="person">
                            <img src="https://pbs.twimg.com/profile_images/762654833455366144/QqQhkuK5.jpg" alt="">
                            <span class="genealogy-user-name">
                                <?php echo$parent->display_name; ?>
                            </span>
                        </div>
                    </div>

                    <div class="hv-item-children">
                        <!-- first level user exist or not -->
                        <?php if (!empty($level[1])) : ?>
                          <div class="hv-item-child hv-item-child-wrapper">
                              <!-- Key component -->
                              <div class="hv-item">

                                  <div class="hv-item-child">
                                      <div class="person">
                                          <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                          <span class="genealogy-user-name">
                                            <b><?php echo $tree[$level[1]]->display_name; ?></b>
                                          </span>
                                          <span class="load-downlines" data-user-id="<?=$tree[$level[1]]->downline_user_id;?>"><i class="fa fa-plus fa-2x"></i></span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <?php else : ?>
                          <div class="hv-item-child hv-item-child-wrapper">
                              <!-- Key component -->
                              <div class="hv-item">

                                  <div class="hv-item-child">
                                      <div class="person">
                                          <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="">
                                          <span class="genealogy-user-name">
                                                   Wilner <b>/ Creative Director</b>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      <?php endif; ?>

                        <?php if (!empty($level[2])) : ?>
                          <div class="hv-item-child hv-item-child-wrapper">
                              <div class="hv-item">

                                  <div class="hv-item-child">
                                      <div class="person">
                                          
                                          <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                                          
                                          <span class="genealogy-user-name">
                                            <b><?php echo $tree[$level[2]]->display_name; ?></b>
                                              
                                          </span>
                                          <span class="load-downlines" data-user-id="<?=$tree[$level[2]]->downline_user_id;?>"><i class="fa fa-plus fa-2x"></i></span>

                                      </div>
                                  </div>


                              </div>
                          </div>
                        <?php else : ?>
                          <div class="hv-item-child hv-item-child-wrapper">
                              <!-- Key component -->
                              <div class="hv-item">

                                  <div class="hv-item-child">
                                      <div class="person">
                                          <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                                          <span class="genealogy-user-name">
                                              <?php echo 'No user'; ?>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($level[3])) : ?>
                          <div class="hv-item-child hv-item-child-wrapper">
                              <!-- Key component -->
                              <div class="hv-item">

                                  <div class="hv-item-child-end">
                                      <div class="person">
                                          <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="">
                                          <span class="genealogy-user-name">
                                            <b><?php echo $tree[$level[3]]->display_name; ?></b>
                                              
                                          </span>
                                          <span class="load-downlines" data-user-id="<?=$tree[$level[3]]->downline_user_id;?>"><i class="fa fa-plus fa-2x"></i></span>

                                      </div>
                                  </div>


                              </div>
                          </div>
                        <?php else : ?>
                          <div class="hv-item-child ">
                              <!-- Key component -->
                              <div class="hv-item">

                                  <div class="hv-item-child-end">
                                      <div class="person">
                                          <img src="<?= EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png'; ?>" alt="">
                                          <span class="genealogy-user-name">
                                              <?php echo 'No user'; ?>
                                              
                                          </span>
                                      </div>
                                  </div>

                              </div>
                          </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>
      </div>

      <div class="expanded-genealogy">
        
      </div>
    </section>

<?php endif; 