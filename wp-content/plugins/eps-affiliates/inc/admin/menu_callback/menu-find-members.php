<?php 

	function afl_members_find () { 
		echo afl_eps_page_header();
		afl_content_wrapper_begin();
		member_find_table();
		//$affiliates_table = new Eps_find_members_data_table();
	?>
			 
		
		<!-- 	<div class="wrap">
			<h1>
			</h1> -->
			<?php

			/**
			 * Manage Members pf eps-affiliates
			 *
			 * Use this hook to add content to this section of AffiliateWP.
			 *
				do_action( 'eps_affiliates_page_top' );

				?>
				<form id="eps-affiliates-filter" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php $affiliates_table->search_box( __( 'Search', 'eps-affiliates' ), 'eps-affiliates' ); ?>

					<input type="hidden" name="page" value="affiliate-eps-find-members" />

					<?php //$affiliates_table->views() ?>
					<?php $affiliates_table->prepare_items() ?>
					<?php $affiliates_table->display() ?>
				</form>
				<?php
				/**
				 * Fires at the bottom of the admin affiliates page.
				 *
				 * Use this hook to add content to this section of AffiliateWP.
				 *
				do_action( 'eps_affiliates_page_bottom' );
				?>
			</div>
	<?php */
		afl_content_wrapper_end();

	}
 function member_find_table () {
		new Afl_enque_scripts('common');
 		
	 	// do_action('eps_table_filter_user');
	  // do_action('eps_table_filter_sponsor');
	  // do_action('eps_table_filter_parent');
   // 	do_action('eps_table_filter_button');

 		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
 		$form['#method'] = 'get';
 		$form['#prefix'] ='<div class="form-group row">';
 		$form['#suffix'] ='</div>';

 		$form['page'] = array(
	 		'#type'  	=> 'hidden',
	 		'#name'		=> 'page',
	 		'#default_value'=>'affiliate-eps-find-members'
	 	);
 		$form['filter-user'] = array(
	 		'#title' 	=> 'username',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-user',
	 		'#auto_complete_path' => 'users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-user']) ? $_GET['filter-user'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'

	 	);
	 	$form['filter-sponsor'] = array(
	 		'#title' 	=> 'sponsor',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-sponsor',
	 		'#auto_complete_path' => 'users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-sponsor']) ? $_GET['filter-sponsor'] : '',
	 		'#prefix' => '<div class="col-md-2">',
	 		'#suffix' => '</div>'
	 	);
	 	$form['filter-parent'] = array(
	 		'#title' 	=> 'parent',
	 		'#type'  	=> 'auto_complete',
	 		'#name'		=> 'filter-parent',
	 		'#auto_complete_path' => 'users_auto_complete',
	 		'#default_value'=> isset($_GET['filter-parent']) ? $_GET['filter-parent'] : '',
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


 		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_members());
		$config['base_url'] 	= '?page=affiliate-eps-find-members';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$affiliates  = _get_members($index, $config['per_page']);

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
			__('Member'),
			__('Parent'),
			__('Sponsor'),
			__('Member status'),
			__('Member Network'),		
			__('Sponsor Network')		
		);
		$rows = array();

		// $affiliates = _get_members();
		// pr($affiliates);
		$i = 0;
		foreach ($affiliates as $key => $member) {
			$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			/*
			 * --------------------------------
			 * Member Name
			 * --------------------------------
			*/
				$rows[$key]['member_name'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $member->display_name
				);

			/*
			 * --------------------------------
			 * Parent Name
			 * --------------------------------
			*/
				$node  = afl_genealogy_node($member->parent_uid);
				if (!empty($node) && !empty($node->display_name)) {
					$parent_name = $node->display_name;
				} else {
					$parent_name = 'unverified';
				}

				$rows[$key]['parent_name'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $parent_name
				);

			/*
			 * --------------------------------
			 * Referrer Name
			 * --------------------------------
			*/
				$node  = afl_genealogy_node($member->referrer_uid);
				if (!empty($node) && !empty($node->display_name)) {
					$parent_name = $node->display_name;
				} else {
					$parent_name = 'unverified';
				}

				$rows[$key]['sponsor_name'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $parent_name
				);
			/*
			 * --------------------------------
			 * Member status
			 * --------------------------------
			*/
				$statuses = list_extract_allowed_values(afl_variable_get('member_status'), 'list_text', '');
			  $value = $statuses[$member->status];
			  $rows[$key]['member_staus'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $value
				);
      /*
			 * --------------------------------
			 * Member Network
			 * --------------------------------
			*/
				$value = sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-sitemap"></i></a>', __('Genealogy tree', 'affiliate-eps'), '?page=affiliate-eps-genealogy-tree&uid='.$member->uid);
			  $value .= sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-slideshare"></i></a>', __('Referred Members', 'affiliate-eps'), '?page=affiliate-eps-refered-members&uid='.$member->uid);
			  $value .= sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-flask"></i></a>', __('Holding Tank', 'affiliate-eps'), '?page=affiliate-eps-holding-tank&uid='.$member->uid);

			  $rows[$key]['member_network'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $value
				);
			/*
			 * --------------------------------
			 * Sponsor Network
			 * --------------------------------
			*/
				 $value = sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-sitemap"></i></a>', __('Genealogy tree', 'affiliate-eps'), '?page=affiliate-eps-genealogy-tree&uid='.$member->referrer_uid);
			  $value .= sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-slideshare"></i></a>', __('Referred Members', 'affiliate-eps'), '?page=affiliate-eps-refered-members&uid='.$member->referrer_uid);
			  $value .= sprintf( '<a title="%s" class="btn m-b-xs btn-sm  btn-addon" href="%s"><i class="fa fa-flask"></i></a>', __('Holding Tank', 'affiliate-eps'), '?page=affiliate-eps-holding-tank&uid='.$member->referrer_uid);

			 	$rows[$key]['sponsor_network'] = array(
					'#type'		=> 'markup',
					'#markup'	=> $value
				);
		}	
		// pr($rows);
		$table['#rows'] = $rows;
		echo apply_filters('afl_render_table',$table);
 }
  function _get_members ( $index = 0, $limit = '' ) {
  		$query = array();
			$query['#select'] = _table_name('afl_user_genealogy');

			$query['#join'] 	= array(
				_table_name('users') => array(
					'#condition' => '`'._table_name('users').'`.`ID`=`'._table_name('afl_user_genealogy').'`.`uid`'
				)
			);
			
			//get only non-deleted members
			$query['#where'] = array(
				'`'._table_name('afl_user_genealogy').'`.`deleted`=0',
				'`'._table_name('afl_user_genealogy').'`.`status`=1'
			);

			//check filters applied
			if (isset($_GET['filter-user']) && !empty($_GET['filter-user'])) {
				$uid =  extract_sponsor_id($_GET['filter-user']);
				$uid = empty($uid) ? 0 : $uid;
				$query['#where'][] = '`'._table_name('afl_user_genealogy').'`.`uid`='.$uid;
			}

			if (isset($_GET['filter-sponsor']) && !empty($_GET['filter-sponsor'])) {
				$suid =  extract_sponsor_id($_GET['filter-sponsor']);
				$suid = empty($suid) ? 0 : $suid;
				$query['#where'][] = '`'._table_name('afl_user_genealogy').'`.`referrer_uid`='.$suid;
			}

			if (isset($_GET['filter-parent']) && !empty($_GET['filter-parent'])) {
				$puid =  extract_sponsor_id($_GET['filter-parent']);
				$puid = empty($puid) ? 0 : $puid;
				$query['#where'][] = '`'._table_name('afl_user_genealogy').'`.`parent_uid`='.$puid;
			}
			
			if (!empty($limit) ) {
				$query['#limit'] = $index.','.$limit;
			}

			$affiliates = db_select($query, 'get_results');
			
			return $affiliates;
  }