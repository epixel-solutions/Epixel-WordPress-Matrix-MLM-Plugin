<div class="options_group woo-gotobweinar-<?php echo $product_type_key;?>" id="<?php echo $product_type_key;?>">
	<p>Scheduling Window</p>
	<?php 
		woocommerce_wp_text_input(
			array(
			'id'             => '_woogotowebinar_scheduling_window_num',
			'label'          => __( 'Schdule Range', 'woocommerce' ),
			'placeholder'	=> '',
			'desc_tip'    	=> 'true',
			'description'    => __( 'Enter number here to correspond day/month or year.', 'woocommerce' ),
			'type'           => 'number',
		));
		woocommerce_wp_select( 
			array( 
				'id'      		=> '_woogotowebinar_scheduling_window_date', 
				'label'   		=> __( 'Choose day/month or year', 'woocommerce' ), 
				'description'   => __( 'Choose day/month or year', 'woocommerce' ), 
				'desc_tip'    	=> 'true',
				'options' => array(
					'day'   => __( 'Day', 'woocommerce' ),
					'month' => __( 'Month', 'woocommerce' ),
					'year' 	=> __( 'Year', 'woocommerce' )
				)
			)
		);		
	?>
	<p>Range Time, From</p>
	<?php 
		woocommerce_wp_text_input(
			array(
			'id'             => '_woogotowebinar_range_time_from',
			'label'          => __( 'From', 'woocommerce' ),
			'placeholder'	=> '',
			'desc_tip'    	=> 'true',
			'description'    => __( 'Enter number for range time.', 'woocommerce' ),
			'type'           => 'number',
		));
		woocommerce_wp_select( 
		array( 
			'id'      => '_woogotowebinar_range_time_from_meridiem', 
			'label'   => __( 'AM/PM', 'woocommerce' ), 
			'description'   => __( 'Ante meridiem and Post meridiem', 'woocommerce' ), 
			'desc_tip'    	=> 'true',
			'options' => array(
				'am'   => __( 'AM', 'woocommerce' ),
				'pm'   => __( 'PM', 'woocommerce' )
				)
			)
		);		
	?>
	<p>Range Time, To</p>
	<?php 
		woocommerce_wp_text_input(
			array(
			'id'             => '_woogotowebinar_range_time_to',
			'label'          => __( 'To', 'woocommerce' ),
			'placeholder'	=> '',
			'desc_tip'    	=> 'true',
			'description'    => __( 'Enter number for range time.', 'woocommerce' ),
			'type'           => 'number',
		));
		woocommerce_wp_select( 
		array( 
			'id'      => '_woogotowebinar_range_time_to_meridiem', 
			'label'   => __( 'AM/PM', 'woocommerce' ), 
			'description'   => __( 'Ante meridiem and Post meridiem', 'woocommerce' ), 
			'desc_tip'    	=> 'true',
			'options' => array(
				'am'   => __( 'AM', 'woocommerce' ),
				'pm'   => __( 'PM', 'woocommerce' )
				)
			)
		);		
	?>
</div>