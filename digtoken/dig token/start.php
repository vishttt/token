<?php
error_reporting(0);

	
	for ($x = 0; $x <= 10000;$x++){
	//	unlink("phone.txt");
	//	$tokenarray = file('getphonetoken.txt');
	//	foreach ($tokenarray as $token) {
	//		system("php getmember.php ".$token."");
	//		system("php getphone.php ".$token."");
	//	}
		// unlink("getphonetoken.txt");
		system("php gettoken.php");
	
	}
?>