<?php

//function that gets the tstrucure of module m


function dumpear($msg,$x){

ob_start();
var_dump($x);
$contents = ob_get_contents();
ob_end_clean();
error_log($msg." ".$contents);

}
function readModuleMediaDataBase($parameters)
{
  global $db,$db_table_prefix; 
  $data = array();

  //$content_id = $parameters['content_id'];
  $module_id = $parameters['module_id'];
  //dumpear("aqui va params",$parameters);


    $sql="SELECT id, name_en, name_es, description,transition 
          FROM " . $db_table_prefix . "module_carrousel 
          WHERE id= " . $module_id;
    
       // error_log("Mensaje readModulem");

    if($module_id != ""){
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);
      
        $data = $row;
        //$data['row'] = $row;
    }else{
        $data = false;
    }

  return $data;
}



//Esta funcion agrega una foto a modulo media (m)

function addModuleMediaPhotoDataBase($parameters)
{
	global $db, $db_table_prefix;
	$parameters["url_media"] = (isset($parameters['url_media'])) ? $parameters['url_media'] : "";
	$parameters["tipo"] = (isset($parameters['tipo'])) ? $parameters['tipo'] : "";
	$parameters["link"] = (isset($parameters['link'])) ? $parameters['link'] : "";
	//dumpear("vamos a dumpear los parametros> ",$parameters);
	//$url_media =  $parameters['url_media'];
	$type =       $parameters['tipo'];
	$mod_m=       $parameters['module_id'];
	$url=       $parameters['url'];
	$link=       $parameters['link'];
	
	$sql="";
	
	$sql = " INSERT INTO " . $db_table_prefix . "carrousel_content (
				url_media,
				type,
				module_carrousel_id,
				link
	  )"
	 ."VALUES('" .$db->sql_escape($url)."'".
		  ",".
		  "" .   $db->sql_escape($type) . ",".
		  "" .   $mod_m . ",
		  '".$db->sql_escape($link)."'".
	 
	  ")";
	// error_log("La consulta INSERSION >>".$sql);
	$db->sql_query($sql);
	// $result = $db->sql_query($sql);
	
	global $db, $db_table_prefix;
	
	$sql2 = "SELECT id,url_media, url_media as url_media_2,type, link FROM" . $db_table_prefix . " carrousel_content  WHERE module_carrousel_id="."" .   $mod_m . " AND id=@@IDENTITY  ";
	
	$result = $db->sql_query($sql2);
	$result= $db->sql_fetchrowset($result);
	  
	return $result;
}




function obtainImagesDB($parameters){
global $db, $db_table_prefix;
//error_log();
  $mod_m=       $parameters['module_id'];
  $sql = "SELECT id,url_media, url_media as url_media_2,type, link FROM" . $db_table_prefix . " carrousel_content 
      WHERE module_carrousel_id=".
      
      "" .   $mod_m . " ";
     
//error_log($sql);
    $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);
  return $result;
}



function deletePhotoDB($parameters){
	global $db, $db_table_prefix;
	//error_log();
	$id=       $parameters['id'];
	$sql = " DELETE FROM carrousel_content 
		  WHERE id=" .   $db->sql_escape($id) . " ";
		 
	global $db, $db_table_prefix;
	
	$sql2="SELECT url_media,type, link FROM carrousel_content where id=".$db->sql_escape($id);
	$result = $db->sql_query($sql2);
	$result= $db->sql_fetchrow($result); 
	$path=$result['url_media'];
	$type=$result['type'];
	if($type==1){
	//Se procede a eliminar del servidor si se trata de una imagen
	$file = basename($path); 
	$folder='/var/www/html/mcbisite/admin/assets/img/';
	unlink($folder.$file);
	}
  	//SQL para eliminar
    $db->sql_query($sql);

}



function obtainVideos($parameters){
global $db, $db_table_prefix;

  $mod_m=       $parameters['mod_m'];


  $sql = "SELECT id, url_mediam, link  FROM" . $db_table_prefix . " carrousel_content 
      WHERE module_carrousel_id=".
      
      "" .   $db->sql_escape($mod_m) . " AND type=2";
     
error_log($sql);
    $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);
  return $result;
}


//You can update specific mod_c
function updatePhotoDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[carrousel_content]  SET 
					link = '" . $db->sql_escape($parameters["link"]) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
		//return $sql;

    $result = $db->sql_query($sql);
    return $result;
}




?>