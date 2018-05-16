<?php 

function afl_system_business_holding_payouts () {
	do_action('eps_affiliate_page_header');
 	do_action('afl_content_wrapper_begin');
		new Afl_enque_scripts('common');
 		afl_system_business_holding_payouts_callback();
 	do_action('afl_content_wrapper_end');
}

function afl_system_business_holding_payouts_callback () {
		
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_holding_payouts());
		$config['base_url'] 	= '?page=affiliate-eps-business-holding-payouts';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_holding_payouts($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Business holding payouts';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
						'class' => array(
								'table',
								'table-bordered',
							)
						);

		$table['#header'] = array(
			__('#'),
			// __('User ID'),
			__('User Name'),
			__('Associate User'),
			__('Category'),
			__('Amount'),
			__('Transaction Date'),
			__('Details'),
		);
		$rows = array();
		// pr($data);
		foreach ($data as $key => $value) {
			$rows[$key]['markup_slno'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);	
			// $rows[$key]['markup_uid'] = array(
			// 	'#type' =>'markup',
			// 	'#markup'=> $value->uid
			// );

			$rows[$key]['markup_uname'] = array(
				'#type' =>'markup',
				'#markup'=> _render_member_name($value->uid)
			);

			$rows[$key]['markup_assoc'] = array(
				'#type' =>'markup',
				'#markup'=> _render_member_name($value->associated_user_id)
			);

			$rows[$key]['markup_category'] = array(
				'#type' =>'markup',
				'#markup'=> $value->category
			);

			$rows[$key]['markup_amount'] = array(
				'#type' =>'markup',
				'#markup'=> afl_get_commerce_amount($value->amount_paid)
			);
			

			$rows[$key]['markup_trans_date'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);
			$rows[$key]['markup_notes'] = array(
				'#type' =>'markup',
				'#markup'=> $value->notes
			);
		}

		$table['#rows'] = $rows;
		echo apply_filters('afl_render_table',$table);
}

function  _get_holding_payouts ( $index = 0, $limit = 0) {
		$query = array();

		$query['#select'] = _table_name('afl_user_holding_transactions');
		$query['#join']  = array(
     _table_name('users') => array(
     		'#condition' => '`'. _table_name('users').'`.`ID`'.'=`'. _table_name('afl_user_holding_transactions').'`.`uid`'
    	)
  	);

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		$query['#where']  = array(
 			'`'._table_name('afl_user_holding_transactions').'`.`paid_status` = 0'
 		);
		$resp = db_select($query, 'get_results');

		return $resp;
}