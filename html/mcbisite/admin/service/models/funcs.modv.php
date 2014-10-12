<?php

//You can create mod_c
function createModFormDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $form_field = $parameters['form_field'];

    /*
    $sql = "INSERT INTO " . $db_table_prefix . "mod_c (
			title_es,
			title_en,
			icon_bar,
			icon_vertical_menu,
			link,
			content_id,
			created_at
			)
			VALUES (
			'" . $db->sql_escape($parameters["title_es"]) . "',
			'" . $db->sql_escape($parameters["title_en"]) . "',
			'" . $db->sql_escape($parameters["icon_bar"]) . "',
			'" . $db->sql_escape($parameters["icon_vertical_menu"]) . "',
			'" . $db->sql_escape($parameters["link"]) . "',
			'" . $db->sql_escape($parameters["content_id"]) . "',
			CURRENT_TIMESTAMP
			)";

    $result = $db->sql_query($sql);
    if ($result) {
        return $result;
    }

    */
    
    return false;
}


?>