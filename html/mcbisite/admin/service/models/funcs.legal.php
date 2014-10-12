<?php

/*
 * Author: Javier Cifuentes
 */

function updateField($parameters = array()) {
    global $db,$db_name;
    $data = array();
    $field = $parameters['field'];
    if ($field == "faq" || $field == "terms" || $field == "legal" || $field == "sitemap") {
        $titulo_ES = $parameters['titlees'];
        $titulo_EN = $parameters['titleen'];
        $contenido_ES = $parameters['valuees'];
        $contenido_EN = $parameters['valueen'];
        $sql = "EXECUTE [dbo].[insertLegalField] 
                            @field = '$field'
                           ,@title_es = '$titulo_ES'
                           ,@title_en = '$titulo_EN'
                           ,@content_en = '$contenido_EN'
                           ,@content_es = '$contenido_ES'";

        $result = $db->sql_query($sql);
        $rows = $db->sql_fetchrowset($result);
        $mod_description="";
        if($field == "faq" ){
            $mod_description="Se modifico el texto de preguntas frecuentes";
        }else if($field == "terms"){
            $mod_description="Se modificaron los t&eacute;rminos y condiciones";
        }else if($field == "legal"){
            $mod_description="Se modifico el contenido legal";
        }else{
            $mod_description="Se modifico el mapa del sition";
        }
        $sql = "Select id from [$db_name].[dbo].[general_info] where link_alias='".$db->sql_escape($field)."'";
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrow($result);
        $id=$result['id'];
        
        insertNotificacion($mod_description, $id);
        
        $data['Result'] = "OK";
        $data['Message'] = "Campo actualizado";
    } else {
        $data['Result'] = "ERROR";
        $data['Message'] = "Campo invalido";
    }

    return $data;
}

function getField($parameters = array()) {
    global $db,$db_name;
    $data = array();
    $field = $parameters['field'];
    if ($field == "faq" || $field == "terms" || $field == "legal" || $field == "sitemap") {
        $sql = "SELECT [title_es_edit] as titleES, 
                       [title_en_edit] as titleEN, 
                       [content_es_edit] as contentES,
                       [content_en_edit] as contentEN
                FROM [$db_name].[dbo].[general_info]
                WHERE 
                    [link_alias] = '".$field."'";
        $result = $db->sql_query($sql);
        $rows = $db->sql_fetchrowset($result);
        $data['Result'] = "OK";
        $data['Message'] = "Contenido Obtenido";
        $data['rows'] = $rows;
        $data['count'] = count($rows);
    } else {
        $data['Result'] = "ERROR";
        $data['Message'] = "Campo invalido";
    }
    return $data;
}
function insertNotificacion($msg,$id){
      global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . " @editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type = 5"
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModuleFooter($parameters=array()){
    
    global $db,$db_name;
    $id=$parameters['id_change'];
    
    $sql = "update [$db_name].[dbo].[general_info] set title_es=title_es_edit,title_en=title_en_edit,content_es=content_es_edit,content_en=content_en_edit where id=".$db->sql_escape($id);
    $db->sql_query($sql);
    
}
function DisapprovedModuleFooter($parameters=array()){
    
    global $db,$db_name;
    $id=$parameters['id_change'];
    
    $sql = "update [$db_name].[dbo].[general_info] set title_es_edit=title_es,title_en_edit=title_en,content_es_edit=content_es,content_en_edit=content_en where id=".$db->sql_escape($id);
    $db->sql_query($sql);
    
}
