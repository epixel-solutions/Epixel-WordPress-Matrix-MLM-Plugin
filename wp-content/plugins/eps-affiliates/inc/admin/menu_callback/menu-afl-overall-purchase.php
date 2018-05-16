<?php 
	function afl_all_purchase () {
			echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		new Afl_enque_scripts('common');
	
		afl_all_purchase_callback();
	echo afl_content_wrapper_end();
	}

	function afl_all_purchase_callback () {
		$uid = get_uid();

		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_all_purchases(array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-all-purchases';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'index' => $index,
			'limit' => $config['per_page']
		);
		$data  = _all_purchases($filter);
		
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  		= $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Overall system Purchases';
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
			__('Category'),
			__('Amount Paid'),
			__('Points'),
			__('Order ID'),		
			__('Purchase Date'),		
			__('Purchase Day'),		
			__('Purchase Week'),		
			__('Purchase Month'),		
			__('Purchase Year'),		
			__('Extra Params'),		
			__('Cron Status'),		
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

			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->category
			);
			$rows[$key]['markup_amount_paid'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid)
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->afl_points, FALSE),
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> $value->order_id
			);
			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);

			$rows[$key]['markup_day'] = array(
				'#type' =>'markup',
				'#markup'=> $value->purchase_day
			);

			$rows[$key]['markup_week'] = array(
				'#type' =>'markup',
				'#markup'=> $value->purchase_week
			);
			$rows[$key]['markup_month'] = array(
				'#type' =>'markup',
				'#markup'=> $value->purchase_month
			);
			$rows[$key]['markup_year'] = array(
				'#type' =>'markup',
				'#markup'=> $value->purchase_year
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> ($value->extra_params)
			);
			$rows[$key]['markup_cron_status'] = array(
				'#type' =>'markup',
				'#markup'=> _render_cron_status($value->cron_status)
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
	}

function _all_purchases ( $filters = array(), $count = FALSE ) {

	$query = array();
	$query['#select'] = _table_name('afl_purchases');
	$query['#join']  = array(
   _table_name('users') => array(
   		'#condition' =>  _table_name('users').'.`ID`'.'='._table_name('afl_purchases').'.`uid`',
  	),
	); 
	if (!empty($filters['limit']) ) {
		$query['#limit'] = $filters['index'].','.$filters['limit'];
	}

	if ( !empty($_GET['uid'])) {
		$query['#where'] = array( 
   			_table_name('afl_purchases').'.`uid`='.$_GET['uid']
    ); 	
	}

	$res = db_select($query, 'get_results');
	if ($count)
		return count($res);
	return $res;
}