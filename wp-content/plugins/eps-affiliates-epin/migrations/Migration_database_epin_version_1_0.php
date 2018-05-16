<?php

/*
 * --------------------------------------------------------------------
 * Migration database version 1.0
 * --------------------------------------------------------------------
*/
class Migration_database_epin_version_1_0 {

	private $tbl_prefix 			= '';
	private $charset_collate 	= '';

	public function __construct (){
		
		global $wpdb;
		$this->tbl_prefix 		 = $wpdb->prefix;
		$this->charset_collate = $wpdb->get_charset_collate();
	}
	public function migration_upgrade(){
		$this->afl_epin();
		$this->afl_epin_history();
	}


	private function afl_epin (){
			$table_name = $this->tbl_prefix . 'afl_epin';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `pin_id` int(11) NOT NULL COMMENT 'The primary identifier for the epin',
					  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The current user id of the epin.',
					  `pin` varchar(128) DEFAULT NULL COMMENT 'The pin number of the epin.',
					   `balance` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Current balance in the epin.',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `status` varchar(128) DEFAULT NULL COMMENT 'Current status of epin.',
					  `transferable` tinyint(4) NOT NULL COMMENT 'Describe whether the epin is transferable or not.',
					  `reusable` tinyint(4) NOT NULL COMMENT 'Describe whether the epin is reusable or not.',
					  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Epin amount.',
					  `charge` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Charge applied to epin.',
					  `deleted` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Deleted/Refunded = 1'
					)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the user epin'";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`pin_id`);' );
			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `pin_id` int(11) NOT NULL AUTO_INCREMENT;' );
	}

	private function afl_epin_history(){
			$table_name = $this->tbl_prefix . 'afl_epin_history';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `transaction_id` int(11) NOT NULL COMMENT 'The primary identifier for the transaction',
					  `pin_id` int(11) NOT NULL COMMENT 'The primary identifier for the epin',
					  `pin` varchar(128) DEFAULT NULL COMMENT 'The pin number of the epin.',
					  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The current user id of the epin.',
					  `created_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created Date',
					  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Epin amount.',
					  `transaction_note` varchar(128) DEFAULT NULL COMMENT 'Transaction notes for epin.'
					)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='The base table for epin transaction history.'";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			global $wpdb;
			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`transaction_id`);' );
			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;' );
		}


// class end here
}

