<?php 
/*
 * ------------------------------------------------
 * User e-wallet trsnsation summary
 * ------------------------------------------------
*/
function afl_ewallet_summary(){
	new Afl_enque_scripts('common');
	
	echo afl_eps_page_header();
	afl_content_wrapper_begin();

	echo '<div class="row">'.do_shortcode('[afl_ewallet_all_earnings_summary_blocks_shortcode]').'</div>';
	afl_ewallet_summary_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_all_transactions(){
	new Afl_enque_scripts('common');

	echo afl_eps_page_header();
	afl_content_wrapper_begin();

	echo '<div class="row">'.do_shortcode('[afl_ewallet_all_earnings_summary_blocks_shortcode]').'</div>';
	
	afl_ewallet_all_transactions_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_income_report(){
	new Afl_enque_scripts('common');
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
	afl_ewallet_income_report_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_withdrawal_report(){
	new Afl_enque_scripts('common');
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
	afl_ewallet_withdrawal_report_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_holding_transactions(){
	new Afl_enque_scripts('common');
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
	echo '<div class="row">'.do_shortcode('[afl_ewallet_all_earnings_holding_summary_blocks_shortcode]').'</div>';
	
	afl_ewallet_holding_transactions_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_holding_summary(){
	new Afl_enque_scripts('common');
	
	echo afl_eps_page_header();
	afl_content_wrapper_begin();

	echo '<div class="row">'.do_shortcode('[afl_ewallet_all_earnings_holding_summary_blocks_shortcode]').'</div>';
	afl_ewallet_holding_summary_callback();
	afl_content_wrapper_end();
}

function afl_ewallet_summary_callback(){

		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_get_ewallet_summary($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-e-wallet';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = _get_ewallet_summary($uid,$filter);
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
			__('Amount'),
			__('Details'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category)),
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->balance).$value->currency_code  	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}
/*
 * -------------------------------------------------
 * E-wallet summary
 * -------------------------------------------------
*/
	function _get_ewallet_summary ($uid = '', $filters = array(), $count = FALSE) {
		global $wpdb;
		if (empty($uid)) {
			$uid  = get_uid();
		}
		$table = _table_name('afl_user_transactions');
		
		if ( !empty($filters['holding_transaction'])) {
		 	$table = _table_name('afl_user_holding_transactions');
		}

		$result = $wpdb->get_results("SELECT `".$table."`.`category`,`currency_code`, SUM(`".$table."`.`balance`) as balance  FROM `".$table."` WHERE `uid` = $uid GROUP BY `category` DESC");
		if ( $count ) {
			return count($result);
		}
		return $result;
	}
/*
 * ------------------------------------------------
 * User e-wallet all trsnsation 
 * ------------------------------------------------
*/
function afl_ewallet_all_transactions_callback(){
		new Afl_enque_scripts('common');

		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (get_all_user_transaction_details($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-ewallet-all-transactions';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = get_all_user_transaction_details($uid,$filter);
		
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
			__('Payment Source'),
			__('Associate Member'),
			__('Amount'),
			__('Credit Status'),
			__('Date'),
			__('Notes'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category))
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name." (".$value->associated_user_id.")",
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid).$value->currency_code,
			);

			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}

			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $status,
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->transaction_date) , 	
			);
			$rows[$key]['markup_6'] = array(
				'#type' =>'markup',
				'#markup'=> $value->notes , 	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}


function afl_ewallet_income_report_callback(){

		new Afl_enque_scripts('common');

		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (get_all_user_transaction_details($uid,array(),TRUE,1));
		$config['base_url'] 	= '?page=affiliate-eps-ewallet-income-report';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = get_all_user_transaction_details($uid,$filter,FALSE,1);
		
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
			__('Payment Source'),
			__('Associate Member'),
			__('Amount'),
			__('Credit Status'),
			__('Date'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category))
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name." (".$value->associated_user_id.")",
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid).$value->currency_code,
			);

			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}

			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $status,
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->transaction_date) , 	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}


function afl_ewallet_withdrawal_report_callback(){

		new Afl_enque_scripts('common');
	
	$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (get_all_user_transaction_details($uid,array(),TRUE,0));
		$config['base_url'] 	= '?page=affiliate-eps-ewallet-withdraw-report';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = get_all_user_transaction_details($uid,$filter,FALSE,0);
		
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
			__('Expense Source'),
			__('Associate Member'),
			__('Amount'),
			__('Credit Status'),
			__('Transaction'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category))
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name." (".$value->associated_user_id.")",
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid).$value->currency_code,
			);

			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}

			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $status,
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->transaction_date) , 	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}


/*
 * ------------------------------------------------
 * User e-wallet all holding trsnsation 
 * ------------------------------------------------
*/
function afl_ewallet_holding_transactions_callback(){

		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (get_all_user_transaction_details($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-ewallet-all-holding-transactions';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		$filter['holding_transaction'] = 1;
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = get_all_user_transaction_details($uid,$filter);
		
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
			__('Payment Source'),
			__('Associate Member'),
			__('Amount'),
			__('Credit Status'),
			__('Date'),
			__('Notes'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category))
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name." (".$value->associated_user_id.")",
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid).$value->currency_code,
			);

			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}

			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $status,
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->transaction_date) , 	
			);
			$rows[$key]['markup_6'] = array(
				'#type' =>'markup',
				'#markup'=> $value->notes , 	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}

function afl_ewallet_holding_summary_callback(){

		$uid = get_uid();
		if (isset($_GET['uid'])) {
			$uid = $_GET['uid'];
		}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_get_ewallet_summary($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-ewallet-holding-transactions-summary';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		$filter['holding_transaction'] = 1;

		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = _get_ewallet_summary($uid,$filter);
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
			__('Amount'),
			__('Details'),
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> ucfirst(strtolower($value->category)),
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->balance).$value->currency_code  	
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}