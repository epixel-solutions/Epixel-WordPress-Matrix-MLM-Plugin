<?php 
	function afl_members_manage() {
		echo afl_eps_page_header();
	
		echo afl_content_wrapper_begin();
			new Afl_enque_scripts('common');
			_mamnage_members_tabs();
		echo afl_content_wrapper_end();
	}
	function _mamnage_members_tabs () {
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'unilevel';  
		
	  //here render the tabs
	  echo '<ul class="tabs--primary nav nav-tabs">';

	  echo '<li class="'.(($active_tab == 'unilevel') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-manage-members&tab=unilevel" >Unilevel</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'matrix') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-manage-members&tab=matrix" >Matrix</a>  
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
	  _manage_member_page_callback_filter( $tree);
	 afl_system_manage_members_callback($tree);
	}
function _manage_member_page_callback_filter ( $tree = 'unilevel' ) {
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
	 	$form['filter-parent'] = array(
	 		'#title' 	=> 'Parent',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-parent',
	 		'#auto_complete_path' => $auto_complete_path,
	 		'#default_value'=> isset($_GET['filter-parent']) ? $_GET['filter-parent'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);
	 	$form['filter-sponsor'] = array(
	 		'#title' 	=> 'Sponsor',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-sponsor',
	 		'#auto_complete_path' => $auto_complete_path,
	 		'#default_value'=> isset($_GET['filter-sponsor']) ? $_GET['filter-sponsor'] : '',
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
	function afl_system_manage_members_callback ( $tree = 'unilevel') {

		$pagination = new CI_Pagination;

		$page_path = http_build_query($_GET);

		$config['total_rows'] =  (affiliate_data($tree,[],TRUE));
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

		if ( isset($_GET['filter-sponsor'])) {
			$filter['filter-sponsor'] = extract_sponsor_id($_GET['filter-sponsor']);
		}

		if ( isset($_GET['filter-parent'])) {
			$filter['filter-parent'] = extract_sponsor_id($_GET['filter-parent']);
		}



		$data  = affiliate_data($tree,$filter,FALSE);
		
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
			__('Parent','affiliates-eps'), 
			__('Sponsor','affiliates-eps'), 
			__('Roles(s)','affiliates-eps'), 
			__('Member Status','affiliates-eps'), 
			__('Rank','affiliates-eps'), 
			__('Registered On','affiliates-eps'), 
		);

		$rows = [];
		foreach ($data as $key => $value) {
			
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_member'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name.'<span class="label bg-info"><a href="#"  class="username">'.$value->user_login.'</a></span>  <span class="label label-primary">'.$value->ID.'</span>'
			);
			$node  = afl_genealogy_node($value->parent_uid,$tree);

			if (!empty($node) && !empty($node->display_name)) {
				$parent_val = $node->display_name;
			} else {
				$parent_val = 'unverified';
			}

			$rows[$key]['markup_parent'] = array(
				'#type' =>'markup',
				'#markup'=> $parent_val
			);

			$node  = afl_genealogy_node($value->referrer_uid,$tree);

			if (!empty($node) && !empty($node->display_name)) {
				$sponsor_val = $node->display_name;
			} else {
				$sponsor_val = 'unverified';
			}
			$rows[$key]['markup_sponsor'] = array(
				'#type' =>'markup',
				'#markup'=> $sponsor_val
			);

			$roles = afl_user_roles($value->uid);
			$roles_val = '';
			$roles_val = implode( ',', array_map( 'strval', $roles ) );

			$rows[$key]['markup_role'] = array(
				'#type' =>'markup',
				'#markup'=> $roles_val,
			);

			$statuses = list_extract_allowed_values(afl_variable_get('member_status'), 'list_text', '');
			$status_val = $statuses[$value->status];
			$rows[$key]['markup_status'] = array(
				'#type' =>'markup',
				'#markup'=> $status_val
			);

			$rows[$key]['markup_rank'] = array(
				'#type' =>'markup',
				'#markup'=>  render_rank($value->member_rank)
			);

			$rows[$key]['markup_registered_on'] = array(
				'#type' =>'markup',
				'#markup'=>  afl_system_date_format($value->created,TRUE)
			);


		}
		$table['#rows'] = $rows;	
		echo afl_render_table($table);
	}

	function affiliate_data($tree = 'matrix', $filter = [], $count = FALSE) {
			$table = _table_name('afl_user_genealogy');
			if ( $tree == 'unilevel') {
				$table = _table_name('afl_unilevel_user_genealogy');
			}
			$query = array();
			$query['#select'] = $table;

			$query['#join'] 	= array(
				_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID`=`'.$table.'`.`uid`'
				),
			);

			if (!empty($filter['limit'])) {
				$query['#limit'] = $filter['index'].','.$filter['limit'];
			}
			
			//get only non-deleted members
			$query['#where'] = array(
				'`'.$table.'`.`deleted`=0',
				'`'.$table.'`.`status`=1'
			);

			//check filters applied
			if (isset($filter['filter-user']) && !empty($filter['filter-user'])) {
				$uid =  ($filter['filter-user']);
				$query['#where'][] = '`'.$table.'`.`uid`='.$uid;
			}

			if (isset($filter['filter-sponsor']) && !empty($filter['filter-sponsor'])) {
				$suid =  ($filter['filter-sponsor']);
				$query['#where'][] = '`'.$table.'`.`referrer_uid`='.$suid;
			}

			if (isset($filter['filter-parent']) && !empty($filter['filter-parent'])) {
				$puid =  ($filter['filter-parent']);
				$query['#where'][] = '`'.$table.'`.`parent_uid`='.$puid;
			}
			
			$affiliates = db_select($query, 'get_results');
			
			if ($count ) {
				return count($affiliates) ;
			}
			return $affiliates;
		}