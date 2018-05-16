<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! function_exists('apyc_get_token')) {
   function apyc_get_token ( )  {
	try{
		$res = Apyc_Citrix_GoToWebinar_DirectLogin::get_instance()->login();
		Apyc_Model_DBToken::get_instance()->access_token('u', $res);
		return Apyc_Model_DBToken::get_instance()->access_token('r');
	}catch(Exception $e){
		write_log('get access token error : ' . $e->getMessage());
		return false;
	}
   }
}
if ( ! function_exists('apyc_get_upcoming_webinars')) {
   function apyc_get_upcoming_webinars ( )  {
		try{
			$query_args = array(
				'get_webinar' => 'upcoming'
			);
			return Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query($query_args);
		}catch(Exception $e){
			write_log('get access token error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_history_webinars')) {
   function apyc_get_history_webinars ( )  {
		try{
			$query_args = array(
				'get_webinar' => 'history'
			);
			return Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query($query_args);
		}catch(Exception $e){
			write_log('get access token error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_all_webinars')) {
   function apyc_get_all_webinars ( )  {
		try{
			$query_args = array(
				'get_webinar' => 'all'
			);
			return Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query($query_args);
		}catch(Exception $e){
			write_log('get access token error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_all_webinars_cache')) {
   function apyc_get_all_webinars_cache ($rest = false)  {
		try{
			return Apyc_Citrix_GoToWebinar_GetAll::get_instance()->cache($rest);
		}catch(Exception $e){
			write_log('get access token error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_access_token')) {
   function apyc_get_access_token ( )  {
		$get = Apyc_Model_DBToken::get_instance()->access_token('r');
		if( !$get ){
			return apyc_get_token();
		}
		return $get;
   }
}
if ( ! function_exists('apyc_create_registrant')) {
   function apyc_create_registrant($webinarKey, $body)  {
		try{
			$ret = Apyc_Citrix_GoToWebinar_CreateRegistrant::get_instance()->create($webinarKey, $body);
			write_log('create registrant : ' . serialize($ret));
			return $ret;
		}catch(Exception $e){
			write_log('create registrant error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_create_webinar')) {
   function apyc_create_webinar($body)  {
		try{
			$ret = Apyc_Citrix_GoToWebinar_CreateWebinar::get_instance()->create($body);
			write_log('create webinar : ' . serialize($ret));
			return $ret;
		}catch(Exception $e){
			write_log('create webinar error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_webinar_free')) {
   function apyc_get_webinar_free($arg)  {
		try{
			$defaults = array(
				'filter_by_subject' => GOTOWEBINAR_FREE_GROUP
			);
			$query_args = wp_parse_args( $arg, $defaults );
			$get = Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query($query_args);
			return $get;
		}catch(Exception $e){
			write_log('get free webinar error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_webinar_paid')) {
   function apyc_get_webinar_paid($arg)  {
		try{
			$defaults = array(
				'filter_by_subject' => GOTOWEBINAR_PAID_GROUP
			);
			$query_args = wp_parse_args( $arg, $defaults );
			$get = Apyc_Citrix_GoToWebinar_GetAll::get_instance()->query($query_args);
			return $get;
		}catch(Exception $e){
			write_log('get paid webinar error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_get_webinar_key')) {
   function apyc_get_webinar_key($webinarKey)  {
		try{
			$get = Apyc_Citrix_GoToWebinar_GetAll::get_instance()->get($webinarKey);
			return $get;
		}catch(Exception $e){
			write_log('get webinar error : ' . $e->getMessage());
			return false;
		}
   }
}
if ( ! function_exists('apyc_time_interval')) {
	function apyc_time_interval($start = '', $end = ''){
		$data_array = array();
		if( $start == '' ){
			$start = date('g:i A');
		}
		$start = new DateTimeImmutable($start);
		if( $end == '' ){
			$end = date('g:i A');
		}
		$end = new DateTimeImmutable($end);
		$interval = new DateInterval('PT1H'); //1 hour interval
		$range = new DatePeriod($start, $interval, $end);
		
		$am = array();
		$pm = array();
		foreach ($range as $time) {
			if( $time->format('A') == 'AM' ){
				$am[] = $time->format('g:i A');
			}
			if( $time->add($interval)->format('A') == 'PM' ){
				$pm[] = $time->add($interval)->format('g:i A');
			}
			$data_array = array(
				'am' => $am,
				'pm' => $pm
			);
		}
		return $data_array;
	}
}
/*
* this is a utz time conversion
* use for creating webinar in the api
* with time interval
* @param $args	array
*	default values
*		'start_date' => date | default	date(Y-m-d)
*		'end_date'	=> date | default empty
*			- if empty get the start date date('Y-m-d')
*		'time_start' => time with am and pm meridian | default empty
*		'time_end' => time with am and pm meridian	| default empty
*			- if empty get the time_start interval
*		'time_interval' =>	numeric	| default 60 minutes (1 hour)
*		'timezone_from' => string	| default WEBINAR_TIME_ZONE
*		'timezone_to' => string	| default UTC
*		'format_output_date' => string	| default Y-m-d\TH:i:s\Z
* @return array
*/
if( !function_exists('webinar_date_time_conversion') ){
	function webinar_date_time_conversion($args = array()){
		$date_time_webinar = array(
			'start_date_time' => '',
			'end_date_time' => '',
		);
		/**
		 * Define the array of defaults
		 */ 
		$defaults = array(
			'start_date' => date('Y-m-d'),
			'end_date' => date('Y-m-d'),
			'time_start' => date('H:i:s'),
			'time_end' => '',
			'time_interval' => 60,
			'timezone_from' => WEBINAR_TIME_ZONE,
			'timezone_to' => 'UTC',
			'format_output_date' => 'Y-m-d\TH:i:s\Z'
		);
				
		/**
		 * Parse incoming $args into an array and merge it with $defaults
		 */ 
		$args = wp_parse_args( $args, $defaults );
		
		$datetime = $args['start_date'].' '.$args['time_start'];
		$dt = new DateTime($datetime, new DateTimeZone($args['timezone_from']));
		$dt->setTimeZone(new DateTimeZone($args['timezone_to']));
		$args['start_date'] = $dt->format($args['format_output_date']);
		
		//if time_end is empty
		if( $args['time_start'] != '' && $args['time_end'] == '' ){
			$minutes = $args['time_interval'];
			$dt->add(new DateInterval('PT' . $minutes . 'M'));
			$args['end_date'] = $dt->format($args['format_output_date']);
		}
		$date_time_webinar = array(
			'start_date_time' => $args['start_date'],
			'end_date_time' => $args['end_date'],
			'time_zone' => $args['timezone_from'],
		);
		//dd($args);
		$date_time_webinar = json_decode(json_encode($date_time_webinar), FALSE);
		return $date_time_webinar;
	}
}