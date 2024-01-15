<?php
// settings
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Dhaka');
// stop PHP from automatically embedding PHPSESSID on local URLs
$site_name = 'MS Bin';
$prt = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $prt.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
define('SITE_URL', $$prt.$_SERVER['HTTP_HOST']);
define('SITE_NAME', $site_name);
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'merajbd6');
define('SITE_DOMAIN', $_SERVER['HTTP_HOST']);
define('BASE_URL', 'https://'.$_SERVER['HTTP_HOST']);
define('CURRENT_URL',$current_url);
//mysql db connection information
$whitelist = array('127.0.0.1','::1');
if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    // production server
    define('DB_HOST','sql311.epizy.com');
    define('DB_NAME','epiz_31381353_msbin');
    define('DB_USER','epiz_31381353');
    define('DB_PASS','2WwGQm2jD6');
} else {
    // localhost
    define('DB_HOST','localhost');
    define('DB_NAME','paste');
    define('DB_USER','root');
    define('DB_PASS','');
}

$custom_tags = 'YES'; // YES to allow users to use their custom tags or NO for random tags generated automatically

$tag_length = 10; // length of random tags
?>