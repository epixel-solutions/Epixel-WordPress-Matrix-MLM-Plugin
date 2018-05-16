<?php 
/** 
 * -------------------------------------------------------------------
 * On this , icludes the functionalities when a member account has 
 * been cacelled
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 *
 * @category   Cancel member account
 * @package    Cancel member account
 * @author 		 < pratheesh@epicelsolutions.com >
 * @copyright  2017-2017 Epixelsolutions
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 * -------------------------------------------------------------------
*/

/**
 * -------------------------------------------------------------------
 * Member cancelation action callback comes here
 * 
 *
 * In here there are 5 functionalities invokes when a member account
 * cancelled
 *
 *		- Get all downlines of cancelled user and assign these to the
 *			next upline actived distributor
 *
 *		- Get all refferals of the cancelled user and assign these to
 *			the next upline acived distributor
 *
 *		- Get the holding members of the cancelled user and assign these
 *			to next upline actived distributor holding tank
 *
 *		- Get the e-wallet amount of the cancelled user and it credited 
 *			to the business
 *
 *		- Remove cancelled user from the unilevel and matrix tree
 *
 * @param $uid : user id of user
 * -------------------------------------------------------------------
*/
	function _afl_member_account_cancel ($uid = '') {
		_re_assign_downlines_cancelled_user($uid);
		_re_assign_refferals_cancelled_user($uid);
		_re_assign_holding_users_cancelled_user($uid);
		_re_credit_ewaalet_amount_cancelled_user($uid);
		_remove_from_tree_cancelled_user($uid);
	}

/**
 * -------------------------------------------------------------------
 * assign the downlines of a user into another user
 *
 * in here, the downline members of a user is assigned to his upline
 * actived distributor
 *
 * Functionalities
 *
 *		-	Get the assign user if default null
 *		- Get the downlines of the user
 *		- All the downlines assign to asign uid with re generating 
 *
 *	@param $uid : user id
 *	@param $assign_user : which users under, it is NULL by default,
 *											 if it null taken the upline actived distributor
 												 as assign user
 * -------------------------------------------------------------------
*/
	function _re_assign_downlines_cancelled_user ($uid = '',$assign_user = '') {

	}