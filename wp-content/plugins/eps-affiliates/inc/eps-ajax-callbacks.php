<?php

function _system_user_autocomplete ( $search_key ) {
  if (isset($_POST['search_key'])) {
    $search_key = $_POST['search_key'];
  }
 
  $query = array();
  $query['#select']  =_table_name('users');
  
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  
  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  echo json_encode($response);
  die();
}

/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
function users_auto_complete_callback($search_key = '') {
  if (isset($_POST['search_key'])) {
    $search_key = $_POST['search_key'];
  }
  $data     = afl_get_users();
  $response = array();

  global $wpdb;
  $querystr = " SELECT * from `"._table_name('users')."` WHERE `display_name` LIKE '%".$search_key."%' ;";


  $query = array();
  $query['#select']  =_table_name('afl_user_genealogy');
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'._table_name('afl_user_genealogy').'`.`uid` '
    )
  );
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  
  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  echo json_encode($response);
  die();
}
/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
function unilevel_users_auto_complete_callback($search_key = '') {
	if (isset($_POST['search_key'])) {
		$search_key = $_POST['search_key'];
	}
	$data 		= afl_get_users();
	$response = array();

  $query = array();
  $query['#select']  =_table_name('afl_unilevel_user_genealogy');
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'._table_name('afl_unilevel_user_genealogy').'`.`uid` '
    )
  );
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  
	foreach ($result as $key => $value) {
		$response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
	}
	echo json_encode($response);
	die();
}



/*
 * ------------------------------------------------
 * Customers auto complete
 * ------------------------------------------------
*/
function customers_auto_complete_callback($search_key = '') {
  if (isset($_POST['search_key'])) {
    $search_key = $_POST['search_key'];
  }
  $data     = afl_get_users();
  $response = array();

  global $wpdb;
  $querystr = " SELECT * from `"._table_name('users')."` WHERE `display_name` LIKE '%".$search_key."%' ;";


  $query = array();
  $query['#select']  =_table_name('afl_customer');
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'._table_name('afl_customer').'`.`uid` '
    )
  );
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  
  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  echo json_encode($response);
  die();
}



/*
 * ------------------------------------------------
 * get users name and id unser the currenlty 
 * logined user
 * ------------------------------------------------
*/
function member_users_auto_complete_callback($search_key = '') {
  $uid = afl_current_uid();
  if (eps_is_admin()) {
    $uid = afl_root_user();
  }
  if (isset($_POST['search_key'])) {
    $search_key = $_POST['search_key'];
  }
  $data     = afl_get_users();
  $response = array();

  global $wpdb;
  $querystr = " SELECT * from `"._table_name('users')."` WHERE `display_name` LIKE '%".$search_key."%' ;";
  
  $genealogy_tree = _table_name('afl_user_genealogy');
  if ( !empty($_POST['tree_mode']) && $_POST['tree_mode'] == 'unilevel') {
    $genealogy_tree = _table_name('afl_unilevel_user_genealogy');
  }

  $query = array();
  $query['#select']  = $genealogy_tree;
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'.$genealogy_tree.'`.`uid` '
    )
  );
  if (!eps_is_admin()) {
    $query['#where'] = array(
      '`'.$genealogy_tree.'`.`referrer_uid` = '.$uid
    );
    $query['#where_or'] = array(
      '`'.$genealogy_tree.'`.`uid` = '.$uid
    );
  }
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  

  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  echo json_encode($response);
  die();
}


/*
 * ------------------------------------------------
 * get users name and id unser the currenlty 
 * logined user
 * ------------------------------------------------
*/
function _get_member_downline_users_as_option($tree_mode = '') {
  $uid = afl_current_uid();
  if (eps_is_admin()) {
    $uid = afl_root_user();
  }

  $data     = afl_get_users();
  $response = array();

  global $wpdb;
  
  $genealogy_tree = _table_name('afl_user_downlines');
  if ( !empty($tree_mode) && $tree_mode == 'unilevel') {
    $genealogy_tree = _table_name('afl_unilevel_user_downlines');
  }

  $query = array();
  $query['#select']  = $genealogy_tree;
  $query['#join'] = array(
    _table_name('users') => array(
     '#condition'=> '`'._table_name('users').'`.`ID` = `'.$genealogy_tree.'`.`downline_user_id` '
    )
  );
  // if (!eps_is_admin()) {
  //   $query['#where'] = array(
  //     '`'.$genealogy_tree.'`.`referrer_uid` = '.$uid
  //   );
  //   $query['#where_or'] = array(
  //     '`'.$genealogy_tree.'`.`uid` = '.$uid
  //   );
  // }

  if (!eps_is_admin()) {
    $uid = afl_root_user();
  }
    $query['#where'] = array(
      '`'.$genealogy_tree.'`.`uid` = '.$uid
    );
    // $query['#where_or'] = array(
    //   '`'.$genealogy_tree.'`.`uid` = '.$uid
    // );
  // }
  $query['#fields'] = array(
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  

  echo '<option value="0">Please select a parent</option>';
  foreach ($result as $key => $value) {
    $response = array('name'=> ($value->user_login.' ('.$value->ID.')'));
    echo '<option value="'.$response['name'].'">'.$response['name'].'</option>';
  }
}


/*
 * ------------------------------------------------
 * get users name and id unser the currenlty 
 * logined user
 * ------------------------------------------------
*/
function member_downlines_auto_complete_callback($search_key = '') {
  $uid = get_uid();
  
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
    _table_name('users') => array('user_login', 'ID')
  );
  $result = db_select($query, 'get_results');
  

  foreach ($result as $key => $value) {
    $response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
  }
  echo json_encode($response);
  die();
}




/*
 * ------------------------------------------------
 * User downlines
 * ------------------------------------------------
*/
 function afl_user_downlines_data_table_callback () {
  $uid 					 = get_current_user_id();
  if (eps_is_admin()) {
    $uid = afl_root_user();
  }

  if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];
  }


 	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
  // pr($filter['search_valu']);
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 50;

  $filter['fields'] = array(
  _table_name('afl_user_downlines') => array('level','relative_position'),
  _table_name('afl_user_genealogy') => array('member_rank','created'),
  _table_name('users') => array('display_name', 'ID')
 );
  $result_count = afl_get_user_downlines($uid,array(),TRUE);
  $filter_count = afl_get_user_downlines($uid,$filter,TRUE);
  // pr($result_count);
  // pr($filter_count);
  $output = [
     "draw"             => $input_valu['draw'],
     "recordsTotal"     => $result_count,
     "recordsFiltered"  => $result_count,
     "data"             => [],
   ];


   $downlines_data = afl_get_user_downlines($uid,$filter, false);

   foreach ($downlines_data as $key => $value) {
   	$output['data'][] = [
   		$value->ID,
      $value->display_name,
   		$value->level,
      $value->relative_position,
   		render_rank($value->member_rank),
   		afl_system_date_format($value->created,TRUE)
   	];
   }
   echo json_encode($output);
 	die();
 }
/*
 * ------------------------------------------------
 * User refered downlines
 * ------------------------------------------------
*/
 function afl_user_refered_downlines_data_table_callback () {
  $uid           = get_current_user_id();
  if (eps_is_admin()) {
    $uid = afl_root_user();
  }
  // if (isset($_GET['uid'])) {
  //   $uid = $_GET['uid'];
  // }


  $input_valu = $_POST;
  if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
  // pr($filter['search_valu']);
  $filter['start']    = !empty($input_valu['start'])  ? $input_valu['start']  : 0;
  $filter['length']   = !empty($input_valu['length']) ? $input_valu['length'] : 50;

  $filter['fields'] = array(
  _table_name('afl_user_genealogy') => array('member_rank', 'relative_position','created','level'),
  _table_name('users') => array('display_name', 'ID')
 );
  $result_count = afl_get_user_refered_downlines($uid,array(),TRUE);
  $filter_count = afl_get_user_refered_downlines($uid,$filter,TRUE);

  $output = [
     "draw"             => $input_valu['draw'],
     "recordsTotal"     => $result_count,
     "recordsFiltered"  => $result_count,
     "data"             => [],
   ];


   $downlines_data = afl_get_user_refered_downlines($uid,$filter, false);

   foreach ($downlines_data as $key => $value) {
    $output['data'][] = [
      $value->ID,
      $value->display_name,
      $value->level,
      $value->relative_position,
      render_rank($value->member_rank),
      afl_system_date_format($value->created,TRUE)
    ];
   }
   echo json_encode($output);
  die();
 }
/*
 * -------------------------------------------------------------------------
 * Expand Genealogy tree
 * -------------------------------------------------------------------------
*/
 function afl_expand_user_genealogy_tree () {
  afl_get_template('plan/matrix/genealogy-tree-expanded.php');
 }
 /*
 * -------------------------------------------------------------------------
 * Expand Genealogy tree
 * -------------------------------------------------------------------------
*/
 function afl_unilevel_expand_user_genealogy_tree () {
  
  afl_get_template('plan/unilevel/genealogy-tree-expanded.php');
 }
 /*
 * -------------------------------------------------------------------------
 * Expand Genealogy tree Toggle
 * -------------------------------------------------------------------------
*/
 function afl_user_expand_toggle_genealogy () {
  afl_get_template('plan/matrix/holding-toggle-genealogy-tree-expanded.php');
 }
/*
 * -------------------------------------------------------------------------
 * Expand unilevel Genealogy tree Toggle
 * -------------------------------------------------------------------------
*/
 function afl_unilevel_user_expand_toggle_genealogy () {
  afl_get_template('plan/unilevel/unilevel-holding-toggle-genealogy-tree-expanded.php');
 }
 /*
  * ------------------------------------------------------------------------
  * Get available spaces under a user
  * ------------------------------------------------------------------------
 */
  function afl_get_available_free_space_callback() {
    // $_POST['sponsor'] = 10;
    // $_POST['uid'] = 1;
    // $_POST['parent'] = 'business.admin+(159)';

    if (!empty($_POST['sponsor']) && !empty($_POST['uid']) && $_POST['parent']) {
      $parent    = extract_sponsor_id($_POST['parent']);
      $tree_mode = !empty($_POST['tree_mode']) ? $_POST['tree_mode'] : 'matrix'; 
      
      switch ($tree_mode) {
        case 'unilevel':
          $downline_table = _table_name('afl_unilevel_user_downlines');
        break;
        default:
          $downline_table = _table_name('afl_user_downlines');
        break;
      }

      if ($parent) {
        $tree_width = afl_variable_get('matrix_plan_width',3);
        $positions  = array();

        for ($i = 1; $i <= $tree_width  ; $i++) {
          $positions[] = $i;
        }
        //get the filled positions of the selected parent
        $query = array();
        $query['#select'] = $downline_table;
        $query['#where']  = array(
          '`'.$downline_table.'`.`uid`='.$parent
        );
        $query['#fields'] = array(
          $downline_table=>  array(
                  'relative_position'
                )
        );
        $relative_positions_res = db_select($query, 'get_results');

        $relative_positions     = array();

        if ($relative_positions_res ){
          foreach ($relative_positions_res as $key => $value) {
            $relative_positions[] = $value->relative_position;
          }
        }

        $free_positions  =  array_merge(array_diff($relative_positions, $positions), array_diff($positions, $relative_positions));
        $html = '';
        $extra  = '<div class="form-item clearfix form-type-checkbox"> Available free space positions </div> ';
        for ($i = 1; $i <= $tree_width ; $i++){
          if (in_array($i, $free_positions)) {
            $html .= '<div class="form-item clearfix form-type-checkbox col-md-2">';
            $html .= '<label class="i-checks">';
            $html .='<input type="radio" name="free_space" id ="'.$i.'" class="form-checkbox checkbox form-control ">';
            $html .='<i></i>';
            $html .='</label>';
            $html .='<label class="option" for="'.$i.'">'.$i.'</label>';
            $html .='</div>';

          }
        }
        if (empty($html)) {
          echo 'There is free space available under this user.';
        } else {
         echo $extra.$html;
        }
         die();
      } else {
        echo 'Invalid parent choosen';
        die();
      }
    }
  }
/*
 * -----------------------------------------------------------------------------
 * Place a user from the tank to the tree
 * -----------------------------------------------------------------------------
*/
 function afl_place_user_from_tank_callback () {
  global $wpdb;
  $response = array();
  if (!empty($_POST['user_id']) && !empty($_POST['sponsor']) && !empty($_POST['parent']) && !empty($_POST['position'])) {
      $parent    = extract_sponsor_id($_POST['parent']);
      $sponsor  = $_POST['sponsor'];
      $uid      = $_POST['user_id'];
      $position = $_POST['position'];

      /*
       * ------------------------------------------------
       * Checking  the user is customer or not
       * if customer only remove the role holding member
       * else provide the role afl_member
       * ------------------------------------------------
      */
        $theUser = new WP_User($uid);
        $user_roles = afl_user_roles($uid);
        if ( !array_key_exists('afl_customer', $user_roles)) {
          if (!has_role($uid, 'afl_member')){
            $theUser->add_role( 'afl_member' );
          }
        } 
        $theUser->remove_role( 'holding_member' );

      $tree_mode = !empty($_POST['tree_mode']) ? $_POST['tree_mode'] : 'matrix'; 
      //insert user to genealogy
      $afl_date_splits = afl_date_splits(afl_date());

      $genealogy_table = $wpdb->prefix . 'afl_user_genealogy';
      if ( $tree_mode == 'unilevel') {
        $genealogy_table = $wpdb->prefix . 'afl_unilevel_user_genealogy';
      }

      $ins_data = array();
      $ins_data['uid']                = $uid;
      $ins_data['referrer_uid']       = $sponsor;
      $ins_data['parent_uid']         = $parent;
      $ins_data['level']              = 1;
      $ins_data['relative_position']  = $position;
      $ins_data['status']             = 1;
      $ins_data['created']            = afl_date();
      $ins_data['modified']           = afl_date();
      $ins_data['joined_day']         = $afl_date_splits['d'];
      $ins_data['joined_month']       = $afl_date_splits['m'];
      $ins_data['joined_year']        = $afl_date_splits['y'];
      $ins_data['joined_week']        = $afl_date_splits['w'];
      $ins_data['joined_date']        = afl_date_combined($afl_date_splits);

      $ins_id = $wpdb->insert($genealogy_table, $ins_data);

      //insert the user to the downlines

      $downline_table = $wpdb->prefix . 'afl_user_downlines';
      
      if ( $tree_mode == 'unilevel') {
        $downline_table = $wpdb->prefix . 'afl_unilevel_user_downlines';
      }

      $downline_ins_data['uid']               = $parent;
      $downline_ins_data['downline_user_id']  = $uid;
      $downline_ins_data['level']             = 1;
      $downline_ins_data['status']            = 1;
      $downline_ins_data['position']          = 1;
      $downline_ins_data['relative_position'] = $position;
      $downline_ins_data['created']           = afl_date();
      $downline_ins_data['member_rank']       = 0;
      $downline_ins_data['joined_day']        = $afl_date_splits['d'];
      $downline_ins_data['joined_month']      = $afl_date_splits['m'];
      $downline_ins_data['joined_year']       = $afl_date_splits['y'];
      $downline_ins_data['joined_week']       = $afl_date_splits['w'];
      $downline_ins_data['joined_date']       = afl_date_combined($afl_date_splits);

      $data_format = array(
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s'
      );

      $downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data, $data_format);
      //insert as the downlines of the uplines
      if ( $tree_mode == 'unilevel') {
        $uplines  = afl_unilevel_get_upline_uids($parent);
      } else {
        $uplines  = afl_get_upline_uids($parent);
      }

      $sp_level = 1;

      foreach ($uplines as $upline_uid) {
        $sp_level = $sp_level + 1;
        $downline_ins_data['uid']               = $upline_uid;
        $downline_ins_data['downline_user_id']  = $uid;
        $downline_ins_data['level']             = $sp_level;
        $downline_ins_data['status']            = 1;
        $downline_ins_data['position']          = 1;
        $downline_ins_data['relative_position'] = $position;
        $downline_ins_data['created']           = afl_date();
        $downline_ins_data['member_rank']       = 0;
        $downline_ins_data['joined_day']        = $afl_date_splits['d'];
        $downline_ins_data['joined_month']      = $afl_date_splits['m'];
        $downline_ins_data['joined_year']       = $afl_date_splits['y'];
        $downline_ins_data['joined_week']       = $afl_date_splits['w'];
        $downline_ins_data['joined_date']       = afl_date_combined($afl_date_splits);

        $data_format = array(
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s'
        );
        $downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data, $data_format);

      }


      /*
       * ---------------------------------------------------------------------- 
       * calculate the fast start bonus not for customer
       * ---------------------------------------------------------------------- 
      */
        $user_roles = afl_user_roles($uid);
        if ( !array_key_exists('afl_customer', $user_roles)) {
          do_action('afl_calculate_fast_start_bonus',$uid,$_POST['sponsor']);
        }



      //remove user from tank
      if ( $tree_mode == 'unilevel') {
        $wpdb->delete(_table_name('afl_unilevel_user_holding_tank'), array('uid'=>$uid));
      } else {
        $wpdb->delete(_table_name('afl_user_holding_tank'), array('uid'=>$uid));
      }

      $response['status'] = 1;
      $response['msg']    = 'Member has been placed successfully';
      echo json_encode($response);
      die();
  } else {
      $response['status'] = 0;
      $response['msg']    = 'Unexpected error occured';
      echo json_encode($response);
      die();
  }

 }



/*
 * ------------------------------------------------------------
 * Ajax callback for autoplace a user
 *
 *
 * ------------------------------------------------------------
*/
 function afl_auto_place_user_ajax_callback () {
  if ( isset($_POST['sponsor']) && isset($_POST['uid'])) {
    $tree_mode = !empty($_POST['tree_mode']) ? $_POST['tree_mode'] : 'matrix'; 
    if ( $tree_mode == 'unilevel') {
      do_action('eps_affiliates_unilevel_force_place_after_holding_expired', $_POST['uid'], $_POST['sponsor']);
    } else {
      do_action('eps_affiliates_force_place_after_holding_expired', $_POST['uid'], $_POST['sponsor']);
    }
    wp_set_message('Success', 'success');
    die();
  }
 }
/*
 * -------------------------------------------------------------
 * Get the next user from the holding tank basd on uid passed
 * -------------------------------------------------------------
*/
  function afl_user_holding_genealogy_toggle_right_callback () {
    $table = 'afl_user_holding_tank';
    if ( isset($_POST['tree']) && $_POST['tree'] == 'unilevel') {
      $table = 'afl_unilevel_user_holding_tank';
    }

    $table = _table_name($table);
    if (isset($_POST['sponsor'])) {
      if (isset($_POST['uid'])) {
        $query  = array();
        $query['#select'] = ($table);
        $query['#join']   = array(
            _table_name('users') => array(
              '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
            )
          );
        $query['#where']  = array(
          '`'.$table.'`.`uid`>'.$_POST['uid'],
          '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
        );
        $result = db_select($query, 'get_row');
        //if the detail doesnot exist send the first row
        if ( empty($result)) {
          $query['#select'] = ($table);
          $query['#join']   = array(
            _table_name('users') => array(
              '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
            )
          );
          $query['#where']  = array(
            '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
          );
          $result = db_select($query, 'get_row');
        }
      } else {
          $query['#select'] = ($table);
          $query['#join']   = array(
            _table_name('users') => array(
              '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
            )
          );
          $query['#where']  = array(
            '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
          );
          $result = db_select($query, 'get_row');
      }
       if (!empty($result)) {
        $result->image_url = EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png';
      }
      // echo _theme_toggle_holding_user($result);
      echo json_encode($result);
      die();
    }
  }
/*
 * -------------------------------------------------------------
 * Get the next user from the holding tank basd on uid passed
 * -------------------------------------------------------------
*/
  function afl_user_holding_genealogy_toggle_left_callback () {
    $table = 'afl_user_holding_tank';
    
    if ( isset($_POST['tree']) && $_POST['tree'] == 'unilevel') {
      $table = 'afl_unilevel_user_holding_tank';
    }

    $table = _table_name($table);

    if (isset($_POST['uid']) && isset($_POST['sponsor'])) {
      $query  = array();
      $query['#select'] = ($table);
      $query['#join']   = array(
        _table_name('users') => array(
          '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
        )
      );
      $query['#where']  = array(
        '`'.$table.'`.`uid`<'.$_POST['uid'],
        '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
      );
      $result = db_select($query, 'get_row');
      //if the detail doesnot exist send the first row
      if ( empty($result)) {
        $query['#select'] = ($table);
        $query['#join']   = array(
          _table_name('users') => array(
            '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
          )
        );
        $query['#where']  = array(
          '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
        );
        $result = db_select($query, 'get_row');
      }
    } else {
        $query['#select'] = ($table);
        $query['#join']   = array(
          _table_name('users') => array(
            '#condition'=>'`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
          )
        );
        $query['#where']  = array(
          '`'.$table.'`.`referrer_uid`='.$_POST['sponsor']
        );
        $result = db_select($query, 'get_row');
    }
      if (!empty($result)) {
        $result->image_url = EPSAFFILIATE_PLUGIN_ASSETS.'images/avathar.png';
      }
      // echo _theme_toggle_holding_user($result);
      echo json_encode($result);

      die();
  }
/*
 * ----------------------------------------------------------------
 *
 * ----------------------------------------------------------------
*/
  function _theme_toggle_holding_user ($data  =array()) {
    $html_tag = '';
    if (!empty($data)) {
      $html_tag .= '<span class="toggle-left-arrow" data-toggle-uid="'.$data->uid.'" onclick="_toggle_holding_node_left(this)">';
      $html_tag .= '<i class="fa fa-caret-left fa-5x"></i>';
      $html_tag .= '</span>';
      $html_tag .= '<div class="holding-toggle-user-image">';
      $html_tag .= '<img src="'.EPSAFFILIATE_PLUGIN_ASSETS."images/no-user.png".'">';
      $html_tag .= '</div>';
      $html_tag .= '<span class="toggle-right-arrow" onclick="_toggle_holding_node_right(this)">';
      $html_tag .= '<i class="fa fa-caret-right fa-5x"></i>';
      $html_tag .= '</span>';
      $html_tag .= '<p>';
      $html_tag .= $data->user_login;
      $html_tag .= '</p>';
      $html_tag .= '</div>';
   
    }
    return $html_tag;
  }