<?php

class User 
{
	public $user_active = 0;
	private $clean_email;
	public $status = false;
	private $clean_password;
	private $clean_login;
	private $unclean_login;
	public $sql_failure = false;
	public $mail_failure = false;
	public $email_taken = false;
	public $login_taken = false;
	public $activation_token = 0;
	
	public $name = "";
	public $description = "";
	public $country_id = "";
	public $enabled = "1";
	public $group_id = "";
	public $phone = "";
	
	function __construct($login,
						 $pass,
						 $email,
						 $uname,
						 $udescription,
						 $ucountryid,
						 $ugroupid,
						 $uphone,
						 $uenabled="1")
	{
		//Used for display only
		$this->unclean_login = $login;
		
		//Sanitize
		$this->clean_email = sanitize($email);
		$this->clean_password = trim($pass);
		$this->clean_login = sanitize($login);
		
		$this->name = $uname;
		$this->description = $udescription;
		$this->country_id = $ucountryid;
		$this->enabled = $uenabled; 
		$this->group_id = $ugroupid;
		$this->phone = $uphone;
		
		if(loginExists($this->clean_login))
		{
			$this->login_taken = true;
		}
		else if(emailExists($this->clean_email))
		{
			$this->email_taken = true;
		}
		else
		{
			//No problems have been found.
			$this->status = true;
		}
	}
	
	public function userPieAddUser()
	{
		global $db,$emailActivation,$websiteUrl,$db_table_prefix, $websiteLogo;
		//$name,$description,$country_id,$enabled,$group_id
		//Prevent this function being called if there were construction errors
		if($this->status)
		{
			
			//Construct a secure hash for the plain text password
			$secure_pass = generateHash($this->clean_password);
			//Construct a unique activation token
			$this->activation_token = generateactivationtoken();
			
			//Do we need to send out an activation email?
			if($emailActivation)
			{
				//User must activate their account first
				$this->user_active = 0;
			
				$mail = new userPieMail();
			
				//Build the activation message
				$activation_message = lang("ACTIVATION_MESSAGE",array($websiteUrl,$this->activation_token, $this->clean_password));
				
				//Define more if you want to build larger structures
				$hooks = array(
					"searchStrs" => array("#ACTIVATION-MESSAGE","#ACTIVATION-KEY","#USERNAME#", "#WEBLOGO", "#WEBSITEURL#", "#NETWORK"),
					"subjectStrs" => array($activation_message ,$this->activation_token,$this->unclean_login, $websiteLogo, $websiteUrl,"")
				);
				
				/* Build the template - Optional, you can just use the sendMail function 
				Instead to pass a message. */
				if(!$mail->newTemplateMsg("new-registration.txt",$hooks))
				{
					$this->mail_failure = true;
				}
				else
				{
					//Send the mail. Specify users email here and subject. 
					//SendMail can have a third parementer for message if you do not wish to build a template.
					
					if(!$mail->sendMail($this->clean_email,"Usuario Administrador"))
					{
						$this->mail_failure = true;
					}
				}
			}
			else
			{
				//Instant account activation
				$this->user_active = 1;
			}	
	
	
			if(!$this->mail_failure)
			{
					$sql = "INSERT INTO ".$db_table_prefix."admin (
							login,
							login_clean,
							password,
							email,
							activationtoken,
							last_activation_request,
							LostpasswordRequest, 
							active,
							group_id, 
							sign_up_date,
							last_sign_in,
							name,
							description,
							country_id,
							phone,
							enabled 
							)
					 		VALUES (
							'".$db->sql_escape($this->unclean_login)."',
							'".$db->sql_escape($this->clean_login)."',
							'".$secure_pass."',
							'".$db->sql_escape($this->clean_email)."',
							'".$this->activation_token."',
							'".time()."',
							'0',
							'".$this->user_active."',
							'".$db->sql_escape($this->group_id)."',
							'".time()."',
							'0',
							'".$db->sql_escape($this->name)."',
							'".$db->sql_escape($this->description)."',
							'".$db->sql_escape($this->country_id)."',
							'".$db->sql_escape($this->phone)."',
							'".$db->sql_escape($this->enabled)."'
							)";
				$op_result = $db->sql_query($sql);
				if($op_result){
					return $op_result;
				}
				return false;
			}
		}
	}
	
	public function getLastInsertion(){
		global $db,$emailActivation,$websiteUrl,$db_table_prefix;
		
		$sql = "SELECT SCOPE_IDENTITY() last_intertion";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$las_insertion =  $row['last_intertion'];
		$sql = "SELECT *, name user_name FROM admin WHERE id = ".$las_insertion;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row;
	}
	
	public function getUserID(){
		global $db,$emailActivation,$websiteUrl,$db_table_prefix;
		$db->sql_nextid();
	}
	
	public function close(){
		global $db,$emailActivation,$websiteUrl,$db_table_prefix;
		$db->sql_close();
	}
}

?>