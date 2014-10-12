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
    case 'getContents':
        $result = treeContents($parameters);
        break;
    case 'createroladminreg':
        $result = crearRol(3, $parameters);
        break;
    case 'createrolgestor':
        $result = crearRol(2, $parameters);
        break;
    case 'createroleditor':
        $result = crearRol(1, $parameters);
        break;
    case 'updateroladminreg':
        $result = updateRol($parameters);
        break;
    case 'updaterolgestor':
        $result = updateRol($parameters);
        break;
    case 'updateroleditor':
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
    case 'listAdminEditor':
        $result = listUserAdmin(1);
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
    case 'listcontentsper':
        $result = listContentPermission($parameters);
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
    case 'updatecontentper':
        $result = updateContentPermission($parameters);
        break;
    case 'deleterole':
        $result = deleteRole($parameters);
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
    global $db,$loggedInUser;
    $sql = '';
    switch ($type) {
        case 1://editor
            $where_editor= "";
            if ($loggedInUser->group_id != SUPER_ADMINISTRADOR) {
                $where_editor = " AND ep.content_id IN (" . getFilterTableRol(array('listPermissionEditor')) . ") ";
            }
            $sql = "SELECT distinct r.id, r.name, r.description from editor_permission ep INNER JOIN role r ON ep.role_id = r.id".$where_editor;
            break;
        case 2://gestor
            $where_gestor= "";
            if ($loggedInUser->group_id != SUPER_ADMINISTRADOR) {
                $where_gestor = " AND mp.page_id IN (" . getFilterTableRol(array('listPermissionGestor')) . ") ";
            }
            $sql = "SELECT distinct r.id, r.name, r.description from manager_permission mp INNER JOIN role r ON mp.role_id = r.id".$where_gestor;
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
    global $db, $loggedInUser;
    $where_site = "";
    if ($loggedInUser->group_id != SUPER_ADMINISTRADOR) {
        $where_site = " WHERE id IN (" . getFilterTableRol(array('listCountries')) . ") ";
    }
    $sql = "SELECT CONCAT('country',id) as id, name as label, CONCAT('../assets/images/countries/',flagcountry) as icon from dbo.country ".$where_site."ORDER BY name";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    //var_dump($sql);
    foreach ($rowset as $key => $rown) {
        $rowset[$key]['items'] = array();
        $itm = array();
        $itm['label'] = "Cargando...";
        $rowset[$key]['items'][0] = $itm;
    }
    return $rowset;
}

function treeSites($parameters = array()) {
    global $db,$loggedInUser;
    $where_site="";
    if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
        $where_site=" AND id IN (".getFilterTableRol(array('listSites')).") AND eliminado=0 ";
    }else{
        $where_site=" AND eliminado=0 ";
    }
    $country_id = $db->sql_escape($parameters['country_id']);
    $siblings = $db->sql_escape($parameters['has_siblings']);
    $sql = "SELECT CONCAT('site',id) as id,title_es as label  from dbo.site WHERE country_group_id = $country_id".$where_site."ORDER BY title_es";
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
    //var_dump($sql);
    return $rowset;
}

function treePages($parameters = array()) {
    global $db,$loggedInUser;
    $site_id = $db->sql_escape($parameters['site_id']);
    $siblings = $db->sql_escape($parameters['has_siblings']);
    $sql = "select CONCAT('page',id) as id, title_es as label from dbo.page where site_id = $site_id order by title_es, created_at DESC";
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
    global $db,$loggedInUser;
    $page_id = $db->sql_escape($parameters['page_id']);
    $sql = "SELECT CONCAT('content',c.id) as id, CONCAT(CONCAT(CONCAT(c.title_es,' ('),ml.description),')') as label  from page_content pc INNER JOIN content c on pc.content_id = c.id INNER JOIN module_list ml ON c.module_id = ml.id WHERE pc.page_id = $page_id and pc.status = 1 and ml.id NOT IN (7) ORDER BY c.title_es";
    $result = $db->sql_query($sql);
    $rowset = $db->sql_fetchrowset($result);
    //var_dump($sql);
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
                $contentids = explode(',', $idents);
                foreach ($contentids as $content) {
                    $sql = 'INSERT INTO dbo.editor_permission(role_id,content_id) VALUES (' . $roleid . ',' . $content . ')';
                    $db->sql_query($sql);
                }
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

function deleteRole($parameters = array()) {
    global $db;
    $rol = $parameters['id'];
    $table = $parameters['table'];
    $sql = "SELECT count(*) as contador FROM admin_role where role_id = $rol";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $contador = $row['contador'];
    $jTableResult = array();
    if ($contador == 0) {
        $permiso=PermissionDelete(array($table,$rol));
        if((bool)$permiso==1){
            $sql_editor = 'DELETE FROM editor_permission  WHERE role_id = ' . $rol;
            $db->sql_query($sql_editor);
            $sql_gestor = 'DELETE FROM manager_permission WHERE role_id=' . $rol;
            $db->sql_query($sql_gestor);
            $sql_regional = 'DELETE FROM regional_manager_permission WHERE role_id=' . $rol;
            $db->sql_query($sql_regional);
            $sql_role = 'DELETE FROM role WHERE id='.$rol;
            $db->sql_query($sql_role);
            $jTableResult['Result'] = "OK";
        }else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "No se pudo eliminar el rol, porque contiene items que pertenecen a otro usuario.";
        }
        
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el rol porque tiene usuarios asignados";
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
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function listUserAdmin($tipo) {
    global $db;
    $sql = "";
    switch ($tipo) {
        case 1://editor
            $sql = "SELECT CONCAT(CONCAT(login,' - '),name) as DisplayText, id as Value FROM admin WHERE group_id = 4";
            break;
        case 2://gestor
            $sql = "SELECT CONCAT(CONCAT(login,' - '),name) as DisplayText, id as Value FROM admin WHERE group_id = 3";
            break;
        case 3://adminreg
            $sql = "SELECT CONCAT(CONCAT(login,' - '),name) as DisplayText, id as Value FROM admin WHERE group_id = 2";
            break;
    }
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $rows;
    return $jTableResult;
}

function asignarRolUsuario($parameters = array()) {
    global $db;
    $id_usuario = $parameters['admin_id'];
    $id_rol = $parameters['rol'];
    $sql = "EXECUTE [dbo].[insertRolUser]"
            . " @id_user = $id_usuario"
            . ",@id_rol = $id_rol";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $jTableResult = array();
    if ($row['insertado'] == 'true') {
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $row;
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "El usuario ya tiene los permisos, o no se le pueden agregar";
    }
    return $jTableResult;
}

function desasignarRolUsuario($parameters = array()) {
    global $db;
    $id_usuario = $parameters['id'];
    $id_rol = $parameters['rol'];
    $sql = "EXECUTE [dbo].[deleteRolUser]"
            . " @id_user = $id_usuario"
            . ",@id_rol = $id_rol";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudieron desasignar los permisos";
    }
    return $jTableResult;
}

function listSitePermission($parameters = array()) {
    global $db;
    $id_rol = $parameters['rol'];
    $sql = "SELECT s.id, s.title_es, s.alias, s.country_id, s.id as site_id from regional_manager_permission rmp INNER JOIN site s on rmp.site_id = s.id WHERE rmp.role_id = $id_rol";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    return $jTableResult;
}

function listPagePermission($parameters = array()) {
    global $db, $loggedInUser;
    $id_rol = $parameters['rol'];
    $sql = "SELECT p.id,p.title_es as page_title,s.title_es as site_title,s.country_id, p.id as page_id FROM manager_permission mp INNER JOIN page p on mp.page_id = p.id INNER JOIN site s on p.site_id = s.id  WHERE mp.role_id =  $id_rol";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    return $jTableResult;
}

function listContentPermission($parameters = array()) {
    global $db;
    $id_rol = $parameters['rol'];
    $sql = "SELECT distinct [content].id,site.title_es AS site_name, page.title_es AS page_name, site.country_id AS site_country, [content].title_es AS content_name, module_list.description AS content_type FROM editor_permission ep INNER JOIN [content] ON ep.content_id = [content].id INNER JOIN page_content ON [content].id = page_content.content_id INNER JOIN page ON page.id = page_content.page_id INNER JOIN site ON page.site_id = site.id INNER JOIN module_list ON [content].module_id = module_list.id WHERE module_list.id NOT IN (7) AND role_id = $id_rol";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    return $jTableResult;
}

function listCountries() {
    global $db;
    $sql = "SELECT id as Value,name as DisplayText from country";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $rows;
    return $jTableResult;
}

function updateSitePermission($parameters = array()) {
    global $db;
    $id_rol = $parameters['rol'];
    $idents = $db->sql_escape($parameters['idents']);
    $elims = $db->sql_escape($parameters['elim']);
    $sql = "DELETE from regional_manager_permission WHERE role_id = $id_rol AND site_id IN($elims)";
    $result = $db->sql_query($sql);
    $siteids = explode(',', $idents);
    foreach ($siteids as $site) {
        $sql = 'INSERT INTO dbo.regional_manager_permission(role_id,site_id) VALUES (' . $id_rol . ',' . $site . ')';
        $db->sql_query($sql);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = array();
    $jTableResult['Record']['alias'] = "";
    $jTableResult['Record']['country_id'] = "";
    $jTableResult['Record']['id'] = "0";
    $jTableResult['Record']['site_id'] = "0";
    $jTableResult['Record']['title_es'] = "Se han guardado sus cambios, espere";
    return $jTableResult;
}

function updatePagePermission($parameters = array()) {
    global $db;
    $id_rol = $parameters['rol'];
    $idents = $db->sql_escape($parameters['idents']);
    $elims = $db->sql_escape($parameters['elim']);
    $sql = "DELETE from manager_permission WHERE role_id = $id_rol AND page_id IN($elims)";
    $result = $db->sql_query($sql);
    $pageids = explode(',', $idents);
    foreach ($pageids as $page) {
        $sql = 'INSERT INTO dbo.manager_permission(role_id,page_id) VALUES (' . $id_rol . ',' . $page . ')';
        $db->sql_query($sql);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = array();
    $jTableResult['Record']['alias'] = "";
    $jTableResult['Record']['country_id'] = "";
    $jTableResult['Record']['id'] = "0";
    $jTableResult['Record']['site_id'] = "0";
    $jTableResult['Record']['title_es'] = "Se han guardado sus cambios, espere";
    return $jTableResult;
}

function updateContentPermission($parameters = array()) {
    global $db;
    $id_rol = $parameters['rol'];
    $idents = $db->sql_escape($parameters['idents']);
    $elims = $db->sql_escape($parameters['elim']);
    $sql = "DELETE from editor_permission WHERE role_id = $id_rol AND content_id IN($elims)";
    $result = $db->sql_query($sql);
    $contentids = explode(',', $idents);
    foreach ($contentids as $content) {
        $sql = 'INSERT INTO dbo.editor_permission(role_id,content_id) VALUES (' . $id_rol . ',' . $content . ')';
        $db->sql_query($sql);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = array();
    $jTableResult['Record']['alias'] = "";
    $jTableResult['Record']['country_id'] = "";
    $jTableResult['Record']['id'] = "0";
    $jTableResult['Record']['site_id'] = "0";
    $jTableResult['Record']['site_name'] = "Se han guardado sus cambios, espere";
    return $jTableResult;
}

function cloneRole($parameters = array()) {
    global $db;
    $baserole = $db->sql_escape($parameters['baserole']);
    $newname = $db->sql_escape($parameters['newname']);
    $newdesc = $db->sql_escape($parameters['newdescription']);
    //clonar permisos
    $sql = "EXECUTE [dbo].[createRole]"
            . " @name ='$newname'"
            . ",@description='$newdesc'";
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $row = $db->sql_fetchrow($result);
        $newroleid = $row['id'];
        $sql_editor = 'INSERT INTO editor_permission(role_id,content_id) SELECT ' . $newroleid . ',content_id  FROM editor_permission WHERE role_id = ' . $baserole;
        $db->sql_query($sql_editor);
        $sql_gestor = 'INSERT INTO manager_permission(role_id,page_id) SELECT ' . $newroleid . ',page_id FROM manager_permission WHERE role_id=' . $baserole;
        $db->sql_query($sql_gestor);
        $sql_regional = 'INSERT INTO regional_manager_permission(role_id,site_id) SELECT ' . $newroleid . ',site_id FROM regional_manager_permission WHERE role_id=' . $baserole;
        $db->sql_query($sql_regional);
//        $sql_users = 'INSERT INTO admin_role(role_id,admin_id) SELECT '.$newroleid.',admin_id FROM admin_role WHERE role_id='.$baserole;
//        $db->sql_query($sql_users);
        $jTableResult['Result'] = "OK";
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo clonar el rol";
    }
    return $jTableResult;
}
?>

