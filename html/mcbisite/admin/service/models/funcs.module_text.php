<?php

function createModuleTextDataBase($parameters = array()) {
    $NewTextID = saveMoudelTextContentDataBase($parameters);

    return $NewTextID;
}

function saveMoudelTextContentDataBase($parameters = array()) {
    global $db, $loggedInUser;
    $new_module_text_id = '';
    $module_text_id = '';
//    print_r($parameters);
    $module_text_id = $parameters["id"];
	
    $parameters["enable_title"] = isset($parameters["enable_title"]) ? $parameters["enable_title"] : "1";
    $change_description = isset($parameters["ch_description"]) ? $parameters["ch_description"] : "Se actualizó el módulo de texto";
	
	$parameters["enable_title"] = intval($parameters["enable_title"]);

    $content_text_es = $parameters["text_es"];
    $content_text_en = $parameters["text_en"];
    $content_id = $parameters["content_id"];
    $enable_title = $parameters["enable_title"];
    
    $title_es = $parameters["title_es"];
    $title_en = isset($parameters["title_en"]) ? $parameters["title_en"] : $title_es;

    if($module_text_id == 'undefined' || $module_text_id == '-1'){
      $query_insertContent = "EXECUTE [dbo].[insertModuleText]
        @title_es = N'".$db->sql_escape($title_es)."'
       ,@title_en = N'".$db->sql_escape($title_en)."'
       ,@content_text_es = N'".$db->sql_escape($content_text_es)."'
       ,@enable_title = ".$db->sql_escape($enable_title)."
       ,@content_text_en = N'".$db->sql_escape($content_text_en)."'
       ,@content_id = ".$db->sql_escape($content_id)."
       ,@created_at = null";
      $new_module_text_id = $db->sql_fetchrowset($db->sql_query($query_insertContent));
      $new_module_text_id = $new_module_text_id[0]['id'];
      $sql = "EXECUTE [dbo].[EditedContent]"
              . "@content =".$db->sql_escape($new_module_text_id).""
              . ",@editor =".$db->sql_escape($loggedInUser->user_id).""
              . ",@description ='".$db->sql_escape($change_description)."' ";
      
      $result = $db->sql_query($sql);
    }else{
      $sql = "UPDATE module_text SET 
              title_es_edit = '" . $db->sql_escape($title_es) . "', 
              title_en_edit = '" . $db->sql_escape($title_en ) . "', 
              enable_title_edit = " . $enable_title . " , 
              content_text_es_edit = '" . $db->sql_escape($content_text_es) . "' , 
              content_text_en_edit = '" . $db->sql_escape($content_text_en) . "' "
            . " WHERE id = " . $db->sql_escape($parameters["id"]) . ";";

      $result = $db->sql_query($sql);
      // INSERTAR CAMBIOS 
      $sql = "EXECUTE [dbo].[EditedContent]"
              . "@content =".$db->sql_escape($content_id).""
              . ",@editor =".$db->sql_escape($loggedInUser->user_id).""
              . ",@description = '".$db->sql_escape($change_description)."'";
      
      $result = $db->sql_query($sql);        
      
      $db->sql_close();

      $new_module_text_id = $module_text_id;
    }

    

    return $new_module_text_id;
}

function ApproveModuleTextContent($parameters = array()){
    global $db,$db_table_prefix, $db_name; 
    $contentid = $parameters['content_id'];
    $sql = "UPDATE module_text SET title_es = title_es_edit, title_en = title_en_edit, enable_title=enable_title_edit, content_text_es = content_text_es_edit, content_text_en = content_text_en_edit WHERE content_id=".$db->sql_escape($contentid) ;
    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;
}
function DisapproveModuleTextContent($parameters = array()){
    global $db,$db_table_prefix, $db_name; 
    $contentid = $parameters['content_id'];
    $sql = "UPDATE module_text SET title_es_edit = title_es, title_en_edit = title_en, enable_title_edit=enable_title, content_text_es_edit = content_text_es, content_text_en_edit = content_text_en WHERE content_id=".$db->sql_escape($contentid) ;
    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;
}



function getMoudelTextContentDataBase($parameters=array())
{
  global $db,$db_table_prefix, $db_name; 
  $data = array();
  $parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
  
  $sql = "SELECT [id], [title_es_edit], [title_en_edit], [content_text_es_edit], [content_text_en_edit], [content_id]
  			FROM [".$db_name."].[dbo].[module_text]
  			WHERE id = ".$db->sql_escape($parameters["id"]);

  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  if($row){
    foreach ($row as $key => $val) {
      //$row[$key] = utf8_decode($row[$key]);
      $row[$key] = mb_convert_encoding($row[$key], "UTF-8", mb_detect_encoding($row[$key], "UTF-8, ISO-8859-1, ISO-8859-15", true));
      //$row[$key] = utf8_encode(htmlentities($row[$key],ENT_COMPAT,'UTF-8'));
    }
  }
  
  $data = $row;
  error_log("CALLING WEB SERVICE");
  //$data['sql'] = $sql;
  return $data;
}

function getModuleTextContentAdmin($parameters=array()){
error_log("Calling getModuleTextContentAdmin");
global $db,$db_table_prefix; 
	$data = array();
	$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
	
	$sql = "SELECT title_es_edit as title_es,title_en_edit as title_en,content_text_es_edit as content_text_es,content_text_en_edit as content_text_en,enable_title


  FROM ".$db_table_prefix."module_text WHERE id = " . $parameters["id"];

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if($row){
		foreach ($row as $key => $val) {
			//$row[$key] = utf8_decode($row[$key]);
			$row[$key] = mb_convert_encoding($row[$key], "UTF-8", mb_detect_encoding($row[$key], "UTF-8, ISO-8859-1, ISO-8859-15", true));
			//$row[$key] = utf8_encode(htmlentities($row[$key],ENT_COMPAT,'UTF-8'));
		}
	}
	
	$data = $row;
	//$data['sql'] = $sql;
	return $data;

}

?>
