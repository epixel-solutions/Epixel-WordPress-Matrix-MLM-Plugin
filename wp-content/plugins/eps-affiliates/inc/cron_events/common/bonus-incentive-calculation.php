<?php 
/**
 * ---------------------------------------------------------------------
 * @author < pratheesh@epixelsolutions.com >
 *
 * In this, the rak incentive has been calculated based on the 
 * requirement
 *
 * The incentive only give @ once, a time period 90 days
 * ---------------------------------------------------------------------
*/

/**
 * ---------------------------------------------------------------------
 * get the genealogy details of users.
 * Get the actived on date
 * get the difference between these two
 *
 * if the day difference is greater than or equals the holding days,
 * save the details to the incentives table 
 * ---------------------------------------------------------------------
*/
	function _member_bonus_incentive_calculation () {
		global $wpdb;

		$current_date 		= afl_date();
		$afl_date_splits  = afl_date_splits($current_date);

		$time_period 	= afl_variable_get('rank_holding_consecutive_days',90);
		$date_behind_period_days = strtotime('-'.$time_period.' days',$current_date);

		//get users from the matrix genealogy
		$query = array();
		$query['#select'] = _table_name('afl_user_genealogy');
		$query['#where']	=	array(
			'status = 1',
			'deleted = 0',
			'created <= '.$date_behind_period_days
		);
		$result = db_select($query, 'get_results');

		foreach ($result as $key => $value) {
			//check already paid the incentive
			if (!_check_incentive_already_given( $value->uid )) {
				$member_rank = $value->member_rank;
				$incentives  = '';
				if ( $member_rank ) {
					for ( $i = 1; $i <= $member_rank; $i++ ) {
						$incen_arr = list_extract_allowed_values(afl_variable_get('rank_'.$i.'_incentives',''),'list_text');
						if ( !empty($incen_arr)) {
							foreach ($incen_arr  as $key => $inc_name) {
								$incentives .= '* '.$inc_name.'<br>';
							}
						}
					}

				  $incentive_history = array();
				  $incentive_history['uid'] 				= $value->uid;
				  $incentive_history['created_on'] 	= afl_date();
				  $incentive_history['member_rank'] = $member_rank;
				  $incentive_history['incentives'] 	= $incentives;
				  $incentive_history['incentive_day'] 	= $afl_date_splits['d'];
				  $incentive_history['incentive_month'] = $afl_date_splits['m'];
				  $incentive_history['incentive_year'] 	= $afl_date_splits['y'];
				  $incentive_history['incentive_week'] 	= $afl_date_splits['w'];
				  $incentive_history['incentive_date'] 	= afl_date_combined($afl_date_splits);

			 		$incentive_id = $wpdb->insert(_table_name('afl_bonus_incentive_history'), $incentive_history);
				}
			}
		}

		//log cron run
   	if ( afl_variable_get('cron_logs_enable')) {
			afl_log('member_bonus_incentive_scheduler','cron run completed',array(),LOGS_INFO);
   	}
	}

/**
 * ---------------------------------------------------------------------
 * Check the incentives already given or paid
 * ---------------------------------------------------------------------
*/
	function _check_incentive_already_given ($uid = '') {
		$query = array();
		$query['#select'] = _table_name('afl_bonus_incentive_history');
		$query['#where']  = array(
			'uid='.$uid
		);
		$result = db_select($query, 'get_row');
		if ( !empty($result)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}