<?php 

/*
 * ------------------------------------------------
 * Business wallet trsnsation summary
 * ------------------------------------------------
*/
function afl_admin_bwallet_summary_data_table_callback(){
// pr("here");
		global $wpdb;
		$uid 					 = get_current_user_id();
		$result = $wpdb->get_results("SELECT `wp_afl_business_transactions`.`category`, SUM(`wp_afl_business_transactions`.`balance`) as balance,`wp_afl_business_transactions`.`currency_code`  FROM `wp_afl_business_transactions`  GROUP BY `category` DESC");
		$output = [
	  
	    "data" 						=> [],
		];
		// pr($result,1);
		foreach ($result as $key => $value) {
			$output['data'][] = [
	   		$key+1,
	     	ucfirst(strtolower($value->category)),
	     	afl_get_commerce_amount($value->balance),
   		];
		}
	echo json_encode($output);
	die();
}
/*
 * ------------------------------------------------
 * Business transaction all trsnsation 
 * ------------------------------------------------
*/
function afl_admin_business_trans_datatable_callback(){
	global $wpdb;
 	$uid 						 = get_current_user_id(); 
 	$uid = 7;
	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
 
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

  $result_count = get_all_business_transaction_details($uid,array(),TRUE); 
  $filter_count = get_all_business_transaction_details($uid,$filter,TRUE); 

  	$output = [
     "draw" 						=> $input_valu['draw'],
     "recordsTotal" 		=> $result_count,
     "recordsFiltered" 	=> $result_count,
     "data" 						=> [],
   	];
    $result = get_all_business_transaction_details($uid,$filter,FALSE);
		foreach ($result as $key => $value) {  
			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}
			
			$member_node  = afl_user_data($value->uid);
			$mbr_name = !empty($member_node->display_name) ? $member_node->display_name : 'unavaiilable';
			$output['data'][] = [
	   		$key+1,
	     	ucfirst(strtolower($value->category)),
	     	$mbr_name." (".$value->uid.")",
	     	$value->display_name." (".$value->associated_user_id.")",
	 			number_format(afl_get_commerce_amount($value->amount_paid), 2, '.', ',')." " .$value->currency_code ,
	     	$status,
	     	afl_system_date_format($value->transaction_date),  	
	     	$value->additional_notes
   		];
		}
	echo json_encode($output);
	die();
}
/*
* ------------------------------------------------------------
* To Get all business transaction details
* @param $uid
* @param $filter = fixed count atarting and ending value eg. get 10-25 rows
* @param $count= total number of rows
* @param $credit status = default value -1 for credit and debit records
* @return $result = arry of values included to user details
*-------------------------------------------------------------
*/
function get_all_business_transaction_details($uid = '7', $filter = array(), $count = FALSE, $credit_status = -1){
	global $wpdb;

	 $query = array();
   $query['#select'] = 'wp_afl_business_transactions';
   $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`wp_users`.`ID`=`wp_afl_business_transactions`.`associated_user_id`'
      )
    );
   	// $query['#where'] = array(
    //   '`wp_afl_business_transactions`.`uid`= '.$uid.''
    // );
   	if(($credit_status != -1) ){
	   	$query['#where'][] = '`wp_afl_business_transactions`.`credit_status`= '.$credit_status.'';
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
/*
 * ------------------------------------------------
 * Business wallet income report summary
 * ------------------------------------------------
*/
function afl_admin_business_income_datatable_callback(){
	global $wpdb;
 	$uid 						 = get_current_user_id(); 
 	$uid = 7;
	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
 
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

  $result_count = get_all_business_transaction_details($uid,array(),TRUE,1); 
  $filter_count = get_all_business_transaction_details($uid,$filter,TRUE,1); 
 
  	$output = [
     "draw" 						=> $input_valu['draw'],
     "recordsTotal" 		=> $result_count,
     "recordsFiltered" 	=> $filter_count,
     "data" 						=> [],
   	];
    $result = get_all_business_transaction_details($uid,$filter,FALSE,1);
		foreach ($result as $key => $value) {  
			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}
			$output['data'][] = [
	   		$key+1,
	     	ucfirst(strtolower($value->category)),
	     	$value->display_name." (".$value->associated_user_id.")",
	 			number_format(afl_get_commerce_amount($value->amount_paid), 2, '.', ',')." " .$value->currency_code ,
	     	$status,
	     	afl_system_date_format($value->transaction_date),  	
	     	$value->additional_notes
   		];
		}
	echo json_encode($output);
	die();

}
/*
 * ------------------------------------------------
 * Business wallet Expense report summary
 * ------------------------------------------------
*/
function afl_admin_business_expense_datatable_callback(){
	global $wpdb;
 	$uid 						 = get_current_user_id(); 
 	$uid = 7;
	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
 
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

  $result_count = get_all_business_transaction_details($uid,array(),TRUE,0); 
  $filter_count = get_all_business_transaction_details($uid,$filter,TRUE,0); 

  	$output = [
     "draw" 						=> $input_valu['draw'],
     "recordsTotal" 		=> $result_count,
     "recordsFiltered" 	=> $filter_count,
     "data" 						=> [],
   	];
    $result = get_all_business_transaction_details($uid,$filter,FALSE,0	);
		foreach ($result as $key => $value) {  
			if($value->credit_status == 1 ){
				$status =  "<button type='button' class='btn btn-success btn-sm'>Credit</button>";
			}
			else{
				$status =  "<button type='button' class='btn btn-danger'>Debit</button>";
			}
			$output['data'][] = [
	   		$key+1,
	     	ucfirst(strtolower($value->category)),
	     	$value->display_name." (".$value->associated_user_id.")",
	 			number_format(afl_get_commerce_amount($value->amount_paid), 2, '.', ',')." " .$value->currency_code ,
	     	$status,
	     	afl_system_date_format($value->transaction_date),  	
	     	$value->additional_notes
   		];
		}
	echo json_encode($output);
	die();
}