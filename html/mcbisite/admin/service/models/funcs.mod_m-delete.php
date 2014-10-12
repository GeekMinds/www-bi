<?php

function readMediaSet($module_id) {
    global $db, $db_table_prefix;

    $sql = "SELECT url_media,type FROM ". $db_table_prefix . "media "
    ." WHERE "

    ."mod_m=". $db->sql_escape($module_id)
    ;
 
    echo $sql;
    //$result = $db->sql_query($sql);
   	//$rows = $db->sql_fetchrowset($result);
    //return $rows;
}







?>