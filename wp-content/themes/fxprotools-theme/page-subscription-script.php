<?php //get_header(); ?>
<?php  
set_query_var('customer_order_id',0);
function create_order_subscription($user_id){
	//setup the billing details
	$address = array(
	    'first_name' => get_the_author_meta('first_name',$user_id),
	    'last_name'  => get_the_author_meta('last_name',$user_id),
	    'company'    => get_the_author_meta('billing_company',$user_id),
	    'email'      => get_the_author_meta('email',$user_id),
	    'phone'      => get_the_author_meta('billing_phone',$user_id),
	    'address_1'  => get_the_author_meta('billing_address_1',$user_id),
	    'address_2'  => get_the_author_meta('billing_address_2',$user_id),
	    'city'       => get_the_author_meta('billing_city',$user_id),
	    'state'      => get_the_author_meta('billing_state',$user_id),
	    'postcode'   => get_the_author_meta('billing_postcode',$user_id),
	    'country'    => get_the_author_meta('billing_country',$user_id)
	);

	$start_date = date("Y-m-d h:i:sa");
	$start_to_time = strtotime(date("Y-m-d h:i:sa"));
	$next_payment = date("Y-m-d h:i:sa", strtotime("+1 hour", $start_to_time));
	$parent_product = wc_get_product(48);

	$args = array(
	    'attribute_subscription-type' => 'normal'
	);

	$product_variation = $parent_product->get_matching_variation($args);
	$product = wc_get_product($product_variation);  

	// Each variation also has its own shipping class

	$quantity = 1;

	$order = wc_create_order( array('customer_id' => $user_id) );

	$order->add_product( $product, $quantity, $args);
	$order->set_address( $address, 'billing' );

	//comment out shipping settings for now
	//$order->set_address( $address, 'shipping' );

	// $order->add_shipping((object)array (
	//     'id' => $selected_shipping_method->id,
	//     'label'    => $selected_shipping_method->title,
	//     'cost'     => (float)$class_cost,
	//     'taxes'    => array(),
	//     'calc_tax'  => 'per_order'
	// ));

	$order->calculate_totals();

	$order->update_status('pending_payment', 'Order Created via Import', TRUE);

	// CREATE SUBSCRIPTION
	$period = WC_Subscriptions_Product::get_period( $product );
	$interval = WC_Subscriptions_Product::get_interval( $product );

	$sub = wcs_create_subscription(array('order_id' => $order->get_id(), 'billing_period' => $period, 'billing_interval' => $interval, 'start_date' => $start_date));
	$sub->update_dates(array('schedule_next_payment' => $next_payment));
	
	$sub->add_product( $product, $quantity, $args);
	$sub->set_address( $address, 'billing' );

	//comment out shipping settings for now
	// $sub->set_address( $address, 'shipping' );

	// $sub->add_shipping((object)array (
	//     'id' => $selected_shipping_method->id,
	//     'label'    => $selected_shipping_method->title,
	//     'cost'     => (float)$class_cost,
	//     'taxes'    => array(),
	//     'calc_tax'  => 'per_order'
	// ));

	$sub->calculate_totals();
	WC_Subscriptions_Manager::expire_subscriptions_for_order($order);

	//wcs_create_renewal_order($sub);
	return $order->get_id();
}

//create_order_subscription(6545);
/* LOOP THROUGH USERS */
//check if user has brought the item
function has_bought_items($user_id) {
    $bought = false;
    $prod_arr = array( '48' );
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => 'shop_order', 
        'post_status' => array('wc-processing', 'wc-completed', 'wc-pending')
    ) );
    foreach ( $customer_orders as $customer_order ) {
        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
        $order = wc_get_order( $customer_order );

        foreach ($order->get_items() as $item) {
            if ( version_compare( WC_VERSION, '3.0', '<' ) ) 
                $product_id = $item['product_id'];
            else
                $product_id = $item->get_product_id();

            if ( in_array( $product_id, $prod_arr ) ) 
                $bought = true;
        }
    }
    return $bought;
}
/*
//execute order and subscription for users
$affiliates = $wpdb->get_results( "SELECT affiliate_id,user_id FROM wp_affiliate_wp_affiliates" );
$json = file_get_contents('https://app.copyprofitshare.com/public/api/users/volishon/get');
$data = json_decode($json, true);
$counter = 1;

$chunk_num = isset( $_GET['chunk_num'] ) ? $_GET['chunk_num'] : 0; //index of chunks to be imported
$chunks = array_chunk($data, 500, true);

dd("No. of chunks:" . sizeof($chunks) );

ob_implicit_flush(true);
ob_start();

foreach ($chunks as $index => $data){

	if( $index != $chunk_num) continue;
	$loop = 1;
	foreach($data as $key => $import_user){
		dd( "#" . $loop . ". Importing: " . $import_user['email'] );
		$loop += 1;
	    flush();
	    ob_flush();

		$user = get_user_by( 'email', strtolower($import_user['email']) );
		
		//if no account yet, create one
		if( !$user ){

			$user_id = create_simple_account($import_user);
			if($user_id){
				$user = get_user_by( 'id', $user_id );
			} else {
				dd($import_user);
				die("Can't create account or find this user.");
			}
		}

		//make sure the user hasn't created any orders yet (clean user)
		if( !has_bought_items($user->ID) ){

			$aff_sponsor_id = affwp_get_affiliate_id( 2936 ); //default sponsor
			$aff_sponsor = get_user_by( 'email', strtolower($import_user['sponsor_email']) );
			
			//if sponsor account exists, use him instead
			if($aff_sponsor){
				$aff_sponsor_id = affwp_get_affiliate_id( $aff_sponsor->ID );
			}

			//echo 'sponsor: ' . $aff_sponsor_id;

	    	//create order and subscription
	    	$order_id = create_order_subscription($user->ID);

	    	//echo 'order_id: ' . $order_id;
			

	    	//add referral entry on the database
			$referral_args = array(
				'affiliate_id' => $aff_sponsor_id,
				'amount'       => 0.00,
				'description'  => 'Business - Normal',
				'reference'    => $order_id,
				'context'      => 'woocommerce',
				'status'       => 'completed',
			);
			affwp_add_referral($referral_args);

			//dd($referral_args);
		}
		else{
			dd( "Already bought item: " . $import_user['email'] );
		}

	}

	dd( "Imported Chunk #: " . $index );
	exit;
}
*/

function create_simple_account( $data ){
	$user_data = array(
		'user_login'    => $data['email'],
		'user_pass'     => 'password123',
		'user_nicename' => $data['email'],
		'user_email'    => $data['email'],
		'role'          => 'afl_member'
	);

	$user_id = wp_insert_user($user_data);
	
	if($user_id > 0){
		add_user_meta( $user_id, '_imported_user', '1' ); 
		add_user_meta( $user_id, '_sponsor_email', $data['sponsor_email'] ); 
		return $user_id;
	} else {
		return false;
	}
}
/*
foreach($affiliates as $affiliate){
	//check if user has bought business
	if(get_the_author_meta('email',$affiliate->user_id) != "" && has_bought_items($affiliate->user_id) == false){
		foreach($data as $obj){
			//check matching data on our users from remote API
		    if(strtolower(get_the_author_meta('email',$affiliate->user_id)) == strtolower($obj['email'])){
		    	//get sponsor ID by email from our database
		  		$aff_sponsor    = get_user_by( 'email', strtolower($obj['sponsor_email']) );
		  		$aff_sponsor_id = 2936;
		  		if(isset($aff_sponsor)){
		  			$aff_sponsor_id = $aff_sponsor->ID;
		  		}
		  		if(!isset($aff_sponsor_id)){
		  			$aff_sponsor_id = 2936;
		  		}

		    	//create order and subscription
		    	create_order_subscription($affiliate->user_id);
		    	//add referral entry on the database
				$referral_args = array(
					'affiliate_id' => $aff_sponsor_id,
					'amount'       => 0.00,
					'description'  => 'Business - Normal',
					'reference'    => get_query_var('customer_order_id'),
					'context'      => 'woocommerce',
					'status'       => 'completed',
				);
				affwp_add_referral($referral_args);
		    	$counter++;
		    	break;
		    }
		}
	}
}
*/


?>

<?php //get_footer(); ?>
