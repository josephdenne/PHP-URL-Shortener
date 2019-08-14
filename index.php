<?php

require('config.php');

// check if the client IP is allowed to shorten
if($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP) {

	// Soft redirect
	header('Location: https://foundthings.com');
	exit;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Found Things URL shortener</title>
		<meta name="robots" content="noindex, nofollow">
	</head>
	<body>
		<form method="post" action="shorten.php" id="shortener">
			<label for="longurl">URL to shorten</label> <input type="text" name="longurl" id="longurl"> <input type="submit" value="Shorten">
		</form>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(function () {
				$('#shortener').submit(function () {
					$.ajax({data: {longurl: $('#longurl').val()}, url: '/shorten/', complete: function (XMLHttpRequest, textStatus) {
						$('#longurl').val(XMLHttpRequest.responseText);
					}});
					return false;
				});
			});
		</script>
	</body>
</html>
