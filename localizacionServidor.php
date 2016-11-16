<?php
	$server=$_SERVER['SERVER_ADDR'];
	$jsonurl = "http://freegeoip.net/json/". $server;
	$json = file_get_contents($jsonurl);
	echo $json;
?>