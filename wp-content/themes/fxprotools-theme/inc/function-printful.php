<?php

require_once 'modules/printful/vendor/autoload.php';

use Printful\Exceptions\PrintfulApiException;
use Printful\Exceptions\PrintfulException;
use Printful\PrintfulApiClient;

if ( ! class_exists( 'CPS_Printful' ) ) {

	class CPS_Printful {
		const OPTION_WC_PRINTFUL_KEY = 'woocommerce_printful_settings';
		const PRINTFUL_API_ORDER_ENDPOINT = 'orders';

		/** @var string */
		private $api_key;
		/** @var PrintfulApiClient */
		private $http_client;

		public function __construct() {
			$printful_options = get_option( self::OPTION_WC_PRINTFUL_KEY );
			$this->api_key = $printful_options['printful_key'];
			$this->set_client( new PrintfulApiClient( $this->api_key ) );
		}

		public function get_order( $order_number, $params = [] ) {
			$path = sprintf( '%s/@%s', self::PRINTFUL_API_ORDER_ENDPOINT, $order_number );
			return $this->get( $path, $params );
		}

		private function get( $path, $params = [] ) {
			$result = false;
			try {
				$result = $this->http_client->get( $path, $params );
			} catch ( PrintfulApiException $e ) {
				error_log( 'Printful API Exception: ' . $e->getCode() . ' ' . $e->getMessage() );
			} catch ( PrintfulException $e ) {
				// API call failed
				error_log( $this->http_client->getLastResponseRaw() );
			}
			return $result;
		}

		/**
		 * Sets PrintfulApiClient client.
		 *
		 * @param PrintfulApiClient $client
		 */
		private function set_client( $client ) {
			$this->http_client = $client;
		}
	}
}

return new CPS_Printful();
