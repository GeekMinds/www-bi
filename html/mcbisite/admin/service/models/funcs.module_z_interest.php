<?php
header('Access-Control-Allow-Origin: *');



function addInterestDB($name_es,$name_en,$interest_type,$img_url,$tags){
	global $db, $db_table_prefix;
	$tags=$db->sql_escape($tags);
	$sql = " INSERT INTO " . $db_table_prefix . "Interest (
	    name_es_edit,
	    name_en_edit,
	    interest_type_edit,
	    img_url_edit,
            removed,
            removed_edit
		)"
	    . "VALUES('" .$db->sql_escape($name_es)."'".
	    ""  .",".
	    "'" .   $db->sql_escape($name_en) . "',".
	    "'" .   $db->sql_escape($interest_type) . "',".
	    "'" .   $db->sql_escape($img_url) . "',".
            "1,".  
	    "0".  
	    ")"
	;
	
	$db->sql_query($sql);
	global $db, $db_table_prefix;
	$sql2 = "SELECT id,name_es,name_en,interest_type,img_url FROM " . $db_table_prefix . "interest " . " WHERE id=@@IDENTITY  ";
	$result = $db->sql_query($sql2);
	$result= $db->sql_fetchrow($result);
	$result['tags']=$tags;
	$id=$result['id'];
        InsertNotification("Se ha creado un nuevo interes: ".$name_es,$id);
	$tags = explode(",", $tags);
	foreach ($tags as &$tag){
	  $tag_id=getTagID($tag);
	  $sql="INSERT INTO tag_interest(tag_id,interest_id) VALUES({$tag_id},{$id})";
	  $db->sql_query($sql);
	}										
	return $result;
}


function getAllTagsDB($parameters){
  global $db, $db_table_prefix;
  $sql="SELECT tag FROM tag";
  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrowset($result);
  return $result;
}
//Nuevo!

function getTags($interest_id){
  global $db, $db_table_prefix;
  $tags="";
  $sql="SELECT tag FROM tag INNER JOIN
    tag_interest ON
    tag.id=tag_interest.tag_id
    AND interest_id=".$interest_id;
  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrowset($result);
  if($result)$tags="";
  foreach ($result as &$tag) {
    $tags .= $tag['tag'].",";
  }
  $tags=rtrim($tags, ",");
  return $tags;
}






function updateInterestDB($id,$name_es,$name_en,$interest_type,$img_url,$tags){

	global $db, $db_table_prefix;
	$sql = " UPDATE " . $db_table_prefix . "interest SET
		"
	    . "name_es_edit='" .$db->sql_escape($name_es)."'".
	    ""  .",".
	    "name_en_edit='" .   $db->sql_escape($name_en) . "',".
	    " interest_type_edit='" .   $db->sql_escape($interest_type) . "',".
	    " img_url_edit='" .   $db->sql_escape($img_url) . "'".
	   
	    " WHERE id=".$db->sql_escape($id)
	;
	$db->sql_query($sql);
        InsertNotification("Se ha editado el interes ".$db->sql_escape($name_es),$id);
	$tags = explode(",", $tags);
	$sql="DELETE FROM tag_interest WHERE interest_id=".$id;
	$db->sql_query($sql);
	foreach ($tags as &$tag) {
	  $tag_id=getTagID($tag);
	  $sql="INSERT INTO tag_interest(tag_id,interest_id) VALUES({$tag_id},{$id})";
	  $db->sql_query($sql); 
	}

}

function getTagID($tag){
	global $db, $db_table_prefix;
	$sql="SELECT id FROM tag WHERE tag='{$tag}'";
	$result = $db->sql_query($sql);
	$result= $db->sql_fetchrow($result);
	$id=intval($result['id']);

	//Esto es para crear tags y validar si ya existen
	if(!$id>0){
	  $sql="INSERT INTO tag(tag) VALUES('{$tag}')";
	  $result= $db->sql_query($sql);
	  $sql="SELECT id FROM TAG where id=@@IDENTITY";
	  $result = $db->sql_query($sql);
	  $result= $db->sql_fetchrow($result);
	  $id=intval($result['id']);
	}
	return $id;
}








function deleteInterestDB($id){
	
	global $db, $db_table_prefix;
	/*
		Eliminar en la base de datos
		Eliminar en el sistema de archivos la imagen
	*/
        $sql="SELECT count(1) as conteo from " . $db_table_prefix . "user_interest where interest_id=".$id;
        $result = $db->sql_query($sql);
	$result= $db->sql_fetchrow($result);
        $conteo = $result["conteo"];
        if(intval($conteo)>0){
            
            return false;   
        }else{
            $sql = "Update " . $db_table_prefix . "interest set removed_edit=1 where id=".$id;
            $db->sql_query($sql);
            InsertNotification("Se ha eliminado el interes", $id);
            return true;
        }
//  	$sql="DELETE FROM tag_interest WHERE interest_id=".$id;
//  	
//        $db->sql_query($sql);
//
//
//
//
//
//	$sql2="SELECT img_url FROM interest where id=".$db->sql_escape($id);
//   	$result2 = $db->sql_query($sql2);
//   	$result2= $db->sql_fetchrow($result2); 
//   	$path=$result2['img_url'];
//    //Se procede a eliminar del servidor si se trata de una imagen
//    //error_log("El path es ".$path);
//    $file = basename($path); 
//    $folder='/var/www/html/mcbisite/admin/assets/img/';
//    //error_log($folder.$file);
//    
//
//	$sql2 = "DELETE FROM " . $db_table_prefix . "interest WHERE id= ".$db->sql_escape($id);
//	$result = $db->sql_query($sql2);
//        if($result){
//            unlink($folder.$file);
//        }
	return $result;
}


function getInterestDB($parameters=array()){
	global $db, $db_table_prefix,$db_name;;
	$data = array();
	$parameters['type'] = (isset($parameters['type'])) ? $parameters['type'] : '';
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,name_es_edit as name_es,name_en_edit as name_en,interest_type_edit as interest_type,img_url_edit as img_url FROM [".$db_name."].[dbo].[interest] where removed_edit=0)
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT id,name_es_edit as name_es,name_en_edit as name_en,interest_type_edit as interest_type,img_url_edit as img_url FROM [".$db_name."].[dbo].[interest] where removed_edit=0 ";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[interest] where removed_edit=0 ";
	
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);

	foreach ($result as &$tupla) {
    $tupla['tags']=getTags($tupla['id']);
  	}

	$result_count= $db->sql_fetchrow($result_count);
	$data["result"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}

function InsertNotification($msg,$id){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . " @editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type = 3"
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModuleInteres($parameters){
    global $db;
    $id = $parameters['id_change'];
    //Copiamos la informacion del backup en la oficial
    $sql = "update interest set name_es=name_es_edit,name_en=name_en_edit,img_url=img_url_edit,interest_type=interest_type_edit,removed=removed_edit where id=".$db->sql_escape($id);
    $db->sql_query($sql);
    //Eliminamos algun interes que no haya sido aprobado
    $sql = "delete from interest where removed=1 and removed_edit=1 and id=".$db->sql_escape($id);
    $db->sql_query($sql);
    
}
function DisapprovedModuleInteres($parameters){
   global $db;
    $id = $parameters['id_change'];
    //Copiamos la informacion del backup en la oficial
    $sql = "update interest set name_es_edit=name_es,name_en_edit=name_en,img_url_edit=img_url,interest_type_edit=interest_type,removed_edit=removed where id=".$db->sql_escape($id);
    $db->sql_query($sql);
    //Eliminamos algun interes que no haya sido aprobado
    $sql = "delete from interest where removed=1 and removed_edit=1 and id=".$db->sql_escape($id);
    $db->sql_query($sql); 
}


?>