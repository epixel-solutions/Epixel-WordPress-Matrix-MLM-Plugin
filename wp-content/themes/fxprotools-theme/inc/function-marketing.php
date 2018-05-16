<?php
/**
 * -----------------------
 * Fxprotools - Custom functions for marketing pages
 * -----------------------
 * custom functions funnels, stats, contacts
 */

function get_funnels()
{
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'menu_order',
		'order'			   => 'ASC',
		'post_status'      => 'publish',
		'post_type'		   => 'fx_funnel',
	);
	return get_posts($args);
}

function property_occurence_count($array, $property, $value)
{

	$count = 0;
	foreach ($array as $object) {
		$record_url =  parse_url($object->{$property}, PHP_URL_HOST) . parse_url($object->{$property}, PHP_URL_PATH);
		$check_url =  parse_url($value , PHP_URL_HOST) . parse_url(  $value, PHP_URL_PATH);
		if ( rtrim($record_url, '/') == rtrim($check_url, '/')  ){
			$count++;
		}
	}
	return $count;
}

function get_unique_property_count($array, $property, $url)
{
	$count = 0;
	foreach($array as $object){
		if( rtrim($object->url, '/') == rtrim($url, '/') ){
			$value = $object->{$property};
			$occurrence = property_occurence_count($array, $property, $value);
			if($occurrence == 1) $count += 1;
		}
	}
	return $count;
}

function get_property_count($array, $property, $url)
{
	$count = 0;
	foreach($array as $object){
		if( rtrim($object->url, '/') == rtrim($url, '/') ){
			if( (int) $object->{$property} > 0) $count++;
		}
	}
	return $count;
}


function date_is_in_range($date_from, $date_to, $date)
{
	$start_ts = strtotime($date_from);
 	$end_ts = strtotime($date_to);
	$ts = strtotime($date);
 	return (($ts >= $start_ts) && ($ts <= $end_ts));
}

function get_funnel_stats($funnel_id, $date_filter = array(), $user_id = 0)
{
	$user_id  = current_user_can('administrator') ? 0 : get_current_user_id();
	$affiliate_id = $user_id == 0 ? : affwp_get_affiliate_id( $user_id );

	
	$visits = get_funnel_visits( $affiliate_id );
	if( $date_filter ){
		foreach($visits as $key => $visit){
			 if( !date_is_in_range($date_filter['date_from'], $date_filter['date_to'], date("m/d/Y", strtotime($visit->date))) ) unset($visits[$key]);
		}
	}
	$funnel = array( 'cp_url' => rwmb_meta('capture_page_url', '', $funnel_id),
		 			 'lp_url' => rwmb_meta('landing_page_url', '', $funnel_id)
		 			);
	$cp_stats = array( 'page_views' => array('all' 	 => 0, 'unique' => 0),
					   'opt_ins' 	=> array('all' 	 => 0, 'rate' 	 => 0),
					   'sales' 		=> array('count' => 0, 'rate'	 => 0),
				);
	$lp_stats = array( 'page_views' => array('all' 	 => 0, 'unique' => 0),
					   'opt_ins' 	=> array('all' 	 => 0, 'rate' 	 => 0),
					   'sales' 		=> array('count' => 0, 'rate' 	 => 0),
				);

	$sales_stats = array( 'customer_sales' => get_total_customer_sales( $funnel, $user_id), 'distributor_sales' => get_total_distributor_sales( $funnel, $user_id) );

	//all
	$cp_stats['page_views']['all'] = property_occurence_count($visits, 'url',  $funnel['cp_url'] );
	$lp_stats['page_views']['all'] = property_occurence_count($visits, 'url', $funnel['lp_url'] );

	//unique
	$cp_stats['page_views']['unique'] = get_unique_property_count($visits, 'ip', $funnel['cp_url']);
	$lp_stats['page_views']['unique'] = get_unique_property_count($visits, 'ip', $funnel['lp_url']);


	//opt ins
	$funnel_id = trim( parse_url( rwmb_meta('capture_page_url', '', $funnel_id), PHP_URL_PATH ), '/');
	$search = FX_Sendgrid_Api::search_contacts($funnel_id, get_current_user_id() );
	$cp_stats['opt_ins']['all'] = $search->recipient_count; 
	$cp_stats['opt_ins']['rate'] = ( $cp_stats['page_views']['all'] >= 1 && $cp_stats['opt_ins']['all'] >= 1) ? round( $cp_stats['opt_ins']['all'] / $cp_stats['page_views']['all'] * 100, 2) : 0;

	//sales
	$cp_stats['sales']['count'] = get_property_count($visits, 'referral_id', $funnel['cp_url']);
	$cp_stats['sales']['rate'] = ( $cp_stats['page_views']['all'] >= 1 && $cp_stats['sales']['count'] >= 1) ? round( $cp_stats['sales']['count'] / $cp_stats['page_views']['all'] * 100, 2) : 0;

	$lp_stats['sales']['count'] = get_property_count($visits, 'referral_id', $funnel['lp_url']);
	$lp_stats['sales']['rate'] = ( $lp_stats['page_views']['all'] >= 1 && $lp_stats['sales']['count'] >= 1) ? round( $lp_stats['sales']['count'] / $lp_stats['page_views']['all'] * 100, 2) : 0;

	$stats = array( 'capture' => $cp_stats,
					'landing' => $lp_stats,
					'totals' => $sales_stats,
				);

	return $stats;
}

function get_funnel_visits( $affiliate_id ){
	global $wpdb;

	$affiliate_cond = '';
	if( $affiliate_id > 0){
		$affiliate_cond = "WHERE affiliate_id = {$affiliate_id}";
	}

	$results = $wpdb->get_results($sql = "SELECT *
        FROM {$wpdb->prefix}affiliate_wp_visits as visits 
        {$affiliate_cond} 
    ");
    return $results;

}



function get_total_distributor_sales( $funnel, $user_id = 0 ){
	global $wpdb;
	
	$affiliate_cond = '';
	$affiliate_id = affwp_get_affiliate_id( $user_id );

	if( $affiliate_id ){
		$affiliate_cond = "AND referrals.affiliate_id = {$affiliate_id}";
	}

	$visit_cond = '';

	if( is_array($funnel) ){
		$visit_cond = "AND (visits.url LIKE '%{$funnel['cp_url']}%' OR visits.url LIKE '%{$funnel['lp_url']}%')";
	}

    $results = $wpdb->get_results($sql = "SELECT COUNT(description) as sales_count
        FROM {$wpdb->prefix}affiliate_wp_referrals as referrals
        LEFT  JOIN {$wpdb->prefix}affiliate_wp_visits as visits on referrals.referral_id = visits.referral_id
        WHERE `description` LIKE \"%Business%\" {$visit_cond} {$affiliate_cond}
    ");



    return isset( $results[0] ) ? $results[0]->sales_count : 0;
}

function get_total_customer_sales( $funnel, $user_id = 0 ){
	global $wpdb;
	$affiliate_cond = '';
	$affiliate_id = affwp_get_affiliate_id( $user_id );

	if( $affiliate_id ){
		$affiliate_cond = "AND referrals.affiliate_id = {$affiliate_id}";
	}

	$visit_cond = '';

	if( is_array($funnel) ){
		$visit_cond = "AND (visits.url LIKE '%{$funnel['cp_url']}%' OR visits.url LIKE '%{$funnel['lp_url']}%')";
	}

    $results = $wpdb->get_results($sql = "SELECT COUNT(description) as sales_count
        FROM {$wpdb->prefix}affiliate_wp_referrals as referrals
        LEFT  JOIN {$wpdb->prefix}affiliate_wp_visits as visits on referrals.referral_id = visits.referral_id
        WHERE  (`description` LIKE \"%Signals%\" OR `description` LIKE \"%Professional%\") {$visit_cond} {$affiliate_cond}
    ");

    return isset( $results[0] ) ? $results[0]->sales_count : 0;
}

function get_total_funnel_sales( $link_url, $user_id = 0 ){
	global $wpdb;
	$affiliate_cond = '';
	$affiliate_id = affwp_get_affiliate_id( $user_id );

	if( $affiliate_id ){
		$affiliate_cond = "AND referrals.affiliate_id = {$affiliate_id}";
	}

	$visit_cond = "(visits.url LIKE '%{$link_url}%')";

    $results = $wpdb->get_results($sql = "SELECT COUNT(description) as sales_count
        FROM {$wpdb->prefix}affiliate_wp_referrals as referrals
        LEFT  JOIN {$wpdb->prefix}affiliate_wp_visits as visits on referrals.referral_id = visits.referral_id
        WHERE {$visit_cond} {$affiliate_cond} 
    ");


    return $results[0]->sales_count;
}

function get_highest_converting_funnel_link( $user_id = 0){
	$funnels = get_funnels();
	$link = '';
	$highest = 0;

	foreach ($funnels as $key => $post){

		$funnel = array( 
			'cp_url' => rwmb_meta('capture_page_url', '', $post->ID),
			'lp_url' => rwmb_meta('landing_page_url', '', $post->ID)
		);

		$cp_sales = get_total_funnel_sales( $funnel['cp_url'], $user_id);
		$lp_sales = get_total_funnel_sales( $funnel['lp_url'], $user_id);

		if( $cp_sales >= $highest){
			$highest = $cp_sales;
			$link = $funnel['cp_url'];
		} 

		if( $lp_sales >= $highest){
			$highest = $lp_sales;
			$link = $funnel['lp_url'];
		}
	} 

	return $link;
}

function get_user_referrals()
{
	if(get_current_user_id() > 0){
		$affiliate_id = affwp_get_affiliate_id( get_current_user_id() );
		$affiliate_referrals = affiliate_wp()->referrals->get_referrals( array(
			'number'       => -1,
			'affiliate_id' => $affiliate_id
		) );
		return $affiliate_referrals;
	}
}


function get_user_active_referrals($user_id = 0)
{
	$user_id = ( $user_id > 0 ) ?  $user_id : get_current_user_id();

	$affiliate_id = affwp_get_affiliate_id( $user_id );
	$affiliate_referrals = affiliate_wp()->referrals->get_referrals( array(
		'number'       => -1,
		'affiliate_id' => $affiliate_id
	) );


	foreach($affiliate_referrals as $key => $referral){
		$order = wc_get_order( $referral->reference );

		if( $order ){
			$user_id = $order->get_user_id();

			if( !wcs_user_has_subscription( $user_id, '', 'active' ) ){
				unset($affiliate_referrals[$key]);
				continue;
			}
		}
		
	}

	return $affiliate_referrals;
}

function get_admin_contacts($affiliate_ids)
{
	$referral_loop_count = 0;
	$contacts = array();
	foreach($affiliate_ids as $affiliate_id){
		$contacts[$referral_loop_count]['id'] = $affiliate_id;
		$contacts[$referral_loop_count]['username'] = get_the_author_meta('user_login', $affiliate_id);
		$contacts[$referral_loop_count]['fname'] = get_the_author_meta('first_name', $affiliate_id);
		$contacts[$referral_loop_count]['lname'] = get_the_author_meta('last_name', $affiliate_id);
		$contacts[$referral_loop_count]['email'] = get_the_author_meta('email', $affiliate_id);
		$contacts[$referral_loop_count]['date'] = random_checkout_time_elapsed(get_the_author_meta('user_registered',$affiliate_id,false));
		//$contacts[$referral_loop_count]['avatar'] = get_avatar_url( $affiliate_id );
		$referral_loop_count++;
	}

	return $contacts;
}

function get_user_contacts($referrals)
{
	$referral_loop_count = 0;
	$contacts = array();
	foreach($referrals as $referral){
		$order = wc_get_order( $referral->reference );
		$contacts[$referral_loop_count]['id'] = $order->get_user_id();
		$contacts[$referral_loop_count]['username'] = get_the_author_meta('user_login', $order->get_user_id());
		$contacts[$referral_loop_count]['fname'] = get_the_author_meta('first_name', $order->get_user_id());
		$contacts[$referral_loop_count]['lname'] = get_the_author_meta('last_name', $order->get_user_id());
		$contacts[$referral_loop_count]['email'] = get_the_author_meta('email', $order->get_user_id());
		$contacts[$referral_loop_count]['date'] = random_checkout_time_elapsed($order->get_date_paid());
		//$contacts[$referral_loop_count]['avatar'] = get_avatar_url($order->get_user_id());
		$referral_loop_count++;
	}

	return $contacts;
}

add_action("wp_ajax_ajax_contacts", "ajax_contacts");
add_action("wp_ajax_nopriv_ajax_contacts", "ajax_contacts");
function ajax_contacts($page_num,$query_offset_multi,$search_string){
	global $wpdb;
	$search_string = null;
	if(isset($_REQUEST['page_num']) && isset($_REQUEST['query_offset_multi']) && isset($_REQUEST['search_string_'])){
		$page_num = $_REQUEST['page_num'];
		$query_offset_multi = $_REQUEST['query_offset_multi'];
		$search_string = $_REQUEST['search_string_'];
	}
	$query_offset = $page_num * $query_offset_multi;
	$affiliate_ids = array();
	$results = array();		
 	$search_results = array();
	$ref_count = 0;
	$ref_count_search = 0;
	$collect_result = array();

	$user = wp_get_current_user();
	if ( in_array( 'administrator', (array) $user->roles ) ) {
		if(isset($search_string) && $search_string != null){
			$search_string = trim($search_string);
			$all_refs = $wpdb->get_results( "SELECT * FROM wp_users WHERE user_login LIKE '%{$search_string}%' OR user_email LIKE '%{$search_string}%' OR user_nicename LIKE '%{$search_string}%' LIMIT 10 OFFSET " . ($query_offset - 10) );
			foreach($all_refs as $affiliate){
				$affiliate_ids[] = $affiliate->ID;
			}
			$ref_count_search = $wpdb->get_var("SELECT COUNT(*) FROM wp_users WHERE user_login LIKE '%{$search_string}%' OR user_email LIKE '%{$search_string}%' OR user_nicename LIKE '%{$search_string}%'");
		}else{
			$all_refs = $wpdb->get_results( "SELECT * FROM wp_users LIMIT 10 OFFSET " . ($query_offset - 10) );
			foreach($all_refs as $affiliate){
				$affiliate_ids[] = $affiliate->ID;
			}
			$ref_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_users");
		}
	}else{
		if(isset($search_string) && $search_string != null){
			$affiliate_id = affwp_get_affiliate_id( get_current_user_id() );
			$referrals = affiliate_wp()->referrals->get_referrals( array(
				'number'       => -1,
				'affiliate_id' => $affiliate_id
			) );
		}else{
			$referrals = $wpdb->get_results( "SELECT * FROM wp_affiliate_wp_referrals WHERE affiliate_id = ". affwp_get_affiliate_id(get_current_user_id()) ." LIMIT 10 OFFSET " . ($query_offset - 10) );
			$ref_count = $wpdb->get_var("SELECT COUNT(*) FROM wp_affiliate_wp_referrals WHERE affiliate_id = " . affwp_get_affiliate_id(get_current_user_id()) );
		}
	}

	if ( in_array( 'administrator', (array) $user->roles ) ){
		$contacts = get_admin_contacts($affiliate_ids);
	}else{
		$contacts = get_user_contacts($referrals);
	}

	if( isset( $search_string ) && !in_array( 'administrator', (array) $user->roles ) && $search_string != null ){		
		foreach ($contacts as $index => $index_item) {		
	       	foreach($index_item as $item){		
	       		if(stripos($item,$search_string) !== false){		
	       			if(!in_array($index, $results,TRUE)){		
	       				array_push($results, $index);
	       				$ref_count_search++;
	       			}		
	       		}		
	       	}		
	    }		
	    foreach($results as $result){		
	    	array_push($search_results,$contacts[$result]);		
	    }		
	    $contacts = $search_results;		
	}

	$collect_result['contacts'] = $contacts;
	$collect_result['ref_count'] = $ref_count;
	$collect_result['ref_count_search'] = $ref_count_search;
	$collect_result['query_offset'] = $query_offset;

	echo json_encode($collect_result);
	wp_die();
}

add_action("wp_ajax_format_contacts", "format_contacts");
add_action("wp_ajax_nopriv_format_contacts", "format_contacts");
function format_contacts($results){
	$user = wp_get_current_user();
	if(isset($_REQUEST)){
		$contacts = $_REQUEST['contacts'];
		$ref_count = $_REQUEST['ref_count'];
		$ref_count_search = $_REQUEST['ref_count_search'];
		$query_offset = $_REQUEST['query_offset'];
		$query_offset_multi = $_REQUEST['query_offset_multi'];
		$page_num = $_REQUEST['page_num'];
		$search_string = $_REQUEST['search_term'];
	}
	ob_start();
	echo '<ul class="fx-list-contacts">';
	//dd($_REQUEST);
	if(!empty($contacts)){
		if( $search_string == "" ){
			//$total_pages = (int)($ref_count / $query_offset_multi);
			$total_pages = ceil($ref_count / $query_offset_multi);
			//echo $total_pages;
			foreach($contacts as $contact){
?>
				<li>
					<div class="media">
						<div class="media-left">
							<img src="<?php echo esc_url( get_avatar_url( $contact['id'] ) ); ?>" />
						</div>
						<div class="media-body">
							<div class="info">
								<h5 class="media-heading text-bold">
									<?php  
										if($contact['fname']){
											echo $contact['fname'] . ' ' . $contact['lname'];
										}else{
											echo $contact['username'];
										}
									?>
								</h5>
								<p><?php echo $contact['email']; ?></p>
							</div>
							<div class="actions">
								<span class="small"><?php echo $contact['date']; ?></span>
								<a href="<?php bloginfo('url');?>/marketing/contacts/user?id=<?php echo $contact['id'] ?>" class="btn btn-default btn-sm m-l-sm">View</a>
							</div>
						</div>
					</div>
				</li>
<?php
			}
		}//if search string is not set end
		else{
			$total_pages = ceil($ref_count_search / $query_offset_multi);
			$search_counter = 1;
			foreach($contacts as $contact){
				if(isset($query_offset) && $search_counter <= (($query_offset - 9) + 9) && $search_counter >= ($query_offset - 9) || in_array( 'administrator', (array) $user->roles )){
?>
					<li>
						<div class="media">
							<div class="media-left">
								<img src="<?php echo esc_url( get_avatar_url( $contact['id'] ) ); ?>" />
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">
										<?php  
											if($contact['fname']){
												echo $contact['fname'] . ' ' . $contact['lname'];
											}else{
												echo $contact['username'];
											}
										?>
									</h5>
									<p><?php echo $contact['email']; ?></p>
								</div>
								<div class="actions">
									<span class="small"><?php echo $contact['date']; ?></span>
									<a href="<?php bloginfo('url');?>/marketing/contacts/user?id=<?php echo $contact['id'] ?>" class="btn btn-default btn-sm m-l-sm">View</a>
								</div>
							</div>
						</div>
					</li>
<?php
				}else{
					// echo $search_counter . "<=" . (($query_offset - 9) + 9);
					// echo '<br>';
					// echo $search_counter . ">=" . ($query_offset - 9);
					// echo '<br>';
					// echo "*******************************";
				}
				$search_counter++;
			}
		}
	}else{
		echo "no contacs found.";
	}
	echo '</ul>';
	get_contact_pagination($page_num,$search_string,$total_pages);

	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
	wp_die();
}

function get_contact_pagination($page_num,$search_string,$total_pages){
	$page_counter = 1;
	ob_start();
?>
	<form id="contact-pagination" class="contact-pagination" method="GET" action="<?php echo get_the_permalink(); ?>">
		<ul class="pagination">
			<?php  
				if($total_pages > 0){
					if($total_pages < 5){
						while($page_counter <= $total_pages){
			?>
							<li class="<?php if($page_num == $page_counter){ echo "active"; } ?>"><a href="#" data-page="<?php echo $page_counter; ?>"><?php echo $page_counter ?></a></li>
			<?php
							$page_counter++;
						}
					}else{

			?>				
						<?php if($page_num >= 2){ ?>
							<li><a data-page="<?php echo ($page_num - 1) ?>"><</a></li>
						<?php } ?>
						<li class="<?php if($page_num == 1){ echo "active"; } ?>"><a href="#" data-page="1">1</a></li>
						<?php if($page_num < $total_pages && $page_num < ($total_pages - 1)){ ?>
							<li class="<?php if($page_num == ($page_num + 1)){ echo "active"; } ?>"><a href="#" data-page="<?php echo ($page_num + 1); ?>"><?php echo ($page_num + 1); ?></a></li>
						<?php } ?>
						<input type="number" name="i" class="form-control" placeholder="page #"></input>
						<?php if(isset($search_string) && $search_string != null){ ?>
							<input type="hidden" name="search" value="<?php echo $search_string ?>">
						<?php } ?>
						<?php if($page_num < $total_pages){ ?>
							<li class="<?php if($page_num == ($total_pages - 1)){ echo "active"; } ?>"><a href="#" data-page="<?php echo ($total_pages - 1); ?>"><?php echo ($total_pages - 1); ?></a></li>
						<?php } ?>
						<li class="<?php if($page_num == $total_pages){ echo "active"; } ?>"><a href="#" data-page="<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
						<?php if($page_num < $total_pages){ ?> 
							<li><a href="#" data-page="<?php echo ($page_num + 1) ?>">></a></li>
						<?php } ?>
			<?php
					}
				}
			?>
		</ul>
		<button type="submit" style="display: none;">Submit</button>
	</form>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
	wp_die();
}

add_action("wp_ajax_export_contacts_csv", "export_contacts_csv");
add_action("wp_ajax_nopriv_export_contacts_csv", "export_contacts_csv");
function export_contacts_csv(){
	$list = array (
	    array('vin', 'bbb', 'ccc', 'dddd'),
	    array('123', '456', '789'),
	    array('"aaa"', '"bbb"')
	);

	$fp = fopen('file.csv', 'w');

	foreach ($list as $fields) {
	    fputcsv($fp, $fields);
	}

	fclose($fp);

	return $fp;
}