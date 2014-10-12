<?php

function loginExists($login) {
    global $db, $db_table_prefix;
    $sql = "SELECT active
			FROM " . $db_table_prefix . "admin
			WHERE
			login_clean = '" . $db->sql_escape(sanitize($login)) . "'
			";
    if (returns_result($sql) > 0)
        return true;
    else
        return false;
}

function emailExists($email) {
    global $db, $db_table_prefix;
    $sql = "SELECT active FROM " . $db_table_prefix . "admin
			WHERE
			email = '" . $db->sql_escape(sanitize($email)) . "'
			";
    if (returns_result($sql) > 0)
        return true;
    else
        return false;
}

//Function lostpass var if set will check for an active account.
function validateactivationtoken($token, $lostpass = NULL) {
    global $db, $db_table_prefix;
    if ($lostpass == NULL) {
        $sql = "SELECT activationtoken
				FROM " . $db_table_prefix . "admin
				WHERE active = 0
				AND
				activationtoken ='" . $db->sql_escape(trim($token)) . "'
			";
    } else {
        $sql = "SELECT activationtoken
				FROM " . $db_table_prefix . "admin
				WHERE active = 1
				AND
				activationtoken ='" . $db->sql_escape(trim($token)) . "'
				AND
				LostpasswordRequest = 1 ";
    }
    if (returns_result($sql) > 0)
        return true;
    else
        return false;
}

function setUseractive($token) {
    global $db, $db_table_prefix;
    $sql = "UPDATE " . $db_table_prefix . "admin
			SET active = 1
			WHERE
			activationtoken ='" . $db->sql_escape(trim($token)) . "'
			";
    return ($db->sql_query($sql));
}

//You can use a activation token to also get user details here
function fetchUserDetails($login = NULL, $token = NULL) {
    global $db, $db_table_prefix;
    if ($login != NULL) {
        $sql = "SELECT * FROM " . $db_table_prefix . "admin
				WHERE
				login_clean = '" . $db->sql_escape(sanitize($login)) . "'
			";
    } else {
        $sql = "SELECT * FROM " . $db_table_prefix . "admin
				WHERE 
				activationtoken = '" . $db->sql_escape(sanitize($token)) . "'
			";
    }
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    //$db->sql_close();
    return ($row);
}

//You can use a activation token to also get admin details here

function fetchUserDetailsByEmail($email = NULL, $token = NULL) {
    global $db, $db_table_prefix;
    if ($email != NULL) {
        $sql = "SELECT * FROM " . $db_table_prefix . "admin
				WHERE
				email = '" . $db->sql_escape(sanitize($email)) . "'
			";
    } else {
        $sql = "SELECT * FROM " . $db_table_prefix . "admin
				WHERE 
				activationtoken = '" . $db->sql_escape(sanitize($token)) . "'
			";
    }
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    //$db->sql_close();
    return ($row);
}

//You can get the list admins with filter options

function getListUsersDataBase($parameters = array()) {
    $parameters['type'] = (isset($parameters['type'])) ? $parameters['type'] : '';
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : '';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    global $db, $db_table_prefix;
    $data = array();
    $sql = "SELECT id AS Value, name AS DisplayText, id, name AS user_name, email, description, login, group_id, country_id, enabled, phone, created_at FROM " . $db_table_prefix . "admin";
    $sql_count = "SELECT COUNT(*) as count FROM " . $db_table_prefix . "admin ";
    if ($parameters['type'] != '') {

        $sql .= " WHERE  group_id = " . $parameters['type'];
    }
    if ($parameters['jtsorting'] != '') {

        $sql .= " ORDER BY " . $parameters['jtsorting'];
    }
    if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
        //$sql .= " LIMIT " .$parameters['jtstartindex'] . ", " .$parameters['jtpagesize'];
    }
    $result = $db->sql_query($sql);
    $result_cont = $db->sql_query($sql_count);
    $rows = $db->sql_fetchrowset($result);
    $count = $db->sql_fetchrow($result_cont);
    //$rows = $db->sql_fetchrowset_array($result);
    $data['rows'] = $rows;
    $data['count'] = $count['count'];
    $db->sql_close();
    return $data;
}

//You can update specific admin

function updateUserDataBase($parameters = array()) {
    global $db, $db_table_prefix;
    $parameters['description'] = (isset($parameters['description'])) ? $parameters['description'] : '';
    $sql = "UPDATE " . $db_table_prefix . "admin SET 
					name = '" . $db->sql_escape($parameters["user_name"]) . "', 
					email = '" . $db->sql_escape($parameters["email"]) . "', 
					description = '" . $db->sql_escape($parameters["description"]) . "', 
					login = '" . $db->sql_escape($parameters["login"]) . "', 
					group_id = " . $db->sql_escape($parameters["group_id"]) . ", 
					country_id = " . $db->sql_escape($parameters["country_id"]) . ", 
					enabled = '" . $db->sql_escape($parameters["enabled"]) . "', 
					phone = '" . $db->sql_escape($parameters["phone"]) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    $result = $db->sql_query($sql);
    $db->sql_close();
    return ($result);
}

//You can delete specific user

function deleteUserDataBase($parameters = array()) {
    global $db, $db_table_prefix;
    $sql = "DELETE FROM " . $db_table_prefix . "admin WHERE id = " . $db->sql_escape($parameters["id"]);
    $result = $db->sql_query($sql);
    return ($result);
}

//flagLostpasswordRequest

function flagLostpasswordRequest($login, $value) {
    global $db, $db_table_prefix;
    $sql = "UPDATE " . $db_table_prefix . "admin
			SET LostpasswordRequest = '" . $value . "'
			WHERE
			login_clean ='" . $db->sql_escape(sanitize($login)) . "'
			";
    return ($db->sql_query($sql));
}

//flagLostpasswordRequestByEmail

function flagLostpasswordRequestByEmail($email, $value) {
    global $db, $db_table_prefix;
    $sql = "UPDATE " . $db_table_prefix . "admin
			SET LostpasswordRequest = " . $value . "
			WHERE
			email ='" . $db->sql_escape($email) . "'";
    error_log($sql);
    $result = $db->sql_query($sql);
    error_log("RESULT::" . json_encode($result));
    return $result;
}

function updatepasswordFromToken($pass, $token) {
    global $db, $db_table_prefix;
    $new_activation_token = generateactivationtoken();
    $sql = "UPDATE " . $db_table_prefix . "admin
			SET password = '" . $db->sql_escape($pass) . "',
			activationtoken = '" . $new_activation_token . "'
			WHERE
			activationtoken = '" . $db->sql_escape(sanitize($token)) . "'";
    return ($db->sql_query($sql));
}

function emailloginLinked($email, $login) {
    global $db, $db_table_prefix;
    $sql = "SELECT login,
			email FROM " . $db_table_prefix . "admin
			WHERE login_clean = '" . $db->sql_escape(sanitize($login)) . "'
			AND
			email = '" . $db->sql_escape(sanitize($email)) . "'
			";
    if (returns_result($sql) > 0)
        return true;
    else
        return false;
}

function emailLinked($email) {
    global $db, $db_table_prefix;
    $sql = "SELECT login,
			email FROM " . $db_table_prefix . "admin
			WHERE email = '" . $db->sql_escape(sanitize($email)) . "'
			";
    if (returns_result($sql) > 0)
        return true;
    else
        return false;
}

function isUserLoggedIn() {
    global $loggedInUser, $db, $db_table_prefix;
    if ($loggedInUser == NULL) {
        return false;
    } else {
        $sql = "SELECT id,
			password
			FROM " . $db_table_prefix . "admin
			WHERE
			id = '" . $db->sql_escape($loggedInUser->user_id) . "'
			AND 
			password = '" . $db->sql_escape($loggedInUser->hash_pw) . "' 
			AND
			active = 1
			AND
			enabled = 1
			";
        //Query the database to ensure they haven't been removed or possibly banned?
        if (returns_result($sql) > 0) {
            return true;
        } else {
            //No result returned kill the user session, user banned or deleted
            $loggedInUser->userLogOut();
            return false;
        }
    }
}

//This function should be used like num_rows, since the PHPBB Dbal doesn't support num rows we create a workaround

function returns_result($sql) {
    global $db;
    $count = 0;
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        $count++;
    }
    $db->sql_freeresult($result);
    return ($count);
}

//Generate an activation key 

function generateactivationtoken() {
    $gen;
    do {
        $gen = md5(uniqid(mt_rand(), false));
    } while (validateactivationtoken($gen));
    return $gen;
}

function updatelast_activation_request($new_activation_token, $login, $email) {
    global $db, $db_table_prefix;
    $sql = "UPDATE " . $db_table_prefix . "admin
			SET activationtoken = '" . $new_activation_token . "',
			last_activation_request = '" . time() . "'
			WHERE email = '" . $db->sql_escape(sanitize($email)) . "'
			AND
			login_clean = '" . $db->sql_escape(sanitize($login)) . "'";

    return ($db->sql_query($sql));
}

function closeDataBaseConnection() {
    global $db, $db_table_prefix;
    $db->sql_close();
}

//usuarios v 2.0
function getInfoUserGroup() {
    global $db, $loggedInUser;
    $sql = "SELECT name, description FROM [group] WHERE id=" . $loggedInUser->group_id;
    $result = $db->sql_query($sql);
    return $db->sql_fetchrowset($result);
}

function getAdminUsers($parameters = array()) {
    global $db, $loggedInUser;
    $sql = "id,login,email,name, phone,country_id, group_id,enabled, id as Value, name as DisplayText FROM admin";
    if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
        $parameters['jtpagesize'] = (int) $parameters['jtstartindex'] + (int) $parameters['jtpagesize'];
        //$sql .= " LIMIT " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
        $sqlAdd = "SELECT * FROM ( SELECT ROW_NUMBER() OVER (";
        if ($parameters['jtsorting'] != '') {
            $sqlAdd .= " ORDER BY " . $parameters['jtsorting'];
        }else{
            $sqlAdd .= " ORDER BY group_id";
        }
        $sqlAdd .= ") as Row," . $sql . ") AS T WHERE T.Row > " . $parameters['jtstartindex'] . " AND T.Row <= " . $parameters['jtpagesize'] . "";
        $sql = $sqlAdd;
    } else {
        $sql = "SELECT ".$sql;
        if ($parameters['jtsorting'] != '') {
            $sql .= " ORDER BY " . $parameters['jtsorting'];
        }
    }
    $result = $db->sql_query($sql);
    return $db->sql_fetchrowset($result);
}
function countAdminUsers(){
    global $db;
    $sql = "SELECT COUNT(*) AS contador FROM admin";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    return $row['contador'];
}

function getCountries() {
    global $db, $loggedInUser;
    $sql = "SELECT id as Value,name as DisplayText from country";
    $result = $db->sql_query($sql);
    return $db->sql_fetchrowset($result);
}

function getProfiles() {
    global $db, $loggedInUser;
    $sql = "SELECT id as Value, name as DisplayText from [group]";
    $result = $db->sql_query($sql);
    return $db->sql_fetchrowset($result);
}

function updateUserAdmin($parameters = array()) {
    global $db;
    $name = $db->sql_escape($parameters['name']);
    $email = $db->sql_escape($parameters['email']);
    $login = $db->sql_escape($parameters['login']);
    $gid = $db->sql_escape($parameters['group_id']);
    $cid = $db->sql_escape($parameters['country_id']);
    $enabled = $db->sql_escape($parameters['enabled']);
    $adminid = $db->sql_escape($parameters['id']);
    $sql = "UPDATE admin SET "
            . "name = '$name',"
            . "email = '$email',"
            . "login = '$login',"
            . "group_id = '$gid',"
            . "country_id='$cid',"
            . "enabled='$enabled'"
            . " WHERE id= $adminid";
    return $db->sql_query($sql);
}

function createUserAdmin($parameters = array()) {
    
}

?>