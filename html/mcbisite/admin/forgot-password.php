<?php
	require_once("service/models/config.php");
	
	//Prevent the user visiting the lost password page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: index.php"); die(); }
?>
<?php
	
$errors = array();
$success_message = "";

//User has confirmed they want their password changed
//----------------------------------------------------------------------------------------------
if(!empty($_GET["confirm"]))
{
	$token = trim($_GET["confirm"]);
	
	if($token == "" || !validateactivationtoken($token,TRUE))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else
	{
		$rand_pass = getUniqueCode(15);
		$secure_pass = generateHash($rand_pass);
		
		$userdetails = fetchUserDetails(NULL,$token);
		
		$mail = new userPieMail();		
						
		//Setup our custom hooks
		$hooks = array(
				"searchStrs" => array("#GENERATED-PASS#","#USERNAME#", "#WEBLOGO", "#WEBSITEURL#", "#NETWORK"),
				"subjectStrs" => array($rand_pass,$userdetails["login"], $websiteLogo, $websiteUrl,"")
		);
					
		if(!$mail->newTemplateMsg("your-lost-password.txt",$hooks))
		{
			$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
		}
		else
		{	
			if(!$mail->sendMail($userdetails["email"],"Nueva contraseña"))
			{
					$errors[] = lang("MAIL_ERROR");
			}
			else
			{
					if(!updatepasswordFromToken($secure_pass,$token))
					{
						$errors[] = lang("SQL_ERROR");
					}
					else
					{	
						//Might be wise if this had a time delay to prevent a flood of requests.
						flagLostpasswordRequest($userdetails["login_clean"],0);
						
						$success_message  = lang("FORGOTPASS_NEW_PASS_EMAIL");
					}
			}
		}
			
	}
}

//----------------------------------------------------------------------------------------------

//User has denied this request
//----------------------------------------------------------------------------------------------
if(!empty($_GET["deny"]))
{
	$token = trim($_GET["deny"]);
	
	if($token == "" || !validateactivationtoken($token,TRUE))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else
	{
	
		$userdetails = fetchUserDetails(NULL,$token);
		
		flagLostpasswordRequest($userdetails['login_clean'],0);
		
		$success_message = lang("FORGOTPASS_REQUEST_CANNED");
	}
}




//----------------------------------------------------------------------------------------------


//Forms posted
//----------------------------------------------------------------------------------------------
if(!empty($_POST))
{
		$email = $_POST["email"];
		
		
		//Perform some validation
		//Feel free to edit / change as required
		
		if(trim($email) == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
		}
		//Check to ensure email is in the correct format / in the db
		else if(!isValidemail($email) || !emailExists($email))
		{
			$errors[] = lang("ACCOUNT_INVALID_EMAIL");
		}	
		
		if(count($errors) == 0)
		{
		
			//Check that the login / email are associated to the same account
			if(!emailLinked($email))
			{
				$errors[] =  lang("ACCOUNT_USER_OR_EMAIL_INVALID");
			}
			else
			{
				//Check if the user has any outstanding lost password requests
				$userdetails = fetchUserDetailsByEmail($email);
				
				error_log(json_encode($userdetails));
				
				if($userdetails["LostpasswordRequest"] == 1)
				{
					$errors[] = lang("FORGOTPASS_REQUEST_EXISTS");
				}
				else
				{
					//email the user asking to confirm this change password request
					//We can use the template builder here
					
					//We use the activation token again for the url key it gets regenerated everytime it's used.
					
					$mail = new userPieMail();
					
					$confirm_url = lang("CONFIRM")."\n".$websiteUrl."forgot-password.php?confirm=".$userdetails["activationtoken"];
					$deny_url = lang("DENY")."\n".$websiteUrl."forgot-password.php?deny=".$userdetails["activationtoken"];
					
					//Setup our custom hooks
					$hooks = array(
						"searchStrs" => array("#CONFIRM-URL#","#DENY-URL#","#USERNAME#", "#WEBLOGO", "#WEBSITEURL#", "#NETWORK"),
						"subjectStrs" => array($confirm_url,$deny_url,$userdetails["login"], $websiteLogo, $websiteUrl,"")
					);
					
					if(!$mail->newTemplateMsg("lost-password-request.txt",$hooks))
					{
						$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
					}
					else
					{
						if(!$mail->sendMail($userdetails["email"],"Pérdida de contraseña"))
						{
							$errors[] = lang("MAIL_ERROR");
						}
						else
						{
							
												
							//echo $email;
							//exit();
							//Update the DB to show this account has an outstanding request
							flagLostpasswordRequestByEmail($email,1);
							
							$success_message = lang("FORGOTPASS_REQUEST_SUCCESS");
						}
					}
				}
			}
		}
}	
//----------------------------------------------------------------------------------------------	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recordar contraseña | <?php echo $websiteName; ?> </title>
<?php require_once("head_inc.php"); ?>
</head>
<body>
<div class="modal-ish">
  <div class="modal-header">
        <h2>Reinicio de contraseña</h2>
  </div>
  <div class="modal-body">
        
        <br>
        
		<?php
        if(!empty($_POST) || !empty($_GET))
        {
            if(count($errors) > 0)
            {
		?>
        	<div id="errors">
            	<?php errorBlock($errors); ?>
            </div> 
        <?php
            }
			else
			{
		?>
            <div id="success">
            
                <p><?php echo $success_message; ?></p>
            
            </div>
        <?php
			}
        }
        ?> 
        
        <div id="regbox">
            <form name="newLostPass" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <p>    
                <label>Email:</label>
                <input type="text" name="email" />
            </p>
            
        </div>
        </div>    
            
 <div class="modal-footer">
<input type="submit" class="btn btn-primary" name="new" id="newfeedform" value="Reiniciar contraseña" />
</div>
                
                </form>
            </div>

			<div class="clear"></div>
            <p style="margin-top:30px; text-align:center;"> <a href="login.php">Login</a></p>
            <div class="clear"></div>
</body>
</html>


