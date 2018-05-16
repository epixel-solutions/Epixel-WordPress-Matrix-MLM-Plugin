<?php
/**
 * -----------------------------------------
 * Creates all the dashbord widgets callbacks
 * -----------------------------------------
 * @author < pratheesh@epixelsolutions.com >
 *
*/
/*
 * ------------------------------------------------------------
 * Memebers downlines count
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_downlines_count', 'afl_user_downlines_count_callback');
 	add_action('wp_ajax_nopriv_afl_user_downlines_count', 'afl_user_downlines_count_callback');

	function afl_user_downlines_count_callback () {
		if (eps_is_admin()) {
			$uid = afl_root_user();
		} else {
			$uid = afl_current_uid();
		}

		$query = array();
		$query['#select'] = _table_name('afl_user_downlines');
		$query['#where']  = array(
			'uid = '.$uid,
			'deleted = 0'
		);
		$query['#expression'] = array(
			'COUNT(downline_user_id) as count'
		);
		$resp = db_select($query, 'get_row');

		$data['text'] 					= (!empty($resp->count)) ? $resp->count : 0;
	  $data['title'] 					= 'downline members';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}


/*
 * ------------------------------------------------------------
 * e-Wallet
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_e_wallet', 'afl_user_e_wallet_callback');
 	add_action('wp_ajax_nopriv_afl_user_e_wallet', 'afl_user_e_wallet_callback');

	function afl_user_e_wallet_callback () {

  	if (eps_is_admin()) {
			$uid = afl_root_user();
		} else {
			$uid = afl_current_uid();
		}
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');

		$data['text'] 					= !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;
	  $data['title'] 					= 'E-Wallet';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}

/*
 * ------------------------------------------------------------
 * income & credits
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_total_credits', 'afl_user_total_credits_callback');
 	add_action('wp_ajax_nopriv_afl_user_total_credits', 'afl_user_total_credits_callback');

	function afl_user_total_credits_callback () {

		if (eps_is_admin()) {
			$uid = afl_root_user();
		} else {
			$uid = afl_current_uid();
		}
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');

		$data['text'] 					= !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;
	  $data['title'] 					= 'Income & credits';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}

/*
 * ------------------------------------------------------------
 * Expense & Debits
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_total_debits', 'afl_user_total_debits_callback');
 	add_action('wp_ajax_nopriv_afl_user_total_debits', 'afl_user_total_debits_callback');

	function afl_user_total_debits_callback () {
		if (eps_is_admin()) {
			$uid = afl_root_user();
		} else {
			$uid = afl_current_uid();
		}
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			'credit_status = 0',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#expression'] = array(
			'SUM(amount_paid) as total'
		);

		$resp = db_select($query, 'get_row');

		$data['text'] 					= !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;

	  $data['title'] 					= 'Expense & Debits';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}
/*
 * ------------------------------------------------------------
 * E-wallet sum
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_e_wallet_sum', 'afl_user_e_wallet_sum_callback');
 	add_action('wp_ajax_nopriv_afl_user_e_wallet_sum', 'afl_user_e_wallet_sum_callback');

	function afl_user_e_wallet_sum_callback () {
		$data['text'] = 0;
    $data['link'] =  '#';
    $data['icon_size'] = 'text-3x';
    $data['icon_color'] = 'fa-money';
    $data['title'] = 'E-Wallet Balance';
    $data['box_color'] = 'bg-gray';
    $data['valu_text_color'] = 'text-white';
    $data['currency_text'] = 'text-white';

	  echo json_encode($data);
	  die();
	}
/*
 * ------------------------------------------------------------
 * B-wallet Income
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_b_wallet_income', 'afl_b_wallet_income_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_income', 'afl_b_wallet_income_callback');

	function afl_b_wallet_income_callback () {
		$query = array();
		$query['#select'] = _table_name('afl_business_transactions');
		$query['#where'] 	= array(
			'deleted = 0',
			'credit_status = 1',
			'hidden_transaction=0'

		);
		$query['#expression'] = array(
			'SUM(balance) as balance'
		);
		$resp = db_select($query, 'get_row');

		$data['text'] = afl_currency_symbol().' '.afl_format_payment_amount($resp->balance, TRUE);
    $data['title_color'] =  'text-success';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet Income';

	  echo json_encode($data);
	  die();
	}
/*
 * ------------------------------------------------------------
 * B-wallet Expense
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_b_wallet_expense', 'afl_b_wallet_expense_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_expense', 'afl_b_wallet_expense_callback');

	function afl_b_wallet_expense_callback () {

		$query = array();
		$query['#select'] = _table_name('afl_business_transactions');
		$query['#where'] 	= array(
			'deleted = 0',
			'credit_status = 0',
			'hidden_transaction=0'
		);
		$query['#expression'] = array(
			'SUM(amount_paid) as balance'
		);
		$resp = db_select($query, 'get_row');

		$data['text'] = afl_currency_symbol().' '.afl_format_payment_amount($resp->balance, TRUE);
    $data['title_color'] =  'text-danger';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet Expense';

	  echo json_encode($data);
	  die();
	}
/*
 * ------------------------------------------------------------
 * B-wallet Balance
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_b_wallet_balance', 'afl_b_wallet_balance_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_balance', 'afl_b_wallet_balance_callback');

	function afl_b_wallet_balance_callback () {

		$query = array();
		$query['#select'] = _table_name('afl_business_transactions');
		$query['#where'] 	= array(
			'deleted = 0',
			'hidden_transaction=0'
		);
		$query['#expression'] = array(
			'SUM(balance) as balance'
		);
		$resp = db_select($query, 'get_row');

		$data['text'] = afl_currency_symbol().' '.afl_format_payment_amount($resp->balance, TRUE);
    $data['title_color'] =  'text-info';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet';

	  echo json_encode($data);
	  die();
	}
/*
 * ------------------------------------------------------------
 * B-wallet Balance
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_member_rank', 'afl_member_rank_callback');
 	add_action('wp_ajax_nopriv_afl_member_rank', 'afl_member_rank_callback');

	function afl_member_rank_callback () {
		//get the member rank
		$uid = afl_current_uid();
		$query = array();

		$query['#select'] = _table_name('afl_unilevel_user_genealogy');
		$query['#where'] 	= array(
			'`'._table_name('afl_unilevel_user_genealogy').'`.`uid` ='.$uid
		);

		$query['#fields'] = array(
			_table_name('afl_unilevel_user_genealogy') => array('member_rank')
		);
		$rank = db_select($query,'get_var');

		if ( empty( $rank ) ) {
			$rank_name = 'Active';
		} else {
			$rank_name = afl_variable_get('rank_'.$rank.'_name');
		}

		$rank_color = afl_variable_get('rank_'.$rank.'_color','#eea236');

 	  echo '<span style="display: inline; padding: .2em .6em .3em; font-size: 100%;font-weight: 700;line-height: 1; color: #fff;
        text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25em;background-color:'.$rank_color.';">'.$rank_name.' </span>';
	  die();
	}
/*
 * ------------------------------------------------------------
 * E-wallet transaction chart
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_e_wallet_transaction_chart', 'afl_e_wallet_transaction_chart_callback');
 	add_action('wp_ajax_nopriv_afl_e_wallet_transaction_chart', 'afl_e_wallet_transaction_chart_callback');

  function afl_e_wallet_transaction_chart_callback () {
  	$visible_value = '';
  	if ( isset($_POST['visible_value']) ){
  		$visible_value = $_POST['visible_value'];
  	}
  	$uid = get_uid();


	  $query = array();
	  $query['#select'] = _table_name('afl_user_transactions');
	  $query['#fields'] = array(
	  	_table_name('afl_user_transactions') => array(
	  		'credit_status','currency_code','transaction_day','transaction_month','transaction_year','category'
	  	)
	  );
	  $query['#where'] = array(
	  	'`deleted`=0',
	  	'`uid`='.$uid
	  );
	  $query['#group_by'] = array(
	  	'credit_status','category'
	  );
	  $query['#expression'] = array(
	  	'SUM(amount_paid) as total_sum'
	  );
	  $query['#order_by'] = array(
	  	'category' =>'ASC',
	  	'created'  => 'DESC'
	  );
	  $transaction_sum = db_select($query, 'get_results');
	  // pr( $transaction_sum);
	  $date_format = 'y';
	  $dates = getDays(7,'year','M-Y');

	  if(!empty($transaction_sum)){
    	foreach ($transaction_sum as $value) {
	      $transaction_date = $value->transaction_year;

	      if(!empty($value->transaction_month)){ $transaction_date .='-'.$value->transaction_month;}
	      if(!empty($value->transaction_day)){ $transaction_date .='-'.$value->transaction_day;}

	      $transaction_date = date($date_format ,strtotime($transaction_date));
	      $credit_status = $value->credit_status;
	      if(!isset($setData[$value->category][$credit_status])){
	        $setData[$value->category][$credit_status] = $dates;
	      }
	      $setData[$value->category][$credit_status][$transaction_date] = (afl_get_payment_amount($value->total_sum));
	    }
	  }

	  $json = array();
	  $currency = afl_currency_symbol();
	  if(!empty($setData)){
	    $var_credit_status = list_extract_allowed_values(afl_variable_get('afl_var_credit_status'),'list_text',false);
	    $var_payment_sources = list_extract_allowed_values(afl_variable_get('afl_payment_sources'),'list_text',false);
	    foreach ($setData as $category_name => $category_value) {
	      foreach ($category_value as $category_key => $category_val) {
	        $label_name = ($var_payment_sources[$category_name]) ? __($var_payment_sources[$category_name]): $category_name;
	        $label_name .= ($var_credit_status[$category_key]) ? ' ('.__($var_credit_status[$category_key]).')' : NULL;
	        $json['series'][] = array(
	          'name' => $label_name,
	          'data' => array_values($category_val),
	        );
	      }
	    }
	  }


	  $json['colors'] = array('#23b7e5', '#7266ba');
	  $element_color = afl_variable_get('recent_transaction_chart_colors',NULL);
	  if(!empty($element_color)){
	    $element_color = str_replace(' ', '', $element_color);
	    $json['colors'] =  explode(",",$element_color);
	  }
	  $json['chart']['fontFamily'] = "Source Sans Pro";
	  $json['chart']['height'] = 350;
	  $json['chart']['type'] = 'areaspline';
	  $json['title']['text'] = NULL;
	  if(!empty($json['series'])){
	    $json['legend']['backgroundColor'] = '#FFFFFF';
	    $json['legend']['itemStyle']['color'] = "#a1a7ac";
	    $json['legend']['itemStyle']['display'] = "none";
	    $json['legend']['itemStyle']['title'] = "none";
	    $json['legend']['itemStyle']['cursor'] = "pointer";
	    $json['legend']['itemStyle']['fontSize'] = "13px";
	    $json['legend']['itemStyle']['fontWeight'] = "normal";
	  }else{
	    $json['series'][] = array(
	      'name' => __('Amount'),
	      'data' => array_values($dates),
	    );
	    $json['legend']['enabled'] = false;
	  }
	  $json['plotOptions'] = array('area'=>array('fillOpacity' => 0.7));
	  $json['xAxis']['gridLineColor'] = '#EFEFEF';
	  $json['xAxis']['categories'] = array_keys($dates);
	  $json['xAxis']['gridLineWidth'] = 1;
	  $json['xAxis']['labels']['style']['fontSize'] = '13px';
	  $json['xAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['yAxis']['minorTickInterval'] = 'auto';
	  $json['yAxis']['title']['style']['textTransform'] = 'uppercase';
	  $json['yAxis']['labels']['style']['fontSize'] = '13px';
	  $json['yAxis']['title']['text'] = NULL;
	  $json['yAxis']['gridLineColor'] = '#EFEFEF';
	  $json['yAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['plotOptions']['candlestick']['lineColor'] = '#323a45';
	  $json['plotOptions']['candlestick']['fontSize'] = '13px';
	  $json['tooltip']['shared'] = true;
	  $json['tooltip']['valueSuffix'] = ' '.$currency;
	  $json['tooltip']['backgroundColor'] = "#f0f3f4";
	  $json['tooltip']['borderWidth'] = 1;
	  $json['tooltip']['borderColor'] = '#e8eaea';
	  $json['tooltip']['enabled'] = true;
	  $json['credits']['enabled'] = false;
	  $json['exporting']['enabled'] = false;

	  $data['text'] = $json;
	  $data['title'] = __('E-wallet Transactions');
	  $data['title_color'] = 'text-muted font-thin-bold';
	  $data['show_chart'] = 'showSpline';
	  $data['icon_color'] = 'text-muted';
	  $data['chart_style'] = 'height:350px';
	  echo json_encode($data);
	  die();
  }
/*
 * ------------------------------------------------------------
 * E-wallet summary
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_e_wallet_summary', 'afl_e_wallet_summary_callback');
 	add_action('wp_ajax_nopriv_afl_e_wallet_summary', 'afl_e_wallet_summary_callback');

 	function afl_e_wallet_summary_callback () {
  	$uid = get_uid();

	  $query = array();
	  $query['#select'] = _table_name('afl_user_transactions');
	  $query['#fields'] = array(
	  	_table_name('afl_user_transactions') => array(
	  		'category'
	  	)
	  );
	  $query['#where'] = array(
	  	'`uid` = '.$uid
	  );
	  $query['#expression'] = array(
	  	'SUM(amount_paid) as Amount'
	  );
	  $query['#group_by'] = array(
	  	'category'
	  );
	  $summary = db_select($query, 'get_results');

	  $data = array();
	  if($summary){
	    $data['ewallet_summary'] = $summary;

	  }
	  echo afl_get_template('dashboard/afl_ewallet_summary.php', $data);
	  die();
 	}
/*
 * ------------------------------------------------------------
 * B-wallet Transactions
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_b_wallet_transactions_chart', 'afl_b_wallet_transactions_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_transactions_chart', 'afl_b_wallet_transactions_callback');

 	function afl_b_wallet_transactions_callback () {
 		$visible_value = '';
  	if ( isset($_POST['visible_value']) ){
  		$visible_value = $_POST['visible_value'];
  	}
  	$uid = get_uid();


	  $query = array();
	  $query['#select'] = _table_name('afl_business_transactions');
	  $query['#fields'] = array(
	  	_table_name('afl_business_transactions') => array(
	  		'credit_status','currency_code','transaction_day','transaction_month','transaction_year','category'
	  	)
	  );
	  $query['#where'] = array(
	  	'`deleted`=0',
	  );
	  $query['#group_by'] = array(
	  	'credit_status','category'
	  );
	  $query['#expression'] = array(
	  	'SUM(amount_paid) as total_sum'
	  );
	  $query['#order_by'] = array(
	  	'category' =>'ASC',
	  	'created'  => 'DESC'
	  );
	  $transaction_sum = db_select($query, 'get_results');
	  // pr( $transaction_sum);
	  $date_format = 'y';
	  $dates = getDays(7,'year','M-Y');

	  if(!empty($transaction_sum)){
    	foreach ($transaction_sum as $value) {
	      $transaction_date = $value->transaction_year;

	      if(!empty($value->transaction_month)){ $transaction_date .='-'.$value->transaction_month;}
	      if(!empty($value->transaction_day)){ $transaction_date .='-'.$value->transaction_day;}

	      $transaction_date = date($date_format ,strtotime($transaction_date));
	      $credit_status = $value->credit_status;
	      if(!isset($setData[$value->category][$credit_status])){
	        $setData[$value->category][$credit_status] = $dates;
	      }
	      $setData[$value->category][$credit_status][$transaction_date] = (afl_get_payment_amount($value->total_sum));
	    }
	  }

	  $json = array();
	  $currency = afl_currency_symbol();
	  if(!empty($setData)){
	    $var_credit_status = list_extract_allowed_values(afl_variable_get('afl_var_credit_status'),'list_text',false);
	    $var_payment_sources = list_extract_allowed_values(afl_variable_get('afl_payment_sources'),'list_text',false);
	    foreach ($setData as $category_name => $category_value) {
	      foreach ($category_value as $category_key => $category_val) {
	        $label_name = !empty($var_payment_sources[$category_name]) ? _($var_payment_sources[$category_name]): $category_name;
	        $label_name .= ($var_credit_status[$category_key]) ? ' ('.__($var_credit_status[$category_key]).')' : NULL;
	        $chat_type = ($var_credit_status[$category_key] =='Credited') ? 'column':'spline';
	        $json['series'][] = array(
	          'type' => $chat_type,
	          'name' => $label_name,
	          'data' => array_values($category_val),
	        );
	      }
	    }
	  }


	  $json['colors'] = array('#23b7e5', '#7266ba');
	  $element_color = afl_variable_get('recent_transaction_chart_colors',NULL);
	  if(!empty($element_color)){
	    $element_color = str_replace(' ', '', $element_color);
	    $json['colors'] =  explode(",",$element_color);
	  }
	  $json['chart']['fontFamily'] = "Source Sans Pro";
	  $json['chart']['height'] = 350;
	  $json['chart']['type'] = 'spline';
	  $json['title']['text'] = NULL;
	  if(!empty($json['series'])){
	    $json['legend']['backgroundColor'] = '#FFFFFF';
	    $json['legend']['itemStyle']['color'] = "#a1a7ac";
	    $json['legend']['itemStyle']['display'] = "none";
	    $json['legend']['itemStyle']['title'] = "none";
	    $json['legend']['itemStyle']['cursor'] = "pointer";
	    $json['legend']['itemStyle']['fontSize'] = "13px";
	    $json['legend']['itemStyle']['fontWeight'] = "normal";
	  }else{
	    $json['series'][] = array(
	      'name' => __('Amount'),
	      'data' => array_values($dates),
	    );
	    $json['legend']['enabled'] = false;
	  }
	  $json['plotOptions'] = array('area'=>array('fillOpacity' => 0.7));
	  $json['xAxis']['gridLineColor'] = '#EFEFEF';
	  $json['xAxis']['categories'] = array_keys($dates);
	  $json['xAxis']['gridLineWidth'] = 1;
	  $json['xAxis']['labels']['style']['fontSize'] = '13px';
	  $json['xAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['yAxis']['minorTickInterval'] = 'auto';
	  $json['yAxis']['title']['style']['textTransform'] = 'uppercase';
	  $json['yAxis']['labels']['style']['fontSize'] = '13px';
	  $json['yAxis']['title']['text'] = NULL;
	  $json['yAxis']['gridLineColor'] = '#EFEFEF';
	  $json['yAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['plotOptions']['candlestick']['lineColor'] = '#323a45';
	  $json['plotOptions']['candlestick']['fontSize'] = '13px';
	  $json['tooltip']['shared'] = true;
	  $json['tooltip']['valueSuffix'] = ' '.$currency;
	  $json['tooltip']['backgroundColor'] = "#f0f3f4";
	  $json['tooltip']['borderWidth'] = 1;
	  $json['tooltip']['borderColor'] = '#e8eaea';
	  $json['tooltip']['enabled'] = true;
	  $json['credits']['enabled'] = false;
	  $json['exporting']['enabled'] = false;

	  $data['text'] = $json;
	  $data['title'] = __('B-Wallet Transactions');
	  $data['title_color'] = 'text-muted font-thin-bold';
	  $data['show_chart'] = 'showSpline';
	  $data['icon_color'] = 'text-muted';
	  $data['chart_style'] = 'height:350px';
	  echo json_encode($data);
	  die();
 	}
/*
 * ------------------------------------------------------------
 * B-wallet Report
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_b_wallet_report_chart', 'afl_b_wallet_report_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_report_chart', 'afl_b_wallet_report_callback');

 	function afl_b_wallet_report_callback () {
 		$json 						= array();
	  $afl_date 				= afl_date();
	  $afl_date_splits 	= afl_date_splits($afl_date);
	  $currency 				= afl_currency_symbol();

	  $query = array();
	  $query['#select'] = _table_name('afl_business_transactions');
	  $query['#fields'] = array(
	  	_table_name('afl_business_transactions') => array(
	  		'credit_status','currency_code','category'
	  	)
	  );
	  $query['#expression'] = array(
	  	'SUM(amount_paid) as total_sum'
	  );
	  $query['#where'] = array(
	  	'`deleted`=0',
	  );
	  $query['#group_by'] = array(
	  	'credit_status'
	  );
	  $query['#order_by'] = array(
	  	'category' =>'ASC',
	  	'created'  => 'DESC'
	  );
	  $transaction_sum = db_select($query, 'get_results');

	  $setData = array();
	  $count =0;
	  foreach ($transaction_sum as $transaction_valu) {
	    $label_name = !empty($transaction_valu->credit_status) ? __('Income') : __('Expenses');
	    $setData[$count]['name'] 				= $label_name;
	    $setData[$count]['y']		 				= (double) (afl_get_payment_amount($transaction_valu->total_sum));
	    $setData[$count]['label_name'] 	= $setData[$count]['y'].' <b>'.$currency.'</b>';
	    if($count == 0){
	      $setData[$count]['sliced'] 		= true;
	      $setData[$count]['selected'] 	= true;
	    }
	    $count++;
	  }
	  if(empty($setData)){
	    $setData = array(array(__('Income'),0),array(__('Expenses'),0));
	  }
	  $json['series']= array(
	    array(
	      'colorByPoint' 	=> true,
	      'data' 					=> $setData,
	    ),
	  );

	  $json['chart']['plotBackgroundColor'] = NULL;
	  $json['chart']['plotBorderWidth'] 		= NULL;
	  $json['chart']['plotShadow'] 					= false;
	  $json['chart']['type'] 								= 'pie';
	  $json['title']['text'] 								= NULL;

	  $json['tooltip']['pointFormat'] =  '{point.label_name} <b> ({point.percentage:.1f}%)</b>';
	  $json['tooltip']['valueSuffix'] 											= ' '.$currency;
	  $json['plotOptions']['pie']['allowPointSelect'] 			= true;
	  $json['plotOptions']['pie']['cursor'] 								= 'pointer';
	  $json['plotOptions']['pie']['dataLabels']['enabled'] 	= true;
	  $json['plotOptions']['pie']['dataLabels']['format'] 	= '<b>{point.name}</b>';
	  $json['plotOptions']['pie']['dataLabels']['style'] 		= '#f00';
	  $json['colors'] 							= array('#7266ba','#23b7e5');
	  $json['exporting']['enabled'] = false;
	  $json['credits']['enabled'] 	= false;
	  $json['chart']['fontFamily'] 	= "Source Sans Pro";

	  $data = array();
	  $data['text'] 				= $json;
	  $data['title'] 				= __('B-Wallet Report');
	  $data['title_color'] 	= 'text-muted font-thin-bold';
	  $data['show_chart'] 	= 'pie';
	  $data['icon_color'] 	= 'text-muted';
	  $data['chart_style'] 	= 'height:350px';

	  echo json_encode($data);
	  die();
 	}
/*
 * ------------------------------------------------------------
 * level users
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_each_level_user_count', 'afl_each_level_user_count_callback');
 	add_action('wp_ajax_nopriv_afl_each_level_user_count', 'afl_each_level_user_count_callback');

 	function afl_each_level_user_count_callback () {
 		$uid 	 = get_uid();
		$width = afl_variable_get('matrix_plan_width', 0);
	 	$depth = afl_variable_get('matrix_plan_height', 0);

	 	$query = array();
	 	$query['#select'] 		= _table_name('afl_user_downlines');
	 	$query['#group_by'] 	= array('level');
	 	$query['#where']    	= array(
			'`uid` ='.$uid,
			'`level`<='.$depth
	 	);
	 	$query['#expression'] = array(
		 'COUNT(downline_user_id) as count',
		 'POWER('.$width.', level) as total'
	 	);

	 	$res = db_select($query, 'get_results');

		if (count($res)<$depth) {
			for ($i = count($res); $i < $depth ; $i++) {
				$res[$i] = new stdClass();
				$res[$i]->level = $i+1;
				$res[$i]->count = 0;
				$res[$i]->total = pow($width, ($i+1));
			}
		}

		echo json_encode($res);
		die();
 	}
/*
 * ------------------------------------------------------------
 * Downlines members count chart
 * ------------------------------------------------------------
*/
 	add_action('wp_ajax_afl_downline_members_chart', 'afl_downline_members_chart_callback');
 	add_action('wp_ajax_nopriv_afl_downline_members_chart', 'afl_downline_members_chart_callback');

 	function afl_downline_members_chart_callback () {

	  $max_list = afl_variable_get('afl_widgets_downline_members_c_max_list',5);

	  $uid 		= get_uid();
	  $query 	= array();
	  $query['#select'] = _table_name('afl_user_downlines');
	  $query['#where']  = array(
	  	'`uid`='.$uid,
	  	'`deleted` = 0'
	  );
	  $query['#expression'] = array(
	  	'COUNT(downline_user_id) as total_count'
	  );
	  $query['group_by'] = array(
	  	'joined_year'
	  );
	  $query['#order_by'] = array(
	  	'created' => 'DESC'
	  );
	  $query['#limit'] = $max_list;

	  $num_of_results = db_select($query, 'get_results');
	  $dates = getDays(7,'day','d-M');

	  $setData = $dates;
	  $date_format = 'Y';
	  if(!empty($num_of_results)){
	    foreach ($num_of_results as $value) {
	      $joined_date = $value->joined_year;
	      if(!empty($value->joined_month)){ $joined_date .='-'.$value->joined_month;}
	      if(!empty($value->joined_day)){ $joined_date .='-'.$value->joined_day;}
	      $joined_date = date($date_format ,strtotime($joined_date));
	      $setData[$joined_date] = (double) $value->total_count;
	    }
	  }

	  $json = array();
	  $json['series'][] = array(
	    'name' => 'Members',
	    'data' => array_values($setData),
	  );
	  $json['colors'] = array('#23b7e5');
	  $json['chart']['fontFamily'] = "Source Sans Pro";
	  $json['chart']['height'] = 240;
	  $json['chart']['type'] = 'area';
	  $json['title']['text'] = NULL;
	  $json['legend']['enabled'] = false;
	  $json['xAxis']['gridLineColor'] = '#EFEFEF';
	  $json['xAxis']['categories'] = array_keys($dates);
	  $json['xAxis']['gridLineWidth'] = 1;
	  $json['xAxis']['labels']['style']['fontSize'] = '13px';
	  $json['xAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['yAxis']['minorTickInterval'] = 'auto';
	  $json['yAxis']['title']['style']['textTransform'] = 'uppercase';
	  $json['yAxis']['labels']['style']['fontSize'] = '13px';
	  $json['yAxis']['title']['text'] = NULL;
	  $json['yAxis']['gridLineColor'] = '#EFEFEF';
	  $json['yAxis']['labels']['style']['color'] = '#a1a7ac';
	  $json['plotOptions']['candlestick']['lineColor'] = '#323a45';
	  $json['plotOptions']['candlestick']['fontSize'] = '13px';
	  $json['plotOptions'] 	= array('area'=>array('fillOpacity' => 0.7));
	  $json['tooltip']['shared'] = true;
	  $json['tooltip']['valueSuffix'] 		= NULL;
	  $json['tooltip']['backgroundColor'] = "#f0f3f4";
	  $json['tooltip']['borderWidth'] = 1;
	  $json['tooltip']['borderColor'] = '#e8eaea';
	  $json['tooltip']['enabled'] = true;
	  $json['credits']['enabled'] = false;

	  $data['text'] 				= $json;
	  $data['title'] 				= __('Downline Members');
	  $data['title_color'] 	= 'text-primary-lt font-thin-bold';
	  $data['show_chart'] 	= 'showSpline';
	  $data['icon_color'] 	= 'text-muted';
	  $data['chart_style'] 	= 'height:240px';

	  echo json_encode($data);
	  die();
 	}