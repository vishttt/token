<?php
error_reporting(0);

$array = file_get_contents("https://graph.facebook.com/me/friends?access_token=".$argv[1]);



$array = json_decode($array, true);

foreach($array['data'] as $member){
	echo $member['id']."\n";
	$Fopen = fopen("member.txt", 'a');
	fwrite($Fopen, "".$member['id']."\n");
	fclose($Fopen);
}
?>