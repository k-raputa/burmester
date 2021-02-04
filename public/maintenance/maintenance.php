<?php

if (file_exists(__DIR__ . '/maintenance.marker')) {
    http_response_code(503);
    echo file_get_contents(__DIR__ . '/index.html');
    die();
}

if (array_key_exists('maintenance.marker', $_GET)) {
	setcookie('maintenance.marker', 'true');
}

if (file_exists(__DIR__ . '/maintenance_backend_cookie.marker') && !array_key_exists('maintenance.marker', $_COOKIE)) {
    http_response_code(503);
    echo file_get_contents(__DIR__ . '/index.html');
    die();
}
