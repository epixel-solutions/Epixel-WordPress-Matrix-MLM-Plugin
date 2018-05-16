<?php

function afl_system_business_profit_report () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_system_business_profit_report_();
	afl_content_wrapper_end();
}

function afl_system_business_profit_report_ () {
		new Afl_enque_scripts('common');

	 	$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_profit());
		$config['base_url'] 	= '?page=affiliate-eps-business-profit';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_profit($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Business Profit Overview(Monthly)';
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
			__('Month'),
			__('Year'),
			__('Profit')		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->transaction_month
			);

			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->transaction_year
			);

			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_currency_symbol().' '.afl_get_payment_amount($value->balance)
			);
		}
		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
}

function _get_profit($index = 0, $limit = '') {

	$date  = afl_date() - (30*24*60*60);
	$afl_date_splits = afl_date_splits($date);
	
	$query = array();
	$query['#select'] = _table_name('afl_business_transactions');
	$query['#fields'] = array(
		_table_name('afl_business_transactions') => array(
			'created',
			'transaction_day',
			'transaction_month',
			'transaction_year'
		)
	);
	$query['#where'] 	= array(
		'deleted = 0',
		'hidden_transaction=0',
	);
	$query['#expression'] = array(
		'SUM(balance) as balance'
	);
	$query['#group_by'] = array(
		'transaction_month',
		'transaction_year'
	);

	if (!empty($limit) ) {
		$query['#limit'] = $index.','.$limit;
	}

	$resp = db_select($query, 'get_results');

		return $resp;
}

