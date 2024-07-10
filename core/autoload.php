<?php


require_once 'config.php';

require_once 'Helper.php';

$helper = new Helper(__DIR__ . '/helpers');

/*
* Database
*/

require_once 'database.php';

/*
* Url
*/
require_once 'classes/url.php';

$url = new url();

$address = $url->getSegments();

/*
* Permissions
*/
require_once 'Permissions.php';

$permissions = new Permissions(__DIR__ . '/permissions.txt');

/*
* Language
*/
$langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$userLang = strtolower($langs[0]);
$userLangFile = 'locales/'. $userLang . '.php';
if(!file_exists($userLangFile)){
    include 'locales/en.php';
}else{ include $userLangFile; }

/*
* User
*/
$member = array();
if(isset($_SESSION['session']) && isset($_SESSION['user'])){

    $db->where("id", @$_SESSION['user']);
    $member = $db->getOne("users");

}


?>