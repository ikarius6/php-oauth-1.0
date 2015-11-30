<?php
	/* 
		PHP OAuth 1.0 [https://github.com/ikarius6/php-oauth-1.0]
		by Mr.Jack [https://keybase.io/mrjack]
	*/
	
	$oauth_consumer_key = 'AAAAAAAAAAAAAAAAA';
	$oauth_consumer_secret = 'AAAAAAAAAAAAAAAAA';
	$oauth_token = 'AAAAAAAAAAAAAAAAA';
	$oauth_token_secret = 'AAAAAAAAAAAAAAAAA';
	$sign_method = 'HMAC-SHA1';
	$version = '1.0';

	$api_url = 'http://some/api/rest/';
	$service = 'some_service';
	$method = "GET";
	
	$params = array(
		'oauth_consumer_key' => $oauth_consumer_key,
		'oauth_token' => $oauth_token,
		'oauth_signature_method' => $sign_method,
		'oauth_timestamp' => time(),
		'oauth_nonce' => uniqid(mt_rand(1, 1000)),
		'oauth_version' => $version
	);
	
	ksort($params);
	$sortedParamsByKeyEncodedForm = array();
	foreach ($params as $param_key => $param_value) {
		$sortedParamsByKeyEncodedForm[] = rawurlencode($param_key) . '=' . rawurlencode($param_value);
	}
	$strParams = implode('&', $sortedParamsByKeyEncodedForm);
	$signature_data = strtoupper($method). '&'. rawurlencode($api_url . $service). '&'. rawurlencode($strParams);

	$oauth_key = rawurlencode($oauth_consumer_secret) . '&' .rawurlencode($oauth_token_secret);; 
	$oauth_signature = base64_encode(hash_hmac('SHA1', $signature_data, $oauth_key, 1));
	$params['oauth_signature'] = rawurlencode($oauth_signature);
	
	$header_string = "";
	foreach($params as $param_key=>$param_value){
		$header_string .= $param_key.'="'.$param_value.'",';
	}
	$header_string = rtrim($header_string, ",");
	
	$header[] = 'Authorization: OAuth '.$header_string;
	//print_r($header);
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_URL => $api_url . $service,
		CURLOPT_HTTPHEADER => $header
	));
	if( $result = curl_exec($curl) ){
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($httpCode != 200) {
			echo "ERROR: ";
		}
		
		echo $result;
	}else{
		echo 'Curl error: ' . curl_error($curl);
	}
	curl_close($curl);
	
	
	