<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion
function getLastInsertion() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
    return $row['last_intertion'];
}


function listTagFilterDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	
    $sql = "SELECT 	TOP 10
 					id,
					tag name
			FROM [".$db_name."].[dbo].[tag] WHERE tag LIKE '%".$db->sql_escape($parameters["q"]) ."%' ";
			
		
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data = $rows;
    $db->sql_close();
    return $data;
}

?>