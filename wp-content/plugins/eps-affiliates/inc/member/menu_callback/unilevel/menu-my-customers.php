<?php
function afl_unilevel_my_customers () {
	do_action('eps_affiliate_page_header');
	do_action('afl_content_wrapper_begin');
		afl_unilevel_my_customers_form();
	do_action('afl_content_wrapper_end');
}

function afl_unilevel_my_customers_form () {
		new Afl_enque_scripts('common');
		
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_my_customers_uni());
		$config['base_url'] 	= '?page=affiliate-eps-my-customers';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _my_customers_uni($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'My customers Overview';
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
			__('Customer Name'),
			__('Joined Date'),
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);

			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);
		}
		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
}

function _my_customers_uni ($index = 0, $limit = '') {
	$uid = get_uid();

	$query = array();
	$query['#select'] = _table_name('afl_customer');
	$query['#join']   = array(
		_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_customer').'`.`uid`'
				)
	);
	$query['#where'] = array(
		'`'._table_name('afl_customer').'`.`referrer_uid` ='.$uid
	);
	$query['#fields'] = array(
		_table_name('users') => array('ID','user_login','display_name'),
		_table_name('afl_customer') => array('created')
	);
	if (!empty($limit) ) {
		$query['#limit'] = $index.','.$limit;
	}
	$res = db_select($query, 'get_results');
	return $res;
}