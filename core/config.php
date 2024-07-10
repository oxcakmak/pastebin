<?php

ob_start();
session_start();

setlocale(LC_ALL, "tr_TR_.UTF-8", "tr_TR", "tr", "turkish");
date_default_timezone_set("Europe/Istanbul");

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(~0);

/**
* Configuration
*/
$config = array();

$config['url'] = 'http://localhost/';
$config['api'] = $config['url'] . 'api/';
$config['assets'] = $config['url'] . 'assets/';

?>