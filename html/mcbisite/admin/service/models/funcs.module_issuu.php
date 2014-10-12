<?php

/*function createModuleTextDataBase($parameters = array()) {
    $NewTextID = saveMoudelTextContentDataBase($parameters);

    return $NewTextID;
}*/


function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function getApiKeyDataBase($parameters = array()) {
    global $db;
    $data = array();
    $new_module_text_id = '';
    $module_text_id = '';
//     
    
    return $data;
}

function createModuleIssuuDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "INSERT INTO  " . $db_table_prefix . "module_issuu (
			html_code_edit,
			content_id,
			download_url_edit,
			issue_code_edit,
            name_edit,
            title_edit
		
			)
			VALUES (".
			"'" . $db->sql_escape($parameters["html_code"]) . "',".
			  $db->sql_escape($parameters["content_id"]) . " ,".
			"'" . $db->sql_escape($parameters["download_url"]) . "',".
			"'" . $db->sql_escape($parameters["issue_code"]). "',".
			"'". $db->sql_escape($parameters["name"]) . "'," .
            "'" . $db->sql_escape($parameters["title"]) . "' 
			)";
			
	error_log($sql);

   // echo"<br>SQL DE LA CREACION:".$sql;
    $result = $db->sql_query($sql);
    if ($result) {
        return true;
    }
    return false;
}

function updateModuleIssuuDataBase($parameters = array()) {
    global $db, $db_table_prefix;



    $sql="UPDATE ". $db_table_prefix . "module_issuu SET ".
    "html_code_edit=". "'" . $db->sql_escape($parameters["html_code"]) . "',".
    "download_url_edit=". "'" . $db->sql_escape($parameters["download_url"]) . "',".
    "issue_code_edit=". "'" . $db->sql_escape($parameters["issue_code"]) . "',".
    "title_edit=". "'" . $db->sql_escape($parameters["title"]) . "',".
    "name_edit=". "'" . $db->sql_escape($parameters["name"]) . "'".       
    " WHERE id=". $db->sql_escape($parameters["module_id"]);
			
			
    $result = $db->sql_query($sql);
    if ($result) {
        return $sql;
    }
    return $sql;
}

function readModuleIssuuDataBase($module_id) {
    global $db, $db_table_prefix;

    $sql = "SELECT html_code_edit as html_code,name_edit as name,title_edit as title,download_url_edit as download_url,issue_code_edit as issue_code FROM ". $db_table_prefix . "module_issuu"
    ." WHERE "
    //."content_id=". $db->sql_escape($parameters["content_id"]) 
    //."AND"
    ."id=". $db->sql_escape($module_id)
    
    ;
 
//error_log($sql) ;
    $result = $db->sql_query($sql);
   $rows = $db->sql_fetchrowset($result);
    return $rows;
}


function deleteISSUU($module_id){

   global $db, $db_table_prefix;

    $sql = "DELETE FROM ". $db_table_prefix . "module_issuu"
    ." WHERE "
    ."id=". $db->sql_escape($module_id);
  
    $db->sql_query($sql);
}
function changefileISSUU($module_id){
    global $db, $db_table_prefix;
    $sql ="Update ". $db_table_prefix . "module_issuu SET ".
    "html_code_edit = 'NO FILE' where id=".$db->sql_escape($module_id);

    $db->sql_query($sql);
    
    
}

function verifyNameISUU($name) {
    global $db, $db_table_prefix;

    $sql = "SELECT id FROM ". $db_table_prefix . "module_issuu"
    ." WHERE "
    ."name_edit = '". $db->sql_escape($name)."' OR name = '". $db->sql_escape($name)."'"
    
    ;

  $result = $db->sql_query($sql);
   $rows = $db->sql_fetchrowset($result);
   $count=count($rows);
   return $count;
}
function verifyTitleISUU($name) {
    global $db, $db_table_prefix;

    $sql = "SELECT id FROM ". $db_table_prefix . "module_issuu"
    ." WHERE "
    ."title_edit = '". $db->sql_escape($name)."' OR title = '". $db->sql_escape($name)."'"
    
    ;

  $result = $db->sql_query($sql);
   $rows = $db->sql_fetchrowset($result);
   $count=count($rows);
   return $count;
}
function ApprovedModuleISSUU($parameters = array()){
    global $db;
    $content_id = $parameters['content_id'];
    $sql = "Select name,id from module_issuu where content_id=".$content_id;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $name = $db->sql_escape($row['name']);
    if(strlen($name)>0){
       deleteFile($row['id'], $name); 
    }

    $sql="update module_issuu set html_code = html_code_edit,download_url=download_url_edit,issue_code=issue_code_edit,title=title_edit,name=name_edit where content_id=".$db->sql_escape($content_id);
    
    $db->sql_query($sql);
    
}
function DisapprovedModuleISSUU($parameters = array()){
    global $db;

    $content_id = $parameters['content_id'];
    $sql = "Select name_edit,id from module_issuu where content_id=".$content_id;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $name = $db->sql_escape($row['name_edit']);
    if(strlen($name)>0){
       deleteFile($row['id'], $name); }
    $sql="update module_issuu set html_code_edit = html_code,download_url_edit=download_url,issue_code_edit=issue_code,title_edit=title,name_edit=name where content_id=".$db->sql_escape($content_id);
    $db->sql_query($sql);
    
}
function deleteFile($module_id,$name){

$key=			'qc7e1dsf6vs5uniq2eqws2r4ul6okhp1';



$action=		'action'.'issuu.document.delete';
$apiKey =		'apiKey'.'fkcar3k11kqafz3yn2g6v113d4yx68dh';
$format =		'format'.'json';
$names=			'names'.$name;
$signature=		md5($key.$action.$apiKey.$format.$names);

$data = 
"action=" 		. "issuu.document.delete&".
'apiKey=' 		.'fkcar3k11kqafz3yn2g6v113d4yx68dh&'.
'format=' 		.'json&'.
'names=' 		. $name.'&'.
'signature=' 	. $signature;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.issuu.com/1_0');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

/*
	Aqui se elimina el pdf del servidor de ISSUU
*/

$result = curl_exec($ch);
/*Eliminar del sistema de archivos*/

$datosDB=readModuleIssuuDataBase($module_id);
$url=$datosDB[0]['download_url'];

    $file = basename($url); 
    $folder='/var/www/html/mcbisite/assets/files/';

    error_log($folder.$file);
    unlink($folder.$file);

/*Eliminar a nivel de DB solo si queda sin archivo y se aprueba*/
//if($all){
//deleteISSUU($module_id);
//}

}
function InsertNotification($content_id,$msg){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}

?>
