<?php
include_once '../settings.php';
class userPieMail {

	//text based system with hooks to replace various strs in txt email templates
	public $contents = NULL;

	//Function used for replacing hooks in our templates
	public function newTemplateMsg($template,$additionalHooks)
	{
		global $mail_templates_dir,$debug_mode;
	
		$this->contents = file_get_contents($mail_templates_dir.$template);

		//Check to see we can access the file / it has some contents
		if(!$this->contents || empty($this->contents))
		{
			if($debug_mode)
			{
				if(!$this->contents)
				{ 
					echo lang("MAIL_TEMPLATE_DIRECTORY_ERROR",array(getenv("DOCUMENT_ROOT")));
							
					die(); 
				}
				else if(empty($this->contents))
				{
					echo lang("MAIL_TEMPLATE_FILE_EMPTY"); 
					
					die();
				}
			}
		
			return false;
		}
		else
		{
			//Replace default hooks
			$this->contents = replaceDefaultHook($this->contents);
		
			//Replace defined / custom hooks
			$this->contents = str_replace($additionalHooks["searchStrs"],$additionalHooks["subjectStrs"],$this->contents);

			//Do we need to include an email footer?
			//Try and find the #INC-FOOTER hook
			if(strpos($this->contents,"#INC-FOOTER#") !== FALSE)
			{
				$footer = file_get_contents($mail_templates_dir."email-footer.txt");
				if($footer && !empty($footer)) $this->contents .= replaceDefaultHook($footer); 
				$this->contents = str_replace("#INC-FOOTER#","",$this->contents);
			}
			
			return true;
		}
	}
	
	public function sendMail($email,$subject,$msg = "")
	{
		global $websiteName,$emailAddress,$emailActivePhpMailer;
		
		$header = "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$header .= "From: ". $websiteName . " <" . $emailAddress . ">\r\n";
		
		 
		//Check to see if we sending a template email.
		if($msg == NULL)
			$msg = $this->contents;

		$message = "";
		$message .= $msg;

		$message = wordwrap($message, 70);
		
		if($emailActivePhpMailer){
			return $this->phpMailer($email,$subject,$msg, $this->attachment);
		}else{
                return mail($email,$subject,$message,$header);               
                }
		
	}
	
	public function phpMailer($email,$subject,$msg, $attachment){
		require_once('./lib/phpmailer/class.phpmailer.php');
		global $websiteName,$emailAddress,$emailActivePhpMailer,$emailHost, $emailPort;
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

		$mail->IsSMTP(); // telling the class to use SMTP
		
		try {
		  $mail->Host       = $emailHost; // SMTP server
		  $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		  $mail->Port  = $emailPort;
		  $mail->AddAddress($email, '');
		  $mail->SetFrom($emailAddress, $websiteName);
		  $mail->AddReplyTo($emailAddress, $websiteName);
		  $mail->Subject = $subject;
		  $mail->AltBody = 'Para ver el mensaje, por favor, utilice un visor de correo electrónico HTML compatible!'; // optional - MsgHTML will create an alternate automatically
		  $mail->MsgHTML($msg);
		  
		  if($attachment!= NULL){
			for($i=0; $i<sizeof($attachment); $i++){
			   $mail->AddAttachment($attachment[$i]);
			}
		  }
		  //$mail->AddAttachment('images/phpmailer.gif');      // attachment
		  //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
		  $mail->Send();
		  error_log("phpMail Message Sent OK to ".$email);
		  return true;
		} catch (phpmailerException $e) {
		  error_log($e->errorMessage()); //Pretty error messages from PHPMailer
		  return false;
		} catch (Exception $e) {
		  error_log($e->getMessage()); //Boring error messages from anything else!
		  return false;
		}
		return true;
	}
       
}


?>