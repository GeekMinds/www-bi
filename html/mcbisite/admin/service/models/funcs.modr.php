<?php

/*
 * Author: Javier Cifuentes
 */

function getModId($content_id) {
    global $db, $db_table_prefix;
    $query = "EXECUTE [dbo].[getModR] 
                            @content_id = $content_id";
    $moduleid = $db->sql_fetchrowset($db->sql_query($query));
    $data = array();
    $data['rows'] = $moduleid[0];
    $data['count'] = count($moduleid);
    return $data;
}

function getModInfo($module_id) {
    global $db, $db_table_prefix;
    $sql = "SELECT     type.[name] as tipo,
                bar.[content_id], 
                bar.[enable_obtain],
                bar.[enable_share],
                bar.[enable_compare],
                bar.[enable_bichat], 
                bar.[enable_poll],
                bar.[obtain_online_form],
                bar.[obtain_download_link],
                bar.[obtain_contact_form],
                bar.[poll_form],
                bar.[enable_favorite]
                
    FROM        [dbo].[interactive_bar] bar, 
                [dbo].[content] content,
                [dbo].[module_list] type

    WHERE       bar.id = $module_id
                AND bar.[content_id] =  content.[id]
                AND content.[module_id] = type.[id]";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $result_cont = count($rows);
    $data = array();
    $data['rows'] = $rows;
    $data['count'] = $result_cont;
    return $data;
}

function getFormInfo($form_id) {
    global $db, $db_table_prefix;
    $sql = "SELECT     name_es,
                created_at,
                content_id,
                adress
                
    FROM       [dbo].module_form
    
    WHERE      id= $form_id;";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $result_cont = count($rows);
    $data = array();
    $data['rows'] = $rows;
    $data['count'] = $result_cont;
    return $data;
}

function getAllForms() {
    global $db, $db_table_prefix;
    $sql = "SELECT  id,    
                name_es,
                created_at,
                content_id,
                adress
                
    FROM       [dbo].module_form;";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $result_cont = count($rows);
    $data = array();
    $data['rows'] = $rows;
    $data['count'] = $result_cont;
    return $data;
}

function updateaField($field, $value, $moduleid) {
    global $db, $db_table_prefix;
    $sql = "UPDATE [dbo].[interactive_bar] SET [$field] = $value WHERE [id] = $moduleid";
    $result = $db->sql_query($sql);
    $data = array();
    if ($result) {
        $data['Result'] = "OK";
        $data['Message'] = "";
    }else{
        $data['Result'] = "ERROR";
        $data['Message'] = ""; 
    }
    return $data; 
}

?>
