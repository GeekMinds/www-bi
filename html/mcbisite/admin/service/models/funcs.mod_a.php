<?php
/*
*/
//You can getLastInsertion
function getLastInsertionModA() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
    //$row = $db->sql_lastinsertion_array($result);
    //$db->sql_close();
    return $row['last_intertion'];
}

//You can create mod_c
function createModADataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "INSERT INTO " . $db_table_prefix . "mod_ (
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
    return false;
}

//You can get the list countries with filter options
function listModADataBase($parameters = array()) {
    global $db, $db_table_prefix;
    $data = array();

    $sql = "SELECT *, id AS Value, name AS DisplayText, name AS name_mod_c FROM " . $db_table_prefix . "mod_c";
    $sql_count = "SELECT COUNT(*) as count FROM " . $db_table_prefix . "mod_c ";


    if ($parameters['jtsorting'] != '') {
        $sql .= " ORDER BY " . $parameters['jtsorting'];
    }

    if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
        $sql .= " LIMIT " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
    }

    $result = $db->sql_query($sql);
    $result_cont = $db->sql_query($sql_count);

    $rows = $db->sql_fetchrowset($result);
    $count = $db->sql_fetchrow($result_cont);
    //$rows = $db->sql_fetchrowset_array($result);

    $data['rows'] = $rows;
    $data['count'] = $count['count'];
    $db->sql_close();
    return $data;
}

function getMenuCInfoDataBase($parameters = array()) {
    global $db, $db_table_prefix;
    $data = array();
    $parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";

    $sql = "SELECT * FROM " . $db_table_prefix . "mod_c WHERE id = " . $parameters["id"];

    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);


    if ($row) {
        foreach ($row as $key => $val) {
            //$row[$key] = utf8_decode($row[$key]);
            $row[$key] = mb_convert_encoding($row[$key], "UTF-8", mb_detect_encoding($row[$key], "UTF-8, ISO-8859-1, ISO-8859-15", true));
            //$row[$key] = utf8_encode(htmlentities($row[$key],ENT_COMPAT,'UTF-8'));
        }
    }

    $data['row'] = $row;
    //$data['sql'] = $sql;
    return $data;
}

//You can get the list of buttons from A module
function getModuleADataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();

    $sql = "SELECT id, title_es, title_en, parent_id, site_id, link, image, istext, sequence, created_at 
			FROM [".$db_name."].[dbo].[mod_a] 
			WHERE site_id = " . $parameters["site_id"] . " AND parent_id IS NULL ORDER BY sequence ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data = $rows;

    return $data;
}

//You can get the list of buttons from A module
function getSubMenuModuleADataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();

    $sql = "SELECT id, title_es, title_en, parent_id, site_id, link, image, istext, sequence, created_at  
			FROM [".$db_name."].[dbo].[mod_a] 
			WHERE parent_id = " . $parameters["parent_id"] . " ORDER BY sequence ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data = $rows;

    return $data;
}

//You can get the list of sub menu optiones from C module
function getSubMenuChildsDataBase($parameters = array()) {
    global $db, $db_table_prefix;
    $data = array();

    $sql = "SELECT * FROM " . $db_table_prefix . "mod_c_submenu WHERE parent_submenu_id = " . $parameters["parent_submenu_id"];

    //error_log($sql);

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data['rows'] = $rows;

    return $data;
}



//You can get the list of sub menu optiones from C module
function saveSubMenuChildsDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $submenuchilds = $parameters['submenuchilds'];


   // error_log('PARAMETERS -- > ' . json_encode($parameters));

    //error_log('SUBMENUCHILDS -- > ' . json_encode($submenuchilds));
	if($parameters['module_id']==""){
		createModADataBase($parameters);
		$last_insertion_id = getLastInsertionModA();
		$parameters['module_id'] = $last_insertion_id;
	}
	
    //module_id
    $sql = 'DELETE FROM mod_c_submenu WHERE mod_c_id = ' . $parameters['module_id'];
    $result = $db->sql_query($sql);

    foreach ($submenuchilds as $submenu) {
        
        $sql = "INSERT INTO [dbo].[mod_c_submenu]
                (
				mod_c_id
                ,[link]
                ,[title_es]
                ,[title_en])
            VALUES
                (
				'" . $db->sql_escape($parameters['module_id']) . "'
                ,'" . $db->sql_escape($submenu['link']) . "'
                ,'" . $db->sql_escape($submenu['title_es']) . "'
                ,'" . $db->sql_escape($submenu['title_es']) . "')";
        $result = $db->sql_query($sql);


		$last_insertion_id = getLastInsertionModA();
		$childs = isset($submenu['childs']) ? $submenu['childs'] : false;

		if($childs!=false){
			foreach ($childs as $valor) {
				
				$sql = "INSERT INTO [dbo].[mod_c_submenu]
					(
					mod_c_id
					,[parent_submenu_id]
					,[link]
					,[title_es]
					,[title_en])
				VALUES
					(
					'" . $db->sql_escape($parameters['module_id']) . "'
					," . $db->sql_escape($last_insertion_id) . "
					,'" . $db->sql_escape($valor['link']) . "'
					,'" . $db->sql_escape($valor['title_es']) . "'
					,'" . $db->sql_escape($valor['title_es']) . "')";
				$result = $db->sql_query($sql);
			}
		}

    }
}


function getModuleDataBase($parameters = array()) {
    $mod_c_id = isset($parameters['mod_c_id']) ? $parameters['mod_c_id'] : '1';
    $resul = '<iframe class="iframe"
                    style=""
                    id="iframe_mod_c"
                    title="iframe"
                    width="100%"
                    src="menulateral.php?mod_c_id=' . $mod_c_id . '"
                    frameborder="0"
                    type="text/html"
                    allowfullscreen="true" allowtransparency="true">
                </iframe>';
    return $resul;
}

function getModuleExternalDataBase($parameters = array()) {
    $mod_c_id = isset($parameters['mod_c_id']) ? $parameters['mod_c_id'] : '1';

    $c = curl_init('http://domain.com/website/bi/website/menulateral.php?mod_c_id=' . $mod_c_id);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $msg = curl_exec($c);
    curl_close($c);

    return $content;
}

//You can update specific mod_c
function updateModADataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "UPDATE " . $db_table_prefix . "mod_c SET 
					name = '" . $db->sql_escape($parameters["name_mod_c"]) . "', 
					code = '" . $db->sql_escape($parameters["code"]) . "', 
					description = '" . $db->sql_escape($parameters["description"]) . "' , 
					abreviature = '" . $db->sql_escape($parameters["abreviature"]) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";

    $result = $db->sql_query($sql);
    $db->sql_close();

    return ($result);
}

//You can update specific mod_c
function updateItemModADataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[mod_a]  SET 
					title_es = '" . $db->sql_escape($parameters["title_es"]) . "', 
					title_en = '" . $db->sql_escape($parameters["title_en"]) . "', 
					image = '" . $db->sql_escape($parameters["image"]) . "',
					link = '" . $db->sql_escape($parameters["link"]) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
	
	//return $sql;

    $result = $db->sql_query($sql);
    return $result;
}

//You can delete specific mod_c
function deleteModADataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "DELETE FROM " . $db_table_prefix . "mod_c WHERE id = " . $db->sql_escape($parameters["id"]);

    $result = $db->sql_query($sql);

    return ($result);
}

?>