<?php 

function afl_admin_processing_queue_data () {
	do_action('eps_affiliate_page_header');
		do_action('afl_content_wrapper_begin');
			afl_admin_clear_queue_form();
			afl_admin_processing_queue_data_table();
		do_action('afl_content_wrapper_end');
}

function afl_admin_processing_queue_data_table () {
	new Afl_enque_scripts('common');
	
	$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_queues());
		$config['base_url'] 	= '?page=affiliate-eps-processing-queue';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_queues($index, $config['per_page']);

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
			__('Name'),
			__('Uid'),
			__('Status'),
			__('Processing Times'),
			__('Data')		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			
			$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);

			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->name
			);

			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->uid
			);

			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> _render_queue_status($value->status)
			);

			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $value->runs
			);

			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> !empty((maybe_unserialize($value->data))) ?
										json_encode(maybe_unserialize($value->data)) : ''
			);
		}
		$table['#rows'] = $rows;
		echo apply_filters('afl_render_table',$table);

}

function _get_queues ($index = 0, $limit = '') {
		$query = array();
		$filter_severity 	= -1;

		$query['#select'] = _table_name('afl_processing_queue');

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		$query['#order_by'] = array(
			'runs' => 'DESC'
		);
		$resp = db_select($query, 'get_results');

			return $resp;
	}

function afl_admin_clear_queue_form () {
	if ( isset($_POST['reset_processing_queue'])) {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE `"._table_name('afl_processing_queue')."`");
		afl_variable_set('remote_user_get_last_get_id',1);
		wp_set_message('Variable reset successfully');
	}

	$form1['#action'] = $_SERVER['REQUEST_URI'];
 	$form1['#method'] = 'post';
 	$form1['#prefix'] ='<div class="form-group row">';
 	$form1['#suffix'] ='</div>';

 	$form1['fieldset'] = array(
 		'#type'=>'fieldset',
 		'#title'=>'Clear Processing Queue'
 	);
 	
 	$form1['fieldset']['markup'] = array(
 		'#type' => 'markup',
 		'#markup' => 'Last fetch Id : '.afl_variable_get('remote_user_get_last_get_id',1),
 	);

 	$form1['fieldset']['reset_processing_queue'] = array(
 		'#name' => 'reset_processing_queue',
 		'#type' => 'submit',
 		'#value' => 'Clear',
 		'#attributes' => array(
 			'class' => array(
 				'btn','btn-primary'
 			)
 		),
 	);
 	echo afl_render_form($form1);
}

function _render_queue_status ($status = '') {
	$ret_val = 'unverified';
	switch ($status) {
		case 0:
			$ret_val = 'processing';
		break;
		case 1:
			$ret_val = 'successfully processed';
		break;
		case 2:
			$ret_val = 'Re-process';
		break;
		case 3:
			$ret_val = 'Failed';
		break;
		default:
			$ret_val = 'Queued';
		break;
	}

	return $ret_val;
}
