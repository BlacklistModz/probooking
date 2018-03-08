<?php

/*$servername = "jitwilaitour.com";
$username = "jitwilai_db";
$password = 'password.';
$db_name = "jitwilai_db";

// Create connection
$con = mysqli_connect($servername, $username, $password, $db_name);

// Check connection
if (mysqli_connect_errno()) {
 	die("Connection failed: " . mysqli_connect_error() );
}
echo "Connected successfully";
die;*/

function siteURL()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'].'/';
	return $protocol.$domainName;
}

date_default_timezone_set("Asia/Bangkok");

// Always provide a TRAILING SLASH (/) AFTER A PATH
define('URL', 'http://localhost/probooking/');

define('UPLOADS_URL', 'http://localhost/probooking/' . 'public/');

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'jitwilaitour_db');
define('DB_USER', 'root');
define('DB_PASS', '');


/*define('DB_TYPE', 'mysql');
define('DB_HOST', 'jitwilaitour.com');
define('DB_NAME', 'jitwilai_db');
define('DB_USER', 'jitwilai_db');
define('DB_PASS', 'password.');
*/

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('WWW_LIBS', ROOT . DS . "libs" . DS);
define('WWW_APPS', ROOT . DS . "apps" . DS);
define('WWW_DOCS', ROOT . DS . "public". DS. 'docs' . DS);
define('WWW_VIEW', ROOT . DS . 'views' . DS);
define('WWW_IMAGES', ROOT . DS . 'public' . DS. 'images' . DS );
define('WWW_IMAGES_AVATAR', WWW_IMAGES . DS . 'avatar' . DS);
define('WWW_UPLOADS', ROOT . DS . "public". DS. 'uploads' . DS);

define('LIBS', 'libs/');
define('DOCS', URL . 'public/docs/');
define('VIEW', URL . 'views/');
define('CSS', URL . 'public/css/');
define('JS', URL . 'public/js/');
define('FONTS', URL . 'public/fonts/');
define('IMAGES', URL . 'public/images/');
define('AVATAR', URL . 'public/images/avatar/');
define('UPLOADS', URL . "public/uploads/");

define('LANG', 'th');
define('COOKIE_KEY_ADMIN', 'u_id');
define('COOKIE_KEY_AGENCY', 'agen_id');

// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'MixitUp200');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'catsFLYhigh2000miles');

define('RECAPTCHA_SITE_KEY', '6LfPBxMTAAAAALX9MpBvvR2sjCKZidyhU-YXYHCY');
define('RECAPTCHA_SECRET_KEY', '6LfPBxMTAAAAACav7aO-axpuFK6r_fDphq6gAs4i');

// This for word AND pdf 
define('PATH_TRAVEL', ".." . DS . "admin" . DS . "admin" . DS . "upload" . DS . "travel" . DS);
define('PATH_PAYMENT', ".." . DS . "admin" . DS . "admin" . DS . "upload" . DS . "payment" . DS);
define('PATH_GUARANTEE', ".." . DS . "admin" . DS . "admin" . DS . "upload" . DS . "guarantee" . DS);
define('PATH_PASSPORT', "..". DS . "admin". DS . "admin". DS ."upload".DS."passport".DS);
define('PATH_ZIP', ".." . DS . "admin" . DS . "passport".DS);
define("PATH_ROOT", "..". DS ."public".DS);