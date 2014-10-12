<?php

require_once("service/models/settings.php");
require_once("service/models/db/" . $dbtype . ".php");
require_once("service/models/lang/" . $language . ".php");
require_once("service/models/class.user.php");
require_once("service/models/class.mail.php");
require_once("service/models/funcs.user.php");
require_once("service/models/funcs.general.php");
require_once("service/models/class.newuser.php");
require_once("service/models/funcs.site.php");
$db = new $sql_db();
if (is_array($db->sql_connect(
                        $db_host, $db_user, $db_pass, $db_name, $db_port, false, false
        ))) {
    die("Unable to connect to the database");
}


if(!isUserLoggedIn()) {
	header("Location: login.php");
	die(); 
}

$action = $_GET['action'];
switch ($action) {
    case "destroy":
        $login = $_GET['login'];
        $website_path = $_GET['website_path'];
        $remember_choice = $_GET['remember_me'];
        $userdetails = fetchUserDetails($login);
        $loggedInUser = new loggedInUser();
        $loggedInUser->email = $userdetails["email"];
        $loggedInUser->name = $userdetails["name"];
        $loggedInUser->user_id = $userdetails["id"];
        $loggedInUser->country_id = $userdetails["country_id"];
        $loggedInUser->group_id = $userdetails["group_id"];
        $loggedInUser->hash_pw = $userdetails["password"];
        $loggedInUser->display_login = $userdetails["login"];
        $loggedInUser->clean_login = $userdetails["login_clean"];
        $loggedInUser->remember_me = $remember_choice;
        $loggedInUser->remember_me_sessid = generateHash(uniqid(rand(), true));
        $sessid = $loggedInUser->getLastSession();
        session_id($sessid);
        session_start();
        if (isset($_SESSION["biAdmin"])) {
            $loggedInUserold = $_SESSION["biAdmin"];
            if ($loggedInUserold->email === $loggedInUser->email) {
                session_destroy();
            } else {
                header("Location: destroySession.php?action=newuser&login=$login&remember_me=$remember_choice&website_path=$website_path");
            }
        }
        session_start();
        session_regenerate_id();
        //Update last sign in
        $loggedInUser->updatelast_sign_in();
        if ($loggedInUser->remember_me == 0) {
            $_SESSION["biAdmin"] = $loggedInUser;
            error_log("remember_me = 0");
            error_log(json_encode($_SESSION));
        } else if ($loggedInUser->remember_me == 1) {
            $db->sql_query("INSERT INTO " . $db_table_prefix . "session VALUES('" . time() . "', '" . serialize($loggedInUser) . "', '" . $loggedInUser->remember_me_sessid . "')");
            setcookie("biAdmin", $loggedInUser->remember_me_sessid, time() + parseLength($remember_me_length));
            error_log("remember_me = 1");
        } else {
            error_log("remember_me = NADA");
        }
        $_SESSION['website_path'] = $website_path;
        header("Location: home.php?hadSessions=true");
        break;
    case "newuser":
        $login = $_GET['login'];
        $website_path = $_GET['website_path'];
        $remember_choice = $_GET['remember_me'];
        $userdetails = fetchUserDetails($login);
        $loggedInUser = new loggedInUser();
        $loggedInUser->email = $userdetails["email"];
        $loggedInUser->name = $userdetails["name"];
        $loggedInUser->user_id = $userdetails["id"];
        $loggedInUser->country_id = $userdetails["country_id"];
        $loggedInUser->group_id = $userdetails["group_id"];
        $loggedInUser->hash_pw = $userdetails["password"];
        $loggedInUser->display_login = $userdetails["login"];
        $loggedInUser->clean_login = $userdetails["login_clean"];
        $loggedInUser->remember_me = $remember_choice;
        $loggedInUser->remember_me_sessid = generateHash(uniqid(rand(), true));
        session_start();
        //Update last sign in
        $loggedInUser->updatelast_sign_in();
        if ($loggedInUser->remember_me == 0)
            $_SESSION["biAdmin"] = $loggedInUser;
        else if ($loggedInUser->remember_me == 1) {
            $db->sql_query("INSERT INTO " . $db_table_prefix . "session VALUES('" . time() . "', '" . serialize($loggedInUser) . "', '" . $loggedInUser->remember_me_sessid . "')");
            setcookie("biAdmin", $loggedInUser->remember_me_sessid, time() + parseLength($remember_me_length));
        }
        $_SESSION['website_path'] = $website_path;
        header("Location: home.php?hadSessions=false");
        break;
}


