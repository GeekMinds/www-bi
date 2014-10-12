<?php

class loggedInUser {

	public $email = NULL;
	public $name = NULL;
	public $hash_pw = NULL;
	public $user_id = NULL;
	public $country_id = NULL;
	public $group_id = NULL;
	public $clean_login = NULL;
	public $display_login = NULL;
	public $remember_me = NULL;
	public $remember_me_sessid = NULL;
	
	//Simple function to update the last sign in of a user
	public function updatelast_sign_in()
	{
		global $db,$db_table_prefix;
		
		$sql = "UPDATE ".$db_table_prefix."admin
			    SET
				last_sign_in = '".time()."',
                                last_session_id = '".session_id()."'    
				WHERE
				id = '".$db->sql_escape($this->user_id)."'";
		
		return ($db->sql_query($sql));
	}
	
	//Return the timestamp when the user registered
	public function signupTimeStamp()
	{
		global $db,$db_table_prefix;
		
		$sql = "SELECT
				sign_up_date
				FROM
				".$db_table_prefix."admin
				WHERE
				id = '".$db->sql_escape($this->user_id)."'";
		
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);
		
		return ($row['sign_up_date']);
	}
	
	//Update a users password
	public function updatepassword($pass)
	{
		global $db,$db_table_prefix;
		
		$secure_pass = generateHash($pass);
		
		$this->hash_pw = $secure_pass;
		if($this->remember_me == 1)
		updateSessionObj();
		
		$sql = "UPDATE ".$db_table_prefix."admin
		       SET
			   password = '".$db->sql_escape($secure_pass)."' 
			   WHERE
			   id = '".$db->sql_escape($this->user_id)."'";
	
		return ($db->sql_query($sql));
	}
	
	//Update a users email
	public function updateemail($email)
	{
		global $db,$db_table_prefix;
		
		$this->email = $email;
		if($this->remember_me == 1)
		updateSessionObj();
		
		$sql = "UPDATE ".$db_table_prefix."admin
				SET email = '".$email."'
				WHERE
				id = '".$db->sql_escape($this->user_id)."'";
		
		return ($db->sql_query($sql));
	}
	
	//Fetch all user group information
	public function groupID()
	{
		global $db,$db_table_prefix;
		
		$sql = "SELECT ".$db_table_prefix."admin.group_id, 
			   ".$db_table_prefix."group.* 
			   FROM ".$db_table_prefix."admin
			   INNER JOIN ".$db_table_prefix."group ON ".$db_table_prefix."admin.group_id = ".$db_table_prefix."group.id 
			   WHERE
			   id  = '".$db->sql_escape($this->user_id)."'";
		
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);

		return($row);
	}
	
	//Is a user member of a group
	public function isGroupMember($id)
	{
		global $db,$db_table_prefix;
	
		$sql = "SELECT ".$db_table_prefix."admin.group_id, 
				".$db_table_prefix."group.* FROM ".$db_table_prefix."admin
				INNER JOIN ".$db_table_prefix."group ON ".$db_table_prefix."admin.group_id = ".$db_table_prefix."group.id
				WHERE id  = '".$db->sql_escape($this->user_id)."'
				AND
				".$db_table_prefix."admin.group_id = '".$db->sql_escape($db->sql_escape($id))."'
				LIMIT 1
				";
		
		if(returns_result($sql))
			return true;
		else
			return false;
		
	}
	
	//Logout
	function userLogOut()
	{
		destorySession("biAdmin");
	}
         
        function getLastSession(){
            global $db,$db_table_prefix;
            $sql = "SELECT last_session_id FROM ".$db_table_prefix."admin WHERE id = '".$db->sql_escape($this->user_id)."'";
            $result = $db->sql_query($sql);
            $rows = $db->sql_fetchrowset($result);
            $row = $rows[0];
            $lastsessionid= $row['last_session_id'];
            return $lastsessionid;
        }

}
?>