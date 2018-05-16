<?php

function afl_bonus_summary_report () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		_afl_bonus_summary_report();
	afl_content_wrapper_end();
}


function _afl_bonus_summary_report () {
		
	afl_get_template('plan/matrix/bonus-summary-widgets-template.php');
	_bonus_nd_incentives_table();
}

function _bonus_nd_incentives_table () {

		new Afl_enque_scripts('common');

		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_bonus_nd_incentives());
		$config['base_url'] 	= '?page=affiliate-eps-business-profit';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_bonus_nd_incentives($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Bonus/Incentives';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
						'class' => array(
								'table',
								'table-bordered',
								'my-table-center',
								
							)
						);

		$table['#header'] = array(
			__('#'),
			__('Title of Bonus'),
			__('Rank Name'),
			__('Details'),
		);
		$rows = array();
		
		$max_rank = afl_variable_get('number_of_ranks');
		if ( $max_rank ) {
			for ( $i = 1 ; $i <= $max_rank; $i++ ) {
				$rows[$i]['markup_slno'] = array(
					'#type' =>'markup',
					'#markup'=> $i
				);
				$rows[$i]['markup_incentive'] = array(
					'#type' =>'markup',
					'#markup'=> afl_variable_get('rank_'.$i.'_incentives','???')
				);

				$rows[$i]['markup_rank_name'] = array(
					'#type' =>'markup',
					'#markup'=> afl_variable_get('rank_'.$i.'_name','Rank '.$i)
				);

				$rows[$i]['markup_details'] = array(
					'#type' =>'markup',
					'#markup'=> _get_furthrer_qualification_to_achieve_rank($i)
				);
			}
		}
		$table['#rows'] = $rows;
		echo apply_filters('afl_render_table',$table);
}

function _get_bonus_nd_incentives ( $index = 0, $limit = '' ) {

}

function _get_furthrer_qualification_to_achieve_rank ($rank = '', $uid = '') {
	$ret_string = '';
	$rank_got 	= '';

	if (empty($uid)) {
		$uid = get_uid();
	}

	//check pv meets
	if (!_check_required_pv_meets($uid, $rank)) {
		$required = afl_variable_get('rank_'.$rank.'_pv',0);
		$earned 	= _get_user_pv($uid); 

		$ret_string .= '</br>* You need '.($required - $earned).' more Personal volume to unlock this';
	}
	
	//check gv meets
	if (!_check_required_gv_meets($uid, $rank)) {
		$required = afl_variable_get('rank_'.$rank.'_gv',0);
		$earned 	= _get_user_gv_v1($uid, $rank, TRUE); 

		$ret_string .= '</br>* You need '.($required - $earned).' more Group volume to unlock this';
	}

	//check distributor count meets
	if (!_check_required_distributors_meets($uid, $rank)) {
		$required = afl_variable_get('rank_'.$rank.'_no_of_distributors',0);
		$earned 	= _get_user_distributor_count($uid); 

		$ret_string .= '</br>* You need '.($required - $earned).' more distributors to unlock this';
	}

	//meet the customer rule
	if (!_check_required_customer_rule($uid, $rank)) {
		$ret_string .= '</br>* You need meet the required customer rule';
	}

	//check the required qualififcation
	if ( !_check_required_qualifications_meets($uid, $rank)) {
		$ret_string .= '</br>* You need meet the required qualification criterias';
	}

	$node = afl_genealogy_node($uid);
	if (isset($node->member_rank)) {
		if ( $node->member_rank >= $rank) {
			$ret_string = 'UNLOCKED';
		}
	}
	return $ret_string;
}