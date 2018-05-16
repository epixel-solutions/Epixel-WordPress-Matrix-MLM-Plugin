<?php 

function afl_incentive_history_report () {
		new Afl_enque_scripts('common');
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_incentive_history_report_table();
	afl_content_wrapper_end();
}

function afl_incentive_history_report_table () {
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_incentive_history_report());
		$config['base_url'] 	= '?page=affiliate-eps-incentive-history-report';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _incentive_history_report($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Incentives History';
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
			__('uid'),
			__('User Name'),
			__('E-mail'),
			__('Rank Name'),
			__('Achieved On'),
			__('Incentives'),
			__('Status'),
		);
		// pr($data);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);

			$rows[$key]['markup_uid'] = array(
				'#type' =>'markup',
				'#markup'=> $value->uid
			);
			$rows[$key]['markup_uname'] = array(
				'#type' =>'markup',
				'#markup'=> $value->user_login
			);
			$rows[$key]['markup_uemail'] = array(
				'#type' =>'markup',
				'#markup'=> $value->user_email
			);
			$rows[$key]['markup_rankname'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank)
			);
			$rows[$key]['markup_created'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created_on, TRUE)
			);
			$rows[$key]['markup_incentives'] = array(
				'#type' =>'markup',
				'#markup'=> $value->incentives
			);

			$paid_status  = list_extract_allowed_values(afl_variable_get('afl_var_incentive_paid_status'),'list_text');
			$rows[$key]['markup_incentives_status'] = array(
				'#type' =>'markup',
				'#markup'=> !empty($paid_status[$value->paid]) ? $paid_status[$value->paid] : $value->paid
			);
		}
	$table['#rows'] = $rows;
	echo apply_filters('afl_render_table',$table);
}

function _incentive_history_report ( $index = 0, $limit = '' ) {
	$query = array();
		$filter_severity 	= -1;

		$query['#select'] = _table_name('afl_bonus_incentive_history');
		$query['#join']  = array(
     _table_name('users') => array(
     		'#condition' => '`'. _table_name('users').'`.`ID`'.'=`'. _table_name('afl_bonus_incentive_history').'`.`uid`'
    	)
  	);

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		$resp = db_select($query, 'get_results');

			return $resp;
}