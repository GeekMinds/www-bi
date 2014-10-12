<?php

require_once("./models/config.php");
$mail_templates_dir = "./models/mail-templates/";

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createUser
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createUser($parameters = array()) {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $jTableResult = array();
    $result = array();
    $errors = array();
    $email = trim($parameters["email"]);
    $login = trim($parameters["login"]);
    $password = trim($parameters["password"]);
    $parameters['country_id'] = isset($parameters['country_id']) ? $parameters['country_id'] : '1';
    $parameters['group_id'] = isset($parameters['group_id']) ? $parameters['group_id'] : '1';
    $parameters['phone'] = isset($parameters['phone']) ? $parameters['phone'] : '';

    //Perform some validation
    //Feel free to edit / change as required
    if (minMaxRange(5, 25, $login)) {
        $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT", array(5, 25));
    }
    if (minMaxRange(6, 50, $password)) {
        $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT", array(6, 50));
    }
    if (!isValidemail($email)) {
        $errors[] = lang("ACCOUNT_INVALID_EMAIL");
    }

    //End data validation
    if (count($errors) == 0) {
        //Construct a user object
        $user = new User($parameters["login"], $parameters["password"], $parameters["email"], $parameters["name"], $parameters["description"], $parameters["country_id"], $parameters["group_id"], $parameters["phone"], $parameters["enabled"]);
        //Checking this flag tells us whether there were any errors such as possible data duplication occured
        if (!$user->status) {
            if ($user->login_taken)
                $errors[] = lang("ACCOUNT_USERNAME_IN_USE", array($login));
            if ($user->email_taken)
                $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
            //$message = errorBlock($errors);	
        }
        else {
            //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
            if (!$user->userPieAddUser()) {
                if ($user->mail_failure)
                    $errors[] = lang("MAIL_ERROR");
                if ($user->sql_failure)
                    $errors[] = lang("SQL_ERROR");
                $errors[] = lang("SQL_ERROR");
            }else {
                if ($emailActivation) {
                    $message = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
                }
                $result = $user->getLastInsertion();
            }
        }
    }


    //Setting the result to jTable
    if (count($errors) <= 0) {
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $result;
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = $errors[0];
    }

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listUser
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listUser($parameters = array()) {
    //Get records from database
    $data = getListUsersDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    error_log(json_encode($jTableResult));

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// updateUser
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateUser($parameters = array()) {
    //Update from database
    updateUserDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// deteleUser
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deteleUser($parameters = array()) {
    //Delete from database
    $result = deleteUserDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//VERSION 2.0
function listAdmins($parameters = array()) {
    $result = getAdminUsers($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result;
    $jTableResult['Options'] = $result;
    $jTableResult['TotalRecordCount'] = countAdminUsers();
    return $jTableResult;
}

function listCountries($parameters = array()) {
    $result = getCountries();
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $result;
    return $jTableResult;
}

function listProfiles($parameters = array()) {
    $result = getProfiles();
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $result;
    return $jTableResult;
}

function updateAdmin($parameters = array()) {
    $jTableResult = array();
    if (updateUserAdmin($parameters)) {
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar la información del administrador";
    }
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

error_log(json_encode($parameters));
$callback = getCallback($parameters);
$action = getAction($parameters);

if (!isUserLoggedIn()) {
    $action = "notlogued";
}

switch ($action) {
    case 'create':
        $result = createUser($parameters);
        break;
    case 'list':
        //$result = listUser($parameters);
        $result = listAdmins($parameters);
        break;
    case 'listCountry':
        $result = listCountries($parameters);
        break;
    case 'listProfiles':
        $result = listProfiles($parameteres);
        break;
    case 'update':
        $result = updateAdmin($parameters);
        break;
    case 'delete':
        //$result = deteleUser($parameters);
        break;
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>