<?php
	include("service/models/config.php");
	
	//Log the user out
	if(isUserLoggedIn()){
		$params = array();
        $params["action_user"] = "LOGOUT";
        $params["description"] = "El usuario ha cerrado sesión.";
        createAdminHistoryDataBase($params);
		$loggedInUser->userLogOut();
	}
	if(!empty($websiteUrl)) 
	{
		$add_http = "";
		
		if(strpos($websiteUrl,"http://") === false)
		{
			$add_http = "http://";
		}
	
		header("Location: home.php");
		die();
	}
	else
	{
		header("Location: home.php");
		//header("Location: http://".$_SERVER['HTTP_HOST']);
		die();
	}	
?>


