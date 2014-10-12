<?php
/*
*/
//You can getLastInsertion
function getLastInsertionModC() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
    //$row = $db->sql_lastinsertion_array($result);
    //$db->sql_close();
	
	//error_log("getLastInsertionModC  ----> ".$row['last_intertion']);
    return $row['last_intertion'];
}

//You can create mod_c
function createModCDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $site_id;
	
	//Para creat una sección o mod_c nuevo es necesario crear el contenedor, por lo que inicialmente se creará
	$params = array();
	$params['module_title'] = $parameters["title_es"];
	$params['module_type_id'] = '7';//El tipo del módulo para un mod_c es el 7, asegurarse de que es así en la tabla module_list
	$content_id = InsertNewContent($params);
	
	$header_data = array();
	$header_data["site_id"] = $site_id;
	$header_data["content_id"] = $content_id;
	insertSectionInHeaderOFSpecificSite($header_data);
	   
    $sql = "INSERT INTO [".$db_name."].[dbo].[mod_c] (
			title_es_edit,
			title_en_edit,
			icon_bar_edit,
			icon_vertical_menu_edit,
			link_edit,
			content_id,
			show_title_edit,
			created_at_edit
			)
			VALUES (
			'" . $db->sql_escape($parameters["title_es"]) . "',
			'" . $db->sql_escape($parameters["title_en"]) . "',
			'" . $db->sql_escape($parameters["icon_bar"]) . "',
			'" . $db->sql_escape($parameters["icon_vertical_menu"]) . "',
			'" . $db->sql_escape($parameters["link"]) . "',
			'" . $db->sql_escape($content_id) . "',
			'" . $db->sql_escape($parameters["show_title"]) . "',
			CURRENT_TIMESTAMP
			)";

    $result = $db->sql_query($sql);
    InsertNotification($db->sql_escape($content_id),"Se creo un nuevo menu en la barra de navegación");
    if ($result) {
		$data = $parameters;
		$data["id"] = getLastInsertionModC();
		$data["active"] = "0";
        return $data;
    }
    
    
    return false;
}

function InsertNewContent($parameters = array()) {
    global $db;
    $query_insertContent = "EXEC [dbo].[insertContent]
		@title_es = N'" . $parameters['module_title'] . "',
		@title_en = N'" . $parameters['module_title'] . "',
		@tags = N'',
		@module_id = " . $parameters['module_type_id'] . ";";
    $NewContentID = $db->sql_fetchrowset($db->sql_query($query_insertContent));
    return $NewContentID[0]['id'];
}

function insertSectionInHeaderOFSpecificSite($parameters=array()){
	 global $db, $db_table_prefix, $db_name;
	 $sql = "INSERT INTO [".$db_name."].[dbo].[header] (
			site_id,
			content_id,
			created_at
			)
			VALUES (
			" . $db->sql_escape($parameters["site_id"]) . ",
			'" . $db->sql_escape($parameters["content_id"]) . "',
			CURRENT_TIMESTAMP
			)";
	$result = $db->sql_query($sql);
}

//You can get the list countries with filter options
function listModCDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
	$parameters["site_id"] = (isset($parameters['site_id'])) ? $parameters['site_id'] : "0";
	$parameters["jtsorting"] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : " sequence_edit ASC ";

	$parameters['cli'] = (isset($parameters['cli'])) ? $parameters['cli'] : '';//que módulo del administrador solicita la información
	$parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : '';//Admin group_id
	if($parameters['cli']=="permissions"){//Se solicita ver el listado de sitios por el módulo de permisos
		if($parameters['group_id']!=""){//Admin group_id
			$group_id = (int)$parameters['group_id'];
			switch($group_id){
				case SUPER_ADMINISTRADOR:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todas las secciones";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
				case ADMINISTRADOR_REGIONAL:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todas las secciones";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
				case ANALISTA:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Solamente permisos a estadísticas";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
			}
		}
	}
	
	
	
    $data = array();

    $sql = "SELECT  bar.id, 
					bar.id AS Value, 
					bar.title_es_edit AS DisplayText, 
					bar.title_es_edit as title_es, 
					bar.title_en_edit as title_en, 
					bar.icon_bar_edit as icon_bar, 
					bar.icon_vertical_menu_edit as icon_vertical_menu, 
					bar.link_edit as link, 
					bar.content_id,
					bar.active_edit as active,
					bar.show_title_edit as show_title";
	$sql_select = "
			FROM 
				[".$db_name."].[dbo].[mod_c] bar,
				[".$db_name."].[dbo].[header] h,
				[".$db_name."].[dbo].[content] c
			WHERE 
				(h.site_id = ".$parameters["site_id"] . ") AND
				c.id = h.content_id AND
				bar.content_id = c.id AND 
                                bar.removed_edit=0";
				
	$sql .= $sql_select;	
    $sql_count = "SELECT COUNT(*) as count ".$sql_select;


    if ($parameters['jtsorting'] != '') {
        $sql .= " ORDER BY " . $parameters['jtsorting'];
    }

	//error_log($sql);

    $result = $db->sql_query($sql);
    $result_cont = $db->sql_query($sql_count);

    $rows = $db->sql_fetchrowset($result);
    $count = $db->sql_fetchrow($result_cont);
    //$rows = $db->sql_fetchrowset_array($result);

    $data['rows'] = $rows;
    $data['count'] = $count['count'];
    //$db->sql_close();
    return $data;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\	OBTENIENDO EL ID DEL CONENEDOR DEL HEADER
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getHeaderBySite($parameters = array()) {
    global $db, $db_table_prefix, $db_name;  
	$parameters["site_id"] = (isset($parameters['site_id'])) ? $parameters['site_id'] : "0";
	$parameters["jtsorting"] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : " sequence ASC ";
	
    $data = array();

    $sql = "SELECT  h.id id,
					h.site_id,
					h.content_id, 
					s.title_es ";
	$sql_select = "
			FROM 
				[".$db_name."].[dbo].[header] h,
				[".$db_name."].[dbo].[site] s
			WHERE 
				h.site_id = ".$parameters["site_id"] . "  AND
				s.id = h.site_id ";
				
	$sql .= $sql_select;	

	//error_log($sql);

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrow($result);
    //$rows = $db->sql_fetchrowset_array($result);

    $data = $rows;
    //$db->sql_close();
    return $data;
}



function getMenuCInfoDataBase($parameters=array())
{
	global $db,$db_table_prefix, $db_name; 
	$data = array();
	$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
	
	$sql = "SELECT title_es_edit as title_es,title_en_edit as title_en, icon_bar_edit as icon_bar,icon_vertical_menu_edit as icon_vertical_menu,link_edit as link,created_at_edit as created_at,sequence_edit as sequence,active_edit as active ,type_edit as type,show_title_edit as show_title,content_id FROM [".$db_name."].[dbo].[mod_c] WHERE id = " . $parameters["id"];

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	
	if($row){
		foreach ($row as $key => $val) {	
			$row[$key] = mb_convert_encoding($row[$key], "UTF-8", mb_detect_encoding($row[$key], "UTF-8, ISO-8859-1, ISO-8859-15", true));
		}
	}
	
	$data['row'] = $row;
	return $data;
}

//You can get the list of buttons from A module
function getMenuDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();

    $sql = "SELECT title_es_edit as title_es,title_en_edit as title_en, icon_bar_edit as icon_bar,icon_vertical_menu_edit as icon_vertical_menu,link_edit as link,created_at_edit as created_at,sequence_edit as sequence,active_edit as active ,type_edit as type,show_title_edit as show_title,content_id FROM [".$db_name."].[dbo].[mod_c] WHERE content_id = " . $parameters["content_id"];

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data['rows'] = $rows;
    $data['count'] = $count['count'];

    return $data;
}

//Recibe como parametro un objecto que contiene dos array ({"inactive":array(), "active":array()}) las secciones activas e inactivas con su respectivo ordenamiento
function updateHeaderSequenceDataBase($parameters = array()){
	$parameters["active"] = (isset($parameters['active'])) ? $parameters['active'] : array();
	$parameters["inactive"] = (isset($parameters['inactive'])) ? $parameters['inactive'] : array();
	InsertNotificationForm($parameters["content_id"],"Se ha cambiado el orden de las secciones",$parameters["site_id"]);
	for($i=0; $i<count($parameters["active"]); $i++){
		$result = updateOrderOfSectionMenu($parameters["active"][$i]);
		
		if(!$result){
			return false;
		}
	}
	for($i=0; $i<count($parameters["inactive"]); $i++){
		$result = updateOrderOfSectionMenu($parameters["inactive"][$i]);
		if(!$result){
			return false;
		}
	}
        
	return true;
}


//Para la actualización del ordenamiento de las secciones en el momento de mostrarlos en el header
function updateOrderOfSectionMenu($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
	 
	//error_log(json_encode($parameters["sequence"]));
	
    $sql = "UPDATE [".$db_name."].[dbo].[mod_c] SET 
					sequence_edit = '" . (int)$db->sql_escape($parameters["sequence"]) . "', 
					active_edit = ".$parameters["active"]."
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    $result = $db->sql_query($sql);
    
   // $db->sql_close();
    return ($result);
}


//You can get the list of buttons from A module
function getAllChildsOfSectionDataBase($parameters=array())
{
	global $db,$db_table_prefix, $db_name; 
	$data = array();
	
	$sql = "SELECT id,mod_c_id,parent_submenu_id,link_edit as link,title_es,title_en,sequence,active,active_edit FROM  [".$db_name."].[dbo].[mod_c_submenu] WHERE active_edit=1 and mod_c_id = " . $parameters["id"] . " ";
	
	//echo($sql);

	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);	
	return $rows;
}

//You can get the list of buttons from A module
function getSubMenuDataBase($parameters=array())
{
	global $db,$db_table_prefix, $db_name; 
	$data = array();
	
	$sql = "SELECT id,mod_c_id,parent_submenu_id,link_edit as link,title_es,title_en,sequence,active,active_edit FROM  [".$db_name."].[dbo].[mod_c_submenu] WHERE mod_c_id = " . $parameters["id"] . " AND parent_submenu_id IS NULL and active_edit=1";
	
	//echo($sql);

	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	
	$data['rows'] = $rows;
	
	return $data;
}



//You can get the list of sub menu optiones from C module
function getSubMenuChildsDataBase($parameters=array())
{
	global $db,$db_table_prefix, $db_name;
	$data = array();
	
	$sql = "SELECT id,mod_c_id,parent_submenu_id,link_edit as link,title_es,title_en,sequence,active,active_edit FROM  [".$db_name."].[dbo].[mod_c_submenu]  WHERE active_edit=1 and parent_submenu_id = " . $parameters["parent_submenu_id"];
	
	//error_log($sql);

	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	
	$data['rows'] = $rows;
	
	return $data;
}

function listPagesDataBase($parameters=array()){
	global $db,$db_table_prefix, $db_name;
	$parameters["section_id"] = (isset($parameters['section_id'])) ? $parameters['section_id'] : "0";
	
	$parameters['cli'] = (isset($parameters['cli'])) ? $parameters['cli'] : '';//que módulo del administrador solicita la información
	$parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : '';//Admin group_id
	if($parameters['cli']=="permissions"){//Se solicita ver el listado de sitios por el módulo de permisos
		if($parameters['group_id']!=""){//Admin group_id
			$group_id = (int)$parameters['group_id'];
			switch($group_id){
				case SUPER_ADMINISTRADOR:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todas las páginas";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
				case ADMINISTRADOR_REGIONAL:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todas las páginas";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
				case GESTOR:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todas las páginas";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
				case ANALISTA:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Solamente permisos a estadísticas";
					$data["rows"][] = $registro;
					$data['count'] = 1;
					return $data;
				break;
			}
		}
	}
	
	
	
	$sql = "SELECT 
				p.id AS Value, 
				p.title_es AS DisplayText,
				p.id page_id,
				p.title_es,
				p.title_en
			FROM
				mod_c_submenu sub,
				page p
			WHERE
				sub.mod_c_id = ".$db->sql_escape($parameters["section_id"])." AND
				sub.link <> '' AND
				p.id = REPLACE(link,'./?page=','')";
				
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	
	$data['rows'] = $rows;
    $data['count'] = count($rows);
	
	return $data;
}

/*
//You can get the list of sub menu optiones from C module
function saveSubMenuChildsDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $submenuchilds = $parameters['submenuchilds'];

    foreach ($submenuchilds as $submenu) {

        foreach ($submenu as $valor) {
            if ($valor["parent_submenu_id"] === '') {
                $valor["parent_submenu_id"] = 'NULL';
            }
            if ($valor["id"] === "-1") {
                $sql = 'INSERT INTO [dbo].[mod_c_submenu]
                    ([mod_c_id]
                    ,[parent_submenu_id]
                    ,[link]
                    ,[title_es]
                    ,[title_en])
                VALUES
                    (' . $db->sql_escape($valor["mod_c_id"]) . '
                    ,' . $db->sql_escape($valor["parent_submenu_id"]) . '
                    ,"' . $db->sql_escape($valor["link"]) . '"
                    ,"' . $db->sql_escape($valor["title_es"]) . '"
                    ,"' . $db->sql_escape($valor["title_en"]) . '")';
            } else {
                $sql = 'UPDATE [dbo].[mod_c_submenu]
                    SET [mod_c_id] = ' . $db->sql_escape($valor["mod_c_id"]) . '
                        ,[parent_submenu_id] = ' . $db->sql_escape($valor["parent_submenu_id"]) . '
                        ,[link] = "' . $db->sql_escape($valor["link"]) . '"
                        ,[title_es] = "' . $db->sql_escape($valor["title_es"]) . '"
                        ,[title_en] = "' . $db->sql_escape($valor["title_en"]) . '"
                    WHERE [id] = ' . $db->sql_escape($valor["id"]);
            }
            $result = $db->sql_query($sql);
            if (!$result) {
                return 'error al insertar ' . $result;
            }
        }
    }
}
*/



//You can get the list of sub menu optiones from C module
function saveSubMenuChildsDataBase($parameters = array()) {
    global $db, $db_table_prefix;
    
    $submenuchilds = $parameters['submenuchilds'];

    
    // error_log('PARAMETERS -- > ' . json_encode($parameters));

    //error_log('SUBMENUCHILDS -- > ' . json_encode($submenuchilds));
	if($parameters['module_id']==""){
		createModCDataBase($parameters);
		$last_insertion_id = getLastInsertionModC();
		$parameters['module_id'] = $last_insertion_id;
	}
    
    
    InsertNotification($parameters['content_id'],"Se realizaron los siguientes cambios en ".$parameters['title_es'].' : '.$parameters['msg']);
    
    //module_id
    $sql = 'UPDATE mod_c_submenu set active_edit=0 WHERE mod_c_id = ' . $parameters['module_id'];
    $result = $db->sql_query($sql);
     deleteAllSubmenuChilds($parameters['module_id']);
     saveChildsDataBase($parameters, "NULL", $submenuchilds);
    /*foreach ($submenuchilds as $submenu) {
        
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
                ,'" . $db->sql_escape($submenu['title_en']) . "')";
        $result = $db->sql_query($sql);


		$last_insertion_id = getLastInsertionModC();
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

    }*/
}





//You can get the list of sub menu optiones from C module
function saveChildsDataBase($parameters = array(), $last_insertion_id, $submenuchilds) {
    global $db, $db_table_prefix;
    foreach ($submenuchilds as $submenu) {
        $sql = "INSERT INTO [dbo].[mod_c_submenu]
                (
				mod_c_id
				,[parent_submenu_id]
                ,[link]
                ,[active]
                ,[active_edit]
                ,[title_es]
                ,[title_en])
            VALUES
                (
				'" . $db->sql_escape($parameters['module_id']) . "'
				," . $db->sql_escape($last_insertion_id) . "
                ,'" . $db->sql_escape($submenu['link']) . "'
                ,0
                ,1
                ,'" . $db->sql_escape($submenu['title_es']) . "'
                ,'" . $db->sql_escape($submenu['title_en']) . "')";
        $result = $db->sql_query($sql);
		$parentId = getLastInsertionModC();
		$childs = isset($submenu['childs']) ? $submenu['childs'] : false;
		if($childs!=false){
			if(sizeof($childs)>0){
				saveChildsDataBase($parameters, $parentId, $childs);
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
function updateModCDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "UPDATE " . $db_table_prefix . "mod_c SET 
					title_es_edit = '" . $db->sql_escape($parameters["title_es"]) . "', 
					title_en_edit = '" . $db->sql_escape($parameters["title_en"]) . "', 
					icon_bar_edit = '" . $db->sql_escape($parameters["icon_bar"]) . "' , 
					icon_vertical_menu_edit = '" . $db->sql_escape($parameters["icon_vertical_menu"]) . "' , 
					show_title_edit = '" . $db->sql_escape($parameters["show_title"]) . "' ,
					link_edit = '" . $db->sql_escape($parameters["link"]) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";

    $result = $db->sql_query($sql);
    //Obtenemos el content_id del modulo modificado
    $sql = "Select content_id from mod_c WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    
    $content = $db->sql_query($sql);
    
    $id =  $db->sql_fetchrow($content);
    
    InsertNotification($id["content_id"],"Se modifico la informacion basica de la seccion ".$parameters["title_es"]);
   // $db->sql_close();

    return ($result);
}

//You can delete specific mod_c
function deleteModCDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "Update  " . $db_table_prefix . "mod_c SET removed_edit=1 WHERE id = " . $db->sql_escape($parameters["id"]);

    $result = $db->sql_query($sql);
    
    $sql = "Select content_id,title_es_edit from mod_c WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    $content = $db->sql_query($sql);
    $id =  $db->sql_fetchrow($content);
    InsertNotification($id["content_id"],"Se elimino la seccion:  ".$id["title_es_edit"]);
    
    return ($result);
}
function InsertNotification($content_id,$msg){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function InsertNotificationForm($content_id,$msg,$id){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type =2"
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModuleBarNav($parameters = array()){
    global $db;
    $content_id = $parameters['content_id'];
    //Cambiamos del backup a la version final
    $sql="update mod_c set  title_es=title_es_edit, title_en=title_en_edit,icon_bar=icon_bar_edit,icon_vertical_menu=icon_vertical_menu_edit,link=link_edit,created_at=created_at_edit,sequence=sequence_edit,active=active_edit  ,type=type_edit,show_title=show_title_edit,removed=removed_edit where content_id=".$db->sql_escape($content_id);
    $db->sql_query($sql);
    //Aprobamos los hijos que tenga en ese momento
    $sql="update mod_c_submenu set active=mod_c_submenu.active_edit from mod_c,mod_c_submenu where content_id = ".$db->sql_escape($content_id)." and mod_c.id=mod_c_submenu.mod_c_id";
    $db->sql_query($sql);
    //Eleiminamos los hijos que haya quedado anulados
    $sql = "select id from mod_c where content_id=".$db->sql_escape($content_id);
    $content = $db->sql_query($sql);
    $row = $db->sql_fetchrow($content);
    $id=$row['id'];
    deleteAllSubmenuChilds($id);
    //Eliminamos todas las entrada que este eliminadas tanto en el backup como en la version final
    $sql = "delete from mod_c where removed=1 and removed_edit=1";
    $db->sql_query($sql);
    
}
function ApprovedModuleBarNavForma($parameters = array()){
    global $db;
    $id = $parameters['id_change'];
    //Solo ha cambiado la secuencia de las cosas por lo que solo acualizamos sequence
    $sql="update mod_c set sequence = sequence_edit from header where site_id = ".$db->sql_escape($id)." and header.content_id = mod_c.content_id";
    $db->sql_query($sql);
    
}
function DisapprovedModuleBarNav($parameters = array()){
    global $db;

    $content_id = $parameters['content_id'];
    //Cambiamos la version final a la version del backup
    $sql="update mod_c set  title_es_edit=title_es, title_en_edit=title_en,icon_bar_edit=icon_bar,icon_vertical_menu_edit=icon_vertical_menu,link_edit=link,created_at_edit=created_at,sequence_edit=sequence,active_edit=active ,type_edit=type,show_title_edit=show_title,removed_edit=removed where content_id=".$db->sql_escape($content_id);
    $db->sql_query($sql);
    
    //Aprobamos los hijos que tenga en ese momento
    $sql="update mod_c_submenu set active_edit=mod_c_submenu.active from mod_c,mod_c_submenu where content_id = ".$db->sql_escape($content_id)." and mod_c.id=mod_c_submenu.mod_c_id";
    $db->sql_query($sql);
    //Eleiminamos los hijos que haya quedado anulados
    $sql = "select id from mod_c where content_id=".$db->sql_escape($content_id);
    $content = $db->sql_query($sql);
    $row = $db->sql_fetchrow($content);
    $id=$row['id'];
    deleteAllSubmenuChilds($id);
    //Eliminamos todas las entrada que este eliminadas tanto en el backup como en la version final
    $sql = "delete from mod_c where removed=1 and removed_edit=1";
    $db->sql_query($sql);
    
}
function DisapprovedModuleBarNavForma($parameters = array()){
    global $db;

    $id = $parameters['id_change'];
    //Solo ha cambiado la secuencia de las cosas por lo que solo acualizamos sequence
    $sql="update mod_c set sequence_edit = sequence from header where site_id = ".$db->sql_escape($id)." and header.content_id = mod_c.content_id";
    $db->sql_query($sql);
    
    
}
function deleteAllSubmenuChilds($id){
  global $db;
  $sql= "delete from mod_c_submenu where active=0 and active_edit=0 and mod_c_id = ". $id;
  $db->sql_query($sql);
}
?>