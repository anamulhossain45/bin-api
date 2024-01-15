<?php
session_start();
require_once('config.php');
require_once('db.php');
require_once('functions.php');
$db = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$GLOBALS['db'] = $db;
require_once('page.php');