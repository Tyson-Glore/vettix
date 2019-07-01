<?php
/**
 * Created by PhpStorm.
 * User: Tyson Glore
 */
// Set default timezone to Miami time
date_default_timezone_set("America/Phoenix");

function get_events()
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,"https://www.vettix.org/sandbox/api/tm-events.php");//?limit=100
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$headers = [
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: en-US,en;q=0.5',
		'Content-Type: Content-Type: application/json',
		'HTTP_CUSTOM_VETTIX: aoekjr02%weragwkL51'
	];

	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec ($curl);

	curl_close ($curl);
	return $server_output;
}
