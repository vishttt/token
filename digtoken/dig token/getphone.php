<?php
error_reporting(0);
$bigArray = array();
$myurl = array();

	$memberarray = file('member.txt');


	foreach ($memberarray as $member) {
		$member = str_replace("%0A","",urlencode($member));
		$bigArray = 'https://graph.facebook.com/'.$member.'/?fields&access_token='.urlencode($argv[1]);
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
		$token = json_decode($response, true);
		if($token['mobile_phone'] != ""){
			$phone = str_replace("+66", "0", $token['mobile_phone']);
			$Fopen = fopen("phone.txt", 'a');
			fwrite($Fopen, "".$phone."\n");
			fclose($Fopen);
			echo "=====================================\n";
			echo "STATUS : SUCCESS\n";
			echo "PHONE : ".$token['mobile_phone']."\n";
			echo "=====================================\n";
		}else{
			echo "=====================================\n";
			echo "STATUS : ERROR\n";
			echo "MSG : NO PHONE\n";
			echo "=====================================\n";
		}
	}
	unlink("member.txt");
?>