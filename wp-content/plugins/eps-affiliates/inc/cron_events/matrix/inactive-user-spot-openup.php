<?php 
/*
 * ---------------------------------------------------------------
 * Open up the spot of deatived user in Matrix tree
 * ---------------------------------------------------------------
 * Get the in-active user list
 * check the days between acived_on and detived_on 
 * check the configuration for maximum deatived 
 * if the day exceeed open up the spot
 * ---------------------------------------------------------------
*/
	function _matrix_inactive_user_spot_open_up(){
		//get deatived users
		$query = array();
		$query['#select'] =_table_name('afl_user_genealogy');
		$query['#where'] 	= array(
			'status=0'
		);
		$query['#limit'] 		 = 500;
		$result = db_select($query, 'get_results');
		
		//loop through the users
		foreach ($result as $key => $value) {
			$actived_on 	= $value->actived_on;
			$deactived_on = $value->deactived_on;

			$_open_up_period 			= afl_variable_get('cancelled_genealogy_spot_openup_period','');
			$_open_up_period_val 	= afl_variable_get('cancelled_genealogy_spot_openup_period_value',0);

			if ( !empty($_open_up_period) && !empty($_open_up_period_val)) {
				//get the difference from actived_on and deactived_on based on what period choose
				$_difference = 0;
				switch ($_open_up_period) {
					case 'day':
						$datediff 	 = $deactived_on - $actived_on;
						$_difference = floor($datediff / (60 * 60 * 24));
					break;
					case 'month':
						$datediff 	 = $deactived_on - $actived_on;
						$_difference = floor($datediff / (60 * 60 * 24 * 30));
					break;
					case 'year':
						$datediff 	 = $deactived_on - $actived_on;
						$_difference = floor($datediff / (60 * 60 * 24 * 30 * 12));
					break;
				}
				pr($_difference);
				//check the differece is greater than maximum allowed
				if ( $_difference > $_open_up_period_val ) {
					//free the spot
					pr('yeah');
				}	
			}
		}

		//log cron run
   	if ( afl_variable_get('cron_logs_enable')) {
			afl_log('matrix_inactive_user_spot_open_up_scheduler','cron run completed',array(),LOGS_INFO);
   	}
	}