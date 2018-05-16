<?php

/*
 * --------------------------------------------------------------------
 * Migration database version 1.0
 * --------------------------------------------------------------------
*/
 class Migration_database_version_1_0 {
 		private $tbl_prefix 			= '';
		private $charset_collate 	= '';

		public function __construct (){
			global $wpdb;
			$this->tbl_prefix 		 = $wpdb->prefix;
			$this->charset_collate = $wpdb->get_charset_collate();
		}
 		//update the database
	 	public function migration_upgrade() {
	 		$this->afl_variables();
	 		$this->afl_user_downlines();
	 		$this->afl_referal_downlines();
	 		$this->afl_user_genealogy();
	 		$this->afl_user_holding_tank();
	 		$this->afl_user_transactions();
	 		$this->afl_user_holding_transactions();
	 		$this->afl_business_transactions();
	 		$this->afl_business_funds();
	 		$this->afl_user_transactions_overview();


	 		$this->afl_business_transactions_overview();
	 		$this->afl_user_fund();
	 		$this->afl_transactions();
	 		$this->afl_transaction_errors();
	 		$this->afl_payout_requests();
	 		$this->afl_payout_history();
	 		$this->afl_user_payment_methods();
	 		$this->afl_transaction_authorization();


	 		$this->afl_purchases();
	 		$this->afl_ranks();
	 		$this->afl_ranks_history();
	 		
	 		$this->afl_tree_last_insertion_positions();
	 		$this->afl_global_pool_bonus_transactions();

	 		$this->afl_customer();
	 		
	 		$this->afl_processing_queue();
	 		
	 		$this->afl_log_messages();
	 		
	 		$this->afl_bonus_incentive_history();
	 		
	 		//tables for procedures
	 		// $this->tmp_table();
	 		// $this->tmp_table_down();

 		/*
 		 * --------------------------------------------------
 		 * Unilevel network tables
 		 * --------------------------------------------------
 		*/
	 		$this->afl_unilevel_user_downlines();
	 		$this->afl_unilevel_user_genealogy();
	 		$this->afl_unilevel_user_holding_tank();
	 		$this->afl_unilevel_tree_last_insertion_positions();
			
			#data export dable
	 		$this->afl_user_exort_data();

	 		//nested set
	 		$this->afl_nested_set_referal();
	 		$this->afl_nested_set_downline();

	 		//nested set
	 		$this->afl_unilevel_nested_set_referal();
	 		$this->afl_unilevel_nested_set_downline();

	 	}
	 	//downgrade the database version
	 	public function migration_downgrade() {
	 		// echo 'down';
	 	}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * Variables Table
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_variables(){
			$table_name = $this->tbl_prefix . 'afl_variable';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `name` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'The name of the variable.',
					  `merchant_id` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) NOT NULL DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'Project name',
					  `value` longblob NOT NULL COMMENT 'The value of the variable.'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Variable table';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User downlines
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_downlines (){
			$table_name = $this->tbl_prefix . 'afl_user_downlines';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_downline_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Registered user',
					  `downline_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Down-line user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `position` int(10) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` int(10) DEFAULT NULL COMMENT 'Genealogy position',
					  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_month` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_year` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_week` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date in format',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `ini_payment` int(10) unsigned DEFAULT '0' COMMENT 'Initial Payments',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Stores the user down-line information';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;

			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_downline_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_downline_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * user Genealogy
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_genealogy(){
			$table_name = $this->tbl_prefix . 'afl_user_genealogy';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_genealogy_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `left_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Child',
					  `middle_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Middle Child',
					  `right_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Child',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy relative position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `extra_info` varchar(300) DEFAULT NULL COMMENT 'Extra Info',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `actived_on` varchar(250) DEFAULT 'default' COMMENT 'Actived on',
					  `deactived_on` varchar(250) DEFAULT 'default' COMMENT 'Deactived on',
					  `remote_user_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote user mlm ID',
					  `remote_sponsor_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote sponsor mlm ID'
					) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Stores the user genealogy information';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  						ADD PRIMARY KEY (`afl_user_genealogy_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_genealogy_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User Transactions
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_transactions(){
			$table_name = $this->tbl_prefix . 'afl_user_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_transactions_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_return` int(11) unsigned DEFAULT '0' COMMENT 'Return Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID',
					  `withdrawal_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Withdrawal Date'
					) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_transactions_id`);');

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_transactions_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}

	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User holding Transactions
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_holding_transactions(){
			$table_name = $this->tbl_prefix . 'afl_user_holding_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_transactions_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_return` int(11) unsigned DEFAULT '0' COMMENT 'Return Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID',
					  `withdrawal_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Withdrawal Date',
					  `paid_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Paid Status'
					) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;

			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_transactions_id`);');

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_transactions_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * Afl business transactions
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_business_transactions () {
			$table_name = $this->tbl_prefix . 'afl_business_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_business_transactions_id` int(11) NOT NULL,
					  `associated_user_id` int(10) unsigned DEFAULT '0' COMMENT 'Associated user id',
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Member',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='Stores the business transactions';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_business_transactions_id`);' );

				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_business_transactions_id` int(11) NOT NULL AUTO_INCREMENT' );
			endif;

		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * Business Funds
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_business_funds () {
			$table_name = $this->tbl_prefix . 'afl_business_funds';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_business_fund_id` int(11) NOT NULL,
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` bigint(20) NOT NULL DEFAULT '0',
					  `modified` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'modified',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions overview';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_business_fund_id`);' );
				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_business_fund_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User transactions Overview
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_transactions_overview () {
			$table_name = $this->tbl_prefix . 'afl_user_transactions_overview';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_transactions_overview_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions overview';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
			//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_transactions_overview_id`);' );
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_transactions_overview_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * user Holding tank
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_holding_tank(){
			$table_name = $this->tbl_prefix . 'afl_user_holding_tank';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_holding_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy relative position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `day_remains` int(10) unsigned DEFAULT '0' COMMENT 'Day remains',
					  `last_updated` int(10) unsigned DEFAULT '0' COMMENT 'Last updated date',
					  `remote_user_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote user mlm ID',
					  `remote_sponsor_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote sponsor mlm ID'
					) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Stores the user genealogy information';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  						ADD PRIMARY KEY (`afl_user_holding_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_holding_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
 /*
	 * -----------------------------------------------------------------------------------------------------------
	 * User transactions Overview
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_business_transactions_overview () {
			$table_name = $this->tbl_prefix . 'afl_business_transactions_overview';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_business_transactions_overview_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the business transactions overview';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_business_transactions_overview_id`);' );
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_business_transactions_overview_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}

	/*
	* -----------------------------------------------------------------------------------------------------------
	* User Fund
	* -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_fund () {
			$table_name = $this->tbl_prefix . 'afl_user_fund';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_fund_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `modified` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Modified Date',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the user funds';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_fund_id`);' );
				//AUTO inc
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_fund_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User Purchases
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_purchases() {
			$table_name = $this->tbl_prefix . 'afl_purchases';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_purchases_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Main user',
					  `category` varchar(250) DEFAULT NULL COMMENT 'Purchase Category',
					  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `afl_points` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
					  `processed_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
					  `purchase_day` int(10) unsigned NOT NULL DEFAULT '0',
					  `purchase_month` int(10) unsigned NOT NULL DEFAULT '0',
					  `purchase_year` int(10) unsigned NOT NULL DEFAULT '0',
					  `purchase_week` int(10) unsigned NOT NULL DEFAULT '0',
					  `purchase_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date in format',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `ini_payment` int(10) unsigned DEFAULT '0' COMMENT 'Initial Payments',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `cron_status` int(10) unsigned NOT NULL DEFAULT '0'
					) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Stores the purchase details';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
					ADD PRIMARY KEY (`afl_purchases_id`);' );

				//AUTO inc
					$wpdb->query( 'ALTER TABLE `'.$table_name.'`
		  							MODIFY `afl_purchases_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User transaction Errors
	 * Transaction errors
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_transaction_errors(){
			$table_name = $this->tbl_prefix . 'afl_transactions_errors';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
		  `afl_transactions_error_id` int(11) NOT NULL,
		  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
		  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
		  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
		  `amount_paid` bigint(20) NOT NULL DEFAULT '0',
		  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
		  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
		  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
		  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
		  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
		  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
		  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
		  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
		  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
		  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
		  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
		  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
		  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
		  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
		  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
		  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
		  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID',
		  `payment_method` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'Payment method',
		  `payment_key` varchar(250) NOT NULL DEFAULT '0' COMMENT 'Payment key',
		  `init_refund` int(10) unsigned DEFAULT '0' COMMENT 'refund initailize status'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the user logs';" ;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_transactions_error_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_transactions_error_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * transaction details
	 *
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_transactions(){
			$table_name = $this->tbl_prefix . 'afl_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `afl_transactions_id` int(11) NOT NULL,
			  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
			  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
			  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
			  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
			  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
			  `next_calculation_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Next Calculation date',
			  `last_processed_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Next Calculation date',
			  `calculation_expiry_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Calculation expiry date',
			  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
			  `amount_paid` bigint(20) NOT NULL DEFAULT '0',
			  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
			  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
			  `downline_member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Downline member rank',
			  `downline_member_parent_rank` int(10) unsigned DEFAULT '0' COMMENT 'Downline member parent rank',
			  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
			  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
			  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
			  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
			  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
			  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
			  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
			  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
			  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
			  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
			  `deleted` tinyint(3) unsigned DEFAULT '0' COMMENT 'Deleted Status',
			  `ini_payment` tinyint(3) unsigned DEFAULT '0' COMMENT 'Initial Payments',
			  `effect_business_wallet` tinyint(3) unsigned DEFAULT '0' COMMENT 'Effect on business wallet',
			  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
			  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
			  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
			  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
			  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the all transactions';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_transactions_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_transactions_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}

	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * Payout Requests
   *
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_payout_requests(){
			$table_name = $this->tbl_prefix . 'afl_payout_requests';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `afl_payout_id` int(11) NOT NULL,
			  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
			  `paid_by` int(10) unsigned DEFAULT '0' COMMENT 'Paid by',
			  `initiated_by` int(10) unsigned DEFAULT '0' COMMENT 'Initiated By',
			  `payout_method` varchar(300) DEFAULT NULL COMMENT 'payout Method',
			  `request_status` int(11) DEFAULT '0' COMMENT 'Request Status',
			  `paid_status` int(11) DEFAULT '0' COMMENT 'Paid Status',
			  `payout_type` varchar(100) DEFAULT NULL COMMENT 'Payout type',
			  `processed_method` varchar(300) DEFAULT NULL COMMENT 'Processed method',
			  `amount_requested` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `charges` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
			  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `category` varchar(100) DEFAULT NULL COMMENT 'Payment category',
			  `notes` varchar(300) DEFAULT NULL COMMENT 'Payment Source',
			  `created` int(10) unsigned DEFAULT '0' COMMENT 'Created',
			  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified date',
			  `paid_date` int(10) unsigned DEFAULT '0' COMMENT 'Modified date',
			  `deleted` int(10) unsigned DEFAULT NULL COMMENT 'Deleted',
			  `payment_date` int(10) unsigned DEFAULT NULL COMMENT 'wdate',
			  `payment_month` int(10) unsigned DEFAULT NULL COMMENT 'wmonth',
			  `payment_year` int(10) unsigned DEFAULT NULL COMMENT 'wyear',
			  `payment_week` int(10) unsigned DEFAULT NULL COMMENT 'wweek',
			  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
			  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
			  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Stores the payout Requests';";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				global $wpdb;
				$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			  if ( empty($res)) :
					//indexes
					$wpdb->query( 'ALTER TABLE `'.$table_name.'`
		  							ADD PRIMARY KEY (`afl_payout_id`);' );
					//AUTO increment
					$wpdb->query( 'ALTER TABLE `'.$table_name.'`
		  							MODIFY `afl_payout_id` int(11) NOT NULL AUTO_INCREMENT;' );
				endif;
		}

	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * Payout History
	 * -----------------------------------------------------------------------------------------------------------
	*/

	 	private function afl_payout_history(){
	 		$table_name = $this->tbl_prefix . 'afl_payout_history';
	 		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `afl_payout_hist_id` int(11) NOT NULL,
			  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
			  `afl_payout_id` int(10) unsigned NOT NULL COMMENT 'afl_payout_id',
			  `paid_by` int(10) unsigned DEFAULT '0' COMMENT 'Paid by',
			  `initiated_by` int(10) unsigned DEFAULT '0' COMMENT 'Initiated By',
			  `payout_method` varchar(300) DEFAULT NULL COMMENT 'payout Method',
			  `request_status` int(11) DEFAULT '0' COMMENT 'Request Status',
			  `paid_status` int(11) DEFAULT '0' COMMENT 'Paid Status',
			  `payout_type` varchar(100) DEFAULT NULL COMMENT 'Payout type',
			  `processed_method` varchar(300) DEFAULT NULL COMMENT 'Processed method',
			  `amount_requested` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `charges` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
			  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
			  `category` varchar(100) DEFAULT NULL COMMENT 'Payment category',
			  `notes` varchar(300) DEFAULT NULL COMMENT 'Payment Source',
			  `created` int(10) unsigned DEFAULT '0' COMMENT 'Created',
			  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified date',
			  `paid_date` int(10) unsigned DEFAULT '0' COMMENT 'Modified date',
			  `deleted` int(10) unsigned DEFAULT NULL COMMENT 'Deleted',
			  `payment_date` int(10) unsigned DEFAULT NULL COMMENT 'wdate',
			  `payment_month` int(10) unsigned DEFAULT NULL COMMENT 'wmonth',
			  `payment_year` int(10) unsigned DEFAULT NULL COMMENT 'wyear',
			  `payment_week` int(10) unsigned DEFAULT NULL COMMENT 'wweek',
			  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
			  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
			  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Stores the payout History'; ";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_payout_hist_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_payout_hist_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

	 	}

 	/*
	 * -----------------------------------------------------------------------------------------------------------
	 *User payment methods
   * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_payment_methods	(){
			$table_name = $this->tbl_prefix . 'afl_user_payment_methods';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `afl_user_payment_method_id` int(11) NOT NULL,
			  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
			  `method` varchar(300) DEFAULT NULL COMMENT 'Payment Source',
			  `completed` int(10) unsigned DEFAULT '0' COMMENT 'completed',
			  `primary_method` int(10) unsigned DEFAULT '0' COMMENT 'Primary Method',
			  `status` int(10) unsigned DEFAULT '0' COMMENT 'Status',
			  `data` mediumtext COMMENT 'data'
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Payment methods';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_payment_method_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_payment_method_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

		}
  /*
	 * -----------------------------------------------------------------------------------------------------------
	 * transaction authorizations
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_transaction_authorization(){
			$table_name = $this->tbl_prefix . 'afl_transaction_authorization';

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			  `transaction_user_id` int(11) NOT NULL COMMENT 'The primary identifier for the transaction authorization.',
			  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The current user id.',
			  `password` varchar(255) NOT NULL COMMENT 'transaction password',
			  `recovery_pin` int(11) DEFAULT NULL COMMENT 'transaction password recovery pin'
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='The base table for ewallet transaction authorization.';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`transaction_user_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `transaction_user_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

		}

	/*
	 * -----------------------------------------------------------------------------------------------------------
	 *  Afl ranks
	 * -----------------------------------------------------------------------------------------------------------
	*/
    private function afl_ranks () {
			$table_name = $this->tbl_prefix . 'afl_ranks';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
							`afl_rank_id` int(11) NOT NULL,
							`uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Main user',
							`member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
							`updated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
							`deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
							`merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
							`extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
							`project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
							`rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
							`rank_day` int(10) unsigned NOT NULL DEFAULT '0',
							`rank_month` int(10) unsigned NOT NULL DEFAULT '0',
							`rank_year` int(10) unsigned NOT NULL DEFAULT '0',
							`rank_week` int(10) unsigned NOT NULL DEFAULT '0',
							`rank_date` varchar(100) DEFAULT NULL COMMENT 'Rank history date Date',
							`expired_date` int(11) DEFAULT '0' COMMENT 'Rank Expiry date',
							`next_expiry` int(11) DEFAULT '0' COMMENT 'Next Rank Expiry Date'
							) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Rank';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
			  							ADD PRIMARY KEY (`afl_rank_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
			  							MODIFY `afl_rank_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

    }
  /*
	 * -----------------------------------------------------------------------------------------------------------
	 *  Afl ranks history
	 * -----------------------------------------------------------------------------------------------------------
	*/
    private function afl_ranks_history () {
			$table_name = $this->tbl_prefix . 'afl_rank_history';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `afl_rank_history_id` int(11) NOT NULL,
						  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Main user',
						  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
						  `updated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
						  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
						  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
						  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
						  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
						  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
						  `rank_day` int(10) unsigned NOT NULL DEFAULT '0',
						  `rank_month` int(10) unsigned NOT NULL DEFAULT '0',
						  `rank_year` int(10) unsigned NOT NULL DEFAULT '0',
						  `rank_week` int(10) unsigned NOT NULL DEFAULT '0',
						  `rank_date` varchar(100) DEFAULT NULL COMMENT 'Rank history date Date'
						) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Rank History';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
			  							ADD PRIMARY KEY (`afl_rank_history_id`);' );
				//AUTO increment
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
			  							MODIFY `afl_rank_history_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

    }

/*
 * -----------------------------------------------------------------------------------------------------------
 *  Referal downlines
 * -----------------------------------------------------------------------------------------------------------
*/
  private function afl_referal_downlines () {
    $table_name = $this->tbl_prefix . 'afl_referal_downlines';
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `afl_referal_downline_id` int(11) NOT NULL,
            `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Registered user',
            `downline_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Down-line user',
            `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
            `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
            `status` tinyint(4) NOT NULL COMMENT 'Status',
            `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
            `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
            `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
            `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
            `sponsor_rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Sponsor Rejoined Phase',
            `parent_rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Parent Rejoined Phase',
            `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
            `order_id` int(10) unsigned NOT NULL DEFAULT '0',
            `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
            `joined_day` int(10) unsigned NOT NULL DEFAULT '0',
            `joined_month` int(10) unsigned NOT NULL DEFAULT '0',
            `joined_year` int(10) unsigned NOT NULL DEFAULT '0',
            `joined_week` int(10) unsigned NOT NULL DEFAULT '0',
            `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date in format',
            `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
            `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
            `ini_payment` int(10) unsigned DEFAULT '0' COMMENT 'Initial Payments',
            `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
            `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
            `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
          ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Stores the user down-line information';
          ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    global $wpdb;
    $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
		if ( empty($res)) :
	    //indexes
	    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                  ADD PRIMARY KEY (`afl_referal_downline_id`);' );
	    //AUTO increment
	    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                  MODIFY `afl_referal_downline_id` int(11) NOT NULL AUTO_INCREMENT;' );
	  endif;
  }
/*
 * -----------------------------------------------------------------------------------------------------------
 *  user last tree insertion position details
 * -----------------------------------------------------------------------------------------------------------
*/
 private function afl_tree_last_insertion_positions () {
 	$table_name = $this->tbl_prefix . 'afl_tree_last_insertion_positions';
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `ins_id` int(11) NOT NULL,
					  `uid` int(11) NOT NULL,
					  `level` int(11) NOT NULL,
					  `position` varchar(250) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    global $wpdb;
    $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
		if ( empty($res)) :
	  	//indexes
	    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                  ADD PRIMARY KEY (`ins_id`);' );
	    //AUTO increment
	    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                  MODIFY `ins_id` int(11) NOT NULL AUTO_INCREMENT;' );
	  endif;
 }
/*
 * -----------------------------------------------------------------------------------------------------------
 * Global pool bonus Transactions
 * -----------------------------------------------------------------------------------------------------------
*/
		private function afl_global_pool_bonus_transactions(){
			$table_name = $this->tbl_prefix . 'afl_global_pool_bonus_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_pool_transactions_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_return` int(11) unsigned DEFAULT '0' COMMENT 'Return Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID'
					) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;

			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_pool_transactions_id`);');

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_pool_transactions_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
/*
 * -----------------------------------------------------------------------------------------------------------
 *  customers Table
 * -----------------------------------------------------------------------------------------------------------
*/
 	private function afl_customer(){
			$table_name = $this->tbl_prefix . 'afl_customer';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_customer_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `left_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Child',
					  `right_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Child',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `expiry_date_2` int(10) unsigned NOT NULL DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `extra_info` varchar(300) DEFAULT NULL COMMENT 'Extra Info',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the cusomer details';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_customer_id`);');

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_customer_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
/*
 * -----------------------------------------------------------------------------------------------------------
 *  Processing Queue
 * -----------------------------------------------------------------------------------------------------------
*/
 		private function afl_processing_queue(){
			$table_name = $this->tbl_prefix . 'afl_processing_queue';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `item_id` int(10) unsigned NOT NULL COMMENT 'Primary Key: Unique item ID.',
						  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The queue name.',
						  `uid` int(10) unsigned NOT NULL COMMENT 'The user to which the item belongs.',
						  `title` varchar(400) NOT NULL COMMENT 'The title of this item.',
						  `data` longblob COMMENT 'The arbitrary data for the item.',
						  `result` longblob COMMENT 'The arbitrary result for the item, only significant if advancedqueue.status <> 0',
						  `expire` int(11) NOT NULL DEFAULT '0' COMMENT 'Timestamp when the claim lease expires on the item.',
						  `status` tinyint(4) NOT NULL DEFAULT '-1' COMMENT 'Indicates whether the item has been processed (-1 = queue, 0 = processing, 1 = successfully processed, 2 = re-process , 3 = Failed).',
						  `created` int(11) NOT NULL DEFAULT '0' COMMENT 'Timestamp when the item was created.',
						  `processed` int(11) NOT NULL DEFAULT '0' COMMENT 'Timestamp when the item was processed.',
						  `runs` int(11) NOT NULL DEFAULT '0' COMMENT 'How many times the queue processed.'
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores items in queues.';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;

			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`item_id`);');

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
/*
 * -----------------------------------------------------------------------------------------------------------
 *  Processing Queue
 * -----------------------------------------------------------------------------------------------------------
*/
 		private function afl_log_messages(){
			$table_name = $this->tbl_prefix . 'afl_log_messages';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `wid` int(11) NOT NULL COMMENT 'Primary Key: Unique log event ID.',
						  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'The users.uid of the user who triggered the event.',
						  `type` varchar(64) NOT NULL DEFAULT '' COMMENT 'Type of log message, for example `user` or `page not found.`',
						  `message` longtext NOT NULL COMMENT 'Text of log message to be passed into the t() function.',
						  `variables` longblob NOT NULL COMMENT 'Serialized array of variables that match the message string and that is passed into the t() function.',
						  `severity` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The severity level of the event ranges from 0 (Emergency) to 7 (Debug)',
						  `link` varchar(255) DEFAULT '' COMMENT 'Link to view the result of the event.',
						  `location` text NOT NULL COMMENT 'URL of the origin of the event.',
						  `referer` text COMMENT 'URL of referring page.',
						  `hostname` varchar(128) NOT NULL DEFAULT '' COMMENT 'Hostname of the user who triggered the event.',
						  `timestamp` int(11) NOT NULL DEFAULT '0' COMMENT 'Unix timestamp of when event occurred.'
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table that contains logs of all system events.';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
							  ADD PRIMARY KEY (`wid`);');

				//AUTO_INCREMENT
				$wpdb->query( "ALTER TABLE `".$table_name."`
	  				MODIFY `wid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique watchdog event ID.';" );
			endif;
		}	

/*
 * -----------------------------------------------------------------------------------------------------------
 *  tmp table for Procedures
 * -----------------------------------------------------------------------------------------------------------
*/ 
	private function tmp_table() {
			$table_name = $this->tbl_prefix . 'tmp_table';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `id` int(11) NOT NULL COMMENT 'The primary identifier for the temp table.',
						  `tid` text COMMENT 'Temp Id',
						  `val` text COMMENT 'Temp Value',
						  `level` int(10) unsigned DEFAULT '0' COMMENT 'Member Level',
						  `board` int(10) unsigned DEFAULT '0' COMMENT 'Member Board'
						) ENGINE=InnoDB AUTO_INCREMENT=761830 DEFAULT CHARSET=utf8 COMMENT='Temp Table.';";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
							  ADD PRIMARY KEY (`id`);');

				//AUTO_INCREMENT
				$wpdb->query( "ALTER TABLE `".$table_name."`
	  				MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for the temp table.';" );
			endif;
	}
/*
 * -----------------------------------------------------------------------------------------------------------
 *  tmp table for Procedures
 * -----------------------------------------------------------------------------------------------------------
*/ 
	private function tmp_table_down() {
			$table_name = $this->tbl_prefix . 'tmp_table_down';
			$sql = "CREATE TABLE IF NOT EXISTS `tmp_table_down` (
						  `id` int(11) NOT NULL COMMENT 'The primary identifier for the temp table.',
						  `tid` text COMMENT 'Temp Id',
						  `val` text COMMENT 'Temp Value',
						  `level` int(10) unsigned DEFAULT '0' COMMENT 'Member Level',
						  `board` int(10) unsigned DEFAULT '0' COMMENT 'Member Board',
						  `testField` text COMMENT 'Test Field'
						) ENGINE=InnoDB AUTO_INCREMENT=174190 DEFAULT CHARSET=utf8 COMMENT='Temp Downline Table.';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query('ALTER TABLE `'.$table_name.'`
							  ADD PRIMARY KEY (`id`);');

				//AUTO_INCREMENT
				$wpdb->query( "ALTER TABLE `".$table_name."`
	  				MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for the temp table.';" );
			endif;
	}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * User downlines
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_unilevel_user_downlines (){
			$table_name = $this->tbl_prefix . 'afl_unilevel_user_downlines';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_downline_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Registered user',
					  `downline_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Down-line user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `position` int(10) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` int(10) DEFAULT NULL COMMENT 'Genealogy position',
					  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_month` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_year` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_week` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date in format',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `ini_payment` int(10) unsigned DEFAULT '0' COMMENT 'Initial Payments',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Stores the user down-line information';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							ADD PRIMARY KEY (`afl_user_downline_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_downline_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;

		}
		/*
	 * -----------------------------------------------------------------------------------------------------------
	 * unilevel user Genealogy
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_unilevel_user_genealogy(){
			$table_name = $this->tbl_prefix . 'afl_unilevel_user_genealogy';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_genealogy_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `left_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Child',
					  `middle_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Middle Child',
					  `right_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Child',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy relative position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `extra_info` varchar(300) DEFAULT NULL COMMENT 'Extra Info',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `actived_on` varchar(250) DEFAULT 'default' COMMENT 'Actived on',
					  `deactived_on` varchar(250) DEFAULT 'default' COMMENT 'Deactived on',
					  `remote_user_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote user mlm ID',
					  `remote_sponsor_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote sponsor mlm ID'
					) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Stores the user genealogy information';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  						ADD PRIMARY KEY (`afl_user_genealogy_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_genealogy_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
	/*
	 * -----------------------------------------------------------------------------------------------------------
	 * unilevel user Holding tank
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_unilevel_user_holding_tank(){
			$table_name = $this->tbl_prefix . 'afl_unilevel_user_holding_tank';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_holding_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy relative position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `day_remains` int(10) unsigned DEFAULT '0' COMMENT 'Day remains',
					  `last_updated` int(10) unsigned DEFAULT '0' COMMENT 'Last updated date',
					  `remote_user_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote user mlm ID',
					  `remote_sponsor_mlmid` int(10) unsigned DEFAULT '0' COMMENT 'Remote sponsor mlm ID'
					) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Stores the user genealogy information';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;
			$res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
				//indexes
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  						ADD PRIMARY KEY (`afl_user_holding_id`);' );

				//AUTO_INCREMENT
				$wpdb->query( 'ALTER TABLE `'.$table_name.'`
	  							MODIFY `afl_user_holding_id` int(11) NOT NULL AUTO_INCREMENT;' );
			endif;
		}
/*
 * -----------------------------------------------------------------------------------------------------------
 *  user last tree insertion position details
 * -----------------------------------------------------------------------------------------------------------
*/
	 private function afl_unilevel_tree_last_insertion_positions () {
	 	$table_name = $this->tbl_prefix . 'afl_unilevel_tree_last_insertion_positions';
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `ins_id` int(11) NOT NULL,
						  `uid` int(11) NOT NULL,
						  `level` int(11) NOT NULL,
						  `position` varchar(250) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );
	    global $wpdb;
	    $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
		  	//indexes
		    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                  ADD PRIMARY KEY (`ins_id`);' );
		    //AUTO increment
		    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                  MODIFY `ins_id` int(11) NOT NULL AUTO_INCREMENT;' );
		  endif;
	 }

 /*
  * ----------------------------------------------------------------------------------------------------------
  * Bonus Incentives  history
  * ----------------------------------------------------------------------------------------------------------
 */
	 private function afl_bonus_incentive_history () {
	 		$table_name = $this->tbl_prefix . 'afl_bonus_incentive_history';
	    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
						  `afl_incentive_history_id` int(11) NOT NULL,
						  `uid` int(11) NOT NULL,
						  `created_on` int(11) NOT NULL,
						  `member_rank` int(11) NOT NULL,
						  `incentives` varchar(250) NOT NULL,
						  `incentive_day` int(11) NOT NULL DEFAULT '0',
						  `incentive_month` int(11) NOT NULL DEFAULT '0',
						  `incentive_year` int(11) NOT NULL DEFAULT '0',
						  `incentive_week` int(11) NOT NULL DEFAULT '0',
						  `incentive_date` varchar(250) NOT NULL DEFAULT '0',
						  `paid` int(11) NOT NULL DEFAULT '0'
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );
	    global $wpdb;
	    $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
		  	//indexes
		    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                  ADD PRIMARY KEY (`afl_incentive_history_id`);' );
		    //AUTO increment
		    $wpdb->query( 'ALTER TABLE `'.$table_name.'`
			                  MODIFY `afl_incentive_history_id` int(11) NOT NULL AUTO_INCREMENT;' );
		  endif;
	 	}

 /*
  * ----------------------------------------------------------------------------------------------------------
  * Data Export details
  * ----------------------------------------------------------------------------------------------------------
 */
	 private function afl_user_exort_data () {
	 		$table_name = $this->tbl_prefix .'afl_user_exort_data';
      $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
              `id` int(11) NULL,
              `userDbId` int(11) NULL,
              `userMlmId` int(11) NULL,
              `name` varchar(60)  NULL DEFAULT '',
              `email` varchar(100) NULL DEFAULT '',
              `phone_number` int(11) NULL,
              `status` varchar(60)  NULL DEFAULT '',
              `auth_sub_date` int(11) NULL,
              `auth_sub_date__date` varchar(60)  NULL DEFAULT '',
              `auth_sub_date__timezone_type` int(11) NULL,
              `auth_sub_date__timezone` varchar(60)  NULL DEFAULT '',
              `auth_merchant_number` int(11) NULL,
              `sponsor_name` varchar(60)  NULL DEFAULT '',
              `sponsor_mlmid` int(11) NULL,
              `sponsor_db_id` int(11) NULL,
              `sponsor_email` varchar(100) NULL DEFAULT '',
              `sponsor_phone_number` int(11) NULL,
              `sponsor_status` varchar(60)  NULL DEFAULT ''
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
      global $wpdb;
      $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
			if ( empty($res)) :
	      //indexes
	      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                    ADD PRIMARY KEY (`id`);' );
	      //AUTO increment
	      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
	                      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;' );
	    endif;
	 	}

	/*
	  * ----------------------------------------------------------------------------------------------------------
	  * nested set referals
	  * ----------------------------------------------------------------------------------------------------------
	 */
		 private function afl_nested_set_referal () {
		 		$table_name = $this->tbl_prefix .'afl_nested_set_referal';
	      $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `node_id` int(11) NOT NULL,
					  `parent_uid` int(10) UNSIGNED NOT NULL COMMENT 'Parent uid',
					  `lft` int(10) UNSIGNED NOT NULL COMMENT 'Left',
					  `rgt` int(10) UNSIGNED NOT NULL COMMENT 'Right'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nested set for referal';";

	      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	      dbDelta( $sql );
	      global $wpdb;
	      $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
				if ( empty($res)) :
		      //indexes
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                    ADD PRIMARY KEY (`node_id`);' );
		      //AUTO increment
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                      MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;' );
		    endif;
		 	}
	/*
	  * ----------------------------------------------------------------------------------------------------------
	  * nested set downlines
	  * ----------------------------------------------------------------------------------------------------------
	 */
		 private function afl_nested_set_downline () {
		 		$table_name = $this->tbl_prefix .'afl_nested_set_downline';
	      $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `node_id` int(11) NOT NULL,
					  `parent_uid` int(10) UNSIGNED NOT NULL COMMENT 'Parent uid',
					  `lft` int(10) UNSIGNED NOT NULL COMMENT 'Left',
					  `rgt` int(10) UNSIGNED NOT NULL COMMENT 'Right'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nested set for Downline';";
					
	      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	      dbDelta( $sql );
	      global $wpdb;
	      $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
				if ( empty($res)) :
		      //indexes
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                    ADD PRIMARY KEY (`node_id`);' );
		      //AUTO increment
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                      MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;' );
		    endif;
		 	}

		/*
	  * ----------------------------------------------------------------------------------------------------------
	  * nested set referals
	  * ----------------------------------------------------------------------------------------------------------
	 */
		 private function afl_unilevel_nested_set_referal () {
		 		$table_name = $this->tbl_prefix .'afl_unilevel_nested_set_referal';
	      $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `node_id` int(11) NOT NULL,
					  `parent_uid` int(10) UNSIGNED NOT NULL COMMENT 'Parent uid',
					  `lft` int(10) UNSIGNED NOT NULL COMMENT 'Left',
					  `rgt` int(10) UNSIGNED NOT NULL COMMENT 'Right'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nested set for referal';";

	      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	      dbDelta( $sql );
	      global $wpdb;
	      $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
				if ( empty($res)) :
		      //indexes
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                    ADD PRIMARY KEY (`node_id`);' );
		      //AUTO increment
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                      MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;' );
		    endif;
		 	}
	/*
	  * ----------------------------------------------------------------------------------------------------------
	  * nested set downlines
	  * ----------------------------------------------------------------------------------------------------------
	 */
		 private function afl_unilevel_nested_set_downline () {
		 		$table_name = $this->tbl_prefix .'afl_unilevel_nested_set_downline';
	      $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `node_id` int(11) NOT NULL,
					  `parent_uid` int(10) UNSIGNED NOT NULL COMMENT 'Parent uid',
					  `lft` int(10) UNSIGNED NOT NULL COMMENT 'Left',
					  `rgt` int(10) UNSIGNED NOT NULL COMMENT 'Right'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nested set for Downline';";
					
	      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	      dbDelta( $sql );
	      global $wpdb;
	      $res = $wpdb->get_results( 'SHOW KEYS FROM `'.$table_name.'` WHERE Key_name = "PRIMARY";' );
				if ( empty($res)) :
		      //indexes
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                    ADD PRIMARY KEY (`node_id`);' );
		      //AUTO increment
		      $wpdb->query( 'ALTER TABLE `'.$table_name.'`
		                      MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;' );
		    endif;
		 	}
} //here closing the class
