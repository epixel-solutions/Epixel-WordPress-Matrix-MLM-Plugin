<?php
function afl_unilevel_refered_members () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_unilevel_refered_members_callback();
	afl_content_wrapper_end();
}

function afl_unilevel_refered_members_callback () {
		new Afl_enque_scripts('common');
	
	do_action('afl_my_distributors_count_template', 'unilevel');
	do_action('afl_my_customers_count_template');
	
	$uid = get_uid();

	if (isset($_GET['uid'])) {
		$uid = $_GET['uid'];
	}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (afl_unilevel_get_user_refered_downlines($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-unilevel-refered-members';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created', 'level'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = afl_unilevel_get_user_refered_downlines($uid,$filter);
		// pr($data);
		
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		// $table['#title'] 			= 'Business Profit Overview(Monthly)';
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
			__('Userid'),
			__('Username'),
			__('Level'),		
			// __('Relative Position'),		
			__('Rank'),		
			__('Created On'),		
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$user_roles =  afl_user_roles($value->ID);
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->ID
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> get_unilevel_level_from(get_uid(),$value->ID)
			);
			// $rows[$key]['markup_4'] = array(
			// 	'#type' =>'markup',
			// 	'#markup'=> get_unilevel_relative_position_from(get_uid(),$value->ID)
			// );
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> (array_key_exists('afl_customer',$user_roles)) ? render_rank('','Customer'):render_rank($value->member_rank)
				
			);
			$rows[$key]['markup_6'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
		
}