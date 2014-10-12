<?php
require_once("service/models/config.php");

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: index.php"); die(); }
?>
<?php
/* 
	Activate a users account
*/
$errors = array();
//Get token param
if(isset($_GET["token"]))
{
		$token = $_GET["token"];
		if(!isset($token))
		{
			$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
		}
		else if(!validateactivationtoken($token)) //Check for a valid token. Must exist and active must be = 0
		{
			$errors[] = "Token no existe / La cuenta ya fue activada";
		}
		else
		{
			//Activate the users account
			if(!setUseractive($token))
			{
				$errors[] = lang("SQL_ERROR");
			}
		}
}
else
{
	$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
}
?>			
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Account Activation | <?php echo $websiteName; ?> </title>
<?php require_once("head_inc.php"); ?>
</head>
<body>
<div class="modal-ish">
    <div class="modal-header">
        <h2>Activación</h2>
    </div>
    <div class="modal-body">
			<?php
            if(count($errors) > 0)
            {
            errorBlock($errors);
            } else { ?> 
            <p>Activación completa. Haz click en el siguiente link <a href="login.php">Entrar.</a></p>    		 
            <?php }?>
    </div>
</div>       
</body>
</html>


	
	
	


