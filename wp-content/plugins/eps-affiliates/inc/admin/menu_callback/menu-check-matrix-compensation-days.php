<?php 
	function _check_matrix_compensation_days () {
		new Afl_enque_scripts('common');
  	echo afl_eps_page_header();
  	echo afl_content_wrapper_begin();
    	_check_matrix_compensation_days_callback_filter();
    	__check_matrix_compensation_days_callback();
  	echo afl_content_wrapper_begin();
	}

	function _check_matrix_compensation_days_callback_filter () {
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

	function __check_matrix_compensation_days_callback () {
		$uid = get_uid();

		

	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (_get_users('',array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-check-matrix-comp-days';
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
		$data  = _get_users('',$filter);
		
		
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
			__('Months Active'),		
			__('Active Distributors(previous Month)'),		
			__('Bonus Amount'),		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_uid'] = array(
				'#type' =>'markup',
				'#markup'=> $value->uid
			);
			$rows[$key]['markup_uname'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);
			$rows[$key]['markup_rank'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank)
			);
			$rows[$key]['markup_days_active'] = array(
				'#type' =>'markup',
				'#markup'=> _get_howmany_months_user_active($value->uid)
			);
			//

			$prev_date = strtotime('-1 month',afl_date());
      $prev_month_split = afl_date_splits($prev_date);

      //get the actived distributos prev month under this user
      $downline_distribs = _get_downline_distributors_($uid , 'matrix');
      $query['#select'] = _table_name('afl_purchases');
	 		$query['#where']  = array(
	 			'category = "Distributor Kit"',
	 			'purchase_month = '.$prev_month_split['m'],
	 			'purchase_year = '.$prev_month_split['y'],
	 		);
	 		$query['#where_in'] = [
	 			'uid' =>  $downline_distribs
	 		];
	 		$query['#expression'] = array(
	 			'COUNT(`'._table_name('afl_purchases').'`.`uid`) as count'
	 		);
			$respo  = db_select($query, 'get_row');
			$count = !empty($respo->count) ? $respo->count : 0;


			$rows[$key]['markup_active_distribs'] = array(
				'#type' =>'markup',
				'#markup'=> $count
			);

			$months_actived = _get_howmany_months_user_active($uid);
			$amount_for_actived_month = afl_variable_get('month_'.$months_actived.'_matrix_compensation', 0);
			$user_amount = $amount_for_actived_month * $count;

			$rows[$key]['markup_bonus_amount'] = array(
				'#type' =>'markup',
				'#markup'=> $user_amount
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
	}

	function _get_users ( $uid = '',$filters = [], $ret_count = FALSE) {
			$afl_date = afl_date();
      $afl_date_splits = afl_date_splits($afl_date);

    	$query['#select'] = _table_name('afl_purchases');
    	$query['#join'] 	= array(
				_table_name('users') => array(
					'#condition' => '`wp_users`.`ID`=`'._table_name('afl_purchases').'`.`uid`'
				)
			);
      $query['#where']  = array(
        'category = "Distributor Kit"',
        'purchase_month = '.$afl_date_splits['m'],
        'purchase_year = '.$afl_date_splits['y'],
      );

      if (isset($filters['uid'])) {
				$query['#where'][] = 'uid='.$filters['uid'];
      }

      if (!empty($filters['length']) ) {
				$query['#limit'] = $filters['start'].','.$filters['length'];
			}

			$query['#order_by'] = array(
				'`uid`' => 'ASC'
			);

      $result  = db_select($query, 'get_results');

			if ( $ret_count ) {
				return count($result);
			}

			return $result;
	}	

	