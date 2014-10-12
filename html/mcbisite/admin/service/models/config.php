<?php


error_reporting(E_ALL ^ E_NOTICE);
if(is_dir("install/"))
{
	header("Location: install/");
	die();
}

require_once("settings.php");

//Dbal Support - Thanks phpBB ; )
require_once("db/".$dbtype.".php");

//Construct a db instance
$db = new $sql_db();
if(is_array($db->sql_connect(
						$db_host, 
						$db_user,
						$db_pass,
						$db_name, 
						$db_port,
						false, 
						false
))) {
	die("Unable to connect to the database");
}

if(!isset($language)) $language = "es";

require_once("lang/".$language.".php");
require_once("class.user.php");
require_once("class.mail.php");
require_once("funcs.user.php");
require_once("funcs.general.php");
require_once("class.newuser.php");
require_once("funcs.site.php");
require_once("funcs.user_history.php");
require_once("funcs.validate.php");

$a = session_id();
if ($a == '') session_start();
//echoCacheConfig();

function echoCacheConfig(){
	/* establecer el limitador de caché a 'private' */
	session_cache_limiter('private');
	$cache_limiter = session_cache_limiter();
	/* establecer la caducidad de la caché a 30 minutos */
	session_cache_expire(180);
	$cache_expire = session_cache_expire();
	/* iniciar la sesión */
	session_start();
	error_log("
//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\	
//\\ 				-----CACHE CONFIG ------
//\\	El limitador de caché ahora está establecido a $cache_limiter
//\\ 	Las páginas de sesión examinadas caducan después de $cache_expire minutos
//\\					
//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\//\

".json_encode($_SESSION)."


");
}
	
	
if(isset($_SESSION["site_id_administered"])){
	$site_id = $_SESSION["site_id_administered"];
}

if(isset($_SESSION["site_name"])){
	$websiteName = $_SESSION["site_name"];
}
if(isset($_SESSION["language"])){
	$language = $_SESSION["language"];
}


/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
//Global User Object Var

//loggedInUser can be used globally if constructed
if(isset($_SESSION["biAdmin"]) && is_object($_SESSION["biAdmin"])){
	$loggedInUser = $_SESSION["biAdmin"];
	
	if(isset($_SESSION['session_time']) ) {
    	$life_session = time() - $_SESSION['session_time'];
        if($life_session > $session_timeout)
        {
            session_destroy();
			$loggedInUser = NULL;
        }else{
			$_SESSION['session_time'] = time();
		}
    }else{
    	$_SESSION['session_time'] = time();
	}
}else if(isset($_COOKIE["biAdmin"])) {
	$db->sql_query("SELECT session_data FROM ".$db_table_prefix."session WHERE id = '".$_COOKIE['biAdmin']."'");
	$dbRes = $db->sql_fetchrowset();
	if(empty($dbRes)) {
		$loggedInUser = NULL;
		setcookie("biAdmin", "", -parseLength($remember_me_length));
	}
	else {
		$obj = $dbRes[0];
		$loggedInUser = unserialize($obj["session_data"]);
	}
}else {
	$db->sql_query("DELETE FROM ".$db_table_prefix."session WHERE ".time()." >= (session_start+".parseLength($remember_me_length).")");
	$loggedInUser = NULL;
}


?>