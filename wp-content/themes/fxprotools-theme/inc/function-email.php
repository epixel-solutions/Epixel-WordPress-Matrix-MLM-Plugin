<?php
function queue_admin_email_scripts($hook) {
    global $post;
    
    // Prevent re-publishing of emails.
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('fx_email' === $post->post_type) {
            wp_enqueue_script('email-script', get_stylesheet_directory_uri().'/assets/js/admin/admin-email.js');
        }
    }
}

function before_email_published($newStatus, $oldStatus) {
    global $post;
    
    // Prevent re-publishing of emails.
    if ($oldStatus === 'publish' && $post->post_type === 'fx_email') {
        wp_die('You cannot re-send or modify and already sent email.');
    }
}

function post_email_published($id) {
    $post = get_post($id);
    
    if ($post->post_type === 'fx_email') {
        // Check the recipient type.
        $recipientType = get_post_meta($id, 'email_recipient_type')[0];
        $personalizations = [];
        $user_ids = [];
        $listType = null;
        
        switch ($recipientType)
        {
            case 'all':
                $listType = 'all';
                
                // Retrieve all users.
                $query = new WP_User_Query(array('fields' => array('ID', 'user_email', 'display_name'), 'orderby' => 'display_name', 'order' => 'ASC'));
                $users = $query->get_results();
                
                // Add each user to the emails list.
                foreach ($users as $user) {
                    if (isset($user->user_email) && $user->user_email && !user_unsubbed_from_list($user->ID, $listType)) {
                        $personalizations[] = array(
                            'to' => array(array(
                                'email' => $user->user_email,
                                'name' => $user->display_name
                            ))
                        );
                    }
                    
                    if (!user_unsubbed_from_list($user->ID, $listType)) {
                        $user_ids[] = $user->ID;
                    }
                }
                break;
            case 'individual':
                // Check what kind of individual.
                $individualType = get_post_meta($post->ID, 'recipient_individual_type')[0];
                
                switch ($individualType) {
                    case 'email':
                        // Name and email provided.
                        $individualName = get_post_meta($post->ID, 'recipient_individual_name')[0];
                        $individualEmail = get_post_meta($post->ID, 'recipient_individual_email')[0];
                        
                        if ($individualEmail) {
                            $personalizations[] = array(
                                'to' => array(array(
                                    'email' => $individualEmail,
                                    'name' => $individualName ? $individualName : $individualEmail
                                ))
                            );
                        }
                        break;
                    case 'user':
                        // WP user selected.
                        $userId = get_post_meta($post->ID, 'recipient_individual_user')[0];
                        $user = get_userdata($userId);
                        
                        if ($user->user_email) {
                            $personalizations[] = array(
                                'to' => array(array(
                                    'email' => $user->user_email,
                                    'name' => $user->display_name
                                ))
                            );
                        }
                    
                        $user_ids[] = $userId;
                        break;
                }
                break;
            case 'product':
                // Check what product.
                $productId = get_post_meta($post->ID, 'recipient_product')[0];
                $listType = 'prod-' . $productId;
                
                // Get orderers of this product.
                $users = get_users_who_ordered(array($productId), array('ID', 'user_email', 'display_name'));
                
                // Add each user to the emails list.
                foreach ($users as $user) {
                    if (isset($user->user_email) && $user->user_email && !user_unsubbed_from_list($user->ID, $listType)) {
                        $personalizations[] = array(
                            'to' => array(array(
                                'email' => $user->user_email,
                                'name' => $user->display_name
                            ))
                        );
                    }
                    
                    if (!user_unsubbed_from_list($user->ID, $listType)) {
                        $user_ids[] = $user->ID;
                    }
                }
                break;
            case 'group':
                // Check the group type.
                $groupType = get_post_meta($post->ID, 'recipient_group')[0];
                
                $query = new WP_User_Query(array('fields' => array('user_email', 'display_name'), 'orderby' => 'display_name', 'order' => 'ASC'));
                $subTypes = array();
                
                switch ($groupType) {
                    case 'customer':
                        $subTypes = fx_customer_subscription_products();
                        update_post_meta($post->ID, 'email_list', 'customer');
                        $listType = 'customer';
                        break;
                    case 'distributor':
                        $subTypes = fx_distributor_subscription_products();
                        $listType = 'distributor';
                        break;
                    case 'both':
                        $subTypes = array_merge(fx_customer_subscription_products(), fx_distributor_subscription_products());
                        $listType = 'customer_distributor';
                        break;
                }
                
                $users = get_users_with_active_subscriptions($subTypes, array('ID', 'user_email', 'display_name'));
                
                // Add each user to the emails list.
                foreach ($users as $user) {
                    if (isset($user->user_email) && $user->user_email && !user_unsubbed_from_list($user->ID, $listType)) {
                        $personalizations[] = array(
                            'to' => array(array(
                                'email' => $user->user_email,
                                'name' => $user->display_name
                            ))
                        );
                    }
                    
                    if (!user_unsubbed_from_list($user->ID, $listType)) {
                        $user_ids[] = $user->ID;
                    }
                }
                break;
        }
        
        if (count($personalizations) > 0) {
            $sendGrid = new \FX_Sendgrid_Api();
            $result = $sendGrid->send_to_many($personalizations, $post->post_title, get_post_meta($post->ID, 'email_content')[0], array('wpemail-id-' . $id));
            
            if ($result['status_code'] != 202) {
                wp_update_post(array(
                    'ID' => $id,
                    'post_status' => 'draft'
                ));
                
                wp_die('Failed to send email: (' . $result['status_code'] . ') ' . $result['body']);
            }
        }
        
        foreach ($user_ids as $userid) {
            update_post_meta($id, '_user_' . $userid . '_state', 'unread');
        }
    }
}

function email_grid_columns($columns) {
    $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Subject' ),
    'date' => __( 'Date' )
    );
    
    return $columns;
}

add_action('admin_enqueue_scripts', 'queue_admin_email_scripts', 10, 1);
add_action('transition_post_status', 'before_email_published', 10, 2);
add_action('rwmb_after_save_post', 'post_email_published', 10, 3);
add_filter('manage_edit-fx_email_columns', 'email_grid_columns');

add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}