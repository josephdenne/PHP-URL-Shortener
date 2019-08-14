<?php

// db options
define('DB_NAME', 'found');
define('DB_USER', 'found');
define('DB_PASSWORD', '3v3r9r33n');
define('DB_HOST', 'localhost');
define('DB_TABLE', 'shortenedurls');

// connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// base location of script (include trailing slash)
define('BASE_HREF', 'https://' . $_SERVER['HTTP_HOST'] . '/');

// change to limit short url creation to a single IP
define('LIMIT_TO_IP', '185.42.16.108');

// change to TRUE to start tracking referrals
define('TRACK', TRUE);

// check if URL exists first
define('CHECK_URL', TRUE);

// change the shortened URL allowed characters
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

// do you want to cache?
define('CACHE', TRUE);

// if so, where will the cache files be stored? (include trailing slash)
define('CACHE_DIR', dirname(__FILE__) . '/cache/');
