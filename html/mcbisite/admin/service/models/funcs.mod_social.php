<?php

//function that gets the tstrucure of module m


function dumpear($x) {

    ob_start();
    var_dump($x);
    $contents = ob_get_contents();
    ob_end_clean();
    error_log(" " . $contents);
}

//Esta funcion agrega una foto a modulo media (m)

function insertSocialDB($parameters) {
    global $db, $db_table_prefix, $site_id;
    $result = "";
    $name = $db->sql_escape($parameters['name']);
    $url = $db->sql_escape($parameters['url']);
    if (mb_substr($url, 0, 4) !== 'http') {
        $url = 'https://' . $url;
    }

//  $url=   parse_url($url);
    //dumpear($url);
//  if (!isset($url['host'])) goto fin;
//  if(strlen($url['host'])>0){
//    if(isset($url['path'])){
//      $url=   $url['host'].$url['path'];  
//    }
//    else{
//      $url=   $url['host'];
//    }


    $image = $db->sql_escape($parameters['image']);
    $id_site = $site_id;
    //$id_site=$parameters['id_site'];

    $sql = " INSERT INTO " . $db_table_prefix . "mod_social(
      name,name_edit,url,url_edit,image,image_edit,id_site,status,status_edit) VALUES(" .
            "'" . $name . "'  ," .
            "'" . $name . "'  ," .
            "'" . $url . "'  ," .
            "'" . $url . "'  ," .
            "'" . $image . "'  ," .
            "'" . $image . "'  ," .
            "'" . $id_site . "',0,1  " .
            ")";
    $result = $db->sql_query($sql);
    if ($result) {
        global $db, $db_table_prefix;
        $sql2 = "SELECT id,name_edit as name,url_edit as url,image_edit as image,id_site FROM" . $db_table_prefix . " mod_social
      WHERE id=@@IDENTITY AND status_edit=1 ";
        $result = $db->sql_query($sql2);
        $result = $db->sql_fetchrow($result);
        insertarCambios($result['id'], 'Se creo una nueva red social (' . $name . ')');
    }
//  }   
//  fin:
    return $result;
}

function insertarCambios($id, $msg) {
    global $db, $loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type =8"
            . ",@description='" . $msg . "'";
    $db->sql_query($sql);
}

function updateSocialDB($parameters) {
    global $db, $db_table_prefix, $site_id;
    $result = "";
    $name = $db->sql_escape($parameters['name']);
    $url = $db->sql_escape($parameters['url']);
    $id = $db->sql_escape($parameters['id']);
    $sqlprev = "SELECT name from mod_social WHERE id = $id";
    $resultprev = $db->sql_fetchrow($db->sql_query($sqlprev));
    if (mb_substr($url, 0, 4) !== 'http')
        $url = 'http://' . $url;

//  $url=   parse_url($url);
//  //dumpear($url);
//
//  if (!isset($url['host'])) goto fin;
//
// 
//  if(strlen($url['host'])>0){
//    if(isset($url['path'])){
//      $url=   $url['host'].$url['path'];  
//    }
//    else{
//      $url=   $url['host'];
//    }


    $image = $db->sql_escape($parameters['image']);
    $id_site = $site_id;

    $sql = " UPDATE " . $db_table_prefix . "mod_social
      SET " .
            "name_edit='" . $name . "'  ," .
            "url_edit='" . $url . "'  ," .
            "image_edit='" . $image . "'  ," .
            "id_site='" . $id_site . "'  " .
            "WHERE id=" . $id;

    error_log($sql);
    $result = $db->sql_query($sql);
    if ($result) {
        global $db, $db_table_prefix;
        $sql2 = "SELECT id,name_edit as name,url_edit as url,image_edit as image,id_site FROM " . $db_table_prefix . " mod_social
      WHERE id=$id  ";
        $result = $db->sql_query($sql2);
        $result = $db->sql_fetchrow($result);
        insertarCambios($result['id'], 'Se actualizó una red social (' . $resultprev['name'] . ')->(' . $name . ')');
    }
//  }   
//  fin:
    return $result;
}

;

function deleteSocialDB($parameters) {
    $result = false;
    $id = $parameters['id'];
    global $db, $db_table_prefix;
    $sqlprev = "SELECT name from mod_social WHERE id = $id";
    $resultprev = $db->sql_fetchrow($db->sql_query($sqlprev));
    $sql2 = "UPDATE mod_social SET status_edit = 0 WHERE id= " . $db->sql_escape($id);
    $result = $db->sql_query($sql2);
    insertarCambios($id, 'Se eliminó una red social (' . $resultprev['name'] . ')');
    return $result;
}

function listSocialDB() {
    global $db, $db_table_prefix, $site_id;
    $sql2 = "SELECT id,name_edit as name,url_edit as url,image_edit as image,id_site 
  			FROM" . $db_table_prefix . " mod_social 
			WHERE status_edit= 1 AND
				id_site = " . $db->sql_escape($site_id);
    $result = $db->sql_query($sql2);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

function listSitesDB() {
    global $db, $db_table_prefix;
    $sentencia = "SELECT id,title_es FROM site ";
    $result = $db->sql_query($sentencia);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

function approveMod_social($parameters = array()) {
    global $db;
    $id = $parameters['id_change'];
    $sql = "UPDATE mod_social SET name = name_edit,url = url_edit,image = image_edit,status = status_edit WHERE id= $id";
    $result = $db->sql_query($sql);
    $sql = "DELETE FROM mod_social WHERE status=0 AND status_edit = 0";
    $result = $db->sql_query($sql);
}

function disapproveMod_social($parameters = array()) {
    global $db;
    $id = $parameters['id_change'];
    $sql = "UPDATE mod_social SET name_edit = name,url_edit = url,image_edit = image,status_edit = status WHERE id= $id";
    $result = $db->sql_query($sql);
    $sql = "DELETE FROM mod_social WHERE status=0 AND status_edit = 0";
    $result = $db->sql_query($sql);
}

?>