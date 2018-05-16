<?php

function afl_my_purchase () {
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		new Afl_enque_scripts('common');
	
		afl_my_purchase_callback();
	echo afl_content_wrapper_end();
}


function afl_my_purchase_callback () {
	
		$uid = get_uid();

		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_my_purchases($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-downline-members';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		$data  = _my_purchases($uid,$filter);
		// pr($data);
		
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
			__('#'),
			__('Category'),
			__('Amount Paid'),
			__('Points'),
			__('Order ID'),		
			__('Purchase Date'),		
			__('Extra Params'),		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
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
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> ($value->extra_params)
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);

}

function _my_purchases ( $uid = '', $filters = array(), $count = FALSE ) {
	if (empty($uid)) 
		$uid = get_uid();

	$query = array();
	$query['#select'] = _table_name('afl_purchases');
	$query['#where'] = array(
		'`'._table_name('afl_purchases').'`.`uid` ='.$uid
	);
	if (!empty($limit) ) {
		$query['#limit'] = $filters['index'].','.$filters['limit'];
	}
	$res = db_select($query, 'get_results');
	if ($count)
		return count($res);
	return $res;
}