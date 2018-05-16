<?php
/**
 * -----------------------------------------------------------------------
 *  Join customer
 * -----------------------------------------------------------------------
*/
	class Eps_affiliates_customer_registration {
		public function afl_join_customer ($POST = array()) {
			//insert the details to table
			$afl_date_splits = afl_date_splits(afl_date());
			$customer_det = array();
			$customer_det['uid'] 					= $POST['uid'];
			$customer_det['referrer_uid'] = $POST['sponsor_uid'];
			$customer_det['parent_uid'] 	= $POST['sponsor_uid'];
			$customer_det['created'] 			= afl_date();
			$customer_det['joined_day'] 	= $afl_date_splits['d'];
			$customer_det['joined_month'] = $afl_date_splits['m'];
			$customer_det['joined_year'] 	= $afl_date_splits['y'];
			$customer_det['joined_week'] 	= $afl_date_splits['w'];
			$customer_det['joined_date'] 	= afl_date_combined($afl_date_splits);

			global $wpdb;
			$ins = $wpdb->insert(
				_table_name('afl_customer'),
				$customer_det
			);
			if ($ins) {
				return true;
			} else {
				return false;
			}
		}
	}
