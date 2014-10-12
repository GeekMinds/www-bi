<?php

//You can getLastInsertion
function getLastInsertionModB() {

    global $db, $emailActivation, $websiteUrl, $db_table_prefix;

    $sql = "SELECT * FROM " . $db_table_prefix . "mod_b WHERE id = LAST_INSERT_ID();";

    $result = $db->sql_query($sql);

    $row = $db->sql_lastinsertion_array($result);

    $db->sql_close();

    return $row;
}

function getModuleList() {
    global $db, $db_table_prefix;

    $data = array();

    $sql = "SELECT [id],[name],[created_at], [description] FROM [dbo].[module_list] WHERE customizable = 1 ";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $result_cont = count($rows);

    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $db->sql_close();

    return $data;
}


function moduleNameDataBase($parameters = array()) {
    global $db, $db_table_prefix;
	
	
    $module_type_id = isset($parameters["module_type_id"]) ? $parameters["module_type_id"] : "1";
	$data = array();
    $sql = "SELECT [id],[name],[created_at], [description] FROM [dbo].[module_list] WHERE id = ".$module_type_id;
	
    $result = $db->sql_query($sql);
	
	
    $rows = $db->sql_fetchrow($result);
	$data['error'] = '0';
    $data['result'] = $rows;
	$data['message'] = '';
	//$db->sql_close();
    return $data;
}


function getPageList() {
    global $db, $db_table_prefix;
    $data = array();
    $sql = "SELECT * FROM dbo.page";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data['rows'] = $rows;
    $data['count'] = count($rows);
    $db->sql_close();
    return $data;
}

//First step to create a new page and content
function createPageDataBase($parameters = array()) {
    global $db, $defaul_pages, $site_id;
    $NewPageID = '';
	
	//error_log("createPageDataBase -> ".json_encode($parameters));

    $page_id = $parameters["page_id"];
	$site_id = isset($parameters["site_id"]) ? $parameters["site_id"]:$site_id;
    $titleES = isset($parameters["page_title_es"]) ? $parameters["page_title_es"]:"";
    $titleEN = isset($parameters["page_title_en"]) ? $parameters["page_title_en"] : $titleES;
    $menu_id = isset($parameters["menu_id"]) ? $parameters["menu_id"]:"";//Viene vacío si es una página suelta y no se está amarrando a un item del menú lateral
    $default_page = isset($parameters["default_page"]) ? $parameters["default_page"]:"";//Cuando se está creando una página de las que son defualt del sitio (como home, registro y login)
	$deleted_content_list = isset($parameters["deleted_content_list"]) ? $parameters["deleted_content_list"]:array();

    $description =  isset($parameters["page_description"]) ? $parameters["page_description"]:"";
	$parameters['content_configuration'] = isset($parameters["content_configuration"]) ? $parameters["content_configuration"]:array();
	

    $query_insertPage = "EXEC [dbo].[insertPage]
		@titleES = N'$titleES',
		@titleEN = N'$titleEN',
		@descript = N'$description',
		@site_id = ".$site_id;


    //se verifica si es una actualización o si hay que crear una página nueva
    if($page_id == 'undefined' || $page_id == ''){
        //se inserta la pagina nueva y se obtiene su id en la variable $NewPageID
		
        $NewPageID = $db->sql_fetchrowset($db->sql_query($query_insertPage));
        $NewPageID = $NewPageID[0]['id'];  
		
		//error_log("Creando página nueva --> ".$NewPageID);
		insertNotification("Se creo una nueva pagina: ".$NewPageID, $NewPageID);
		if($menu_id!=""){//Actualizando el campo link de la tabla de module_c_submenu, para asignarle página al submenu lateral
			$params = array();
			$params["id"] = $menu_id;
			$params["link"] = "./?page=".$NewPageID;
			updateLinkSectionMenu($params);
		}
		
		if($default_page!=""){//Si viene para ser asginada a una de las páginas que son dafault para el sitio
			if(isset($defaul_pages[$default_page])){//Los alias permitidos para estas páginas default son : home, login, register
				$params = array();
				$params["alias"] = $default_page;
				$params["page_id"] = $NewPageID;
				$params["site_id"] = $site_id;
				if(!registerDefaultPage($params)){
					error_log("ERROR: No se realizó el registro de la página defaul ".$params["alias"] .".");
				}
			}
		}
		
    }else{
        $NewPageID = $parameters["page_id"];
    }
    

    $array_content_configuration = $parameters['content_configuration'];

    $result = array();
    $content = array();
    
    foreach ($array_content_configuration as $valor) {
        if ($valor["content_id"] === "-1") {
            $valor["content_id"] = InsertNewContent($valor);
            $content_id = InsertNewPageContent($NewPageID, $valor["content_id"]);
            $content[] = $content_id;
            $ContentConfigurationID = insertContentConfiguration($content_id, $valor);
        }else{
            $ContentConfigurationID = updateContentConfiguration($NewPageID, $valor["content_id"], $valor);
        }
        
    }
	insertNotification("Se ha cambiado la configuración de la pagina: ".$NewPageID, $NewPageID);
	//CONTENIDO A ELIMINAR, por el momento solo se eliminara la relación que existe con la página, queda pendiente eliminar el module en si
	//Hay módulos q no se podrán eliminar tales como el localizador
	foreach ($deleted_content_list as $valor) {
		if($valor["content_id"] != "-1" && $valor["page_id"] != ""){
			deleteContentFromPage($valor);
		}
	}
	
	//$deleted_content_list
	
    
    $result['PageID'] = $NewPageID;
    $result['ContentID'] = $content;
    
	error_log("Configuración de distribucion de la pagina ->".$result['PageID']." ... OK");
	
    return $result;
}


//Para la actualización del ordenamiento de las secciones en el momento de mostrarlos en el header
function updateLinkSectionMenu($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
	
    $sql = "UPDATE [".$db_name."].[dbo].[mod_c_submenu] SET 
					link_edit = '" . $db->sql_escape($parameters["link"]) . "' 
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    $result = $db->sql_query($sql);
   // $db->sql_close();
    return ($result);
}

//Register Default page in site
function registerDefaultPage($parameters = array()) {
    global $db, $db_table_prefix, $db_name;

    $sql = "INSERT INTO [".$db_name."].[dbo].[default_page] (
			alias,
			page_id,
			site_id
			)
			VALUES (
			'" . $db->sql_escape($parameters["alias"]) . "',
			'" . $db->sql_escape($parameters["page_id"]) . "',
			'" . $db->sql_escape($parameters["site_id"]) . "'
			)";
	
    $result = $db->sql_query($sql);
	
    if ($result) {
        return $result;
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

function InsertNewPageContent($page_id, $content_id) {
    global $db;

    $query_insertPageContent = "EXEC [dbo].[insertPageContent]
		@page_id = $page_id,
		@content_id = $content_id";
    $NewPageContentID = $db->sql_fetchrowset($db->sql_query($query_insertPageContent));
    $sql = "SELECT description FROM [module_list],[content] where [content].[id]=".$content_id." AND [module_list].[id]=[content].[module_id];";
    $result= $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    insertNotification("Se agrego un nuevo modulo ".$row['description']." en la pagina: ".$page_id, $page_id);
    return $NewPageContentID[0]['id'];
}

function insertContentConfiguration($page_content_id, $parameters = array()) {
    global $db;

    $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
        ' . $parameters['size_x'] . '
       ,' . $parameters['size_y'] . '
       ,' . $parameters['col'] . '
       ,' . $parameters['row'] . '
       ,' . $page_content_id . '
       ,1';
    $NewContentConfigurationID = $db->sql_fetchrowset($db->sql_query($query_insertContentConfiguration));
    return $NewContentConfigurationID[0]['id'];
}

function updateContentConfiguration($page_id, $content_id, $parameters = array()) {
    global $db;
    $page_content_id = "";

    //Query para obtener  el id de la tabla page_content
    $sql = "SELECT id FROM page_content 
            WHERE page_id = " . $db->sql_escape($page_id) . " 
            AND content_id = " . $db->sql_escape($content_id) . ";";

    $result = $db->sql_query($sql);
    $page_content_id = $db->sql_fetchrow($result);

	if(!$page_content_id){
		$page_content_id = array();
		$page_content_id['id'] = InsertNewPageContent($page_id, $content_id);
		$ContentConfigurationID = insertContentConfiguration($page_content_id['id'], $parameters);
	}
	
    error_log("ID PAGE CONTENT " . $page_content_id["id"]);

    //Query para realizar el update de las configuraciones de contenidos en la tabla content_configuration
    $sql = "UPDATE content_configuration SET size_x = '" . $db->sql_escape($parameters['size_x']) . "', 
              size_y_edit = '" . $db->sql_escape($parameters['size_y'] ) . "', 
              col_edit = '" . $db->sql_escape($parameters['col']) . "' , 
              row_edit = '" . $db->sql_escape($parameters['row']) . "' 
              WHERE page_content_id = '" . $db->sql_escape($page_content_id["id"]) . "';";
    
    
    $result = $db->sql_query($sql);
    
    return $page_content_id;
}


function deleteContentFromPage($parameters = array()) {
    global $db, $db_table_prefix;


    $sql = "UPDATE " . $db_table_prefix . "page_content set status_edit=0
                        WHERE 
				page_id = " . $db->sql_escape($parameters["page_id"]) . " AND 
				content_id = ". $db->sql_escape($parameters["content_id"]);
    insertNotification("Se elimino un modulo de la pagina: ".$parameters["page_id"], $parameters["page_id"]);
    $result = $db->sql_query($sql);

    return ($result);
}
function deleteAllContentFromPage($parameters = array()) {
    global $db, $db_table_prefix; 
    //1.Eliminamos el contenido de la pagina
    $sql = "UPDATE " . $db_table_prefix . "page_content set status_edit=0
			WHERE 
				page_id = " . $db->sql_escape($parameters["page_id"]);

    $result = $db->sql_query($sql);
    //2. Eliminamos la referencia a la pagina del directorio 
    $sql = "UPDATE " . $db_table_prefix . "mod_c_submenu
                        SET
                             link_edit=''
			WHERE 
				id = " . $db->sql_escape($parameters["menu_id"]);
    $result = $db->sql_query($sql);
    //3. Por ultimo eliminamos la pagina
    
    $sql = "UPDATE  " . $db_table_prefix . "page set active_edit=0  
            WHERE id = "  . $db->sql_escape($parameters["page_id"]);
    $result = $db->sql_query($sql);
    insertNotification("Se elimino completamente la pagina: ".$parameters["page_id"], $parameters["page_id"]);
    return ($result);
}

//You can get the list countries with filter options
function listModBDataBase($parameters = array()) {

    global $db, $db_table_prefix;

    $data = array();

    $sql = "SELECT *, id AS Value, name AS DisplayText, name AS name_mod_b FROM " . $db_table_prefix . "mod_b";

    $sql_count = "SELECT COUNT(*) as count FROM " . $db_table_prefix . "mod_b ";

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

//You can get the list of buttons from A module

function getButtonsDataBase($parameters = array()) {

    global $db, $db_table_prefix;

    $data = array();

    $sql = "SELECT * FROM " . $db_table_prefix . "mod_b ";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $data['rows'] = $rows;

    $data['count'] = $count['count'];

    $db->sql_close();

    return $data;
}

//You can update specific mod_b
function updateModBDataBase($parameters = array()) {

    global $db, $db_table_prefix;

    $sql = "UPDATE " . $db_table_prefix . "mod_b SET 

					name = '" . $db->sql_escape($parameters ["name_mod_b"]) . "', 

					code = '" . $db->sql_escape($parameters ["code"]) . "', 

					description = '" . $db->sql_escape($parameters ["description"]) . "' , 

					abreviature = '" . $db->sql_escape($parameters ["abreviature"]) . "'

			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";

    $result = $db->sql_query($sql);

    $db->sql_close();

    return($result);
}

//You can delete specific mod_b
function deleteModBDataBase($parameters = array()) {

    global $db, $db_table_prefix;

    $sql = "DELETE FROM " . $db_table_prefix . "mod_b WHERE id = " . $db->sql_escape($parameters["id"]);

    $result = $db->sql_query($sql);

    return ($result);
}
function extLink($parameters = array()){
     global $db, $db_table_prefix;
     $url = $parameters["url"];
     $id = $parameters["id"];
    $sql = "EXECUTE [dbo].[setExternalLink] 
                            @url = '$url'
                           ,@id = $id;";

    $result = $db->sql_query($sql);
    $data = array();
    if($result){
        $data["Result"] = "OK";
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;
}
function insertNotification($msg,$id){
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type =6"
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModulePageConfiguration($parameters=array()){
    global $db;$db_table_prefix;
    $id = $parameters['id_change'];
    //autorizamos todos los modulos.
    $sql = "UPDATE ".$db_table_prefix."page_content set status=status_edit where page_id=".$id;
    $db->sql_query($sql);
    //Actualizamos la posicion de todos los modulos de esa pagina.
    $sql = "update ".$db_table_prefix."content_configuration  set size_x=size_x_edit, size_y=size_y_edit,col=col_edit,row=row_edit from  page_content pc where page_content_id=pc.id and pc.page_id=".$id;
    $db->sql_query($sql);
    //Eliminamos modulos que no hayan sido aprobados.
    $sql = "DELETE FROM ".$db_table_prefix."page_content where status=0 AND status_edit=0 AND page_id=".$id;
    $db->sql_query($sql);
    //Actualizamos el submenu
    $sql = "UPDATE ".$db_table_prefix."mod_c_submenu set link=link_edit where (link_edit = concat('./?page=','".$id."') OR link = concat('./?page=','".$id."'))" ;
    $db->sql_query($sql);
    //Actualizamos la tabla de paginas
    $sql = "UPDATE ".$db_table_prefix."page set active=active_edit where id=".$id;
    $db->sql_query($sql);
    //Eliminamos la pagina si esta ya no esta activa en edit y en forma oficial
    $sql = "DELETE FROM ".$db_table_prefix."page where active=0 AND active_edit=0 AND id=".$id;
    $db->sql_query($sql);
}
function DisapprovedModulePageConfiguration($parameters=array()){
    global $db;$db_table_prefix;
    $id = $parameters['id_change'];
    //autorizamos todos los modulos.
    $sql = "UPDATE ".$db_table_prefix."page_content set status_edit=status where page_id=".$id;
    $db->sql_query($sql);
    //Actualizamos la posicion de todos los modulos de esa pagina.
    $sql = "update ".$db_table_prefix."content_configuration  set size_x_edit=size_x, size_y_edit=size_y,col_edit=col,row_edit=row from  page_content pc where page_content_id=pc.id and pc.page_id=".$id;
    $db->sql_query($sql);
    //Eliminamos modulos que no hayan sido aprobados.
    $sql = "DELETE FROM ".$db_table_prefix."page_content where status=0 AND status_edit=0 AND page_id=".$id;
    $db->sql_query($sql);
    //Actualizamos el submenu
    $sql = "UPDATE ".$db_table_prefix."mod_c_submenu set link_edit=link where  (link_edit = concat('./?page=','".$id."') OR link = concat('./?page=','".$id."'))" ;
    $db->sql_query($sql);
    //Actualizamos la tabla de paginas
    $sql = "UPDATE ".$db_table_prefix."page set active_edit=active where id=".$id;
    $db->sql_query($sql);
    //Eliminamos la pagina si esta ya no esta activa en edit y en forma oficial
    $sql = "DELETE FROM ".$db_table_prefix."page where active=0 AND active_edit=0 AND id=".$id;
    $db->sql_query($sql);
}
?>
