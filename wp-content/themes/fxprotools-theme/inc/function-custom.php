<?php
/**
 * ----------------------------
 * Fxprotools - Cusom Functions
 * ----------------------------
 * All custom functions
 */

defined('SKIP_PASSWORD_CHECKPOINT') || define('SKIP_PASSWORD_CHECKPOINT', false);
defined('ONBOARD_CHECKLIST_META_KEY') || define('ONBOARD_CHECKLIST_META_KEY','_onboard_checklist');

function get_user_checklist()
{
    $checklist = get_user_meta(get_current_user_id(), ONBOARD_CHECKLIST_META_KEY, true);
    return ( is_array($checklist) && count($checklist) === 7 ) ? $checklist : ( ( count($checklist) !== 7 ) ? update_user_checklist( get_current_user_id(), $checklist ) : register_user_checklist( get_current_user_id() ) );
}

function get_checklist_next_step_url()
{
    $checklist = get_user_checklist();
    foreach($checklist as $key => $value){
        if( empty($value) ){
            switch($key){
                case 'verified_email': return home_url() . '/verify-email/';
                case 'verified_profile': return home_url() . '/my-account/';
                case 'scheduled_webinar': return home_url() . '/coaching/';
                case 'accessed_products': return home_url() . '/access-products/';
                case 'got_shirt': return home_url() . '/free-shirt/';
                case 'shared_video': return home_url() . '/share-video/';
                case 'referred_friend': return home_url() . '/refer-a-friend/';
            }
        }
    }
    return '#';
}

function is_nav_locked(){
    $user = wp_get_current_user();
    return ( ( $user->user_login == "austinicomedez" || $user->user_login == "fxprotools" || is_user_fx_customer() || is_user_fx_distributor() ) & NAV_LOCK == true ) ? true : false ;
}

function get_locked_urls( $stage )
{
    // return allowed url depends stage level. 
    $_stage_1 = array( 'basic-training', 'market-signals', 'course', 'marketing', 'team', 'wallet', 'referral-program', 'compensation-plan', 'access-products' );
    $_stage_2 = array( 'team', 'wallet' );
    $_stage_3 = array();

    // $_stage_2 = array_merge( $_stage_2, $_stage_1 );
    // $_stage_3 = array_merge( $_stage_3, $_stage_1, $_stage_2 );
    
    switch ($stage) {
        case 1: return $_stage_1;
        case 2: return $_stage_2;
        case 3: return $_stage_3;
    }
}

add_action( 'template_redirect', 'is_page_locked' );
function is_page_locked()
{   
    if( is_nav_locked() ){
        // get current page slug.
        $_page_slug = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
        if($_page_slug){
            $_parent = get_post_ancestors( $_page_slug->post_id );
            $_page_slug = ( count($_parent) > 0 ) ? get_post($_parent[count($_parent)-1])->post_name : $_page_slug->post_name;

            // get user stage level.
            $_stage_lvl = get_user_stage_lvl();
            // fetch unlocked urls
            $_locked_urls = get_locked_urls($_stage_lvl);
            // redirect to dashboard 
            if ( array_search( $_page_slug, $_locked_urls ) !== false ) {
                wp_redirect(home_url() . '/dashboard/');
                exit;
            }
        }else{ return; }
        
    }
}

function get_user_stage_lvl()
{
    $_checklist = get_user_checklist();
    // $_checklist = [
    //     'verified_email' => true,
    //     'verified_profile' => true,
    //     'scheduled_webinar' => false,
    //     'accessed_products' => false,
    //     'got_shirt' => false,
    //     'shared_video' => false,
    //     'referred_friend' => false,
    // ];
    if( is_nav_locked() ){
        if( $_checklist['verified_email'] === true && $_checklist['verified_profile'] === true && $_checklist['scheduled_webinar'] === true && $_checklist['accessed_products'] === true && $_checklist['got_shirt'] === true  && $_checklist['shared_video'] === true && $_checklist['referred_friend'] === true ) {
            return 3;
        } elseif( $_checklist['verified_email'] === true && $_checklist['verified_profile'] === true && $_checklist['scheduled_webinar'] === true ) {
            return 2;
        } else {
            return 1;
        }
    }
}

function resend_email_verification()
{
    if( get_current_user_id() > 0){
        send_email_verification(get_current_user_id());
    }
}

function verify_email_address($verification_code)
{
    if( get_current_user_id() > 0)
    {
        // just get passed this step if debug is enabled
        if (WP_DEBUG) {
	        pass_onboarding_checklist( 'verified_email' );
	        return true;
        }
        $user = get_user_by('id', get_current_user_id() );
        $secret = "fxprotools-";
        $hash = MD5( $secret . $user->data->user_email);
        if($hash == $verification_code)
        {
	        pass_onboarding_checklist('verified_email');
            return true;
        } else{
            return false;
        }
    } else {
        return false;
    }
}

function pass_onboarding_checklist( $step ) {
	if ( ! empty ( $step ) ) {
		$checklist = get_user_checklist();
		$checklist[ $step ] = true;
		update_user_meta( get_current_user_id(), ONBOARD_CHECKLIST_META_KEY, $checklist );
	}
}

function random_checkout_time_elapsed(  $full = false)
{
    $now = new DateTime;
    $ago = new DateTime;
    $ago->modify("-" .  mt_rand(15, 3600) . " seconds");
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}



function get_customer_orders($user_id)
{
    $order_statuses = array('wc-on-hold', 'wc-processing', 'wc-completed', 'wc-pending', 'wc-cancelled', 'wc-refunded', 'wc-failed');
    $customer_user_id = $user_id;

    $customer_orders=get_posts( array(
            'meta_key' => '_customer_user',
            'meta_value' => $customer_user_id,
            'post_type' => 'shop_order',
            'post_status' => $order_statuses,
            'numberposts' => -1
    ) );
    return $customer_orders;
}

function get_order_columns()
{
    $my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
        'order-number'  => __( 'Order', 'woocommerce' ),
        'order-date'    => __( 'Date', 'woocommerce' ),
        'order-status'  => __( 'Status', 'woocommerce' ),
        'order-total'   => __( 'Total', 'woocommerce' ),
        'order-actions' => '&nbsp;',
    ) );
    return $my_orders_columns;
}

function get_purchased_items($user_id)
{
    $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() ),
    ) ) );
    return $customer_orders;
}




/* -------------------------
    Actions and Filters
 --------------------------*/

add_action('wp', 'enforce_page_access');
function enforce_page_access()
{
    global $post;
    if( !isset($post) ) return;
    $slug = $post->post_name;
    $guest_allowed_pages = ThemeSettings::GUEST_ALLOWED_PAGES;

    if( is_user_logged_in() ) {
	    // only allow 'password-checkpoint' to be accessed by imported users that hasn't updated their password yet
	    if ( is_page( 'password-checkpoint' ) && ! has_imported_user_update_password() ) {
		    return 0;
	    }
	    // if the page being visited is not for public, and the user hasn't changed their password yet
	    if ( ! in_array( $slug, array_merge($guest_allowed_pages, [ 'my-account', 'inbox' ]) ) && ! has_imported_user_update_password() ) {
		    wp_redirect( '/password-checkpoint' );
		    exit;
	    }
	    // if the page being accessed are for logged out user or for users that has not updated their password yet, go to dashboard
	    if ( is_page( 'log-out-notice' ) || ( is_page( 'password-checkpoint' ) && has_imported_user_update_password() ) ) {
		    wp_redirect('/dashboard');
		    exit;
	    }

	    if (is_page('access-products') && !get_user_meta(get_current_user_id(), '_skip_referral')) {
	        wp_redirect('/referral-program');
	        exit;
        }
        return 0;
    }
    if( !is_product() && !is_cart() && !is_checkout() && !is_shop() && !is_404() && !is_front_page() ) {
        if( !in_array($slug, $guest_allowed_pages) ){
	        $args = [ 'redirect_to' => $slug ];
	        if (!empty($_GET)) {
	            $args = array_merge($args, $_GET);
            }
	        wp_redirect( home_url() . '/login/?' . http_build_query( $args ) );
	        exit;
        }
    }
}

//add_action('wp', 'restirct_customer_access');
function restirct_customer_access()
{
    global $post;
    if( is_user_logged_in() && is_user_fx_customer() ) {
        if ( url_segment(1) == 'team' ) {
            wp_redirect( home_url() . '/dashboard' );
        }
        return 0;
    }
}

add_filter('login_redirect', 'customer_login_redirect');
function customer_login_redirect( $redirect_to){
	if ( ! empty( $redirect_to ) ) {
		if ( strpos( $redirect_to, home_url() ) !== false ) {
			return $redirect_to;
		}
		return home_url( $redirect_to );
	}
    return home_url('dashboard');
}

function has_imported_user_update_password( $user = null ) {
	if ( SKIP_PASSWORD_CHECKPOINT ) {
		return true;
	}

	if ( ! isset( $user ) ) {
		$user = wp_get_current_user();
	}
	$checkpoint_roles = [  ];
	foreach ( $checkpoint_roles as $checkpoint_role ) {
		if ( in_array( $checkpoint_role, (array) $user->roles ) ) {
			return get_user_meta( $user->ID, '_imported_user_password_changed', false );
		}
	}

	return true;
}

add_action('init', 'course_category_rewrite');
function course_category_rewrite()
{
    add_rewrite_rule('course-category/([^/]*)/?','index.php?category_slug=$matches[1]&course_category=1','top');
}

add_action('template_redirect', 'course_category_template');
function course_category_template()
{
    if ( get_query_var( 'category_slug' ) ) {
        add_filter( 'template_include', function() {
            return get_template_directory() . '/sfwd-course-category.php';
        });
    }
}

add_filter('query_vars', 'course_category_vars');
function course_category_vars( $vars )
{
    $vars[] = 'course_category';
    $vars[] = 'category_slug';
    return $vars;
}

add_action('user_register', 'register_user_checklist');
function register_user_checklist($user_id)
{
    $checklist = array(
        'verified_email' 	=> false,
        'verified_profile'	=> false,
        'scheduled_webinar'	=> false,
        'accessed_products' => false,
        'got_shirt'			=> false,
        'shared_video'		=> false,
        'referred_friend'	=> false,
    );
    add_user_meta( $user_id, ONBOARD_CHECKLIST_META_KEY, $checklist);
}

function update_user_checklist($user_id, $prev_checklist)
{
    $checklist = array(
        'verified_email' 	=> false,
        'verified_profile'	=> false,
        'scheduled_webinar'	=> false,
        'accessed_products' => false,
        'got_shirt'			=> false,
        'shared_video'		=> false,
        'referred_friend'	=> false,
    );
    $checklist = array_replace_recursive($checklist, $prev_checklist);
    update_user_meta( $user_id, ONBOARD_CHECKLIST_META_KEY, $checklist);
}

add_action('user_register', 'send_email_verification');
function send_email_verification($user_id)
{
    $user = get_user_by('id', $user_id);
    $secret = "fxprotools-";
    $hash = MD5( $secret . $user->data->user_email);
    $to =  $user->data->user_email;
    $subject = 'Please verify your Email Address';
    $message = "Click <a href='" . home_url() . '/verify-email/?code=' . $hash . "' target='_blank'>here</a> to verify your email address.";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $to, $subject, $message, $headers );
}

add_action('user_register', 'register_affiliate');
function register_affiliate($user_id)
{
    $data = array('user_id' => $user_id, 'notes' => 'affiliate added via fxprotools');
    $affiliate_id = affwp_add_affiliate($data);
}

add_action('affwp_notify_on_approval', 'disable_affiliate_welcome_email');
function disable_affiliate_welcome_email()
{
    return false;
}

add_action('wp', 'track_user_history');
function track_user_history()
{
    if( is_user_logged_in() ){
        //delete_user_meta(get_current_user_id(), "track_user_history");
        $track_user_history = get_user_meta( get_current_user_id(), "track_user_history", true );
        $track_user_history = $track_user_history  ? $track_user_history : array();
        $link = '<a href="'. get_the_permalink() .'">' . get_the_permalink() . '</a>';
        if( isset($_POST['user_login']) ){
            $link = $link . " " . get_the_author_meta('first_name', get_current_user_id()) . " " . get_the_author_meta('last_name', get_current_user_id()) . " changed his username to " . $_POST['user_login'];
        }
        $data = array(
            'time' => date("Y-m-d h:i:sa"),
            'link' => $link,
            'title' => get_the_title()
        );
        array_push($track_user_history, $data);
        update_user_meta(get_current_user_id(), 'track_user_history', $track_user_history);
    }

}

add_action( 'show_user_profile', 'add_extra_profile_fields' );
add_action( 'edit_user_profile', 'add_extra_profile_fields' );
function add_extra_profile_fields( $user ) { ?>
    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_sms_subs">SMS/Text Messaging</label></th>
            <td>
                <select id="user_sms_subs" name="user_sms_subs">
                    <option value="no" <?php if(get_the_author_meta( 'user_sms_subs', $user->ID ) == "no"){echo 'selected';} ?>>no</option>
                    <option value="yes" <?php if(get_the_author_meta( 'user_sms_subs', $user->ID ) == "yes"){echo 'selected';} ?>>yes</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="user_email_subs">Email Updates</label></th>
            <td>
                <select id="user_email_subs" name="user_email_subs">
                    <option value="no" <?php if(get_the_author_meta( 'user_email_subs', $user->ID ) == "no"){echo 'selected';} ?>>no</option>
                    <option value="yes" <?php if(get_the_author_meta( 'user_email_subs', $user->ID ) == "yes"){echo 'selected';} ?>>yes</option>
                </select>
            </td>
        </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );
function save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    update_usermeta( $user_id, 'user_sms_subs', $_POST['user_sms_subs'] );
    update_usermeta( $user_id, 'user_email_subs', $_POST['user_email_subs'] );
}

add_action("wp_ajax_check_valid_username", "check_valid_username");
add_action("wp_ajax_nopriv_check_valid_username", "check_valid_username");
function check_valid_username()
{
    $new_username = $_REQUEST['new_username'];
    if (validate_username($new_username) && !username_exists($new_username))
    {
        if(strlen($new_username) <= 30 && strlen($new_username) >= 3 && preg_match("/^([[:alnum:]])*$/", $new_username))
        {
            echo "1";
        }
        else
        {
            echo "2";
        }
    }
    else{
        if(!strlen($new_username) <= 30 || !strlen($new_username) >= 3 || !preg_match("/^([[:alnum:]])*$/", $new_username))
        {
            echo "2";
        }
        else
        {
            echo "0";
        }
    }

    wp_die();
}
function sess_start() {
    if (!session_id())
    session_start();
}
add_action('init','sess_start');

/**
 * Use to render metabox page template option #1
 * @param  string $page_element
 */

 // TODO: change the function name to get_mb_pto later on
function get_mb_pto1( $page_element, $pto = 'pto1' ) {
    switch ( $page_element ) {
        case 'main_header_menu':
            if( get_user_stage_lvl() === 1 )
                return mb_menu_display( $pto, rwmb_meta( $pto . '_display_main_header_menu'), get_term( 48 ), 'fx-nav-options', new Nav_Main_Stage_Header_Menu_Walker(), 'Main Header Menu', '' );
            elseif( get_user_stage_lvl() === 2 ) 
                return ( get_user_meta( get_current_user_id(), '_activate_stage_2_navs', true ) == 0 ) ? mb_menu_display( $pto, rwmb_meta( $pto . '_display_main_header_menu'), get_term( 60 ), 'fx-nav-options', new Nav_Main_Stage_Header_Menu_Walker(), 'Main Header Menu', '' ) : mb_menu_display( $pto, rwmb_meta( $pto . '_display_main_header_menu'), get_term( 51 ), 'fx-nav-options', new Nav_Main_Stage_Header_Menu_Walker(), 'Main Header Menu', '' );
            else
                return mb_menu_display( $pto, rwmb_meta( $pto . '_display_main_header_menu'), rwmb_meta( $pto . '_main_header_menu'), 'fx-nav-options', new Nav_Main_Header_Menu_Walker(), 'Main Header Menu', '' );
            break;
        case 'secondary_header_menu':
            if( get_user_stage_lvl() === 1 && ( is_page('dashboard') || is_page('referral-program') || is_page('compensation-plan') || is_page('compensation-plan') || is_page('access-products') ) )
                return mb_menu_display( $pto, rwmb_meta( $pto . '_display_header_menu'), get_term( 54 ), 'fx-nav-options', new Nav_Secondary_Stage_Header_Menu_Walker(), 'Dashboard Secondary Menu', '' );
            elseif( get_user_stage_lvl() === 2 && ( is_page('dashboard') || is_page('referral-program') || is_page('compensation-plan') || is_page('compensation-plan') || is_page('access-products') ) ){
                $_stage_2_nav = get_user_meta( get_current_user_id(), '_activate_stage_2_navs', true );
                return ( $_stage_2_nav == 0 ) ? mb_menu_display( $pto, rwmb_meta( $pto . '_display_header_menu'), get_term( 57 ), 'fx-nav-options', new Nav_Secondary_Header_Menu_Walker(), 'Dashboard Secondary Menu', '' ) : mb_menu_display( $pto, rwmb_meta( $pto . '_display_header_menu'), rwmb_meta( $pto . '_secondary_header_menu'), 'fx-nav-options', new Nav_Secondary_Header_Menu_Walker(), 'Dashboard Secondary Menu', '' ); ;
            }
            else
                return mb_menu_display( $pto, rwmb_meta( $pto . '_display_header_menu'), rwmb_meta( $pto . '_secondary_header_menu'), 'fx-nav-options', new Nav_Secondary_Header_Menu_Walker(), 'Dashboard Secondary Menu', '' );
                break;
        case 'footer_left_menu':
            return mb_menu_display( $pto, rwmb_meta( $pto . '_display_footer_menu'), rwmb_meta( $pto . '_footer_menu_fl'), 'footer-nav', '', 'Footer Menu 1', '' );
            break;
        case 'footer_middle_menu':
            return mb_menu_display( $pto, rwmb_meta( $pto . '_display_footer_menu'), rwmb_meta( $pto . '_footer_menu_mid'), 'footer-nav', '', 'Footer Menu 2', '' );
            break;
        case 'footer_right_menu':
            return mb_menu_display( $pto, rwmb_meta( $pto . '_display_footer_menu'), rwmb_meta( $pto . '_footer_menu_fr'), 'footer-nav', '', 'Footer Menu 3', 'with-log-inout' );
            break;
        case 'video_embed':
            if ( $pto == 'pto1' ) :
                $video_url = is_user_fx_customer() ? rwmb_meta( $pto . '_video_url_customer') : rwmb_meta( $pto . '_video_url_distributor') ;
            elseif ( $pto == 'pto2' || $pto == 'pto3' ) :
                $video_url = rwmb_meta( $pto . '_video_url');
            endif;
            $video_autostart        = rwmb_meta( $pto . '_video_autostart');
            $video_disable_controls = rwmb_meta( $pto . '_video_disable_controls');
            $video_disable_related  = rwmb_meta( $pto . '_video_disable_related');
            $video_hide_info        = rwmb_meta( $pto . '_video_hide_info');
            $video_disable_sharing  = rwmb_meta( $pto . '_video_disable_sharing');
            $scroll_class           = "";
            $scroll_url             = "";
            $float_class            = "";
            $default_yt_video       = "";

            if( count( is_mb_video_scroll( $pto, $video_url ) ) > 0 ){
                $arr_scroll     = is_mb_video_scroll( $pto, $video_url );
                $scroll_class   = ( !empty( rtrim($arr_scroll[0]) ) ) ? $arr_scroll[0] : '';
                $scroll_url     = ( !empty( rtrim($arr_scroll[1]) ) ) ? $arr_scroll[1] : '';
            }

            if( count( is_mb_video_float( $pto ) ) > 0 ){
                $arr_float      = is_mb_video_float( $pto );
                $float_class    = $arr_float[0];
            }

            $html = '<div class="fx-video-container" ' . $default_yt_video . ' id="' . $float_class . '" data-ptoaction="' . $scroll_class . '" data-ptoautostart="' . implode(' ', $video_autostart) . '" data-ptodisablecontrols="' . implode(' ', $video_disable_controls) . '" data-ptohideinfo="'. implode(' ', $video_hide_info) .'" data-ptodisablerelated="'. implode(' ', $video_disable_related) .'" data-ptourl="' . $scroll_url . '">';
            $html .= ( !empty($scroll_class) ) ? '' : wp_oembed_get($video_url) ;
            $html .= '</div>';
            
            return $html;
            
            break;
        default:
            # code...
            break;
    }
}


function is_mb_video_scroll( $pto = 'pto1', &$video ){
    $video_scrolling = implode( ' ', rwmb_meta( $pto . '_video_scrolling') );
    if( !empty( rtrim($video_scrolling) ) && $video_scrolling == 'yes' ) 
        return array('pto--scrolling-video', $video);
}

function is_mb_video_float( $pto = 'pto1' ){
    $video_floating = implode( ' ', rwmb_meta( $pto . '_video_floating') );
    if( !empty( rtrim($video_floating) ) && $video_floating == 'yes' )
        return array('pto--floating-video');
}

function mb_menu_display( &$pto, $display, $menu, $menu_class = '', $walker = '', $fallback, $location = '' ) {
    // menu fallback
    $menu_fb = $fallback;
    // check for menu display value
    if( !empty( rtrim( $display ) ) ){
        if( rtrim( $display ) == 'yes' ){
            if( $menu ){
                $term_id = $menu->term_id;
                $params = array(
                    'menu'            => $term_id,
                    'theme_location'  => '',
                    'container'       => false,
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_id'         => $term_id,
                    'menu_class'      => $menu_class . ' x' . $pto,
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'           => 0,
                    'walker'          => $walker,
                    'theme_location'  => $location,
                );
                return wp_nav_menu( $params );
            }else return wp_nav_menu( array('menu' => $menu_fb,'menu_class' => $menu_class, 'walker' => $walker ) );
        }else return;
    }
    // if menu display is default
    else{
        return wp_nav_menu( array('menu' => $menu_fb,'menu_class' => $menu_class . ' xpto_default', 'walker' => $walker ) );
    }
}

function get_mb_multi_pto( $page_element ) {
    ob_start();
    echo get_mb_pto1( $page_element, 'pto1' );
    $x_pto1 = ob_get_contents();
    ob_end_clean();

    ob_start();
    echo get_mb_pto1( $page_element, 'pto2' );
    $x_pto2 = ob_get_contents();
    ob_end_clean();

    ob_start();
    echo get_mb_pto1( $page_element, 'pto3' );
    $x_pto3 = ob_get_contents();
    ob_end_clean();

    // return $menu = strpos( $x_pto1, 'xpto1' ) ? $x_pto1 : ( strpos( $x_pto2, 'xpto2' ) ? $x_pto2 : ( strpos( $x_pto3, 'xpto3' ) ? $x_pto3 : $x_pto1 ) ) ;
    return $menu = ( strpos( $x_pto1, 'xpto1' ) || empty( $x_pto1 ) ) ? $x_pto1 : ( ( strpos( $x_pto2, 'xpto2' ) || empty( $x_pto2 ) )  ? $x_pto2 : ( ( strpos( $x_pto3, 'xpto3' ) || empty( $x_pto3 ) ) ? $x_pto3 : $x_pto1 ) ) ;
}

function get_sms_for_user($statuses, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $response = get_posts(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_sms',
        'meta_key'			=> '_user_' . $user_id . '_state',
        'meta_query'		=> array(
            array(
                'key'       => '_user_' . $user_id . '_state',
                'value'     => $statuses,
                'compare'   => 'IN',
            )
        )
    ));

    return $response;
}

function get_emails_for_user($statuses, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $response = get_posts(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_email',
        'meta_key'			=> '_user_' . $user_id . '_state',
        'meta_query'		=> array(
            array(
                'key'       => '_user_' . $user_id . '_state',
                'value'     => $statuses,
                'compare'   => 'IN',
            )
        )
    ));

    return $response;
}

function count_sms_for_user($statuses, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $response = new WP_Query(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_sms',
        'meta_key'			=> '_user_' . $user_id . '_state',
        'meta_query'		=> array(
            array(
                'key'       => '_user_' . $user_id . '_state',
                'value'     => $statuses,
                'compare'   => 'IN',
            )
        )
    ));

    return $response->found_posts;
}

function count_emails_for_user($statuses, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $response = new WP_Query(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_email',
        'meta_key'			=> '_user_' . $user_id . '_state',
        'meta_query'		=> array(
            array(
                'key'       => '_user_' . $user_id . '_state',
                'value'     => $statuses,
                'compare'   => 'IN',
            )
        )
    ));

    return $response->found_posts;
}

function count_sent_sms()
{
    $response = new WP_Query(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_sms',
        'post_status'       => 'publish'
    ));

    return $response->found_posts;
}

function count_sent_emails()
{
    $response = new WP_Query(array(
        'posts_per_page'	=> -1,
        'orderby'			=> 'modified',
        'order'				=> 'DESC',
        'post_type'			=> 'fx_email',
        'post_status'       => 'publish'
    ));

    return $response->found_posts;
}

function get_users_who_ordered($product_ids, $user_fields = array('user_email'))
{
    global $wpdb;
    $select = [];

    foreach ($user_fields as $field) {
        $select[] = 'users.' . $field . ' as ' . $field;
    }

    $select = implode(', ', $select);
    $ids = implode(',', $product_ids);

    $results = $wpdb->get_results($sql = "SELECT DISTINCT {$select}
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        INNER JOIN {$wpdb->prefix}postmeta as user_id ON user_id.post_id = order_items.order_id AND user_id.meta_key = '_customer_user'
        INNER JOIN {$wpdb->users} as users ON users.ID = user_id.meta_value
        INNER JOIN {$wpdb->posts} as posts ON posts.ID = order_items.order_id AND post_type = 'shop_order'
        WHERE order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ($ids)
    ");

    return $results;
}

function get_users_with_active_subscriptions($subscription_ids, $user_fields = array('user_email'))
{
    global $wpdb;
    $select = [];

    foreach ($user_fields as $field) {
        $select[] = 'users.' . $field . ' as ' . $field;
    }

    $select = implode(', ', $select);
    $ids = implode(',', $subscription_ids);

    $results = $wpdb->get_results($sql = "SELECT DISTINCT {$select}
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        INNER JOIN {$wpdb->prefix}postmeta as user_id ON user_id.post_id = order_items.order_id AND user_id.meta_key = '_customer_user'
        INNER JOIN {$wpdb->users} as users ON users.ID = user_id.meta_value
        INNER JOIN {$wpdb->posts} as posts ON posts.ID = order_items.order_id AND post_status = 'wc-active' AND post_type = 'shop_subscription'
        WHERE order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ($ids)
    ");

    return $results;
}

function user_unsubbed_from_sms($user_id) {
    return !!get_the_author_meta('sms_unsubbed', $user_id);
}

function user_unsubbed_from_list($user_id, $list_name) {
    global $wpdb;
    
    return $wpdb->get_var("SELECT unsub.meta_id as 'unsub_id'
        FROM {$wpdb->postmeta} unsub
        INNER JOIN {$wpdb->postmeta} list ON
            unsub.post_id = list.post_id AND
            list.meta_key = 'email_list'
        WHERE unsub.meta_key = '_user_{$user_id}_unsubscribe' AND
        list.meta_value = '{$list_name}'");
}

// redirect to custom login page instead of wordpress page
add_action('wp_login_failed', 'custom_redirect_login_failed');
function custom_redirect_login_failed($username) {
    $args = [
        'login' => 'failed',
        'username' => $username
    ];
	if ( isset( $_REQUEST['redirect_to'] ) ) {
		$args['redirect_to'] = urlencode( $_REQUEST['redirect_to'] );
	}
    wp_redirect(get_bloginfo('url') . '/login?' . http_build_query($args));
}

// redirects the user to dashboard if already logged in and went to /login
add_action( 'wp', 'check_if_logged_in' );
function check_if_logged_in() {
    if ( is_user_logged_in() && is_page('login' ) ) {
        wp_redirect( '/dashboard' );
        exit;
    }
}

// redirect to homepage after logging out
add_action('wp_logout','confirm_logout');
function confirm_logout(){
    wp_safe_redirect( '/log-out-notice' );
    exit();
}

// redirect to /login when accessing wp-login.php
add_action('init','redirect_to_login');
function redirect_to_login(){
    global $pagenow;
	if ( 'wp-login.php' == $pagenow && !isset($_GET['action'])  ) {
		if ( is_user_logged_in() ) {
			wp_redirect( '/dashboard' );
			exit();
		} else {
			// if not submitting login credentials
			if ( empty( $_POST ) ) {
				wp_redirect( '/login' );
				exit();
			}
		}
	}
}

// Button Shortcode
add_shortcode('fx-button', 'fx_shortcode_buton');
function fx_shortcode_buton($atts, $content = null)
{
    // Extract shortcode attributes
    extract(shortcode_atts(array(
        'url'    => '',
        'title'  => '',
        'target' => '',
        'text'   => '',
        'class'  => '',
    ), $atts ));

    $content = $text ? $text : $content;

    if($url){
        $link_attr = array(
            'href'   => esc_url( $url ),
            'title'  => esc_attr( $title ),
            'target' => ('blank' == $target) ? '_blank' : '',
            'class'  => 'btn btn-danger '.$class
        );
        $link_attrs_str = '';
        foreach($link_attr as $key => $val){
            if($val){
                $link_attrs_str .= ' '. $key .'="'. $val .'"';
            }
        }
        return '<a'.$link_attrs_str.'>'.do_shortcode($content).'</a>';
    } else {
        return '<a href="#" class="btn btn-danger">'.do_shortcode($content).'</a>';
    }
}

// redirect to custom logout confirmation page
add_action( 'login_form_logout', 'custom_logout_notice' );
function custom_logout_notice() {
    if ( ! is_user_logged_in() ) {
        wp_redirect( '/login' );
        exit();
    }
    wp_logout();
    exit();
}

/**
* Check if user has active subscription
* @see class Apyc_User
* @param	$user_id	integer		the user id, if its null we get the current user id loged in
* @return boolean	| 	Apyc_User method hasActiveSubscription()
**/
if ( ! function_exists('apyc_has_active_user_subscription')) {
    function apyc_has_active_user_subscription ($user_id = null)  {
        return Apyc_User::get_instance()->hasActiveSubscription($user_id);
    }
}

function user_membership_duration() {
	$today_obj = new DateTime( date( 'Y-m-d', strtotime( 'today' ) ) );
	$register_date = get_the_author_meta( 'user_registered', get_current_user_id() );
	$registered_obj = new DateTime( date( 'Y-m-d', strtotime( $register_date ) ) );
	$interval_obj = $today_obj->diff( $registered_obj );

	return $interval_obj->days;
}

function display_fx_gauge ($max_step, $step_taken = 0, $atts = []) {
    $default = [
        'gauge_base' => 237,
        'gauge_max' => 470,
        'fill' => '#03ae78'
    ];
	$args = wp_parse_args( $atts, $default );
	extract ($args);

	$step_taken = $step_taken<= $max_step ? $step_taken : $max_step;
	$average = ceil( ( $gauge_max - $gauge_base ) / $max_step );
	$angle = $gauge_base + ( $average * $step_taken );

	$html =<<<HTML
<svg id="meter" viewBox="0 0 217.36 118.8">
    <circle r="75" cx="50%%" cy="95%%" stroke="#DDD"
            stroke-width="60" fill="none"></circle>
    <circle r="75" cx="50%%" cy="95%%" stroke="%s"
            stroke-width="60" fill="none" stroke-dasharray="%s, 943"></circle>
    %s
    <g class="danger-dial-tuner" transform="rotate(126 108.93 111.42)" style="transform: rotate(%sdeg);transform-origin: 108.93px 111.42px 0px;">
        <path class="danger-dial-tuner__needle"
              d="M109.82,104.28l-1.6-.13h0c-18-1.25-55.68,7.26-55.68,7.26s37.66,8.51,55.69,7.26h0.16a7.3,7.3,0,0,0,1.45-.15c5.22-.4,7.16-7.12,7.16-7.12S115,104.67,109.82,104.28Z"
              transform="translate(0 0.01)"></path>
        <circle class="danger-dial-tuner__knob" cx="108.93" cy="111.42" r="4.1" style="fill: #fff;"></circle>
    </g>
</svg>
<span class="number">%s</span> of <span class="number">%s</span>
HTML;

    $cover_circle =<<<COVER
<circle r="75" cx="50%%" cy="94%%" stroke="#DDD"  stroke-width="60" fill="none" stroke-dasharray="%s, 943"></circle>
COVER;

	$svg = sprintf($html,
        $fill,
		$angle,
        sprintf($cover_circle, $step_taken > 0 ? 200 : 240),
		ceil( $step_taken / $max_step * 180 ),
		$step_taken,
		$max_step
    );

	return $svg;
}


add_filter( 'body_class','fx_user_role_class' );
function fx_user_role_class( $classes ) {

    if( is_user_fx_distributor() || current_user_can('administrator')){
        $classes[] = 'distributor';
    }

    if ( is_user_fx_customer() ){
        $classes[] = 'customer';
    }

    if(!current_user_can('administrator')){
        $classes[] = 'is-not-admin';
    }else{
        $classes[] = 'is-admin';
    }

    if( is_user_logged_in() ){
        $subscription = get_user_main_subscription();
        $subscription_product_id = $subscription['product_id'];
        if($subscription_product_id == 2699 || $subscription_product_id == 2928 || $subscription_product_id == 2927){
            $classes[] = 'is_signal_subscriber';
        }
    }

    return $classes;
}

function load_custom_wp_admin_page_css($hook) {
    if ( 'post.php' != $hook ) {
        return;
    }
    wp_enqueue_style( 'custom_wp_admin__page_css', get_template_directory_uri() . '/assets/css/admin/admin-page.css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_page_css' );

function load_custom_wp_admin_page_js($hook) {
    if ( 'post.php' != $hook ) {
        return;
    }
    wp_enqueue_script( 'custom_wp_admin_page_js', get_template_directory_uri() . '/assets/js/admin/admin-page.js' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_page_js' );

function restrict_customer_admin_access() {
    if ( ! current_user_can('administrator') && !defined( 'DOING_AJAX' ) ) {
        wp_redirect( home_url() . '/my-account' );
        exit;
    }
}
add_action( 'admin_init', 'restrict_customer_admin_access' );


/** Add referral/affiliate information into edit order page **/
// Display field value on the order edit page (not in custom fields metabox)
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    $my_custom_field = get_post_meta( $order->id, '_my_field_slug', true );

    global $wpdb;

    $result_affiliate_id = $wpdb->get_row( $wpdb->prepare(  "SELECT DISTINCT affiliate_id FROM {$wpdb->prefix}affiliate_wp_referrals WHERE reference = '%s' LIMIT 1;", $order->id ) );

    if ( ! empty( $result_affiliate_id ) ) {
    	$result_user_id = $wpdb->get_row( $wpdb->prepare(  "SELECT DISTINCT user_id FROM {$wpdb->prefix}affiliate_wp_affiliates WHERE affiliate_id = '%s' LIMIT 1;", $result_affiliate_id->affiliate_id ) );

    	$user_info = get_userdata($result_user_id->user_id);

        echo '<p><strong>'. __("Referring sponsor", "woocommerce").':</strong> <a href="/wp-admin/admin.php?page=affiliate-wp-referrals&affiliate_id=' . $result_affiliate_id->affiliate_id . '">' . $user_info->display_name . '</a></p>';
    }
}


/** Add custom fields to the webinar custom post type **/
function add_publish_meta_options($post_obj) {

  global $post;
  $post_type = 'fx_webinar'; // If you want a specific post type
  //$value = get_post_meta($post_obj->ID, 'check_meta', true); // If saving value to post_meta

  $values = get_post_custom( $post_obj->ID );
  $selected = isset( $values['webinar_type'] ) ? esc_attr( $values['webinar_type'][0] ) : '';

  if($post_type==$post->post_type) {
    echo  '<div class="misc-pub-section misc-pub-section-last">'
         .'<label for="webinar_type">Webinar Type: </label>
            <select name="webinar_type" id="webinar_type">
                <option value="gotowebinar" ' . selected( $selected, 'gotowebinar' ) . '>GoToWebinar</option>
                <option value="other" ' . selected( $selected, 'other' ) . '>Other</option>
                <option value="private" ' . selected( $selected, 'private' ) . '>Private Coaching</option>
            </select>'
         .'</div>';
  }
}

/*
 * Add the extra options to the 'Publish' box
 */
add_action('post_submitbox_misc_actions', 'add_publish_meta_options');


/** Save the data for the webinar type and other custom fields for webinar type **/
add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
         
    if( isset( $_POST['webinar_type'] ) )
        update_post_meta( $post_id, 'webinar_type', esc_attr( $_POST['webinar_type'] ) );
}
