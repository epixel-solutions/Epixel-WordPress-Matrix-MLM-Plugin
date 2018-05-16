<?php
	function afl_admin_recent_log_messages () {
		do_action('eps_affiliate_page_header');
		do_action('afl_content_wrapper_begin');
		afl_admin_recent_log_messages_filter_form();
		_exposed_callback_filter();
		_clear_log_message_form();
			afl_admin_recent_log_messages_table();
		do_action('afl_content_wrapper_end');
	}

	function afl_admin_recent_log_messages_filter_form () {
		new Afl_enque_scripts('common');

		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'all';  
	  //here render the tabs
	  echo '<ul class="tabs--primary nav nav-tabs">';

	  echo '<li class="'.(($active_tab == 'all') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-recent-log-messages&tab=all" >All ('._get_severity_log_count().')</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'critical') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-recent-log-messages&tab=critical" >Critical ('._get_severity_log_count(LOGS_CRITICAL).')</a>  
	          </li>';
	  echo '<li class="'.(($active_tab == 'error') ? 'active' : '').'">
	            	<a href="?page=affiliate-eps-recent-log-messages&tab=error" >Error ('._get_severity_log_count(LOGS_ERROR).')</a>  
	          </li>';
    echo '<li class="'.(($active_tab == 'warning') ? 'active' : '').'">
        	<a href="?page=affiliate-eps-recent-log-messages&tab=warning" >Warning ('._get_severity_log_count(LOGS_WARNING).')</a>  
      </li>';
    echo '<li class="'.(($active_tab == 'notice') ? 'active' : '').'">
        	<a href="?page=affiliate-eps-recent-log-messages&tab=notice" >Notice ('._get_severity_log_count(LOGS_NOTICE).')</a>  
      </li>';
    echo '<li class="'.(($active_tab == 'info') ? 'active' : '').'">
        	<a href="?page=affiliate-eps-recent-log-messages&tab=info" >Info ('._get_severity_log_count(LOGS_INFO).')</a>  
      </li>';
     echo '<li class="'.(($active_tab == 'debug') ? 'active' : '').'">
        	<a href="?page=affiliate-eps-recent-log-messages&tab=debug" >Debug ('._get_severity_log_count(LOGS_DEBUG).')</a>  
      </li>';
	  echo '</ul>';


	}
function _exposed_callback_filter ( $tree = 'unilevel' ) {
	
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
	 	$form['fieldset'] = [
 			'#type'=>'fieldset',
 			'#title' => 'Filter log Messages'
 		];
 		$form['fieldset']['type'] = array(
	 		'#title' 	=> 'Category',
	 		'#type'  	=> 'select',
	 		'#name'		=> 'type',
	 		'#multiple'		=> TRUE,
	 		'#options' => _get_distinct_type_logs(),
	 		'#default_value'=> isset($_GET['type']) ? $_GET['type'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);

	 	$form['fieldset']['submit'] = array(
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

	function _clear_log_message_form () {
		
		if ( isset($_POST['clear_logs'])) {
			global $wpdb;
			$wpdb->query("TRUNCATE TABLE `"._table_name('afl_log_messages')."`");
		}


		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
 		$form['#method'] = 'post';
 		$form['#prefix'] ='<div class="form-group row">';
 		$form['#suffix'] ='</div>';
 		$form['fieldset'] = [
 			'#type'=>'fieldset',
 			'#title' => 'Clear log Messages'
 		];

 		$form['fieldset']['markup'] = [
 			'#type'=>'markup',
 			'#markup' => 'This will permanently remove the log messages from the database.'
 		];
 		$form['fieldset']['submit'] = array(
	 		'#title' => 'Submit',
	 		'#type' => 'submit',
	 		'#name' => 'clear_logs',
	 		'#value' => 'Clear log messages',
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

	function afl_admin_recent_log_messages_table () {
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_logs());
		$config['base_url'] 	= '?page=affiliate-eps-recent-log-messages';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_logs($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= '';
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
			__('Status'),
			__('Type'),
			__('Date'),
			__('Message'),
			__('Variables')		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$markup = '';
			if ( $value->severity == LOGS_WARNING) {
				$markup = '<i class="fa fa-exclamation-triangle " style="color:yellow;"></i>';
			}

			if ( $value->severity == LOGS_CRITICAL) {
				$markup = '<i class="fa fa-exclamation" ></i>';
			}

			if ( $value->severity == LOGS_ERROR) {
				$markup = '<i class="fa fa-times"></i>';
			}

			if ( $value->severity == LOGS_NOTICE) {
				$markup = '<i class="fa fa-check"></i>';
			}

			if ( $value->severity == LOGS_INFO) {
				$markup = '<i class="fa fa-info-circle"></i>';
			}

			if ( $value->severity == LOGS_DEBUG) {
				$markup = '';
			}
				$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_00'] = array(	
				'#type' =>'markup',
				'#markup'=> $markup
			);

			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> $value->type
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> date('Y-m-d H:i:s',$value->timestamp)
			);

			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->message
			);

			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> !empty((maybe_unserialize($value->variables))) ?
										json_encode(maybe_unserialize($value->variables)) : ''
			);
		}
		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
	}

	function _get_logs ($index = 0, $limit = '') {
		$query = array();
		$filter_severity 	= -1;

		$query['#select'] = _table_name('afl_log_messages');

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		
		if ( !empty($_GET['severity']) && $_GET['severity'] !='none') {
			$query['#where'][] = 'severity='.$_GET['severity'];
		}
		if ( !empty($_GET['type']) && $_GET['type'] !='none') {
			$query['#where'][] = 'type="'.$_GET['type'].'"';
		}

		if ( isset($_GET['tab'])) {
			switch ($_GET['tab']) {
				case 'warning':
					$filter_severity = LOGS_WARNING;
				break;
				case 'critical':
					$filter_severity = LOGS_CRITICAL;
				break;
				case 'error':
					$filter_severity = LOGS_ERROR;
				break;
				case 'notice':
					$filter_severity = LOGS_NOTICE;
				break;
				case 'info':
					$filter_severity = LOGS_INFO;
				break;
				case 'debug':
					$filter_severity = LOGS_DEBUG;
				break;
			}
		}

		if ( $filter_severity != -1 ) {
			$query['#where'][] = 'severity="'.$filter_severity.'"';
		}

		$resp = db_select($query, 'get_results');

			return $resp;
	}

	function _get_log_types(){
		$query['#select'] = _table_name('afl_log_messages');
		$query['#fields'] = array(
			 _table_name('afl_log_messages') => array('type')
		);
		$query['#group_by'] = array(
			'type'
		);
		$resp = db_select($query, 'get_results');
		$ret = array();
		$ret['none'] = 'None';
		foreach ($resp as $key => $value) {
			$ret[$value->type] = $value->type;
		}
		return $ret;
	}

	function _get_severity_log_count ($severity = -1) {
		$query['#select'] = _table_name('afl_log_messages');
		if ( $severity != -1 ) {
			$query['#where'][] = 'severity="'.$severity.'"';
		}
		$resp = db_select($query, 'get_results');
		return count($resp);
	}

	function _get_distinct_type_logs () {
		$types = _get_log_types();
		return $types;
	}