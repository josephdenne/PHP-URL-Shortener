<?php

/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

ini_set('display_errors', 1);

$url_to_shorten = get_magic_quotes_gpc() ? stripslashes(trim($_REQUEST['longurl'])) : trim($_REQUEST['longurl']);

if(!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten)) {

	require('../config.php');

	// check if the client IP is allowed to shorten
	if($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP)
	{
		die('You are not allowed to shorten URLs with this service.');
	}

	// check if the URL is valid
	if(CHECK_URL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '404')
		{
			die('Not a valid URL');
		}
		curl_close($ch);
	}

	// check if the URL has already been shortened
	$already_shortened = mysqli_query($conn, 'SELECT id FROM ' . DB_TABLE. ' WHERE long_url="' . mysqli_real_escape_string($conn, $url_to_shorten) . '"');
	$result = mysqli_fetch_array($already_shortened);

	if($result[0] > 0)
	{
		// URL has already been shortened
		$shortened_url = getShortenedURLFromID($result[0]);
	}
	else
	{
		// URL not in database, insert
		mysqli_query($conn, 'LOCK TABLES ' . DB_TABLE . ' WRITE;');
		mysqli_query($conn, 'INSERT INTO ' . DB_TABLE . ' (long_url, created, creator) VALUES ("' . mysqli_real_escape_string($conn, $url_to_shorten) . '", "' . time() . '", "' . mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR']) . '")');
		$shortened_url = getShortenedURLFromID(mysqli_insert_id($conn));
		mysqli_query($conn, 'UNLOCK TABLES');
	}
	echo BASE_HREF . $shortened_url;
}

function getShortenedURLFromID ($integer, $base = ALLOWED_CHARS)
{
	$length = strlen($base);

	while($integer > $length - 1)
	{
		$out = $base[fmod($integer, $length)] . $out;
		$integer = floor( $integer / $length );
	}

	if ($integer > $length - 1) {
		return $base[$integer] . $out;
	}
	else {
		return $base[$integer];
	}
}
