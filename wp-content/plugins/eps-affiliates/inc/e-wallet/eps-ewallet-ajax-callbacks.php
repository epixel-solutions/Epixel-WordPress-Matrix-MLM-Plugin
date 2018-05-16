<?php 
/*
 * ------------------------------------------------
 * User e-wallet trsnsation summary
 * ------------------------------------------------
*/
// function afl_user_ewallet_summary_data_table_callback(){

// 		global $wpdb;
// 		$uid 					 = get_current_user_id();
// 		$result = $wpdb->get_results("SELECT `"._table_name('afl_user_transactions')."`.`category`,`currency_code`, SUM(`"._table_name('afl_user_transactions')."`.`balance`) as balance  FROM `"._table_name('afl_user_transactions')."` WHERE `uid` = $uid GROUP BY `category` DESC");
// 		$output = [
// 	    "draw" 						=> 1,
// 	    "recordsTotal" 		=> 25,
// 	    "recordsFiltered" 	=> 2,
// 	    "data" 						=> [],
// 		];
// 		foreach ($result as $key => $value) {
// 			$output['data'][] = [
// 	   		$key+1,
// 	     	$value->category,
// 	     	afl_format_payment_amount($value->balance).$value->currency_code  	
//    		];
// 		}
// 	echo json_encode($output);
// 	die();
// }

/*
 * ------------------------------------------------
 * User e-wallet all trsnsaction 
 * ------------------------------------------------
*/
// function afl_user_ewallet_all_transaction_data_table(){
// 	global $wpdb;
// 	$uid 						 = afl_current_uid();
	
// $input_valu = $_POST;
//  	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
//      $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
//   }
//   if(!empty($input_valu['search']['value'])) {
//      $filter['search_valu'] = $input_valu['search']['value'];
//   }
 
//   $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
//   $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

//   $result_count = get_all_user_transaction_details($uid,array(),TRUE); 
//   $filter_count = get_all_user_transaction_details($uid,$filter,TRUE); 

// // 
//     $result = get_all_user_transaction_details($uid,$filter);

//   	$output = [
//      "draw" 						=> $input_valu['draw'],
//      "recordsTotal" 		=> $result_count,
//      "recordsFiltered" 	=> $filter_count,
//      "data" 						=> [],
//    ];
//     $result = get_all_user_transaction_details($uid,$filter);
// 		foreach ($result as $key => $value) { 
// 			if($value->credit_status == 1 ){
// 				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
// 			}
// 			else{
// 				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
// 			}
// 			$output['data'][] = [
// 	   		$key+1,
// 	     	ucfirst(strtolower($value->category)),
// 	     	$value->display_name." (".$value->associated_user_id.")",
// 	 			// number_format($value->amount_paid, 2, '.', ',')." " .$value->currency_code ,
// 	 			afl_format_payment_amount($value->amount_paid).$value->currency_code,
// 	     	$status,
// 	     	afl_system_date_format($value->transaction_date) , 	
// 	     	$value->notes  	
//    		];
// 		}
// 	echo json_encode($output);
// 	die();
// }
/*
 * ------------------------------------------------
 * Common function for  e-wallet trsnsaction 
 * @param $uid :
 * @param $filter: array of strting and ending count
 * @param $count : number of details in one execution
 * @param $credit status : -1 => Debit and credit (default Value)
 *													0 => Expense/Debited
 *													1 => Income/Credited
 * ------------------------------------------------
*/
function get_all_user_transaction_details ($uid = '7', $filter = array(), $count = false, $credit_status = -1){
		global $wpdb;

	 $query = array();
	 $table = _table_name('afl_user_transactions');
	 
	 if ( !empty($filter['holding_transaction'])) {
	 	$table = _table_name('afl_user_holding_transactions');
	 }

   $query['#select'] = $table;
   $query['#join']  = array(
      _table_name('users') => array(
        '#condition' => '`'._table_name('users').'`.`ID`=`'.$table .'`.`associated_user_id`'
      )
    );
   $query['#where'] = array(
      '`'.$table .'`.`uid`= '.$uid.''
    );
   	
   	if(($credit_status != -1) ){
	   	$query['#where'][] = '`'.$table .'`.`credit_status`= '.$credit_status.'';
   	}

   	$limit = '';
		if (isset($filter['start']) && isset($filter['length'])) {
			$limit .= $filter['start'].','.$filter['length'];
		}
	
		if (!empty($limit)) {
			$query['#limit'] = $limit;
		}
		if (!empty($filter['search_valu'])) {
			$query['#like'] = array('`display_name`' => $filter['search_valu']);

		}
   $query['#order_by'] = array(
      '`transaction_date`' => 'ASC'
    );
	 $result = db_select($query, 'get_results');
	 if ($count)
			return count($result); 
		return $result;
}


// function afl_user_ewallet_income_data_table(){
// 	global $wpdb;
// 	$uid 						 = get_current_user_id();
// 	// $uid = 7;
 
// 	$input_valu = $_POST;
//  	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
//      $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
//   }
//   if(!empty($input_valu['search']['value'])) {
//      $filter['search_valu'] = $input_valu['search']['value'];
//   }
 
//   $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
//   $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

//   $result_count = get_all_user_transaction_details($uid,array(),TRUE,1); 
//   $filter_count = get_all_user_transaction_details($uid,$filter,TRUE,1);
//   $result 			= get_all_user_transaction_details($uid,$filter,FALSE,1);
//   	$output = [
//      "draw" 						=> $input_valu['draw'],
//      "recordsTotal" 		=> $result_count,
//      "recordsFiltered" 	=> $filter_count,
//      "data" 						=> [],
//    ];
//     $result = get_all_user_transaction_details($uid,$filter,FALSE,1);
// 		foreach ($result as $key => $value) { 
// 			if($value->credit_status == 1 ){
// 				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
// 			}
// 			else{
// 				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
// 			}
// 			$output['data'][] = [
// 	   		$key+1,
// 	     	ucfirst(strtolower($value->category)),
// 	     	$value->display_name." (".$value->associated_user_id.")",
// 	 			afl_format_payment_amount($value->amount_paid).$value->currency_code,
// 	     	$status,
// 	     	afl_system_date_format($value->transaction_date)  	
//    		];
// 		}
// 	echo json_encode($output);
// 	die();
// }

// function afl_user_ewallet_expense_report_data_table(){
// 	global $wpdb;
// 	$uid 						 = get_current_user_id();
// 	// $uid = 7;
 
// 	$input_valu = $_POST;
//  	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
//      $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
//   }
//   if(!empty($input_valu['search']['value'])) {
//      $filter['search_valu'] = $input_valu['search']['value'];
//   }
 
//   $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
//   $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

//   $result_count = get_all_user_transaction_details($uid,array(),TRUE,0); 
//   $filter_count = get_all_user_transaction_details($uid,$filter,TRUE,0);
//   $result 			= get_all_user_transaction_details($uid,$filter,FALSE,0);
//   	$output = [
//      "draw" 						=> $input_valu['draw'],
//      "recordsTotal" 		=> $result_count,
//      "recordsFiltered" 	=> $filter_count,
//      "data" 						=> [],
//    ];
//     $result = get_all_user_transaction_details($uid,$filter,FALSE,0);
// 		foreach ($result as $key => $value) { 
// 			if($value->credit_status == 1 ){
// 				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
// 			}
// 			else{
// 				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
// 			}
// 			$output['data'][] = [
// 	   		$key+1,
// 	     	ucfirst(strtolower($value->category)),
// 	     	$value->display_name." (".$value->associated_user_id.")",
// 	 			afl_get_commerce_amount($value->amount_paid)." " .$value->currency_code ,
// 	     	$status,
// 	     	afl_system_date_format($value->transaction_date)  	
//    		];
// 		}
// 	echo json_encode($output);
// 	die();
// }


