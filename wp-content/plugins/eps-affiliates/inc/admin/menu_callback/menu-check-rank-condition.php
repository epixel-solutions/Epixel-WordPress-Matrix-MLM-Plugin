<?php 
	function _check_rank_page () {
		new Afl_enque_scripts('common');
  	echo afl_eps_page_header();
  	echo afl_content_wrapper_begin();
    	_check_rank_page_callback_filter();
    	_check_rank_achievement_callback_filter();
  	echo afl_content_wrapper_begin();
	}

	function _check_rank_page_callback_filter () {
		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
 		$form['#method'] = 'get';
 		$form['#prefix'] ='<div class="form-group row">';
 		$form['#suffix'] ='</div>';

 		$form['page'] = array(
	 		'#type'  	=> 'hidden',
	 		'#name'		=> 'page',
	 		'#default_value'=>!empty($_GET['page']) ? $_GET['page'] : '',
	 	);
 		$form['filter-user'] = array(
	 		'#title' 	=> 'username',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-user',
	 		'#auto_complete_path' => 'sys_users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-user']) ? $_GET['filter-user'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);
	 	$form['filter-uid'] = array(
	 		'#title' 	=> 'user id',
	 		'#type'  	=> 'textfield',
	 		'#name'		=> 'filter-uid',
	 		'#default_value'=> isset($_GET['filter-uid']) ? $_GET['filter-uid'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#title' => 'Submit',
	 		'#type' => 'submit',
	 		'#value' => 'Filter',
	 		'#attributes' => array(
	 			'class' => array(
	 				'btn','btn-primary'
	 			)
	 		),
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'
	 		
	 	);

 		echo afl_render_form($form);
	}

	function _check_rank_achievement_callback_filter () {
		$uid = get_uid();

		

	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_get_unilevel_users('',array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-check-rank';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		if ( isset($_GET['filter-user'])) {
			$filter['uid'] = extract_sponsor_id($_GET['filter-user']);
		}
		if (!empty($_GET['filter-uid'])) {
			$filter['uid'] = $_GET['filter-uid'];
		}
		$data  = _get_unilevel_users('',$filter);
		
		
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
			__('UID'),
			__('Username'),
			__('Current Rank'),		
			__('Next Rank'),		
			__('Conditions'),		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->uid
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank)
			);
			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank + 1)
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> _user_rank_condition_table($value->uid , $value->member_rank + 1 ),
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
	}

	function _get_unilevel_users ( $uid = '',$filters = [], $ret_count = FALSE) {
			$query = array();
			$query['#select'] = _table_name('afl_unilevel_user_genealogy');
			$query['#join'] 	= array(
				_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_unilevel_user_genealogy').'`.`uid`'
				),
			);
			$query['#fields'] = array(
				_table_name('afl_unilevel_user_genealogy') => array('uid','member_rank'),
				_table_name('users') => array('display_name')
			);

			if ( !empty($filters['uid']) ) {
				$query['#where'] = array(
					'`'._table_name('afl_unilevel_user_genealogy').'`.`uid`='.$filters['uid'].''
				);
			}

			if (!empty($filters['length']) ) {
				$query['#limit'] = $filters['start'].','.$filters['length'];
			}

			$query['#order_by'] = array(
				'`uid`' => 'ASC'
			);
			$result = db_select($query, 'get_results');

			if ( $ret_count ) {
				return count($result);
			}

			return $result;
	}	

	function _user_rank_condition_table ( $uid = '',$rank = '') {
		$table = array();
		$table['#links']  = '';
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
			__('PV'),
			__('GV'),		
			__('Distributor Count'),		
		);
		if ( afl_variable_get('enable_rank_customer_rule'))  {
			$table['#header'][] = __('Customer Rule');
		}
		$table['#header'][]	=	__('Qualification');

		
		$rows = array();
		/* ------ CRITERIA 1  --------------------------------------------------------*/
			$key = 0;
			$status = _check_required_pv_meets($uid, $rank);
			if ($status) {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-up  text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-down  text-danger m-b-xs"></i></span>';
			}
			$rows[$key]['markup_pv_cond'] = array(
				'#type' =>'markup',
				'#markup'=> $condition
			);
		/* ------ CRITERIA 1  --------------------------------------------------------*/

		/* ------ CRITERIA 2  --------------------------------------------------------*/
			$status = _check_required_gv_meets($uid, $rank);
			if ($status) {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-up  text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-down  text-danger m-b-xs"></i></span>';
			}
			$rows[$key]['markup_gv_cond'] = array(
				'#type' =>'markup',
				'#markup'=> $condition
			);
		/* ------ CRITERIA 2  --------------------------------------------------------*/

		/* ------ CRITERIA 3  --------------------------------------------------------*/
			$status = _check_required_distributors_meets($uid, $rank);
			if ($status) {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-up  text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-down  text-danger m-b-xs"></i></span>';
			}
			$rows[$key]['markup_distrib_cond'] = array(
				'#type' =>'markup',
				'#markup'=> $condition
			);
		/* ------ CRITERIA 3  --------------------------------------------------------*/

		/* ------ CRITERIA 4  --------------------------------------------------------*/
		if ( afl_variable_get('enable_rank_customer_rule'))  {
			$status = _check_required_customer_rule($uid, $rank);
			if ($status) {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-up  text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-down  text-danger m-b-xs"></i></span>';
			}
			$rows[$key]['markup_quali_cond'] = array(
				'#type' =>'markup',
				'#markup'=> $condition
			);
		} 
		/* ------ CRITERIA 4  --------------------------------------------------------*/

		/* ------ CRITERIA 5  --------------------------------------------------------*/
			$status = _check_required_qualifications_meets($uid, $rank);
			if ($status) {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-up  text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-lg fa-thumbs-o-down  text-danger m-b-xs"></i></span>';
			}
			$rows[$key]['markup_cust_rule_cond'] = array(
				'#type' =>'markup',
				'#markup'=> $condition
			);
		/* ------ CRITERIA 5  --------------------------------------------------------*/


		$table['#rows'] = $rows;

		return apply_filters('afl_render_table',$table);
	}