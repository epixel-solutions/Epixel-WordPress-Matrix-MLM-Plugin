<?php
	/**
	 * -------------------------------------------------------------------
	 * @package Background Process
	 * @version 1.0
	 * -------------------------------------------------------------------
  */
  /**
	 * -------------------------------------------------------------------
	 * Check the class WP_Async_Request exist or not
	 * -------------------------------------------------------------------
  */
	  if ( ! class_exists( 'WP_Async_Request', false ) ) {
	  	require_once EPSAFFILIATE_PLUGIN_DIR . 'libraries/wp-async-request.php';
	  }
	/**
	 * -------------------------------------------------------------------
	 * Check the class WP_Background_Process exist or not
	 * -------------------------------------------------------------------
	*/
		if ( ! class_exists( 'WP_Background_Process', false ) ) {
	  	require_once EPSAFFILIATE_PLUGIN_DIR . 'libraries/wp-background-process.php';
	  }
	class WP_Userembedd_Process extends WP_Background_Process {

	/**
	 * -------------------------------------------------------------------
	 * @var string
	 * -------------------------------------------------------------------
  */
		protected $action = 'userembedd_process';

	/**
	 * -------------------------------------------------------------------
	 * Task
	 * -------------------------------------------------------------------
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 * -------------------------------------------------------------------
  */
		protected function task( $item ) {
			backgroundProcess($item);
			return false;
		}

	/**
	 * -------------------------------------------------------------------
	 * Complete
	 * -------------------------------------------------------------------
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 * -------------------------------------------------------------------
  */
		protected function complete() {
			parent::complete();
		}
	/*
	 * --------------------------------------------------------------------
	 * function to be called in the background: referenced in protected 
	 * task function above
	 * --------------------------------------------------------------------
	*/
		// public function backgroundProcess($str) {
		//   error_log($str);
		//   sleep(20);
		//   file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log.txt', $str.PHP_EOL,FILE_APPEND);
		// }
	}
	$process_all = new WP_Userembedd_Process;

//function to be called in the background: referenced in protected task function above
function backgroundProcess($str) {
  file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log2.txt', json_encode($str).PHP_EOL,FILE_APPEND);
  //check his sponsor exist in genealogy
  //create new user based on the input
  //
  sleep(20);
}