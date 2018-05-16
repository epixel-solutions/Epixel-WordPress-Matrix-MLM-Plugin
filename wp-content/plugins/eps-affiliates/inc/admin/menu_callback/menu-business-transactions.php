<?php 
function afl_business_transactions(){
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		new Afl_enque_scripts('common');
		afl_business_transactions_();
	afl_content_wrapper_end();
}

function afl_business_transactions_ (){
	
	$page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '';  
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'all';  
		
	  //here render the tabs
	  echo '<ul class="tabs--primary nav nav-tabs">';

	  echo '<li class="'.(($active_tab == 'all') ? 'active' : '').'">
	            	<a href="?page='.$page.'&tab=all" >OverAll</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'credit') ? 'active' : '').'">
	            	<a href="?page='.$page.'&tab=credit" >Credit</a>  
	          </li>';
    echo '<li class="'.(($active_tab == 'debit') ? 'active' : '').'">
      	<a href="?page='.$page.'&tab=debit" >Dredit</a>  
    </li>';
	  echo '</ul>';

	  switch ($active_tab) {
	  	case 'credit':
	  		$filter['credit_status'] = 1;	
	  	break;
	  	case 'debit':
	  		$filter['credit_status'] = 0;	
	  	break;
	  	
	  	default:
	  		$filter['credit_status'] = -1;	
  		break;
	  }

	  if ( isset($_GET['filter-user'])) {
			$filter['uid'] = extract_sponsor_id($_GET['filter-user']);
	  }

	  if ( isset($_GET['filter-assoc'])) {
			$filter['assoc_uid'] = extract_sponsor_id($_GET['filter-assoc']);
	  }

	  _business_transactions_page_callback_filter( );

	 afl_business_transactions_callback($filter);
}

function _business_transactions_page_callback_filter () {

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
 		$form['tab'] = array(
	 		'#type'  	=> 'hidden',
	 		'#name'		=> 'tab',
	 		'#default_value'=>!empty($_GET['tab']) ? $_GET['tab'] : '',
	 	);
	 	
 		$form['filter-user'] = array(
	 		'#title' 	=> 'Member',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-user',
	 		'#auto_complete_path' => 'sys_users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-user']) ? $_GET['filter-user'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);
		$form['filter-assoc'] = array(
	 		'#title' 	=> 'Associate Member',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-assoc',
	 		'#auto_complete_path' => 'sys_users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-assoc']) ? $_GET['filter-assoc'] : '',
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

function afl_business_transactions_callback ( $filter = []) {
		$pagination = new CI_Pagination;

		$url  = http_build_query($_GET);
		$config['total_rows'] =  (_all_business_transactions($filter,TRUE));
		// $config['base_url'] 	= '?page=affiliate-eps-business-transaction';
		$config['base_url'] 	= '?'.$url;
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter['index'] = $index;
		$filter['limit'] = $config['per_page'];


		$data  = _all_business_transactions($filter);
		// pr($data);
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  		= $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Overall Business Transactions';
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
			__('Category'),
			__('Member'),
			__('Associated Member'),
			__('Amount'),
			__('Credit Status'),		
			__('Date'),		
			__('Additional Notes'),		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);

			$rows[$key]['markup_category'] = array(
				'#type' =>'markup',
				'#markup'=> $value->category
			);
			$rows[$key]['markup_uname'] = array(
				'#type' =>'markup',
				'#markup'=> _render_member_name($value->uid)
			);
			$rows[$key]['markup_assoc_uname'] = array(
				'#type' =>'markup',
				'#markup'=> _render_member_name($value->associated_user_id)
			);
			$rows[$key]['markup_amount_paid'] = array(
				'#type' =>'markup',
				'#markup'=> afl_format_payment_amount($value->amount_paid),
			);

			$rows[$key]['markup_status'] = array(
				'#type' =>'markup',
				'#markup'=> _render_credit_status($value->credit_status),
			);
			$rows[$key]['markup_trnsaction_date'] = array(
				'#type' =>'markup',
				'#markup'=> afl_system_date_format($value->created,TRUE)
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
}

function _all_business_transactions ( $filter = [], $count = FALSE) {
	$query = array();
   $query['#select'] = _table_name('afl_business_transactions');
   $query['#join']  = array(
      'wp_users' => array(
        '#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_business_transactions').'`.`associated_user_id`'
      )
    );
   	$limit = '';
		if (isset($filter['index']) && isset($filter['limit'])) {
			$limit .= $filter['index'].','.$filter['limit'];
		}
		
		if (isset($filter['credit_status']) && $filter['credit_status'] != -1) {
			$query['#where'] = [
				'`'._table_name('afl_business_transactions').'`.`credit_status` = '.$filter['credit_status']
			];
		}
		if (!empty($limit)) {
			$query['#limit'] = $limit;
		}

		if (!empty($filter['uid'])) {
			$query['#where'][] = '`'._table_name('afl_business_transactions').'`.`uid` = '.$filter['uid'];
		}

		if (!empty($filter['assoc_uid'])) {
			$query['#where'][] = '`'._table_name('afl_business_transactions').'`.`associated_user_id` = '.$filter['assoc_uid'];
		}
   	$query['#order_by'] = array(
      '`transaction_date`' => 'ASC'
    );
	 $result = db_select($query, 'get_results');
	 
	 if ($count)
			return count($result); 
		return $result;
}