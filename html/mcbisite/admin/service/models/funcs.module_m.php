<?php

//function that gets the tstrucure of module m

/*
function dumpear($msg, $x) {

    ob_start();
    var_dump($x);
    $contents = ob_get_contents();
    ob_end_clean();
    error_log($msg . " " . $contents);
}*/

function readModuleMediaDataBase($parameters) {
    global $db, $db_table_prefix;
    $data = array();

//$content_id = $parameters['content_id'];
    $module_id = $parameters['module_id'];


    $sql = "SELECT id, name_en_edit as name_en, name_es_edit as name_es, description_edit as description,transition_edit as transition 
          FROM " . $db_table_prefix . "mod_m 
          WHERE id= " . $module_id;

// error_log("Mensaje readModulem");

    if ($module_id != "") {
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);

        $data = $row;
//$data['row'] = $row;
    } else {
        $data = false;
    }

    return $data;
}

//Esta funcion agrega una foto a modulo media (m)

function addModuleMediaPhotoDataBase($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
    $parameters["url_media"] = (isset($parameters['url_media'])) ? $parameters['url_media'] : "";
    $type = $parameters['tipo'];
    $mod_m = $parameters['module_id'];
    $url = $parameters['url'];
    $tags = $db->sql_escape($parameters['tags']);

    $sql = " INSERT INTO " . $db_table_prefix . "media (
	  url_media,
	  type,
	  mod_m,
          url_media_edit,
          edit_status,
          current_status
	  )"
            . "VALUES('" . $db->sql_escape($url) . "'," .
            "" . $db->sql_escape($type) . "," .
            "" . $mod_m . "" . "," .
            "'" . $db->sql_escape($url) . "'" .
            ",1,0)";

    $db->sql_query($sql);
// $result = $db->sql_query($sql);
    global $db, $db_table_prefix;

    $sql2 = "SELECT id,url_media_edit as url_media, url_media_edit as url_media_2,type FROM" . $db_table_prefix . " media  WHERE mod_m=" . "" . $mod_m . " AND id=@@IDENTITY  ";
    $result = $db->sql_query($sql2);
    $result = $db->sql_fetchrowset($result);
    $id_media = $result[0]['id'];

    $result[0]['tags'] = $tags;
    if ($tags != '') {
        $tags = explode(",", $tags);

        foreach ($tags as &$tag) {
            $id_tag = insertTag($tag);
            $sql = "INSERT INTO tag_media(id_tag,id_media) VALUES({$id_tag},{$id_media})";
            $db->sql_query($sql);
        }
    }
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM mod_m WHERE id=".$mod_m ;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se subio una imagen (" . $url . ")'";
    $db->sql_query($sql);
    return $result;
}

function updateMediaDB($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
    $parameters["url_media"] = (isset($parameters['url_media'])) ? $parameters['url_media'] : "";
//$type =     $parameters['tipo'];
    $mod_m = $parameters['module_id'];
    $url = "";
    if (isset($parameters['url'])) {

        if (strlen($parameters['url']) > 0)
            $url = $parameters['url'];
        else
            $url = $parameters['url_media_2'];
    }else {

        $url = $parameters['url_media'];
    }
    $url = $parameters['url_media_2'];
    $id = $parameters['id'];
    $tags = $db->sql_escape($parameters['tags']);

    $sql = "SELECT url_media_edit as url FROM media WHERE id=" . $db->sql_escape($id);
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $urlbefore = $rows[0]["url"];

    $sql = " UPDATE " . $db_table_prefix . "media SET "
            . "url_media_edit='" . $db->sql_escape($url) . "'" .
            "" . " " .
            "WHERE id=" . $id;


    $db->sql_query($sql);
// $result = $db->sql_query($sql);
//    global $db, $db_table_prefix;

    $sql2 = "SELECT id,url_media_edit as url_media, url_media_edit as url_media_2,type FROM" . $db_table_prefix . " media  id=" . $id;

    $result = $db->sql_query($sql2);
    $result = $db->sql_fetchrowset($result);
//$id_media=$result[0]['id'];
    $result[0]['tags'] = $tags;


    $tags = explode(",", $tags);
    $sql = "DELETE FROM tag_media WHERE id_media=" . $id;
    $db->sql_query($sql);
    foreach ($tags as &$tag) {
        $id_tag = insertTag($tag);

        $sql = "INSERT INTO tag_media(id_tag,id_media) VALUES({$id_tag},{$id})";
        $db->sql_query($sql);
    }
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM mod_m WHERE id=" . $mod_m;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]["content_id"];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se modifico una imagen (" . $urlbefore . "->" . $url . ")'";
    $db->sql_query($sql);
    return $result;
}

function insertTag($tag) {
    global $db, $db_table_prefix;
    $sql = "SELECT id FROM tag WHERE tag='" . $tag . "'";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    $id = intval($result['id']);



    /*
      //Esto es para crear tags y validar si ya existen
      if(!$id>0){
      $sql="INSERT INTO tag(tag) VALUES('{$tag}')";
      $result= $db->sql_query($sql);
      $sql="SELECT id FROM TAG where id=@@IDENTITY";
      $result = $db->sql_query($sql);
      $result= $db->sql_fetchrow($result);
      $id=intval($result['id']);
      } */

    return $id;
}

function obtainImagesDB($parameters) {
    global $db, $db_table_prefix;
//error_log();
    $offset = $parameters["jtstartindex"];
    error_log(json_encode($parameters));
    $size = $parameters["jtpagesize"];
    $mod_m = $parameters['module_id'];
    $sql = "SELECT id,url_media_edit as url_media, url_media_edit as url_media_2,type FROM" . $db_table_prefix . " media 
    WHERE mod_m=" .
            "" . $mod_m . " AND edit_status = 1 

    ORDER BY id
    OFFSET {$offset} ROWS
    FETCH NEXT 5 ROWS ONLY

    ";

    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

    foreach ($result as &$tupla) {
        $tupla['tags'] = getTags($tupla['id']);
    }
    $jtable['Records'] = $result;

    $sql = "SELECT count (id) as total  FROM {$db_table_prefix}.media WHERE mod_m={$mod_m} AND edit_status=1";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);

    $jtable['RecordCount'] = $result['total'];

    return $jtable;
}

//Nuevo!
function getAllTagsDB($parameters) {
    global $db, $db_table_prefix;


    $sql = "SELECT tag FROM tag";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

//Nuevo!

function getTags($id_media) {
    global $db, $db_table_prefix;
    $tags = "";
    $sql = "SELECT tag FROM tag INNER JOIN
    tag_media ON
    tag.id=tag_media.id_tag
    AND id_media=" . $id_media;
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    if ($result)
        $tags = "";
    foreach ($result as &$tag) {
        $tags .= $tag['tag'] . ",";
    }
    $tags = rtrim($tags, ",");
    return $tags;
}

function deletePhotoDB($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
//error_log();

    $id = $parameters['id'];
//  $sql="DELETE FROM tag_media WHERE id_media=".$id;
//  $db->sql_query($sql);
//  $sql = " DELETE FROM media 
//          WHERE id=".
//      
//      "" .   $db->sql_escape($id) . " ";
    $sql = "UPDATE media SET edit_status = 0
          WHERE id=" .
            "" . $db->sql_escape($id) . " ";
//global $db, $db_table_prefix;
//
//$sql2="SELECT url_media_edit as url_media,type FROM media where id=".$db->sql_escape($id);
//   $result = $db->sql_query($sql2);
//   $result= $db->sql_fetchrow($result); 
//   $path=$result['url_media'];
//   $type=$result['type'];
//  if($type==1){
//    //Se procede a eliminar del servidor si se trata de una imagen
//    $file = basename($path); 
//    $folder='/var/www/html/mcbisite/assets/img/';
//
//    if(file_exists($folder.$file)){
//    unlink($folder.$file);
//    }else{
//     error_log("El archivo no pudo ser encotrado para eliminar.");
//    }
    $db->sql_query($sql);
    //obtener la url de la imagen para poden decir cual imagen se elimino
    $sql = "SELECT url_media_edit as url,mod_m FROM media WHERE id=" . $db->sql_escape($id);
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $url = $rows[0]["url"];
    $mod_m = $rows[0]["mod_m"];

    $sql = "DELETE  FROM tag_media WHERE id_media in (SELECT id FROM media WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM media WHERE edit_status = 0 and current_status = 0";
//SQL para eliminar
    $db->sql_query($sql);
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM mod_m WHERE id=" . $mod_m;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]["content_id"];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se elimino una imagen  (" . $url . ")'";
    $db->sql_query($sql);
}

function obtainVideos($parameters) {
    global $db, $db_table_prefix;

    $mod_m = $parameters['mod_m'];


    $sql = "SELECT id, url_media_edit FROM" . $db_table_prefix . " media 
WHERE mod_m=" .
            "" . $mod_m . " AND type=2 AND edit_status = 1";

    error_log($sql);
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

function ApprovedModuleMedia($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "UPDATE mod_m SET name_es = name_es_edit, name_en = name_en_edit,description = description_edit, transition = transition_edit WHERE content_id =" . $contentid;
    $db->sql_query($sql);
    $sql = "SELECT id from mod_m WHERE content_id=".$contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $id = $rows[0]["id"];
    $sql = "UPDATE media SET url_media = url_media_edit, current_status = edit_status WHERE mod_m=".$id;
    $db->sql_query($sql);
    $sql = "DELETE  FROM tag_media WHERE id_media in (SELECT id FROM media WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM media WHERE edit_status = 0 and current_status = 0";
    $db->sql_query($sql);
}

function DisapprovedModuleMedia($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "UPDATE mod_m SET name_es_edit = name_es, name_en_edit = name_en, description_edit = description, transition_edit = transition WHERE content_id = " . $contentid;
    $db->sql_query($sql);
    $sql = "SELECT id from mod_m WHERE content_id=".$contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $id = $rows[0]["id"];
    $sql = "UPDATE media SET url_media_edit = url_media, edit_status = current_status WHERE mod_m=".$id;
    $db->sql_query($sql);
    $sql = "DELETE  FROM tag_media WHERE id_media in (SELECT id FROM media WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM media WHERE edit_status = 0 and current_status = 0";
    $db->sql_query($sql);
}

?>