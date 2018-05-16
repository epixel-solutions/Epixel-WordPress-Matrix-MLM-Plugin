<?php
function afl_admin_test_codes(){
  echo afl_eps_page_header();
  echo afl_content_wrapper_begin();
    afl_test_codes_callback();
  echo afl_content_wrapper_begin();
}


function check_rank_achied() {
  new Afl_enque_scripts('common');
  $uid = 14885;
  $rank = 1;
 pr(_check_required_pv_meets($uid,$rank));
 pr(_check_required_gv_meets($uid,$rank));
 pr(_check_required_distributors_meets($uid,$rank));
 pr(_check_required_qualifications_meets($uid,$rank));
 pr(_check_required_customer_rule($uid,$rank));
  
}

function afl_test_codes_callback () {
pr(time());
  // }
// require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/common/bonus-incentive-calculation.php';
//     if (function_exists('_member_bonus_incentive_calculation')) {
//       _member_bonus_incentive_calculation();
//     }

  // $afl_date = afl_date();
  //   //get the purchase details today
  //   $query = array();
  //   $query['#select'] = _table_name('afl_purchases');
  //   $query['#fields'] = array(
  //     _table_name('afl_purchases') => array('uid')
  //   );
  //   $query['#where'] = array(
  //     '`'._table_name('afl_purchases').'`.`created`= '.$afl_date,
  //     '`'._table_name('afl_purchases').'`.`cron_status` != 2',
  //     '`'._table_name('afl_purchases').'`.`category` = "product purchase"',
  //   );
  //   $query['#limit'] = 500;
  //   $data = db_select($query, 'get_results');


  //   pr($data);
  // $str = unserialize('a:10:{s:8:"userDbId";s:4:"3808";s:9:"userMlmId";s:7:"1001557";s:4:"name";s:15:"Kholeka Kholeka";s:5:"email";s:17:"lineok2@gmail.com";s:12:"phone_number";s:10:"0827016716";s:6:"status";s:6:"Active";s:13:"auth_sub_date";a:3:{s:4:"date";s:26:"2017-06-05 10:38:03.000000";s:13:"timezone_type";s:1:"3";s:8:"timezone";s:3:"UTC";}s:20:"auth_merchant_number";s:1:"1";s:12:"sponsor_name";s:12:"Thandi Ncube";s:13:"sponsor_mlmid";s:7:"1001520";}');
  // pr($str,1); 

  // if ( afl_variable_get('afl_enable_que_processing')) {
    /*require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-remote-user-embedd-cron-callback.php';
    if (function_exists('_process_embedd_users_queue')) {
      _process_embedd_users_queue();
    }*/
  // }



     // pr($roles);
  /*$args = array(
        'role' => 'holding_member',
      );
     $users = get_users($args);
     pr($users);
*/
//      foreach ($users as $key => $value) {
//       if ($value->ID != afl_root_user())
//         wp_delete_user($value->ID);
//      }

  // $table_name = 'wp_afl_user_exort_data';
  //     $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
  //             `id` int(11) NULL,
  //             `userDbId` int(11) NULL,
  //             `userMlmId` int(11) NULL,
  //             `name` varchar(60)  NULL DEFAULT '',
  //             `email` varchar(100) NULL DEFAULT '',
  //             `phone_number` int(11) NULL,
  //             `status` varchar(60)  NULL DEFAULT '',
  //             `auth_sub_date` int(11) NULL,
  //             `auth_sub_date__date` varchar(60)  NULL DEFAULT '',
  //             `auth_sub_date__timezone_type` int(11) NULL,
  //             `auth_sub_date__timezone` varchar(60)  NULL DEFAULT '',
  //             `auth_merchant_number` int(11) NULL,
  //             `sponsor_name` varchar(60)  NULL DEFAULT '',
  //             `sponsor_mlmid` int(11) NULL,
  //             `sponsor_db_id` int(11) NULL,
  //             `sponsor_email` varchar(100) NULL DEFAULT '',
  //             `sponsor_phone_number` int(11) NULL,
  //             `sponsor_status` varchar(60)  NULL DEFAULT ''
  //           ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  //           pr($sql);
      // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      // // dbDelta( $sql );
      // global $wpdb;
      // //indexes
      // $wpdb->query( "ALTER TABLE wp_afl_purchases ADD COLUMN `cron_status` int(10) unsigned NOT NULL DEFAULT '0'" );
      // //AUTO increment
      // $wpdb->query( 'ALTER TABLE `'.$table_name.'`
      //                 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;' );
   /*$uid = get_uid();
  
  if (isset($_POST['search_key'])) {
    $search_key = $_POST['search_key'];
  }

  $response = array();

  $tree = _table_name('afl_user_downlines');
  if ( !empty($_POST['tree_mode']) && $_POST['tree_mode'] == 'unilevel') {
    $tree = _table_name('afl_unilevel_user_downlines');
  }

  $query = array();
  $query['#select']  = $tree;
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'.$tree.'`.`downline_user_id` '
    )
  );
  // if (!eps_is_admin()) {
    $query['#where'] = array(
      '`'.$tree.'`.`uid` = '.$uid
    );
  // }
  $query['#fields'] = array(
    _table_name('users') => array('user_login','ID')
  );
  // $query['#expression'] = array(
  //   'DISTINCT(`'._table_name('users').'`.`user_login`) as `user_login`'
  // );
  $result = db_select($query, 'get_results');
  

  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  pr($response);*/
  
    /*if ( afl_variable_get('afl_enable_que_processing')) {
    require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-remote-user-embedd-cron-callback.php';
    if (function_exists('_process_embedd_users_queue')) {
      _process_embedd_users_queue();
    }
  }*/
  // do_action('eps_affiliates_place_user_in_holding_tank',14190,162);
  // $query = array();
  // $query['#select'] = _table_name('afl_purchases');
  // $query['#join']  = array(
  //  _table_name('users') => array(
  //     '#condition' =>  _table_name('users').'.`ID`'.'='._table_name('afl_purchases').'.`uid`',
  //   ),
  // ); 
  // if (!empty($limit) ) {
  //   $query['#limit'] = $filters['index'].','.$filters['limit'];
  // }
  // $res = db_select($query, 'get_results');
  // pr($res);

  // require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/global-pool-bonus-calculation.php';
  // if (function_exists('_calculate_global_pool_bonus')) {
  //   _calculate_global_pool_bonus();
  // }
// $params['category'] = 2;     // Overwrite if exists
// $params['name'] = 2;     // Overwrite if exists
    

// pr(http_build_query($params));

// pr(afl_build_url('http://example.com/search', $params));
// pr( $afl_date_splits = afl_date_splits($date));
//   pr(_get_company_profit_monthly());

}

function afl_admin_global_pool_check_user () {
    $pagination = new CI_Pagination;

    $config['total_rows'] =  (_pool_bonus_check(array(),TRUE));
    $config['base_url']   = '?page=affiliate-eps-unilevel-all-customers';
    $config['per_page']   = 50;

    
    $index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
    $filter = array(
      'index' => $index,
      'limit' => $config['per_page']
    );
    $data  = _pool_bonus_check($filter);
    
    $pagination->initialize($config);
    $links = $pagination->create_links();

    $table = array();
    $table['#links']      = $links;
    $table['#name']       = '';
    // $table['#title']       = 'Overall system Purchases';
    $table['#prefix']     = '';
    $table['#suffix']     = '';
    $table['#attributes'] = array(
            'class' => array(
                'table',
                'table-bordered',
                'my-table-center',
              )
            );

    $table['#header'] = array(
      __('#'),
      __('user ID'),
      __('Username'),
      __('Sponsor'),
      __('Parent'),
    );
    $rows = array();
    foreach ($data as $key => $value) {
      $rows[$key]['markup_0'] = array(
        '#type' =>'markup',
        '#markup'=> ($index * 1) + ($key + 1)
      );

      $rows[$key]['markup_uid'] = array(
        '#type' =>'markup',
        '#markup'=> $value->uid
      );
      $rows[$key]['markup_uname'] = array(
        '#type' =>'markup',
        '#markup'=> $value->display_name
      );

      $sponsor_node  = afl_user_data($value->referrer_uid);
      $rows[$key]['markup_sponsor'] = array(
        '#type' =>'markup',
        '#markup'=> !empty($sponsor_node->display_name) ? $sponsor_node->display_name : 'unavailable'
      );

      $parent_node  = afl_user_data($value->parent_uid);
      $rows[$key]['markup_parent'] = array(
        '#type' =>'markup',
        '#markup'=> !empty($parent_node->display_name) ? $parent_node->display_name : 'unavailable'
      );
    }
  
    $table['#rows'] = $rows;

    echo apply_filters('afl_render_table',$table);
}

function _pool_bonus_check () {

}





function insertuser () {
  $uid  = 162;
  for ($rank = 13; $rank >0; $rank--)  :
  $below_rank = $rank - 1;
  $meets_flag = 0;

  if ( $below_rank > 0 ){
    //loop through the below ranks qualifications exists or not
    for ( $i = $below_rank; $i > 0; $i-- ) {
      pr(' ----------------------------------------------------------- ');
      pr('Main Rank : '.$rank);
      pr('Rank : '.$i);
      /*
       * --------------------------------------------------------------
       * get the required rank holders neede in one leg
       * --------------------------------------------------------------
      */
        $required_in_one_count = afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_count', 0);
        pr( "Required in 1 leg : ". $required_in_one_count);
      if ( $required_in_one_count ) {
        /*
         * --------------------------------------------------------------
         * get the required count in how many legs
         * --------------------------------------------------------------
        */
          $required_in_legs_count    = afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_in_legs ', 0);
          pr("Coutable legs : ".$required_in_legs_count);
        //if in legs count specified
        if ( $required_in_legs_count ) {
          /*
           * ---------------------------------------------------------------
           * get the first level downlines of this user
           * get count of the first level users having the rank
           * if the rank users exists set the status as 1
           * else unset status as 0
           * this status adds to the condition_statuses array
           *
           * count the occurence of 0 and 1 in this array
           *
           * if the occurence of status is greater than or equals the count of
           *  required in howmany legs count set the meets flag
           * else unset
           * ---------------------------------------------------------------
          */


          $downlines = afl_get_user_downlines_uid($uid, array('level'=>1), false);

          $condition_statuses  = array();
          //find the ranks ($i) of this downlines
          foreach ($downlines as $key => $value) {
              //get the downlines users downlines count having the rank $i
              $down_downlines_count = afl_get_user_downlines_uid($value->downline_user_id, array('member_rank'=>$i), true);
              if ( $down_downlines_count )
                $status = 1;
              else
                $status = 0;
              $condition_statuses[] = $status;
          }
          //count the occurence of 1 and 0
          $occurence = array_count_values($condition_statuses);

          //if the occurence of 1 is greater than or equals the count of legs needed it returns true
          if ( isset($occurence[1])  && $occurence[1] >= $required_in_legs_count ){
            $meets_flag = 1;
          } else {
            $meets_flag = 0;
            break;
          }

        } else {
          /*
           * ---------------------------------------------------------------
           * get the first level downlines of this user
           * get count of the first level users having the rank
           * if the count meets required_count_in_leg set meets_flag
           * else unset
           * ---------------------------------------------------------------
          */
            $downlines = array();
            $result = afl_get_user_downlines_uid($uid, array('level'=>1), false);
            foreach ($result as $key => $value) {
              $downlines[] = $value->downline_user_id;
            }

            $implodes = implode(',', $downlines);
            //check the ranks under this users
            $query = array();

            $query['#select'] = _table_name('afl_user_downlines');
            $query['#where'] = array(
              '`'._table_name('afl_user_downlines').'`.`member_rank`='.$i,
              '`'._table_name('afl_user_downlines').'`.`uid` IN ('.$implodes.')'
            );
            $query['#expression'] = array(
              'COUNT(`'._table_name('afl_user_downlines').'`.`member_rank`) as count'
            );
            $result = db_select($query, 'get_row');
            $rank_existed_count = $result->count;

            // foreach ($downlines as $key => $value) {
            //   //get the downlines users downlines count having the rank $i
            //   $down_downlines_count = afl_get_user_downlines_uid($value->downline_user_id, array('member_rank'=>$i), true);
            //   pr($down_downlines_count);
              if ( $rank_existed_count >= $required_in_one_count ){
                $meets_flag = 1;
              } else {
                $meets_flag = 0;
                break;
              }
            // }
        }
      } else {
        $meets_flag = 1;
      }

      pr(' ----------------------------------------------------------- ');
    }
  }
  pr('Rank '.$rank. " -" .$meets_flag);
endfor;
}
