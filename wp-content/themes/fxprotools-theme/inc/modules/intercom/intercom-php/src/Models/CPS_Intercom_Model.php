<?php
namespace Intercom\Models;

if(!class_exists('CPS_Intercom_Model')){
	class CPS_Intercom_Model {
		const KEY_ID = 'ID';
		const KEY_USER_ID = 'user_id';
		const KEY_EMAIL = 'email';
		const KEY_NAME = 'name';
		const KEY_PHONE = 'phone';
		const KEY_SIGNED_UP_AT = 'signed_up_at';
		const KEY_LAST_SEEN_IP = 'last_seen_ip';
		const KEY_CUSTOM_ATTRIBUTES = 'custom_attributes';
		const KEY_UID = 'uid';
		const KEY_NICKNAME = 'nickname';
		const KEY_FIRST_NAME = 'first_name';
		const KEY_LAST_NAME = 'last_name';
		const KEY_USER_SMS_SUBS = 'user_sms_subs';
		const KEY_USER_EMAIL_SUBS = 'user_email_subs';
		const KEY_BILLING_COMPANY = 'billing_company';
		const KEY_BILLING_ADDRESS_1 = 'billing_address_1';
		const KEY_BILLING_ADDRESS_2 = 'billing_address_2';
		const KEY_BILLING_CITY = 'billing_city';
		const KEY_BILLING_STATE = 'billing_state';
		const KEY_BILLING_POSTCODE = 'billing_postcode';
		const KEY_SHIPPING_COMPANY = 'shipping_company';
		const KEY_SHIPPING_ADDRESS_1 = 'shipping_address_1';
		const KEY_SHIPPING_ADDRESS_2 = 'shipping_address_2';
		const KEY_SHIPPING_CITY = 'shipping_city';
		const KEY_SHIPPING_STATE = 'shipping_state';
		const KEY_SHIPPING_POSTCODE = 'shipping_postcode';
		const KEY_WEBSITE = 'website';
		const KEY_FACEBOOK = 'facebook';
		const KEY_TWITTER = 'twitter';
		const KEY_GOOGLEPLUS = 'googleplus';
		const KEY_CHECKLIST_VERIFIED_EMAIL = 'checklist_verified_email';
		const KEY_CHECKLIST_VERIFIED_PROFILE = 'checklist_verified_profile';
		const KEY_CHECKLIST_SCHEDULED_WEBINAR = 'checklist_scheduled_webinar';
		const KEY_CHECKLIST_ACCESSED_PRODUCTS = 'checklist_accessed_products';
		const KEY_CHECKLIST_GOT_SHIRT = 'checklist_got_shirt';
		const KEY_CHECKLIST_SHARED_VIDEO = 'checklist_shared_video';
		const KEY_CHECKLIST_REFERRED_FRIEND = 'checklist_referred_friend';
		const KEY_EVENT_NAME = 'event_name';
		const KEY_CREATED_AT = 'created_at';
		const KEY_ACTIVE_SUBSCRIPTION = 'subscription';
		const UID_TEMPLATE = '%s?%s';
		const INTERCOM_SWITCH_PAGE = '/intercom-switch';

		public static function get_name(array $data) {
			return sprintf( '%s %s', $data[self::KEY_FIRST_NAME], $data[self::KEY_LAST_NAME] );
		}

		public static function get_uid( $data ) {
			$args = [ self::KEY_UID => $data[self::KEY_ID] ];
			return sprintf( self::UID_TEMPLATE, home_url( self::INTERCOM_SWITCH_PAGE ), http_build_query( $args ) );
		}
	}
}
