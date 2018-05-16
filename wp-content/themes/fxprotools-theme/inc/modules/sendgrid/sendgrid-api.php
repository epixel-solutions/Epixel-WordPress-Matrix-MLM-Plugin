<?php
require ABSPATH . 'vendor/autoload.php';
if(!class_exists('FX_Sendgrid_Api')){
	class FX_Sendgrid_Api {
		const SENDGRID_API_KEY = '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';
		const SENDGRID_FXPROTOOLS_LIST_ID = '2027971';
		
		public function __construct()
		{
			
		}	
		// Get all contacts of a list
		public function get_contacts($list_id = self::SENDGRID_FXPROTOOLS_LIST_ID)
		{
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			$query_params = json_decode('{"page": 1, "page_size": 1, }');
			$response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->get(null, $query_params);
			return $response->body();
		}
		static function search_contacts( $funnel_id, $user_id )
		{
			$query_params = json_decode('{"campaign": "' . $funnel_id .'", "affiliate_user_id" : "'.$user_id.'"}');
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			$response =  $sg->client->contactdb()->recipients()->search()->get(null, $query_params);
			return json_decode( $response->body() );
		}
		public function add_recipient( $recipient = array() ){
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			$request_body = array( (object) $recipient );
			$response = $sg->client->contactdb()->recipients()->post($request_body);
			$response_body = json_decode($response->body());
			$recipient_id = $response_body->persisted_recipients[0];
			return $recipient_id;
		}
		public function add_recipient_to_list($recipient_id = '', $list_id = self::SENDGRID_FXPROTOOLS_LIST_ID)
		{
			if(!$recipient_id) return;
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			$request_body = array( $recipient_id );
			$response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->post($request_body);
			return $response->statusCode();
		}
		
		public function send_to_many($personalizations, $subject, $content, $categories)
		{
			if (!is_array($personalizations) || !$content || !$subject) return;
			
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			$request_body = array(
				'personalizations' => $personalizations,
				'subject' => $subject,
				'from' => array(
					'email' => get_option("email_settings")['email_from_address'],
					'name' => get_option("email_settings")['email_from_name']
				),
				'content' => array(array(
					'type' => 'text/html',
					'value' => $content
				)),
				'categories' => $categories
			);
			
			$response = $sg->client->mail()->send()->post($request_body);
			
			return array(
				'status_code' => $response->statusCode(),
				'body' => $response->body()
			);
		}
		
		public function get_stats_for_category($category, $startDate)
		{
			$sg = new \SendGrid( self::SENDGRID_API_KEY );
			
			$response = $sg->client->categories()->stats()->get(null, array(
				'start_date' => $startDate,
				'categories' => $category
			));
			
			return $response->body();
		}
	}
}
//$recipient = array('email' => 'user'.rand().'@gmail.com', "first_name" => 'test', 'last_name' => 'test', 'campaign' => '123' );
//$recipient_id = FX_Sendgrid_Api::add_recipient($recipient);
//dd( FX_Sendgrid_Api::search_contacts('campaign', 'f1' ) );