<?php
/*
 * ----------------------------------------------------------------
 * Set cron queue status
 * ----------------------------------------------------------------
*/
 function processing_queue_status_update ($unique_id = '', $status = '') {
 	global $wpdb;
 	$wpdb->update(
 		_table_name('afl_processing_queue'),
 		array(
 			'status'=>$status
 		),
 		array(
 			'item_id'=>$unique_id
 		)
 	);
 }
/*
 * ----------------------------------------------------------------
 * update how many times the queue processed
 * ----------------------------------------------------------------
*/
 function processing_queue_processed_increment ($unique_id = '') {
 		global $wpdb;
 		$res = $wpdb->query($wpdb->prepare('UPDATE '._table_name("afl_processing_queue").' SET runs = runs+1 WHERE item_id = %d',
 			$unique_id)
 		);
 }

/*
 * ---------------------------------------------------------------
 * Remove Queue Data
 * ---------------------------------------------------------------
*/
 function processing_queue_remove ($unique_id = '') {
 		global $wpdb;
 		$res = $wpdb->query($wpdb->prepare('DELETE FROM  '._table_name("afl_processing_queue").' WHERE item_id = %d',
 			$unique_id)
 		);
 }
/*
 * ---------------------------------------------------------------
 * update processed times
 * ---------------------------------------------------------------
*/
 function processing_queue_processed_time_set ($unique_id = '', $processed_time = '') {
 		global $wpdb;
 		$res = $wpdb->query($wpdb->prepare('UPDATE '._table_name("afl_processing_queue").' SET runs = %d WHERE item_id = %d',
 			$processed_time,$unique_id)
 		);
 }