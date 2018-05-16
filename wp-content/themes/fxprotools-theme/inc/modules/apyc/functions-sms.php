<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
* Will get user group with mobile only
* @see Apyc_User
* @param $group	array | string
* @return Apyc_User | Array
* 	sample return
*	array(
*		id => User ID,
*		name => name,
*		mobile => mobile_number,
*	)
**/
if ( ! function_exists('apyc_get_usergroup_withmobile')) {
   function apyc_get_usergroup_withmobile($group = array())  {
		if( !empty($group) ){
			$user = new Apyc_User;
			return $user->getGroupWithMobileNumber($group);
		}
		return false;
   }
}
/**
* Send SMS
* @param	$arg	array	
* 					default | sample array
*					array(
*						'sending_to' 	=> array of User Roles | Default to array('Customer', 'Distributor'),
*						'msg'			=> String | message for SMS,
*						'from_number'	=> Optional	| if empty it will auto get the from number to API,
*						'send_to'		=> default to  apyc_get_usergroup_withmobile, follow the format
*											on function apyc_get_usergroup_withmobile return
*					)
* @return Apyc_SendSMS
**/
if ( ! function_exists('apyc_send_sms')) {
   function apyc_send_sms($arg = array())  {
		$sending_to = array('Customer', 'Distributor');
		if( isset($arg['sending_to']) 
			&& !empty($arg['sending_to'])
		){
			$sending_to = $arg['sending_to'];
		}
		
		$msg = '';
		if( isset($arg['msg']) 
			&& trim($arg['msg']) != ''
		){
			$msg = $arg['msg'];
		}
		
		$from_number = '';
		if( isset($arg['from_number'])
			&& trim($arg['from_number']) != '' 
		){
			$from_number = $arg['from_number'];
		}
		
		$send_to = apyc_get_usergroup_withmobile(array('sending_to'=>$sending_to));
		if( isset($arg['send_to'])
			&& !empty($arg['send_to'])
		){
			$send_to = $arg['send_to'];
		}
		$arg = array(
			'msg' => $msg,
			'from_number' => $from_number,
			'send_to' => $send_to
		);
		//dd($arg);
		//ready to send sms
		//$send_sms = new Apyc_SendSMS;
		//return $send_sms->send($user_query)
   }
}