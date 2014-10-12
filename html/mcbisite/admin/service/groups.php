<?php

/* Author: Javier Cifuentes
 */
require_once("./models/config.php");
$parameters = getParameters($_POST, $_GET);

error_log(json_encode($parameters));
$callback = getCallback($parameters);
$action = getAction($parameters);

if (!isUserLoggedIn()) {
    $action = "notlogued";
}
switch ($action) {
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    case 'list':
        $result = listGroups($parameters);
        break;
    case 'create':
        $result = createGroup($parameters);
        break;
    case 'update':
        $result = updateGroup($parameters);
        break;
    case 'delete':
        $result = deleteGroup($parameters);
        break;
    case 'listuser':
        $result = listUsersGroup($parameters);
        break;
    case 'searchUser':
        $result = usersLike($parameters);
        break;
    case 'adduser':
        $result = addUserGroup($parameters);
        break;
    case 'removeuser':
        $result = removeUserGroup($parameters);
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

function listGroups($parameters = array()) {
    global $db;
    $sql = "SELECT id, name FROM community";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function createGroup($parameters = array()) {
    global $db;
    $name = $db->sql_escape($parameters['name']);
    $sql = "EXECUTE [dbo].[insertCommunity]"
            . " @name ='$name'";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $row;
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo crear el grupo";
    }
    return $jTableResult;
}

function updateGroup($parameters = array()) {
    global $db;
    $id = $db->sql_escape($parameters['id']);
    $name = $db->sql_escape($parameters['name']);
    $sql = "UPDATE community SET name = '$name' WHERE id = $id";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el grupo";
    }
    return $jTableResult;
}

function deleteGroup($parameters = array()) {
    global $db;
    $id = $db->sql_escape($parameters['id']);
    $sql = "DELETE FROM community WHERE id = $id";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el grupo";
    }
    return $jTableResult;
}

function listUsersGroup($parameters = array()) {
    global $db;
    $group_id = $db->sql_escape($parameters['group']);
    $sql = "SELECT uc.id_user, uc.id_community, u.first_name, u.last_name, u.email, u.cif FROM user_community uc INNER JOIN [user] u ON uc.id_user = u.id WHERE uc.id_community = $group_id";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function usersLike($parameters = array()) {
    global $db;
    $query = $db->sql_escape($parameters['query']);
    $sql = "SELECT id, CONCAT('CIF:', CONCAT(cif,CONCAT(' - ',CONCAT(first_name,CONCAT(' ', CONCAT(last_name,CONCAT(' - EMAIL: ',email))))))) as text from [user] WHERE first_name like '%$query%' or last_name like '%$query%' or email like '%$query%' or cif like '%$query%'";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function addUserGroup($parameters = array()) {
    global $db;
    $group_id = $db->sql_escape($parameters['group']);
    $user_id = $db->sql_escape($parameters['usuario']);
    $sql = "EXECUTE [dbo].[insertUserGroup]"
            . " @groupid ='$group_id'"
            . ",@userid='$user_id'";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $row;
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo agregar el usuario al grupo";
    }
    return $jTableResult;
}

function removeUserGroup($parameters = array()) {
    global $db;
    $group_id = $db->sql_escape($parameters['group']);
    $user_id = $db->sql_escape($parameters['id_user']);
    $sql = "DELETE FROM user_community WHERE id_user = $user_id AND id_community = $group_id";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el usuario del grupo";
    }
    return $jTableResult;
}
