<?php 
function afl_system_features_and_configurations () {
		new Afl_enque_scripts('common');
	
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();

	$table = array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class' => array(
							'table',
							'table-bordered',
							'table-responsive'
						)
					);
	$table['#header'] = array('Configuration', 'Link');
	/*
	 * --------------------------------------------------------------------------
	 * advanced config
	 * --------------------------------------------------------------------------
	*/
		$i = 0;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Advanced Configuration',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-system-configurations"><span class="btn btn-rounded btn-sm btn-icon btn-default"><i class="fa fa-cog"></i></span></a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * compensation plan config
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Compensation Plan Configuration',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-compensation-plan-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * rank conf
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Rank Configuration',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-rank-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);

	/*
	 * --------------------------------------------------------------------------
	 * payout config
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Payout Configurations',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-payout-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);

	//payment method conf
	// $rows[5]['label_1'] = array(
	// 	'#type' => 'label',
	// 	'#title'=> 'Payment Method Configurations',
	// );
	// $rows[5]['link_1'] = array(
	// 	'#type' => 'markup',
	// 	'#markup' => '<a href="?page=affiliate-eps-payment-method-configurations">
	// 									<span class="btn btn-rounded btn-sm btn-icon btn-default">
	// 										<i class="fa fa-cog"></i>
	// 									</span>
	// 								</a>',
	// );
	
	/*
	 * --------------------------------------------------------------------------
	 * pool bonus config
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Pool Bonus Configurations',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-pool-bonus-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * Genealogy Configuration
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Genealogy Configurations',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-genealogy-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * variable configurations
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Variable Configurations',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-variable-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * Advanced Queue configurations
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Advanced Queue Configurations',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-advanced-queue-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * Pagination pages configuration
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Pagination pages configuration',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-pagination-pages-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);
	/*
	 * --------------------------------------------------------------------------
	 * roles and permissions config
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Roles and Permissions',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-role-config-settings">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);


	/*
	 * --------------------------------------------------------------------------
	 * HyperWallet Conf
	 * --------------------------------------------------------------------------
	*/
		$i++;
		$rows[$i]['label_1'] = array(
			'#type' => 'label',
			'#title'=> 'Hyper Wallet Configuration',
		);
		$rows[$i]['link_1'] = array(
			'#type' => 'markup',
			'#markup' => '<a href="?page=affiliate-eps-hyperwallet-configurations">
											<span class="btn btn-rounded btn-sm btn-icon btn-default">
												<i class="fa fa-cog"></i>
											</span>
										</a>',
		);


	$table['#rows'] = apply_filters('eps_affiliates_features_configuration',$rows);

	echo afl_content_wrapper_end();

	echo afl_render_table($table);
}
