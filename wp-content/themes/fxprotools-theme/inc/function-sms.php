<?php
use Twilio\Rest\Client;

function queue_admin_sms_scripts($hook) {
    global $post;
    
    // Prevent re-publishing of SMSs.
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('fx_sms' === $post->post_type) {
            wp_enqueue_script('email-script', get_stylesheet_directory_uri().'/assets/js/admin/admin-email.js');
        }
    }
}

function before_sms_published($newStatus, $oldStatus) {
    global $post;
    
    // Prevent re-publishing of emails.
    if ($oldStatus === 'publish' && $post->post_type === 'fx_sms') {
        wp_die('You cannot re-send or modify and already sent SMS.');
    }
}

function post_sms_published($id) {
    $post = get_post($id);
    
    if ($post->post_type === 'fx_sms') {
        // Check the recipient type.
        $recipientType = get_post_meta($id, 'sms_recipient_type')[0];
        $phones = [];
        $user_ids = [];
        $listType = null;
        
        switch ($recipientType)
        {
            case 'all':
                $listType = 'all';
                
                // Retrieve all users.
                $query = new WP_User_Query(array('fields' => array('ID'), 'orderby' => 'display_name', 'order' => 'ASC'));
                $users = $query->get_results();
                
                // Add each user to the emails list.
                foreach ($users as $user) {
                    $phone = get_the_author_meta('phone_number', $user->ID);
                    
                    if ($phone && !user_unsubbed_from_sms($user->ID)) {
                        $phones[] = [
                            'phone' => $phone,
                            'user' => $user->ID
                        ];
                    }
                    
                    if (!user_unsubbed_from_sms($user->ID)) {
                        $user_ids[] = $user->ID;
                    }
                }
                break;
            case 'individual':
                // Check what kind of individual.
                $individualType = get_post_meta($post->ID, 'recipient_individual_type')[0];
                
                switch ($individualType) {
                    case 'email':
                    case 'sms':
                        // SMS provided.
                        $individualSms = get_post_meta($post->ID, 'recipient_individual_sms')[0];
                        
                        if ($individualSms) {
                            $phones[] = [
                                'phone' => $individualSms,
                                'user' => 0
                            ];
                        }
                        break;
                    case 'user':
                        // WP user selected.
                        $userId = get_post_meta($post->ID, 'recipient_individual_user')[0];
                        $user = get_userdata($userId);
                        
                        $phone = get_the_author_meta('phone_number', $userId);
                        
                        if ($phone && !user_unsubbed_from_sms($userId)) {
                            $phones[] = [
                                'phone' => $phone,
                                'user' => $userId
                            ];
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
                    $phone = get_the_author_meta('phone_number', $user->ID);
                    
                    if ($phone && !user_unsubbed_from_sms($user->ID)) {
                        $phones[] = [
                            'phone' => $phone,
                            'user' => $user->ID
                        ];
                    }
                    
                    if (!user_unsubbed_from_sms($user->ID)) {
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
                    $phone = get_the_author_meta('phone_number', $user->ID);
                    
                    if ($phone && !user_unsubbed_from_sms($user->ID)) {
                        $phones[] = [
                            'phone' => $phone,
                            'user' => $user->ID
                        ];
                    }
                    
                    if (!user_unsubbed_from_sms($user->ID)) {
                        $user_ids[] = $user->ID;
                    }
                }
                break;
        }
        
        $twilio = new SendTwilio();
        
        if (count($phones) > 0) {
            foreach ($phones as $phone) {
                $sid = $twilio->send_sms($phone['phone'], get_post_meta($post->ID, 'sms_content')[0]);
                $userid = $phone['user'];
                
                if ($userid > 0) {
                    update_post_meta($id, '_' . $sid . '_user', $userid);
                }
                
                if ($userid > 0 && $sid == 'NULL') {
                    update_post_meta($id, '_user_' . $userid . '_bounce', true);
                }
            }
        }
        
        foreach ($user_ids as $userid) {
            update_post_meta($id, '_user_' . $userid . '_state', 'unread');
        }
    }
}

function sms_grid_columns($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'content' => __( 'SMS Content' ),
        'date' => __( 'Date' )
    );
    
    unset($columns['title']);
    return $columns;
}

function sms_custom_columns($column, $post_id) {
    switch ($column) {
        case 'content':
            ?>
            <a href="<?php echo get_edit_post_link($post_id); ?>"><?php echo sanitize_text_field(rwmb_meta('sms_content', null, $post_id)); ?></a>
            <?php
            break;
    }
}

class SendTwilio {
	protected $sid = TWILIO_ACCOUNT_SID;
	protected $token = TWILIO_TOKEN;
	
	public function send_sms($number, $content) {
		$client = new Client($this->sid, $this->token);
		$services = $client->messaging->v1->services->read();
		
		if ($services) {
			foreach ($services as $service) {
				$service_array = array(
					'sid' => $service->sid
				);
			}
		}
		
		try {
    		if (isset($service_array['sid'])) {
    			$phoneNumbers = $client->messaging->v1->services($service_array['sid'])->phoneNumbers->read();
    			
    			if ($phoneNumbers) {
    				foreach($phoneNumbers as $phoneNumber){
    					$phone_numbers_array = array(
    						'from_phone_number' => $phoneNumber->phoneNumber
    					);
    				}
    			}
    			
    			$from = $phone_numbers_array['from_phone_number'];
    		}
    		
    		$msg = substr($content, 0, 1600);
    		
    		$message = $client->messages->create(
    		  $number, // Text this number
    		  array(
    			'from' => $from, // From a valid Twilio number
    			'body' => $msg,
    			'statusCallback' => admin_url('admin-ajax.php?action=twilio_callback')
    		  )
    		);
    		
    		return $message->sid;
		} catch (\Exception $e) {
		    return 'NULL';
		}
	}
}

add_action('admin_enqueue_scripts', 'queue_admin_sms_scripts', 10, 1);
add_action('transition_post_status', 'before_sms_published', 10, 2);
add_action('rwmb_after_save_post', 'post_sms_published', 10, 3);
add_filter('manage_edit-fx_sms_columns', 'sms_grid_columns');
add_filter('manage_fx_sms_posts_custom_column', 'sms_custom_columns', 10, 2);