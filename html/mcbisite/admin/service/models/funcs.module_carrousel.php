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

function readModuleCarrouselDataBase($parameters) {
    global $db, $db_table_prefix;
    $data = array();

    //$content_id = $parameters['content_id'];
    $module_id = $parameters['module_id'];


    $sql = "SELECT id, name_en_edit as name_en, name_es_edit as name_es, description_edit as description,transition_edit as transition 
          FROM " . $db_table_prefix . "module_carrousel 
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

function addModuleCarrouselPhotoDataBase($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
    $parameters["url_media"] = (isset($parameters['url_media'])) ? $parameters['url_media'] : "";
    $parameters["link"] = (isset($parameters['link'])) ? $parameters['link'] : "";
    $type = $parameters['tipo'];
    $module_carrousel = $parameters['module_id'];
    $url = $parameters['url'];
    $lang = $parameters['lang'];
    $tags = $db->sql_escape($parameters['tags']);

    $sql = " INSERT INTO " . $db_table_prefix . "carrousel_content(
				url_media,
				url_media_edit,
				type,
				module_carrousel_id,
				edit_status,
				current_status,
                                link,
				link_edit,
                                lang,
                                lang_edit
			)"
			. "VALUES('" . $db->sql_escape($url) . "'" .
				"" . "," .
				"'" . $db->sql_escape($url) . "'," .
				"" . $db->sql_escape($type) . "," .
				"" . $module_carrousel . "" .",
				1,
				0,".
				"'" . $db->sql_escape($parameters["link"]) . "',".
                                 "'" . $db->sql_escape($parameters["link"]) . "',".
                                  "'" . $db->sql_escape($lang) . "',".
                                 "'" . $db->sql_escape($lang) . "'".
			")";

    $db->sql_query($sql);
    // $result = $db->sql_query($sql);
    global $db, $db_table_prefix;

    $sql2 = "SELECT id,url_media, url_media as url_media_2,type, link_edit as link FROM" . $db_table_prefix . " carrousel_content  WHERE module_carrousel_id=" . "" . $module_carrousel . " AND id=@@IDENTITY  ";
    $result = $db->sql_query($sql2);
    $result = $db->sql_fetchrowset($result);
    $id = $result[0]['id'];

    $result[0]['tags'] = $tags;
    if ($tags != '') {
        $tags = explode(",", $tags);

        foreach ($tags as &$tag) {
            $id_tag = insertTagCarrousel($tag);
            $sql = "INSERT INTO tag_content(id_tag,id_carrousel_content) VALUES({$id_tag},{$id})";
            $db->sql_query($sql);
        }
    }
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM module_carrousel WHERE id=" . $module_carrousel;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]["content_id"];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se subio una imagen (" . $url . ")'";
    $db->sql_query($sql);
    return $result;
}

function updateCarrouselDB($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
    $parameters["url_media"] = (isset($parameters['url_media'])) ? $parameters['url_media'] : "";
    $parameters["link"] = (isset($parameters['link'])) ? $parameters['link'] : "";
//$type =     $parameters['tipo'];
    $module_carrousel = $parameters['module_id'];
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

//    $sql = " UPDATE " . $db_table_prefix . "carrousel_content SET "
//            . "url_media_edit='" . $db->sql_escape($url) . "'" .
//            "" . " " .
//            "WHERE id=" . $id;
//
//
//    $db->sql_query($sql);
//    // $result = $db->sql_query($sql);
//
//    global $db, $db_table_prefix;
    $sql = "SELECT url_media_edit as url, url_media_edit url_media FROM carrousel_content WHERE id=" . $db->sql_escape($id);
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $urlbefore = $rows[0]["url"];
    $sql = "UPDATE carrousel_content SET url_media_edit='" . $url . "', link_edit = '".$db->sql_escape($parameters["link"])."', lang_edit = '".$db->sql_escape($parameters["lang"])."'  WHERE id=" . $id;
    $db->sql_query($sql);


    $sql2 = "SELECT id,	
					url_media_edit,
					url_media_edit as url_media, 
					url_media_edit as url_media_2,
					type, 
					link_edit as link 
			  FROM " . $db_table_prefix . "carrousel_content  WHERE id=" . $id;
    $result = $db->sql_query($sql2);
    $result = $db->sql_fetchrowset($result);
    //$id_carrousel_content =$result[0]['id'];
    $result[0]['tags'] = $tags;


    $tags = explode(",", $tags);
    $sql = "DELETE FROM tag_carrousel_content WHERE id_carrousel_content =" . $id;
    $db->sql_query($sql);
    foreach ($tags as &$tag) {
        $id_tag = insertTagCarrousel($tag);

        $sql = "INSERT INTO tag_carrousel_content(id_tag,id_carrousel_content) VALUES({$id_tag},{$id})";
        $db->sql_query($sql);
    }
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM module_carrousel WHERE id=" . $module_carrousel;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]["content_id"];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se modifico una imagen (" . $urlbefore . "->" . $url . ")'";
    $db->sql_query($sql);
    return $result;
}

function insertTagCarrousel($tag) {
    global $db, $db_table_prefix;
    $sql = "SELECT id FROM tag WHERE tag='" . $tag . "'";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    $id = intval($result['id']);


    /*
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

function obtainCarrouselImagesDB($parameters) {
    global $db, $db_table_prefix;
//error_log();
    $module_carrousel = $parameters['module_id'];
    $sql = "SELECT id,url_media_edit as url_media, url_media_edit as url_media_2,type, link_edit as link, lang_edit as lang  FROM" . $db_table_prefix . " carrousel_content
    WHERE module_carrousel_id=" .
            "" . $module_carrousel . " AND edit_status=1";

    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

    foreach ($result as &$tupla) {
        $tupla['tags'] = getCarrouselTags($tupla['id']);
    }
    return $result;
}

//Nuevo!
function getAllCarrouselTagsDB($parameters) {
    global $db, $db_table_prefix;
    //$module_id=$parameters['module_id'];

    $sql = "SELECT tag FROM tag";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

//Nuevo!
function getCarrouselTags($id_carrousel_content) {
    global $db, $db_table_prefix;
    $tags = "";
    $sql = "SELECT tag FROM tag INNER JOIN
    tag_carrousel_content ON
    tag.id=tag_carrousel_content.id_tag
    AND id_carrousel_content =" . $id_carrousel_content;
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

function deleteCarrouselPhotoDB($parameters) {
    global $db, $db_table_prefix, $loggedInUser;
//error_log();

    $id = $parameters['id'];
//    $sql = "DELETE FROM tag_carrousel_content WHERE id_carrousel_content =" . $id;
//    $db->sql_query($sql);
//    Ya no se borran los tags
    $sql = "UPDATE carrousel_content SET edit_status = 0
          WHERE id=" .
            "" . $db->sql_escape($id) . " ";

//    $sql2 = "SELECT url_media_edit,type FROM carrousel_content where id=" . $db->sql_escape($id);
//    $result = $db->sql_query($sql2);
//    $result = $db->sql_fetchrow($result);
//    $path = $result['url_media'];
//    $type = $result['type'];
//    if ($type == 1) {
//        //Se procede a eliminar del servidor si se trata de una imagen
//        $file = basename($path);
//        $folder = '/var/www/html/mcbisite/assets/img/';
//
//        if (file_exists($folder . $file)) {
//            unlink($folder . $file);
//        } else {
//            error_log("El archivo no pudo ser encotrado para eliminar.");
//        }
//    }
//    Ya no se eliminan los archivos del servidor pero cuando los dos stauts estan en 0 se elimina el registro
    $db->sql_query($sql);
    //obtener la url de la imagen para poden decir cual imagen se elimino
    $sql = "SELECT url_media_edit as url, url_media_edit url_media, module_carrousel_id FROM carrousel_content WHERE id=" . $db->sql_escape($id);
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $url = $rows[0]["url"];
    $module_carrousel = $rows[0]["module_carrousel_id"];
    $sql = "DELETE  FROM tag_carrousel_content WHERE id_carrousel_content in (SELECT id FROM carrousel_content WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM carrousel_content WHERE edit_status = 0 and current_status = 0";
    //SQL para eliminar
    $db->sql_query($sql);
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM module_carrousel WHERE id=" . $module_carrousel;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]["content_id"];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se elimino una imagen  (" . $url . ")'";
    $db->sql_query($sql);
}

function obtainCarrouselVideos($parameters) {
    global $db, $db_table_prefix;

    $module_carrousel = $parameters['module_carrousel'];


    $sql = "SELECT id, url_media_edit as url_media FROM" . $db_table_prefix . " carrousel_content
      WHERE module_carrousel=" .
            "" . $module_carrousel . " AND type=2 AND edit_status=1";

    error_log($sql);
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

function ApprovedModuleCarrousel($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "UPDATE module_carrousel SET name_es = name_es_edit, name_en = name_en_edit,description = description_edit, transition = transition_edit WHERE content_id =" . $contentid;
    $db->sql_query($sql);
    $sql = "SELECT id from module_carrousel WHERE content_id=".$contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $id = $rows[0]["id"];
    $sql = "UPDATE carrousel_content SET url_media = url_media_edit, current_status = edit_status, link=link_edit,lang=lang_edit WHERE module_carrousel_id=".$id;
    $db->sql_query($sql);
    $sql = "DELETE  FROM tag_carrousel_content WHERE id_carrousel_content in (SELECT id FROM carrousel_content WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM carrousel_content WHERE edit_status = 0 and current_status = 0";
    $db->sql_query($sql);
}

function DisapprovedModuleCarrousel($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "UPDATE module_carrousel SET name_es_edit = name_es, name_en_edit = name_en,description_edit = description, transition_edit = transition WHERE content_id =" . $contentid;
    $db->sql_query($sql);
    $sql = "SELECT id from module_carrousel WHERE content_id=".$contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $id = $rows[0]["id"];
    $sql = "UPDATE carrousel_content SET url_media_edit = url_media, edit_status = current_status, link_edit=link,lang_edit=lang WHERE module_carrousel_id=".$id;
    $db->sql_query($sql);
    $sql = "DELETE  FROM tag_carrousel_content WHERE id_carrousel_content in (SELECT id FROM carrousel_content WHERE edit_status = 0 and current_status = 0)";
    $db->sql_query($sql);
    $sql = "DELETE FROM carrousel_content WHERE edit_status = 0 and current_status = 0";
    $db->sql_query($sql);
}

?>