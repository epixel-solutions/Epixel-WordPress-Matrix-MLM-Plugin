<?php 

function afl_team_purchases_overview () {
		echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		new Afl_enque_scripts('common');
		afl_team_purchase_overview_template();
	echo afl_content_wrapper_end();
}

function afl_team_purchase_overview_template () {
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'unilevel';  
	  //here render the tabs
	  echo '<ul class="tabs--primary nav nav-tabs">';

	  echo '<li class="'.(($active_tab == 'unilevel') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-team-purchases-reports&tab=unilevel" >My team Unilevel</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'matrix') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-team-purchases-reports&tab=matrix" >My team Matrix</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'downline_unilevel') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-team-purchases-reports&tab=downline_unilevel" >My downline Unilevel</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'downline_matrix') ? 'active' : '').'">
          	<a href="?page=affiliate-eps-team-purchases-reports&tab=downline_matrix" >My downline Matrix</a>  
        </li>';
	  echo '</ul>';

	  switch ($active_tab) {
	  	case 'matrix':
	  		$tree = 'matrix';	
	  	break;
  	
  		case 'downline_unilevel':
  		$tree = 'downline_unilevel';	
	  	break;

	  	case 'downline_matrix':
  		$tree = 'downline_matrix';	
	  	break;
	  	
	  	default:
	  		$tree = 'unilevel';	
  		break;
	  }
	 _team_purchase_overview_template_callback($tree);
}
	
	function _team_purchase_overview_template_callback ( $tree = 'unilevel') {
		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		$page_path = http_build_query($_GET);

		$pagination = new CI_Pagination;

		$config['total_rows'] =  (afl_get_team_purchases($uid,array(),TRUE,$tree));
		$config['base_url'] 	= '?'.$page_path;
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'index' => $index,
			'limit' =>$config['per_page']
		);
		$data  = afl_get_team_purchases($uid,$filter,FALSE,$tree);
		
		
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		// $table['#title'] 			= 'Business Profit Overview(Monthly)';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
						'class' => array(
								'table',
								'table-bordered',
								'my-table-center',
							)
						);

		$table['#header'] = array(
			__('#','affiliates-eps'), 
			__('UID','affiliates-eps'), 
			__('Member','affiliates-eps'), 
			__('Category','affiliates-eps'), 
			__('Point','affiliates-eps'), 
			__('Purchase Date','affiliates-eps'), 
		);

		$rows = [];
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_uid'] = array(
				'#type' =>'markup',
				'#markup'=> $value->uid
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->category
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->afl_points, FALSE),
			);
			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);
		}
		$table['#rows'] = $rows;	
		echo afl_render_table($table);
	}


	//get current user downline ids
	function afl_get_team_purchases ( $uid = '',$filter = [],$count = FALSE,$tree = 'matrix') {
		
		$uids = _tree_uids($uid,$tree);
		if ( $tree == 'downline_unilevel') {
			$uids = array_ret(afl_get_unilevel_user_downlines_uid($uid),'downline_user_id');
		} 

		if ( $tree == 'downline_matrix') {
			$uids = array_ret(afl_get_user_downlines_uid($uid),'downline_user_id');
		}

		$query['#select'] = _table_name('afl_purchases');
		$query['#join']  = array(
     _table_name('users') => array(
     		'#condition' => _table_name('users').'.`ID`'.'='._table_name('afl_purchases').'.`uid`'
    	)
  	);
		$query['#where_in'] = [
			'uid' => $uids
		];
		if (!empty($filter['limit'])) {
				$query['#limit'] = $filter['index'].','.$filter['limit'];
			}
		$res = db_select($query, 'get_results');
		if ($count)
			return count($res);
		return $res;
	}

