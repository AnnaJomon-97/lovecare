<?php
session_start();
defined('BASE_URL')  OR define('BASE_URL', 'http://localhost/lovecare/');
defined('APP_NAME')  OR define('APP_NAME', 'Love&Care');
defined('BASE_PATH')  OR define('BASE_PATH', dirname( dirname(__FILE__) )  );
// Database
define ( 'DB_HOST', 'localhost' );
define ( 'DB_USER', 'root' );
define ( 'DB_PASSWORD', '' );
define ( 'DB_NAME', 'lovecare_db' );
require_once 'db.php';
require_once 'Session.php';
require_once 'Validation.php';
require_once 'Input.php';
require_once 'Upload.php';
require_once 'functions.php';
?>