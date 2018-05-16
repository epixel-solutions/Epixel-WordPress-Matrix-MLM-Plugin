<?php 
/**
* 
*/
	class Db_transaction {
	/**
	 * --------------------------------------------------------------
	 * Db_transaction instance.
	 * --------------------------------------------------------------
	 * all the transaction based details are comes here
	 * @access private
	 * @since  1.0
	 * @var    Db_transaction
	 *
	*/

	/*
	 * --------------------------------------------------------------
	 * Start the transaction
	 * --------------------------------------------------------------
	*/
		public function start (){
			global $wpdb;
			$wpdb->query('START TRANSACTION');
		}
	/*
	 * --------------------------------------------------------------
	 * commit the transaction
	 * --------------------------------------------------------------
	*/
		public function commit () {
			global $wpdb;
			$wpdb->query('COMMIT');
		}
	/*
	 * --------------------------------------------------------------
	 * Start the transaction
	 * --------------------------------------------------------------
	*/
		public function roll_back () {
			global $wpdb;
			$wpdb->query('ROLLBACK');
		}
	}