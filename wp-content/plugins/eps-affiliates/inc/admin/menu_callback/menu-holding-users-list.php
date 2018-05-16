<?php 
	function _holding_users_list () {
			new Afl_enque_scripts('common');
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		_holding_users_list_tabs();
	afl_content_wrapper_end();
	}

	function _holding_users_list_tabs () {
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'unilevel';  
		
	  //here render the tabs
	  echo '<ul class="tabs--primary nav nav-tabs">';

	  echo '<li class="'.(($active_tab == 'unilevel') ? 'active' : '').'">
	            	<a href="?page='.$_GET['page'].'&tab=unilevel" >Unilevel</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'matrix') ? 'active' : '').'">
	            	<a href="?page='.$_GET['page'].'&tab=matrix" >Matrix</a>  
	          </li>';
	  echo '</ul>';

	  switch ($active_tab) {
	  	case 'matrix':
	  		$tree = 'matrix';	
	  	break;

	  	
	  	default:
	  		$tree = 'unilevel';	
  		break;
	  }
	  _holding_users_list_callback_filter( $tree);
	 _holding_users_list_callback($tree);
	}
function _holding_users_list_callback_filter ( $tree = 'unilevel' ) {
		$auto_complete_path = 'unilevel_users_auto_complete';
		if ( $tree == 'matrix' ){
			$auto_complete_path = 'users_auto_complete';
		}
		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
 		$form['#method'] = 'get';
 		$form['#prefix'] ='<div class="form-group row">';
 		$form['#suffix'] ='</div>';

 		$form['page'] = array(
	 		'#type'  	=> 'hidden',
	 		'#name'		=> 'page',
	 		'#default_value'=>!empty($_GET['page']) ? $_GET['page'] : '',
	 	);
 		$form['tab'] = array(
	 		'#type'  	=> 'hidden',
	 		'#name'		=> 'tab',
	 		'#default_value'=>!empty($_GET['tab']) ? $_GET['tab'] : '',
	 	);
	 	
 		$form['filter-user'] = array(
	 		'#title' 	=> 'username',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-user',
	 		'#auto_complete_path' => $auto_complete_path,
	 		'#default_value'=> isset($_GET['filter-user']) ? $_GET['filter-user'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);
	 	$form['submit'] = array(
	 		'#title' => 'Submit',
	 		'#type' => 'submit',
	 		'#value' => 'Filter',
	 		'#attributes' => array(
	 			'class' => array(
	 				'btn','btn-primary'
	 			)
	 		),
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'
	 		
	 	);

 		echo afl_render_form($form);
	}

	function _holding_users_list_callback ( $tree = 'unilevel' ) {
		$pagination = new CI_Pagination;

		$page_path = http_build_query($_GET);

		$config['total_rows'] =  (affiliate_holding_data($tree,[],TRUE));
		$config['base_url'] 	= '?'.$page_path;
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'index' => $index,
			'limit' => $config['per_page']
		);

		if ( isset($_GET['filter-user'])) {
			$filter['filter-user'] = extract_sponsor_id($_GET['filter-user']);
		}

		$data  = affiliate_holding_data($tree,$filter,FALSE);
		// pr($data,1);
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
			__('Member','affiliates-eps'), 
			__('Rank','affiliates-eps'), 
			__('Holding users ','affiliates-eps'), 
			__('','affiliates-eps'), 
		);

		$rows = [];
		foreach ($data as $key => $value) {
			
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_member'] = array(
				'#type' =>'markup',
				'#markup'=> _render_member_name($value->referrer_uid)
			);

			$node  = afl_genealogy_node($value->referrer_uid,$tree);

			$rows[$key]['markup_rank'] = array(
				'#type' =>'markup',
				'#markup'=>  render_rank($node->member_rank)
			);
			$rows[$key]['markup_holding_count'] = array(
				'#type' =>'markup',
				'#markup'=>  ($value->holding_count)
			);

			$path = '?page=affiliate-eps-unilevel-holding-tank';
			if ( $tree == 'matrix'){ 
				$path = '?page=affiliate-eps-holding-tank';
			}	

			$rows[$key]['markup_view_tank_link'] = array(
				'#type' =>'markup',
				'#markup'=>  '<a target="_blank" href="'.$path.'&uid='.$value->referrer_uid.'">View Holding tank</a>'
			);

		}
		$table['#rows'] = $rows;	
		echo afl_render_table($table);
	}

	function affiliate_holding_data($tree = 'matrix', $filter = [], $count = FALSE) {
			$table = _table_name('afl_user_holding_tank');
			if ( $tree == 'unilevel') {
				$table = _table_name('afl_unilevel_user_holding_tank');
			}
			$query = array();
			$query['#select'] = $table;

			$query['#join'] 	= array(
				_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID`=`'.$table.'`.`referrer_uid`'
				),
			);

			if (!empty($filter['limit'])) {
				$query['#limit'] = $filter['index'].','.$filter['limit'];
			}
			

			//check filters applied
			if (isset($filter['filter-user']) && !empty($filter['filter-user'])) {
				$uid =  ($filter['filter-user']);
				$query['#where'][] = '`'.$table.'`.`referrer_uid`='.$uid;
			}

			// if (isset($filter['filter-sponsor']) && !empty($filter['filter-sponsor'])) {
			// 	$suid =  ($filter['filter-sponsor']);
			// 	$query['#where'][] = '`'.$table.'`.`referrer_uid`='.$suid;
			// }

			$query['#group_by'] = [
				$table.'.`referrer_uid`'
			];
			
			$query['#expression'] = [
				'COUNT(uid) as holding_count'
			];

			$affiliates = db_select($query, 'get_results');
			
			if ($count ) {
				return count($affiliates) ;
			}
			return $affiliates;
		}