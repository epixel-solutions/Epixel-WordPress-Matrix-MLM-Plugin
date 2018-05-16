<?php 
	function afl_unilevel_all_customers () {
		echo afl_eps_page_header();
		echo afl_content_wrapper_begin();
		new Afl_enque_scripts('common');
	
		afl_unilevel_all_customers_callback();
		echo afl_content_wrapper_end();
	}

	function afl_unilevel_all_customers_callback () {

		//get user downlines details based on the uid
		// $data = afl_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_system_customers(array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-unilevel-all-customers';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'index' => $index,
			'limit' => $config['per_page']
		);
		$data  = _system_customers($filter);
		
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  		= $links;
		$table['#name'] 			= '';
		// $table['#title'] 			= 'Overall system Purchases';
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


function _system_customers ( $filters = array(), $count = FALSE ) {

	$query = array();
	$query['#select'] = _table_name('afl_customer');
	$query['#join']  = array(
   _table_name('users') => array(
   		'#condition' =>  _table_name('users').'.`ID`'.'='._table_name('afl_customer').'.`uid`',
  	),
	); 
	if (!empty($filters['limit']) ) {
		$query['#limit'] = $filters['index'].','.$filters['limit'];
	}

	if ( !empty($_GET['uid'])) {
		$query['#where'] = array( 
   			_table_name('afl_customer').'.`uid`='.$_GET['uid']
    ); 	
	}

	$res = db_select($query, 'get_results');
	if ($count)
		return count($res);
	return $res;
}