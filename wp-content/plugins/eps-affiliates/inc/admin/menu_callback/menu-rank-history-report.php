<?php
	
	function afl_rank_history_report () {
			new Afl_enque_scripts('common');
		echo afl_eps_page_header();
		afl_content_wrapper_begin();
			afl_rank_history_report_table();
		afl_content_wrapper_end();
	}

	function afl_rank_history_report_table () {
		$uid = get_uid();

		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_rank_history_report($uid));
		$config['base_url'] 	= '?page=affiliate-eps-rank-history-report';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _rank_history_report($uid,$index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Rank History';
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
			__('Rank Name'),
			__('Achieved On'),
			__('Incentives'),
			__('Incentive Status'),

		);
		// pr($data);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_rankname'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank)
			);
			$rows[$key]['markup_created'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->updated, TRUE)
			);
			$rows[$key]['markup_incentives'] = array(
				'#type' =>'markup',
				'#markup'=> afl_variable_get('rank_'.$value->member_rank.'_incentives','???')
			);

			$paid_status  = list_extract_allowed_values(afl_variable_get('afl_var_incentive_paid_status'),'list_text');
			$inc_data 		= _get_incentive_data($value->uid, $value->member_rank);
			
			$paid_status = 0;
			if ( !empty($inc_data) && !empty($inc_data->paid)) {
				$paid_status = 1;
			}

			$rows[$key]['markup_incentives_status'] = array(
				'#type' =>'markup',
				'#markup'=> !empty($paid) ? 'Paid' : 'Not Paid',
			);
		}
	$table['#rows'] = $rows;
	echo apply_filters('afl_render_table',$table);
}

function _rank_history_report ( $uid = '',$index = 0, $limit = '' ) {
		$query = array();
		$query['#select'] = _table_name('afl_rank_history');
		$query['#join']  = array(
     _table_name('users') => array(
     		'#condition' => '`'. _table_name('users').'`.`ID`'.'=`'. _table_name('afl_rank_history').'`.`uid`'
    	)
  	);
		$query['#where'] = [
			'`uid`='.$uid
		];
		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		$resp = db_select($query, 'get_results');

			return $resp;
}

function _get_incentive_data ( $uid = '',$rank = 0) {
		$query = array();
		$query['#select'] = _table_name('afl_bonus_incentive_history');

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		$query['#where'] = [
			'`uid`='.$uid,
			'`member_rank`='.$rank,
		];
		$resp = db_select($query, 'get_results');

		return $resp;
}