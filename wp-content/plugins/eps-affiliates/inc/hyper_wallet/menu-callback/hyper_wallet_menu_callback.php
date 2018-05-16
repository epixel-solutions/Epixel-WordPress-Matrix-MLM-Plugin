<?php

function afl_admin_hyper_wallet() {
		new Afl_enque_scripts('common');


		echo afl_eps_page_header();
		afl_content_wrapper_begin();
			// pr('---Code Here----');

			afl_hyper_wallet_list_users();
			
		afl_content_wrapper_end();
}

function afl_hyper_wallet_list_users() {

	$eps_hyper_wallet = new EpsHyperWallet();

}


function afl_hyper_wallet_config_settings() {
	// pr('here');
		echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_hyper_wallet_config_settings_form();
	afl_content_wrapper_end();
}

function afl_hyper_wallet_config_settings_form() {
	new Afl_enque_scripts('common');

	if ( isset($_POST['submit']) ) {
	 	$validation = afl_hyper_wallet_config_settings_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_hyper_wallet_config_settings_form_submit($_POST);
	 	}
	 }

	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	
 	$form['hyper_wallet_program_token'] = array(
 		'#type' 					=> 'text',
 		'#title' 					=> 'Program Token',
 		'#required'				=> TRUE,
 		'#default_value' 	=> afl_variable_get('hyper_wallet_program_token',''),
 		'#prefix'					=> '<div class="form-group row">',
 		'#suffix' 				=> '</div>'
 	);

 	$form['hyper_wallet_username'] = array(
 		'#type' 					=> 'text',
 		'#title' 					=> 'API Username',
 		'#required'				=> TRUE,
 		'#default_value' 	=> afl_variable_get('hyper_wallet_username',''),
 		'#prefix'					=> '<div class="form-group row">',
 		'#suffix' 				=> '</div>'
 	);


 	$form['hyper_wallet_password'] = array(
 		'#type' 					=> 'text',
 		'#title' 					=> 'API Password',
 		'#required'				=> TRUE,
 		'#default_value' 	=> afl_variable_get('hyper_wallet_password',''),
 		'#prefix'					=> '<div class="form-group row">',
 		'#suffix' 				=> '</div>'
 	);

 	$form['hyper_wallet_url'] = array(
 		'#type' 					=> 'text',
 		'#title' 					=> 'Request URL',
 		'#required'				=> TRUE,
 		'#default_value' 	=> afl_variable_get('hyper_wallet_url',''),
 		'#prefix'					=> '<div class="form-group row">',
 		'#suffix' 				=> '</div>'
 	);


 	$form['hyper_wallet_payment_purpose'] = array(
 		'#type' 					=> 'text',
 		'#title' 					=> 'Payment Purpose',
 		'#required'				=> TRUE,
 		'#default_value' 	=> afl_variable_get('hyper_wallet_payment_purpose','OTHER'),
 		'#prefix'					=> '<div class="form-group row">',
 		'#suffix' 				=> '</div>'
 	);
 
 
 	$form['submit'] = array(
 		'#type' => 'submit',
 		'#value' =>'Save configuration'
 	);
 	echo afl_render_form($form);

}


/* 
 * ----------------------------------------------------------------------------
 * Form Validation
 * ----------------------------------------------------------------------------
*/
function afl_hyper_wallet_config_settings_form_validation($POST){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	// $values = $POST['var'];
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		echo wp_set_message($error, 'danger');
    }
	}
	return $flag;
}

/* 
 * ----------------------------------------------------------------------------
 * Form Submit action
 * ----------------------------------------------------------------------------
*/
function afl_hyper_wallet_config_settings_form_submit($POST){
	$checking_vars	 = array();
	$checking_vars[] = 'holding_tank_expiry_autoplace';

	foreach ($POST as $key => $value) {
		afl_variable_set($key, maybe_serialize($value));
	}

	foreach ($checking_vars as $key ) {
		if ( !array_key_exists($key, $POST)) {
			afl_variable_set($key, '');
		}	
	}

	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');

	}



function afl_hyper_wallet_new_user($data = NULL) {
	if(!$data) {
		return FALSE;
	}
	// pr($data,1);
	$admin_conf = afl_hyper_wallet_admin_conf_data();


	$server = $admin_conf['hyper_wallet_url'];
	$program_token = $admin_conf['hyper_wallet_program_token'];
	$username = $admin_conf['hyper_wallet_username'];
	$password = $admin_conf['hyper_wallet_password'];

	// pr($data);
	$client = new \Hyperwallet\Hyperwallet($username, $password, null, $server);


	$user = new \Hyperwallet\Model\User();
	$user
	  ->setClientUserId($data['clientUserId'])
	  ->setProgramToken($program_token)
	  ->setProfileType(\Hyperwallet\Model\User::PROFILE_TYPE_INDIVIDUAL)
	  ->setFirstName($data['firstName'])
	  ->setLastName($data['lastName'])
	  ->setEmail($data['email'])
	  ->setAddressLine1($data['addressLine1'])
	  ->setCity($data['city'])
	  ->setStateProvince($data['stateProvince'])
	  ->setCountry($data['country'])
	  ->setPostalCode($data['postalCode']);

	try {
		// pr($user);
	    $createdUser = $client->createUser($user);
	    // var_dump($createdUser);
	    // pr($createdUser);

	    $data['token'] = $createdUser->token;
	    $data['createdOn'] = $createdUser->createdOn;
	    return $data;
	} catch (\Hyperwallet\Exception\HyperwalletException $e) {
	    // Add error handling here
		$msg = $e->getMessage();
		// pr('exception'); 
		// pr($msg); 
		// var_dump($msg);
		// pr($e);
  	echo wp_set_message(__('Some errors occured. Please Verify your data.'), 'error');
		hyper_wallet_error_handling($e);
  	// echo wp_set_message(__($msg), 'error');


	}

// pr($client ,1);
	}



	function afl_hyper_wallet_admin_conf_data () {
		$data = array();

		$data['hyper_wallet_program_token'] = afl_variable_get('hyper_wallet_program_token', '');
		$data['hyper_wallet_username'] = afl_variable_get('hyper_wallet_username', '');
		$data['hyper_wallet_password'] = afl_variable_get('hyper_wallet_password', '');
		$data['hyper_wallet_url'] = afl_variable_get('hyper_wallet_url', '');
		$data['hyper_wallet_payment_purpose'] = afl_variable_get('hyper_wallet_payment_purpose', '');

		return $data;
	}

function afl_hyper_wallet_counries() {
	return array(
		'AF' => 'Afghanistan',
		'AX' => 'Åland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'VG' => 'British Virgin Islands',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'BQ' => 'Caribbean Netherlands',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos [Keeling] Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'HR' => 'Croatia',
		'CW' => 'Curaçao',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'EE' => 'Estonia',
		'FK' => 'Falkland Islands',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'FX' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong SAR China',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'XK' => 'Kosovo',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LS' => 'Lesotho',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macau SAR China',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn Islands',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Réunion',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthélemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'São Tomé and Príncipe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'YU' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SX' => 'Sint Maarten',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'KR' => 'South Korea',
		'SS' => 'South Sudan',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UM' => 'U.S. Outlying Islands',
		'VI' => 'U.S. Virgin Islands',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VA' => 'Vatican City',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'ZM' => 'Zambia',
		);
}



function show_hyper_wallet($atts) {
	// pr($atts);
	$data = json_decode($atts['data']);
// pr($data);
	$countries = afl_hyper_wallet_counries();

	$data->country = $countries[$data->country];

	$table = array();
	// $table['#links']  = $links;
	$table['#name'] 			= '';
	$table['#title'] 			= 'Hyper Wallet Account';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class' => array(
							'table',
							'table-bordered',
						)
					);

	$table['#header'] = array(
		// __('#'),
		__('Title'),
		__('Value'),
	);
	$rows = array();
	// pr($data);
	$i = 0;
	foreach ($data as $key => $value) {
		// pr($key);
		$title = ucfirst(implode(' ', preg_split('/(?=[A-Z])/',$key)));
		$rows[$i]['title'] = array(
					'#type' =>'markup',
					'#markup'=> $title
				);

		$rows[$i]['Value'] = array(
					'#type' =>'markup',
					'#markup'=> $value
				);
		$i++;
	}
	$table['#rows'] = $rows;
	return apply_filters('afl_render_table',$table);


  }



/**
 * Hyper Wallet Payout
 * 
 * @param $args array('uid', 'amount')
 * 
 * $destinationToken
			string

			A token identifying where the funds will be sent. Required.

			For Portal programs provide a userToken
			For Select programs provide a userToken
			For Direct programs provide a bank-account-token
			For Card programs provide a prepaid-card-token
 */

function afl_hyper_wallet_payout($id = NULL) {
	if(!$id) {return FALSE;}

	global $wpdb;
	$table 				= _table_name('afl_payout_requests');
	
	$query 						=   array();
 	$query['#select'] = $table;
 	$query['#where'] 	= array(
 		'afl_payout_id ='.$id
 	);
 	$row = db_select($query, 'get_row');
 	// pr($row,1);
	$uid = $row->uid;

	$amount = afl_get_payment_amount($row->amount_paid);

	$admin_conf = afl_hyper_wallet_admin_conf_data();

	$table = _table_name('afl_user_payment_methods');
	$check = $wpdb->get_row("SELECT  * FROM `$table` WHERE `uid` = $uid AND `method` = 'method_hyperwallet' AND `status` = 1 ");
	$check_data = json_decode($check->data);

	if(isset($check_data->token) && !empty($check_data->token)) {
		// pr($check_data);
		$destinationToken = $check_data->token;

		$currency = afl_currency();

		$uniquePaymentId = uniqid('paymentId-');

		// pr($admin_conf);
		$server = $admin_conf['hyper_wallet_url'];
		$programToken = $admin_conf['hyper_wallet_program_token'];
		$username = $admin_conf['hyper_wallet_username'];
		$password = $admin_conf['hyper_wallet_password'];
		$paymentPurpose = $admin_conf['hyper_wallet_payment_purpose'];

		$client = new \Hyperwallet\Hyperwallet($username, $password, null, $server);


		$payment = new \Hyperwallet\Model\Payment();
		$payment
		    ->setDestinationToken($destinationToken)
		    ->setProgramToken($programToken)
		    ->setClientPaymentId($uniquePaymentId)
		    ->setCurrency($currency)
		    ->setAmount($amount)
		    ->setPurpose($paymentPurpose);
		try {
		    $payment = $client->createPayment($payment);
		    // var_dump('Payment created', $payment);
		    // exit;
		    return TRUE;
		} catch (\Hyperwallet\Exception\HyperwalletException $e) {
		    // pr( $e->getTraceAsString());
		    $error = $e->getMessage();
		    echo wp_set_message($error, 'danger');
		    return FALSE;
		}


	}


}


/**
 * 
 * Hyper Wallet Error Handling - New User Creation
 * 
 */

function hyper_wallet_error_handling(\Hyperwallet\Exception\HyperwalletException $e) {
  $errorResponse = $e->getErrorResponse();
  $errors = $errorResponse->getErrors();
  foreach ($errors as $key => $value) {

    $field = $value->getfieldName();
    $message = $value->getMessage();

    $error = $field.' : '.$message;
    echo wp_set_message($error, 'error');
  }
 }