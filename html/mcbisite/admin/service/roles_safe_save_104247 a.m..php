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
    case 'listroleditor':
        $result = listRoles(1, $parameters);
        break;
    case 'listrolgestor':
        $result = listRoles(2, $parameters);
        break;
    case 'listroladminreg':
        $result = listRoles(3, $parameters);
        break;
    case 'getCountries':
        $result = treeCountries($parameters);
        break;
    case 'getSites':
        $result = treeSites($parameters);
        break;
    case 'getPages':
        $result = treePages($parameters);
        break;
    case 'createroladminreg':
        $result = crearRol(3, $parameters);
        break;
    case 'createrolgestor':
        $result = crearRol(2, $parameters);
        break;
    case 'updateroladminreg':
        $result = updateRol($parameters);
        break;
    case 'updaterolgestor':
        $result = updateRol($parameters);
        break;
    case 'listuserp':
        $result = listUserRol($parameters);
        break;
    case 'listAdminReg':
        $result = listUserAdmin(3);
        break;
    case 'listAdminManager':
        $result = listUserAdmin(2);
        break;
    case 'createuserp':
        $result = asignarRolUsuario($parameters);
        break;
    case 'deleteuserp':
        $result = desasignarRolUsuario($parameters);
        break;
    case 'listsitesper':
        $result = listSitePermission($parameters);
        break;
    case 'listpagesper':
        $result = listPagePermission($parameters);
        break;
    case 'listcountries':
        $result = listCountries();
        break;
    case 'clonerole':
        $result = cloneRole($parameters);
        break;
    case 'updatesiteper':
        $result = updateSitePermission($parameters);
        break;
    case 'updatepageper':
        $result = updatePagePermission($parameters);
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

function listRoles($type, $parameters = array()) {
    global $db;
    $sql = '';
    switch ($type) {
        case 1://editor
            $sql = "SELECT distinct r.id, r.name, r.description from editor_permission ep INNER JOIN role r ON ep.role_id = r.id";
            break;
        case 2://gestor
            $sql = "SELECT distinct r.id, r.name, r.description from manager_permission mp INNER JOIN role r ON mp.role_id = r.id";
            break;
        case 3://adminreg
            $sql = "SELECT distinct r.id, r.name, r.description from regional_manager_permission rmp INNER JOIN role r ON rmp.role_id = r.id";
            break;
    }
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function treeCountries($parameters = array()) {
    global $db;
    $sql = "SELECT CONCAT('country',id) as id, name as label, CONCAT('../assets/images/countries/',flagcountry) as icon from dbo.country ";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    foreach ($rowset as $key => $rown) {
        $rowset[$key]['items'] = array();
        $itm = array();
        $itm['label'] = "Cargando...";
        $rowset[$key]['items'][0] = $itm;
    }
    return $rowset;
}

function treeSites($parameters = array()) {
    global $db;
    $country_id = $db->sql_escape($parameters['country_id']);
    $siblings = $db->sql_escape($parameters['has_siblings']);
    $sql = "SELECT CONCAT('site',id) as id,title_es as label  from dbo.site WHERE country_group_id = $country_id";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    if ($siblings == "true") {
        foreach ($rowset as $key => $rown) {
            $rowset[$key]['items'] = array();
            $itm = array();
            $itm['label'] = "Cargando...";
            $rowset[$key]['items'][0] = $itm;
        }
    }
    return $rowset;
}

function treePages($parameters = array()) {
    global $db;
    $site_id = $db->sql_escape($parameters['site_id']);
    $siblings = $db->sql_escape($parameters['has_siblings']);
    $sql = "select CONCAT('page',id) as id, title_es as label from dbo.page where site_id = $site_id order by created_at DESC";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    if ($siblings == "true") {
        foreach ($rowset as $key => $rown) {
            $rowset[$key]['items'] = array();
            $itm = array();
            $itm['label'] = "Cargando...";
            $rowset[$key]['items'][0] = $itm;
        }
    }
    return $rowset;
}

function treeContents($parameters = array()) {
    global $db;
    $page_id = $db->sql_escape($parameters['page_id']);
    $sql = "SELECT CONCAT('content',c.id) as id, CONCAT(CONCAT(CONCAT(c.title_es,' ('),ml.description),')') as label  from page_content pc INNER JOIN content c on pc.content_id = c.id INNER JOIN module_list ml ON c.module_id = ml.id WHERE pc.page_id = $page_id and pc.status = 1";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    return $rowset;
}

function crearRol($tipo, $parameters = array()) {
    global $db;
    $name = $db->sql_escape($parameters['name']);
    $description = $db->sql_escape($parameters['description']);
    $idents = $db->sql_escape($parameters['idents']);
    //crear el rol 
    $sql = "EXECUTE [dbo].[createRole]"
            . " @name ='$name'"
            . ",@description='$description'";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $roleid = $row['id'];
        switch ($tipo) {
            case 1://editor
                $sql = "SELECT distinct r.id, r.name, r.description from editor_permission ep INNER JOIN role r ON ep.role_id = r.id";
                break;
            case 2://gestor
                $pageids = explode(',', $idents);
                foreach ($pageids as $page) {
                    $sql = 'INSERT INTO dbo.manager_permission(role_id,page_id) VALUES (' . $roleid . ',' . $page . ')';
                    $db->sql_query($sql);
                }
                break;
            case 3://adminreg
                $siteids = explode(',', $idents);
                foreach ($siteids as $site) {
                    $sql = 'INSERT INTO dbo.regional_manager_permission(role_id,site_id) VALUES (' . $roleid . ',' . $site . ')';
                    $db->sql_query($sql);
                }
                break;
        }
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $row;
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo crear el rol";
    }
    return $jTableResult;
}

function updateRol($parameters = array()) {
    global $db;
    $name = $db->sql_escape($parameters['name']);
    $description = $db->sql_escape($parameters['description']);
    $idrol = $parameters['id'];
    $sql = "UPDATE dbo.role SET name = '$name', description = '$description' WHERE id= $idrol";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el rol";
    }
    return $jTableResult;
}

function listUserRol($parameters = array()) {
    global $db;
    $rol = $parameters['rol'];
    $sql = "SELECT a.id,a.name,a.email,a.login,ar.admin_id FROM admin_role ar INNER JOIN admin a ON ar.admin_id = a.id AND ar.role_id = $rol";
    $result = $db->sq