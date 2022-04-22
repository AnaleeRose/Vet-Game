<?php

// handles dev vs live configurations
$url = $_SERVER['SERVER_NAME'];

define('ENVIRONMENT', 'prod');

// base url depending on whether we're online or not
if (ENVIRONMENT === 'dev') {
    define('BASE_URL', 'http://localhost/drgame/');
    define('BASE_URI', 'http://localhost/drgame/');
	define('PUBLICROOT', 'C:\xampp2\htdocs\drgame');
	define('ROOT', 'C:\xampp2\htdocs\drgame\app');
} else {
    define('PUBLICROOT', '/var/www/html/talesfrom.space/public_html/vet');
    define('ROOT', '/var/www/html/talesfrom.space/public_html/vet/app');
    define('BASE_URL', 'https://talesfrom.space/vet/');
    define('BASE_URI', 'https://talesfrom.space/vet/');
}
