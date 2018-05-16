<?php 
/*
 * ----------------------------------------------------------
 * Affiliates registraion related methods
 * ----------------------------------------------------------
*/
class Eps_affiliates_unilevel_registration {

	public function afl_join_unilevel_member ($post_data = array()) {
		$response = array();
		try{
			$db_transaction = new Db_transaction();
		
			$plan_width = afl_variable_get('matrix_plan_width',3);
			if (!empty($post_data)) {
				//insert to the geanealogy 
				if (!empty($post_data['uid'] && !empty($post_data['sponsor_uid']))) {
					
				/*
				 * ------------------------------------------------
				 * Checking  the user is customer or not
				 * if customer only remove the role holding member
				 * else provide the role afl_member
				 * ------------------------------------------------
				*/
					$user_roles = afl_user_roles($post_data['uid']);
					if ( !array_key_exists('afl_customer', $user_roles)) {
						if (!has_role($post_data['uid'], 'afl_member')){
							$theUser = new WP_User($post_data['uid']);
							$theUser->remove_role( 'holding_member' );
							$theUser->add_role( 'afl_member' );
						}
					} else {
						$theUser = new WP_User($post_data['uid']);
						$theUser->remove_role( 'holding_member' );
					}

					//first check the downlines count of sponsor and find out which level insert
					global $wpdb;
					$table_name = _table_name('afl_unilevel_user_genealogy');
					$sponsor = $post_data['sponsor_uid'];
					// if tables exists
					if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
						//First insert to downlines table
					 	// get user id counts in each level of the sponsor
						// also the count of the uids must lessthan or equals maximum of level users
						$query 			= 'SELECT count(`downline_user_id`) as count, `level` FROM `'._table_name('afl_unilevel_user_downlines').'` WHERE `uid`= %d GROUP BY `level` HAVING count(`downline_user_id`) < POWER(3,`level`)';
					  $row 				= $wpdb->get_row($wpdb->prepare($query,$sponsor));
					  
					  $max_query 	= 'SELECT MAX(`level`) FROM `'._table_name('afl_unilevel_user_downlines').'` WHERE `uid`= %d';
					  $max_level	= $wpdb->get_var($wpdb->prepare($max_query,$sponsor));
					  

					  
					  /**
	 					 * ----------------------------------------------------------------------------
	 					 * Here get the maximum level.
	 					 * If the maximum level get from the database is empty, then the
	 					 		level is 1
	 					 * if the row get from the database is empty but maximum level available,which 
	 					 *  means all the levels are filled and need to add new level, thus level will set
	 					 *  maximum level + 1
	 					 * else if unfilled row get and maximum level get thus level will be the 
	 					 *  maximum level
	 					 * ----------------------------------------------------------------------------
					  */
					  if (empty($max_level)){
					  	$level 		= 1;
					  	$relative_position = 1;
					  } else if(empty($row) && !empty($max_level)) {
					  	$level 		= $max_level + 1 ;
					  } else {
					  	$level 		= $max_level;
					  }
					  /**
					   * -----------------------------------------------------------------------------
					   * Here finds the $level, relative position and parent
					   * -----------------------------------------------------------------------------
					   * Condition 1 : 
					   *          if no row find from the table and the maximum level is empty, means 
					   *          need to insert first data.Thus set @var $level and @var $position
					   *					is 1 and @var $parent bust be the sponsor
					   * Condition 2 : 
					   *					 if the row is empty but the maximum level exist, means all the 
					   *           levels are filled and need to start filling with new level
					   *					 Thus @var $level set maximum level + 1 and @var relative position 
					   *					 set to 1 (starting from first)
					   * Else 
					   * 					 we get some unfilled detail.
					   *					- get all the maximum levelth users relative position of the sponsor
					   *					- create a relative positions array amd sort this ascending order
					   *					- after the sorting, get the middle relative position from the array
					   *						| if the count is even two middle number will get,
					   * 						| the next relative position is taken first middle + 1
					   *						| if the count if odd only one relative position get
					   *						|	this will be taken as the middle value
					   *					-	then need to find out the relative position of newly added member
					   *					- and it will be find by an equation
					   *					-
					   *					- @var $middle_relative_position  if the last added position
					   *					-
					   *					- pow($plan_width, $level) - ($middle_relative_position - 1 );
					   *					-
					   *					- @var Level : 2
					   *					- @var plan_width : 3
					   *					- pow(3,2) - (3 - 1) = 7
					   *					-	last added in the 3rd position then next add to 7 
					   *					-
					   *					- Another example
					   *					-	
					   *					-	
					   *					- @var Level : 2
					   *					- @var plan_width : 3
					   *					- @var last inserted in : 4
					   *					-
					   *					- Next relative position to add : 
					   *					- pow(3,2) - (4 - 1) = 6
					   * -----------------------------------------------------------------------------
					  */

					 	if (empty($row) && empty($max_level)) {
					 		$level 		= 1;
					 		$position = 1;
				 			$parent		=  $post_data['sponsor_uid'];
				 			
				 			$last_inserted = _get_unilevel_last_inserted_positon($sponsor, $level);
				 			$newly_added_pos = empty($last_inserted) ? 'FL' : $last_inserted;

					 	} else if(empty($row) && !empty($max_level)){
					 		$level 						 = $max_level + 1;
					  	$relative_position = 1;

					  	$parent_query = 'SELECT `downline_user_id` FROM `'._table_name('afl_unilevel_user_downlines').'` WHERE `uid`= %d AND `level`= %d' ;
					  	$parent_uid		= $wpdb->get_var($wpdb->prepare($parent_query,$sponsor,$level));
					  	$parent 			=	$parent_uid;
					  	
				 			$last_inserted = _get_unilevel_last_inserted_positon($sponsor, $level);
					  	$newly_added_pos = empty($last_inserted) ? 'FL' : $last_inserted;

					 	} else {
					 		if (!empty($row->level)) {
					 			$level = $row->level;
					 		}

					  	$relative_positions_q = 'SELECT `relative_position` FROM `'._table_name('afl_unilevel_user_downlines').'` WHERE `uid`= %d AND `level`= %d' ;
					  	$relative_positions 	= $wpdb->get_results($wpdb->prepare($relative_positions_q,$sponsor,$level));

					  	$positions_array = array();
					  	foreach ($relative_positions as $key => $value) {
					  		$positions_array[] = $value->relative_position;
					  	}
					  	sort($positions_array);

					  	//get last inserted position position (FL / FR ) this user
					  	/*
					  	 * ----------------------------------------------------------------
					  	 * Get the last inserted position of the sponsor on this level
					  	 * If it is FL then the unfilled array sort in descending order and 
					  	 * 	insert to the first index
					  	 * if it is FR then the unfilled position array sort ascending order 
					  	 * and insert to the first position
					  	 *
					  	 * if the inserted details is empty, needs to insert in FL thus the 
					  	 * array sort as ascending
					  	 * ----------------------------------------------------------------
					  	*/

					  	$last_inserted = _get_unilevel_last_inserted_positon($sponsor, $level);
					  	$unfilled_pos  = array_diff(range(1, pow($plan_width, $level)), $positions_array);
					  	
					  	switch ($last_inserted) {
					  		case 'FL':
					  			$newly_added_pos = 'FR';
					  			rsort($unfilled_pos);	
					  		break;
					  		case 'FR':
					  			$newly_added_pos = 'FL';
					  			sort($unfilled_pos);	
					  		break;
					  		default:
					  			$newly_added_pos = 'FL';
					  			sort($unfilled_pos);	
					  		break;
					  		
					  	}

					  	$next_relative_position = $unfilled_pos[0];
					  	// pr($positions_array);
					  	// pr($level);
					  	// pr($next_relative_position);
					  // 	// pr($positions_array);
					  // 	if(count($positions_array)%2 === 0){
							//     $var = (count($positions_array)-1)/2;

							//     // $middle_relative_position = $positions_array[$var];
							//     // $middle_relative_position = $positions_array[$var+1] - 1;
							//     $next_relative_position   = $positions_array[$var] + 1;
							// }else{
							//     $var = count($positions_array)/2;
							// 		$middle_relative_position = $positions_array[$var];
							// 		$next_relative_position 	= pow($plan_width, $level) - ($middle_relative_position - 1 );
							// }
							// pr($next_relative_position);
					  	
					  	$relative_position = $next_relative_position;
					  	

					 	}
					 	$afl_date_splits = afl_date_splits(afl_date());
					 	// pr("POS : ".$relative_position);
					 	// pr("Level : ".$level);
					 	// pr("SPONSOR :".$sponsor);

						$parent = $this->afl_get_relative_parent($relative_position,$level,$sponsor);
					 	
						
						//relative position is found based on the sponsor and the parent based relative position found here

						$parent_raltive_position = 0 ;
						$reminder = $relative_position % $plan_width;
						// if no reminder, then $plan_width position, else reminder position
						$parent_raltive_position = ($reminder == 0 ) ? $plan_width : $reminder; 
						//examples
						// 1/3 : rem 1 then 1 th
						// 2/3 : rem 2 then 2 th
						// 3/3 : rem 0 then 3 th
					 	//insert to the downlines

						$downline_table = $wpdb->prefix . 'afl_unilevel_user_downlines';
						if($wpdb->get_var("SHOW TABLES LIKE '$downline_table'") == $downline_table) {
							$downline_ins_data['uid'] 							= $parent;
						 	$downline_ins_data['downline_user_id'] 	= $post_data['uid'];
						 	$downline_ins_data['level'] 						= 1;
						 	$downline_ins_data['status'] 						=	1;
						 	$downline_ins_data['position'] 					=	1;
						 	$downline_ins_data['relative_position']	=	$parent_raltive_position;
						 	$downline_ins_data['created'] 					= afl_date();
						 	$downline_ins_data['member_rank'] 			= 0;
						 	$downline_ins_data['joined_day'] 				= $afl_date_splits['d'];
						 	$downline_ins_data['joined_month'] 			= $afl_date_splits['m'];
						 	$downline_ins_data['joined_year'] 			= $afl_date_splits['y'];
						 	$downline_ins_data['joined_week'] 			= $afl_date_splits['w'];
						 	$downline_ins_data['joined_date'] 			= afl_date_combined($afl_date_splits);
						 	
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


					 		/*
					 		 * -----------------------------------------------------------------
					 		 * here adds the downline details to the sponsors upline
					 		 * -----------------------------------------------------------------
					 		*/
					 		$uplines 	= afl_unilevel_get_upline_uids($parent);

					 		$sp_level = 1;

					 		foreach ($uplines as $upline_uid) {
					 			$sp_level = $sp_level + 1;

					 			//get parent relative position from $upline uid
					 			$upline_reltive_pos = get_unilevel_relative_position_from($upline_uid, $parent);
					 			//findout the relative position number
					 			/*
					 			 * ----------------------------------------------------
					 			 * (relative position from the upline user - 1) * plan width 
					 			         + relative position added to the parent
					 			 *
					 			 * ----------------------------------------------------
					 			*/
					 			$upline_relation 		= ($upline_reltive_pos - 1) * $plan_width + $parent_raltive_position;


					 			$downline_ins_data['uid'] 							= $upline_uid;
							 	$downline_ins_data['downline_user_id'] 	= $post_data['uid'];
							 	$downline_ins_data['level'] 						= $sp_level;
							 	$downline_ins_data['status'] 						=	1;
							 	$downline_ins_data['position'] 					=	1;
							 	$downline_ins_data['relative_position']	=	$upline_relation;
							 	$downline_ins_data['created'] 					= afl_date();
							 	$downline_ins_data['member_rank'] 			= 0;
							 	$downline_ins_data['joined_day'] 				= $afl_date_splits['d'];
							 	$downline_ins_data['joined_month'] 			= $afl_date_splits['m'];
							 	$downline_ins_data['joined_year'] 			= $afl_date_splits['y'];
							 	$downline_ins_data['joined_week'] 			= $afl_date_splits['w'];
							 	$downline_ins_data['joined_date'] 			= afl_date_combined($afl_date_splits);

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
						}


					/*
					 * ------------------------------------------------------------------------------------------
					 * Insert user to the genealogy table
					 * ------------------------------------------------------------------------------------------
					*/
						//get parent position 
						$parent = $this->afl_get_relative_parent($relative_position,$level,$sponsor);
						
					/*
				 	 * ---------------------------------------------------------------------- 
				 	 * insert the genealogy details 
				 	 * ---------------------------------------------------------------------- 
				 	*/
					 	$ins_data = array();
					 	$ins_data['uid'] 								= $post_data['uid'];
					 	$ins_data['referrer_uid'] 			= $post_data['sponsor_uid'];
					 	$ins_data['parent_uid'] 				= $parent;
					 	$ins_data['level'] 							= $level;
					 	$ins_data['relative_position']	= $relative_position;
					 	$ins_data['status'] 						= 1;
					 	$ins_data['created'] 						= afl_date();
					 	$ins_data['modified'] 					= afl_date();
					 	$ins_data['joined_day'] 				= $afl_date_splits['d'];
					 	$ins_data['joined_month'] 			= $afl_date_splits['m'];
					 	$ins_data['joined_year'] 				= $afl_date_splits['y'];
					 	$ins_data['joined_week'] 				= $afl_date_splits['w'];
					 	$ins_data['joined_date'] 				= afl_date_combined($afl_date_splits);
					 	$ins_data['actived_on'] 				= afl_date();
					 	
					 	$ins_id = $wpdb->insert($table_name, $ins_data);
					/*
				 	 * ---------------------------------------------------------------------- 
					 * Insert  to the nested set
				 	 * ---------------------------------------------------------------------- 
					*/
						//update_nested_set('afl_unilevel_nested_set_referal',$post_data['uid'],$post_data['sponsor_uid'],'insert');
	     			//update_nested_set('afl_unilevel_nested_set_downline',$post_data['uid'],$parent,'insert');

					/*
				 	 * ---------------------------------------------------------------------- 
				 	 * insert the position details to tree last insertion position
				 	 * ---------------------------------------------------------------------- 
				 	*/
					 	_update_inserted_positon(
					 			$post_data['sponsor_uid'], 
					 			$level, $newly_added_pos,
					 			_table_name('afl_unilevel_tree_last_insertion_positions')
					 	);
					/*
				 	 * ---------------------------------------------------------------------- 
				 	 * calculate the fast start bonus not for customer
				 	 * ---------------------------------------------------------------------- 
				 	*/
				 		$user_roles = afl_user_roles($post_data['uid']);
				 		if ( !array_key_exists('afl_customer', $user_roles)) {
					 		do_action('afl_calculate_fast_start_bonus',$post_data['uid'],$post_data['sponsor_uid']);
				 		}
					}
				}
			}
		} catch(Exception $e){
			// $db_transaction->roll_back();
			afl_log('unilevel_user_registration','Data',$e,LOGS_ERROR);
		}
	}
	/*
	 * -----------------------------------------------------------------
	 * Here get the relative position upline user position
	 * -----------------------------------------------------------------
	*/
	public function afl_get_relative_parent ($relative_position = '', $level = '', $sponsor = '') {
		global $wpdb;
		//level not 1,
		//if level 1 the parent but be he
		if ($level > 1 ) {
			if (!empty($relative_position)) {
				$plan_width 			= afl_variable_get('matrix_plan_width',3);
				$parent_position 	= '';

				$parent_relative_pos = $relative_position / $plan_width;
				if (is_float($parent_relative_pos)) {
					$parent_position =  intval($parent_relative_pos)+1;
				} else
				 $parent_position  =  $parent_relative_pos;
			}
			// pr($parent_position);

			if (!empty($parent_position)) {
				//get the sposnors's `$parent_position` positions th user id
				// $parent_query = 'SELECT `uid` FROM `wp_afl_user_genealogy` WHERE `referrer_uid`= %d AND `level`= %d AND `relative_position` = %d' ;
				$parent_query = 'SELECT `downline_user_id` FROM `'._table_name('afl_unilevel_user_downlines').'` WHERE `uid`= %d AND `level`= %d AND `relative_position` = %d' ;
				
				// pr($sponsor);
				// pr($level - 1);
				// pr($parent_position);
				$parent_uid		= $wpdb->get_var($wpdb->prepare($parent_query,$sponsor,($level - 1),$parent_position));
				// pr($parent_query);
				if ($parent_uid) {
					return $parent_uid;
				} else {
					return false;
				}
			}
		} else {
			return $sponsor;
		}
		
	}

	/*
	 * -----------------------------------------------------------------
	 * Add the user to the 7 day holding tank
	 * -----------------------------------------------------------------
	*/
	 public function afl_add_to_holding_tank ($post_data = array()) {

	 	global $wpdb;
	 	if (!empty($post_data)) {
	 		$uid 			= $post_data['uid'];
	 		$sponsor 	= $post_data['sponsor_uid'];
	 		$afl_date_splits = afl_date_splits(afl_date());


	 		//create array to insert to the holding tank
	 		$ins_data = array();
	 		$ins_data['uid'] 								= $uid;
		 	$ins_data['referrer_uid'] 			= $sponsor;
		 	$ins_data['parent_uid'] 				= 0;
		 	$ins_data['level'] 							= 0;
		 	$ins_data['relative_position']	= 0;
		 	$ins_data['status'] 						= 1;
		 	
		 	if (empty($post_data['created_date']))
		 		$ins_data['created'] 						= afl_date();
		 	if (empty($post_data['modified_date']))
		 		$ins_data['modified'] 					= afl_date();

		 	$ins_data['joined_day'] 				= $afl_date_splits['d'];
		 	$ins_data['joined_month'] 			= $afl_date_splits['m'];
		 	$ins_data['joined_year'] 				= $afl_date_splits['y'];
		 	$ins_data['joined_week'] 				= $afl_date_splits['w'];
		 	$ins_data['joined_date'] 				= afl_date_combined($afl_date_splits);
		 	$ins_data['day_remains'] 				= afl_variable_get('holding_tank_holding_days',7);

		 	$holding_tank = $wpdb->prefix . 'afl_unilevel_user_holding_tank';
			$downline_ins_id = $wpdb->insert($holding_tank, $ins_data);


			//remove user afl_member role anda add holding Member role
			$theUser = new WP_User($post_data['uid']);
			$theUser->add_role( 'holding_member' );
			$theUser->remove_role( 'afl_member' );

	 	}
	 }
	

}
