<?php
require_once('modules/intercom/intercom-php/vendor/autoload.php');

use Intercom\IntercomClient;
use Intercom\IntercomUsers;
use Intercom\IntercomLeads;
use Intercom\IntercomEvents;
use Intercom\Models\CPS_Intercom_Model;

use GuzzleHttp\Exception\GuzzleException;

class CPS_Intercom {

	const ACCESS_TOKEN = 'dG9rOmUxMzMyODcyX2UxMGRfNDZmOF84ZjM5XzY4MTc1MWJiNTBmNzoxOjA=';
	const SECRET_KEY = 'l_-sHsUYbgK3VTBs9AoKgG7kBc1fMAT7fnEgIt1A';
	const HASH = 'sha256';
	const INTERCOM_ID_USER_META = '_intercom_user_id';
	const EVENT_REGISTER_USER = 'register-user';
	const EVENT_REGISTER_LEAD = 'register-lead';
	const EVENT_UPDATE_PROFILE = 'update-profile';


	/** @var array */
	private $user_roles = [
		'administrator',
		'editor',
		'author',
		'contributor',
		'shop_manager',
		'group_leader',
		'business_admin',
		'business_director',
	];

	/** @var array */
	private $lead_roles = [
		'subscriber',
		'customer',
		'holding_member',
		'afl_member',
		'afl_customer',
	];

	/** @var IntercomClient */
	private $client;

	/**
	 * CPS_Intercom constructor.
	 */
	public function __construct() {
		$this->client = new IntercomClient( self::ACCESS_TOKEN, null );
		add_action( 'user_register', [ $this, 'intercom_add_user' ] );
		add_action( 'profile_update', [ $this, 'intercom_update_user' ] );
		add_action( 'delete_user', [ $this, 'intercom_delete_user' ] );
	}

	/**
	 * @param $user
	 *
	 * @return false|null|string
	 */
	public static function get_user_intercom_HMAC( $user ) {
		if ( $user ) {
			return hash_hmac(
				self::HASH, // hash function
				$user->user_email,
				self::SECRET_KEY
			);
		}
		return null;
	}

	// region User

	/**
	 * Creates an intercom account
	 *
	 * @param $user_id int
	 */
	public function intercom_add_user( $user_id ) {
		if ( ! empty( $_POST ) ) {
			/**
			 * @var $role string
			 */
			extract( $_POST );
			if ( ( ! empty( $role ) && in_array( $role, $this->user_roles ) ) || ! isset( $role ) ) {
				$user_data = $this->generate_data( 'user', $user_id );

				$intercomUser = $this->create_user( $user_data );
				if ( $intercomUser ) {
					add_user_meta( $user_id, self::INTERCOM_ID_USER_META, $intercomUser->id );
					$this->create_event( self::EVENT_REGISTER_USER, $user_id );
				}
			} elseif ( in_array( $role, $this->lead_roles ) ) {
				$lead_data = $this->generate_data( 'lead', $user_id );
				$intercomLead = $this->create_lead ($lead_data);
				if ( $intercomLead ) {
					add_user_meta( $user_id, self::INTERCOM_ID_USER_META, $intercomLead->id );
					$this->create_event( self::EVENT_REGISTER_LEAD, $user_id );
				}
			}
		}
	}

	/**
	 * @param $user_id int
	 */
	public function intercom_update_user( $user_id ) {
		$intercom_data = $this->generate_data( 'user', $user_id  );
		$intercomUser = $this->create_user( $intercom_data );

		if ( ! isset( $user_meta[ self::INTERCOM_ID_USER_META ] ) ) {
			add_user_meta( $user_id, self::INTERCOM_ID_USER_META, $intercomUser->id );
		}

		$this->create_event( self::EVENT_UPDATE_PROFILE, $user_id );
	}

	public function intercom_delete_user( $user_id ) {
		// use intercom ID instead of user ID to delete only those who have intercom account
		$intercom_user_id = get_user_meta( $user_id, self::INTERCOM_ID_USER_META, true );
		if ( ! empty( $intercom_user_id ) ) {
			$this->delete_user( $intercom_user_id );
		}
	}

	/**
	 * @param array $data
	 *
	 * @return bool|mixed
	 */
	private function create_user( array $data ) {
		$user = new IntercomUsers( $this->client );
		try {
			/** @var IntercomUsers */
			return $user->create( $data );
		} catch ( GuzzleException $e ) {
			error_log( $e->getMessage() );
		}
		return false;
	}

	/**
	 * @param $id
	 *
	 * @return IntercomUsers/void
	 */
	private function delete_user( $id ) {
		$user = new IntercomUsers( $this->client );
		try {
			/** @var IntercomUsers */
			return $user->deleteUser( $id );
		} catch ( GuzzleException $e ) {
			error_log( $e->getMessage() );
		}
	}

	// endregion

	// region Leads

	/**
	 * @param array $data
	 *
	 * @return bool|mixed
	 */
	private function create_lead( array $data ) {
		$lead = new IntercomLeads( $this->client );
		try {
			/** @var IntercomUsers */
			return $lead->create( $data );
		} catch ( GuzzleException $e ) {
			error_log( $e->getMessage() );
		}
		return false;
	}

	// endregion

	/**
	 * @param $event_name
	 * @param $user_id
	 */
	private function create_event( $event_name, $user_id ) {
		$event = new IntercomEvents( $this->client );
		try {
			$event->create( [
				CPS_Intercom_Model::KEY_EVENT_NAME => $event_name,
				CPS_Intercom_Model::KEY_CREATED_AT => strtotime( "now" ),
				CPS_Intercom_Model::KEY_USER_ID    => $user_id,
			] );
		} catch ( GuzzleException $e ) {
			error_log( $e->getMessage() );
		}
	}

	// region User Data

	/**
	 * @param string $type
	 * @param null $user_id
	 *
	 * @return array
	 */
	private function generate_data( $type, $user_id ) {
		$data = [];

		/**
		 * @var $email string
		 * @var $billing_email string
		 * @var $first_name string
		 * @var $billing_first_name string
		 * @var $last_name string
		 * @var $billing_last_name string
		 */
		extract( $_POST );

		$name = sprintf( '%s %s', $first_name ?: $billing_first_name, $last_name ?: $billing_last_name );
		switch ( $type ) {
			case 'user' :
				if ( ! empty( $_POST ) ) {
					$data = [
						CPS_Intercom_Model::KEY_EMAIL        => $email ?: $billing_email,
						CPS_Intercom_Model::KEY_USER_ID      => $user_id,
						CPS_Intercom_Model::KEY_NAME         => $name,
						CPS_Intercom_Model::KEY_SIGNED_UP_AT => strtotime( "now" ),
					];
				}
				break;
			case 'lead':
				if ( ! empty( $_POST ) ) {
					$data = [
						CPS_Intercom_Model::KEY_EMAIL => $email ?: $billing_email,
						CPS_Intercom_Model::KEY_NAME  => $name,
					];
				}
				break;
		}

		$user_meta = $this->flatten_user_meta( $user_id );
		$user_onboard_checklist = $this->get_onboard_checklist( $user_id );
		$user_info = array_merge( (array) $data, $user_meta, $user_onboard_checklist );
		return $this->arrange_intercom_data( $user_info );
	}

	private function flatten_user_meta( $user_id ) {
		$user_meta = get_user_meta( $user_id );
		$data = [];
		foreach ( $user_meta as $meta_key => $value ) {
			$data[ $meta_key ] = $value[0];
		}

		return $data;
	}

	/**
	 * @param $user_id
	 *
	 * @return string Product Name if exists, otherwise empty string
	 */
	private function get_active_subscription( $user_id ) {
		/** @var WC_Subscription $subscriptions */
		$subscriptions = wcs_get_users_subscriptions( $user_id );

		/** @var WC_Subscription $subscription */
		foreach ( $subscriptions as $subscription ) {
			if ( $subscription->has_status( 'active' ) ) {
				$items = $subscription->get_items();

				/**
				 * @var WC_Order_Item_Product $item
				 */
				foreach ( $items as $item ) {
					$product = wc_get_product( $item->get_product_id() );

					if ( $product ) {
						// return the first one
						return $product->get_name();
					}
				}
			}
		}
		return '';
	}

	private function arrange_intercom_data( $data ) {
		$phone = '';
		if ( ! empty( $data['phone_number'] ) ) {
			$phone = $data['phone_number'];
		} elseif ( ! empty( $data['billing_phone'] ) ) {
			$phone = $data['billing_phone'];
		}
		return [
			CPS_Intercom_Model::KEY_USER_ID           => $data['ID'],
			CPS_Intercom_Model::KEY_EMAIL             => $data['user_email'],
			CPS_Intercom_Model::KEY_NAME              => CPS_Intercom_Model::get_name( $data ),
			CPS_Intercom_Model::KEY_PHONE             => $phone,
			CPS_Intercom_Model::KEY_SIGNED_UP_AT      => strtotime( $data['user_registered'] ),
			CPS_Intercom_Model::KEY_LAST_SEEN_IP      => $this->get_real_IP(),
			CPS_Intercom_Model::KEY_CUSTOM_ATTRIBUTES => $this->get_custom_attributes( $data ),
		];
	}

	private function get_custom_attributes( array $data ) {
		return [
			CPS_Intercom_Model::KEY_ID                          => $this->set_empty_or_value( $data, 'ID' ),
			CPS_Intercom_Model::KEY_UID                         => $this->get_uid( $data ),
			CPS_Intercom_Model::KEY_ACTIVE_SUBSCRIPTION         => $this->get_active_subscription( $data['ID'] ),
			CPS_Intercom_Model::KEY_NICKNAME                    => $this->set_empty_or_value( $data, 'nickname' ),
			CPS_Intercom_Model::KEY_FIRST_NAME                  => $this->set_empty_or_value( $data, 'first_name' ),
			CPS_Intercom_Model::KEY_LAST_NAME                   => $this->set_empty_or_value( $data, 'last_name' ),
			CPS_Intercom_Model::KEY_USER_SMS_SUBS               => $this->set_empty_or_value( $data, 'user_sms_subs' ),
			CPS_Intercom_Model::KEY_USER_EMAIL_SUBS             => $this->set_empty_or_value( $data, 'user_email_subs' ),
			CPS_Intercom_Model::KEY_BILLING_COMPANY             => $this->set_empty_or_value( $data, 'billing_company' ),
			CPS_Intercom_Model::KEY_BILLING_ADDRESS_1           => $this->set_empty_or_value( $data, 'billing_address_1' ),
			CPS_Intercom_Model::KEY_BILLING_ADDRESS_2           => $this->set_empty_or_value( $data, 'billing_address_2' ),
			CPS_Intercom_Model::KEY_BILLING_CITY                => $this->set_empty_or_value( $data, 'billing_city' ),
			CPS_Intercom_Model::KEY_BILLING_STATE               => $this->set_empty_or_value( $data, 'billing_state' ),
			CPS_Intercom_Model::KEY_BILLING_POSTCODE            => $this->set_empty_or_value( $data, 'billing_postcode' ),
			CPS_Intercom_Model::KEY_SHIPPING_COMPANY            => $this->set_empty_or_value( $data, 'shipping_company' ),
			CPS_Intercom_Model::KEY_SHIPPING_ADDRESS_1          => $this->set_empty_or_value( $data, 'shipping_address_1' ),
			CPS_Intercom_Model::KEY_SHIPPING_ADDRESS_2          => $this->set_empty_or_value( $data, 'shipping_address_2' ),
			CPS_Intercom_Model::KEY_SHIPPING_CITY               => $this->set_empty_or_value( $data, 'shipping_city' ),
			CPS_Intercom_Model::KEY_SHIPPING_STATE              => $this->set_empty_or_value( $data, 'shipping_state' ),
			CPS_Intercom_Model::KEY_SHIPPING_POSTCODE           => $this->set_empty_or_value( $data, 'shipping_postcode' ),
			CPS_Intercom_Model::KEY_WEBSITE                     => $this->set_empty_or_value( $data, 'website' ),
			CPS_Intercom_Model::KEY_FACEBOOK                    => $this->set_empty_or_value( $data, 'facebook' ),
			CPS_Intercom_Model::KEY_TWITTER                     => $this->set_empty_or_value( $data, 'twitter' ),
			CPS_Intercom_Model::KEY_GOOGLEPLUS                  => $this->set_empty_or_value( $data, 'googleplus' ),
			CPS_Intercom_Model::KEY_CHECKLIST_VERIFIED_EMAIL    => $this->set_empty_or_value( $data, 'verified_email' ),
			CPS_Intercom_Model::KEY_CHECKLIST_VERIFIED_PROFILE  => $this->set_empty_or_value( $data, 'verified_profile' ),
			CPS_Intercom_Model::KEY_CHECKLIST_SCHEDULED_WEBINAR => $this->set_empty_or_value( $data, 'scheduled_webinar' ),
			CPS_Intercom_Model::KEY_CHECKLIST_ACCESSED_PRODUCTS => $this->set_empty_or_value( $data, 'accessed_products' ),
			CPS_Intercom_Model::KEY_CHECKLIST_GOT_SHIRT         => $this->set_empty_or_value( $data, 'got_shirt' ),
			CPS_Intercom_Model::KEY_CHECKLIST_SHARED_VIDEO      => $this->set_empty_or_value( $data, 'shared_video' ),
			CPS_Intercom_Model::KEY_CHECKLIST_REFERRED_FRIEND   => $this->set_empty_or_value( $data, 'referred_friend' ),
		];
	}

	private function get_uid( $data ) {
		$args = [ CPS_Intercom_Model::KEY_UID => $data['ID'] ];
		return sprintf( CPS_Intercom_Model::UID_TEMPLATE, home_url( CPS_Intercom_Model::INTERCOM_SWITCH_PAGE ), http_build_query( $args ) );
	}

	private function get_onboard_checklist( $user_id ) {
		return get_user_meta( $user_id, ONBOARD_CHECKLIST_META_KEY, true );
	}

	private function get_real_IP() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )   //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )   //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	private function set_empty_or_value( $data, $index, $default = '' ) {
		if ( isset( $data[ $index ] ) ) {
			return $data[ $index ];
		}
		return $default;
	}

	// endregion
}

return new CPS_Intercom();
