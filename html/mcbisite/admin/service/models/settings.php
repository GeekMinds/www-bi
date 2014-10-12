<?php
//General Settings
//--------------------------------------------------------------------------
date_default_timezone_set('America/Guatemala');

//Database Information
$dbtype = "mssql"; 
$dbms = "mssql"; 
$db_host = "donasqlweb.czek7k8d8d3f.us-west-2.rds.amazonaws.com:1433";
$db_user = "mncsqldona";
$db_pass = "MNCdona0909";
$db_name = "bisite02";
$db_port = "";
$db_table_prefix = "";/*userpie_*/

//Default  of pages of site
$defaul_pages = array();
$defaul_pages["home"] = "";
$defaul_pages["login"] = "";
$defaul_pages["register"] = "";
$defaul_pages["geolocator"] = "";


$language = "es";

//Generic website variables
$websiteName = "Administración Portal Bi";
$websiteUrl = "http://54.200.51.188/mcbisite/admin/"; //including trailing slash
$websiteLogo = "http://54.200.51.188/mcbisite/assets/images/logo.png";
$site_id = false;

//Do you wish UserPie to send out emails for confirmation of registration?
//We recommend this be set to true to prevent spam bots.
//False = instant activation
//If this variable is falses the resend-activation file not work.
$emailActivation = true;

//In hours, how long before UserPie will allow a user to request another account activation email
//Set to 0 to remove threshold
$resend_activation_threshold = 1;
//Mail configuration
$emailActivePhpMailer = false;
$emailHost = "10.2.200.155";
$emailPort = 25;
//Tagged onto our outgoing emails
$emailAddress = "portal@corpbi.com.gt";

//Date format used on email's
$emailDate = date("l \\t\h\e jS");

//Directory where txt files are stored for the email templates.
$mail_templates_dir = "service/models/mail-templates/";

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$emailDate);

//Display explicit error messages?
$debug_mode = true;

//Remember me - amount of time to remain logged in.
$remember_me_length = "1wk";
//seconds it takes to close the session
//$session_timeout = 86400;//24 hours
$session_timeout = 900;//15 minutos

//Documentar la siguiente línea para quitar el modo DEBUG
define("DEBUG_EXTRA",true);
//---------------------------------------------------------------------------


define("SUPER_ADMINISTRADOR",1);
define("ADMINISTRADOR_REGIONAL",2);
define("GESTOR",3);
define("EDITOR",4);
define("ANALISTA",5);
?>
