<?php
require_once("service/models/config.php");

//print_r($_SESSION);
if(!isUserLoggedIn())
{ 
 	//include('login.php'); 
	header("Location: login.php");
} else {
	$original_visited_url = (isset($_SESSION['original_visited_url'])) ? $_SESSION['original_visited_url'] : "home.php";
	$_SESSION['original_visited_url'] = "";
	if($original_visited_url==""){
		$original_visited_url = "home.php";
	}
	header("Location: ".$original_visited_url);
} ?>