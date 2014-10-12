<?php

error_reporting(E_ALL ^ E_NOTICE);
/*
 */

//You can getLastInsertion
function getLastInsertionSite() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    return $row['last_intertion'];
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//You can get the list sites with filter options
function listSiteDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name,$loggedInUser;
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';

    $parameters['cli'] = (isset($parameters['cli'])) ? $parameters['cli'] : ''; //que módulo del administrador solicita la información
    $parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : ''; //Admin group_id

    error_log(json_encode($parameters));
    if ($parameters['cli'] == "permissions") {//Se solicita ver el listado de sitios por el módulo de permisos
        if ($parameters['group_id'] != "") {//Admin group_id
            $group_id = (int) $parameters['group_id'];
            switch ($group_id) {
                case SUPER_ADMINISTRADOR:
                    $registro = array();
                    $registro["Value"] = "0";
                    $registro["DisplayText"] = "Todos los portales";
                    $data["rows"][] = $registro;
                    return $data;
                    break;
                case ANALISTA:
                    $registro = array();
                    $registro["Value"] = "-1";
                    $registro["DisplayText"] = "Solamente permisos a estadísticas";
                    $data["rows"][] = $registro;
                    return $data;
                    break;
            }
        }
    }

    $where_site="";
    if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
    	$where_site="WHERE id IN (".getPermissionSite($loggedInUser->group_id).") AND eliminado=0";
    }else{
    	$where_site=" WHERE eliminado=0";

    }
    $sql = "";
    $sql_size = "";
    if ($parameters['jtpagesize'] != '') {
        $parameters['jtpagesize'] = (int) $parameters['jtstartindex'] + (int) $parameters['jtpagesize'];
        $sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY " . $parameters['jtsorting'] . ") AS Row,
							id Value, 
							title_es_edit as  DisplayText, 
							id,
							title_es_edit as title_es,
							title_en_edit as title_en,
							alias_edit as alias,
							description_edit as description , 
							country_id_edit as country_id,
							background_light_color_edit as background_light_color, 
							background_middle_color_edit as background_middle_color, 
							background_dark_color_edit as background_dark_color,
                            foreground_color_edit as foreground_color,
							local_currency_id_edit as local_currency_id,
							foreing_currency_id_edit as foreing_currency_id,
							show_exchange_rate_edit as show_exchange_rate,
							country_group_id_edit as country_group_id 
					FROM [" . $db_name . "].[dbo].[site]".$where_site." )
					AS user_with_numbers
				WHERE Row > " . $parameters['jtstartindex'] . " AND Row <= " . $parameters['jtpagesize'] . "";
    } else {
        $sql = "SELECT  

	id Value, 
							title_es_edit as  DisplayText, 
							id,
							title_es_edit as title_es,
							title_en_edit as title_en,
							alias_edit as alias,
							description_edit as description , 
							country_id_edit as country_id,
							background_light_color_edit as background_light_color, 
							background_middle_color_edit as background_middle_color, 
							background_dark_color_edit as background_dark_color,
                            foreground_color_edit as foreground_color,
							local_currency_id_edit as local_currency_id,
							foreing_currency_id_edit as foreing_currency_id,
							show_exchange_rate_edit as show_exchange_rate,
						 	country_group_id_edit as country_group_id  
						FROM [" . $db_name . "].[dbo].[site] ".$where_site;
    }

    $sql_count = "SELECT COUNT(*) total FROM [" . $db_name . "].[dbo].[site] ".$where_site;


    //error_log("La consulta>".$sql);
    $result = $db->sql_query($sql);
    $result_count = $db->sql_query($sql_count);

    $result = $db->sql_fetchrowset($result);
    $result_count = $db->sql_fetchrow($result_count);
    //dumpear($result);
    $data["rows"] = $result;
    $data["count"] = $result_count['total'];

    return $data;
}

//You can create site
function createSiteDataBase($parameters = array()) {
    require_once("funcs.page_content.php");

    if (strtolower($parameters["background_light_color"]) == "#40baeb") {
        $parameters["background_light_color"] = "";
    }
    if (strtolower($parameters["background_middle_color"]) == "#033d78") {
        $parameters["background_middle_color"] = "";
    }
    if (strtolower($parameters["background_dark_color"]) == "#022b51") {
        $parameters["background_dark_color"] = "";
    }

    global $db, $db_table_prefix, $db_name,$loggedInUser;
    //GetDate
    $sql = "INSERT INTO [" . $db_name . "].[dbo].[site] (
			title_es_edit,
			title_en_edit,
			alias_edit,
			description_edit,
			country_id_edit,
			background_light_color_edit,
			background_middle_color_edit,
			background_dark_color_edit,
            foreground_color_edit,
			created_at,
			local_currency_id_edit,
			foreing_currency_id_edit,
			show_exchange_rate_edit,
			country_group_id_edit,
			agregado
			)
			VALUES (
			'" . $db->sql_escape($parameters["title_es"]) . "',
			'" . $db->sql_escape($parameters["title_en"]) . "',
			'" . $db->sql_escape(strtolower($parameters["alias"])) . "',
			'" . $db->sql_escape($parameters["description"]) . "',
			'" . $db->sql_escape($parameters["country_id"]) . "',
			'" . $db->sql_escape($parameters["background_light_color"]) . "',
			'" . $db->sql_escape($parameters["background_middle_color"]) . "',
			'" . $db->sql_escape($parameters["background_dark_color"]) . "',
                        '" . $db->sql_escape($parameters["foreground_color"]) . "',
			GetDate(),
			'" . $db->sql_escape($parameters["local_currency_id"]) . "',
			'" . $db->sql_escape($parameters["foreing_currency_id"]) . "',
			'" . $db->sql_escape(intval($parameters["show_exchange_rate"])) . "',
			'" . $db->sql_escape(intval($parameters["country_group_id"])) . "', 
			1
			)";


    $result = $db->sql_query($sql);

    $parameters["id"] = $db->sql_nextid();
    $site_id = $parameters["id"];

    if ($result) {
        //CADA VEZ QUE SE CREA UN SITIO SE CREA DEFAUL UNA PAGINA HOME
        $create_home = '{
						"page_id":"",
						"site_id":"' . $site_id . '",
						"page_title_es":"Home",
						"page_title_en":"Home",
						"page_description":"",
						"menu_id":"",
						"default_page":"home",
						"content_configuration":[]
						}';

        createPageDataBase(json_decode($create_home, true));


        //CADA VEZ QUE SE CREA UN SITIO SE CREA DEFAUL UNA PAGINA LOGIN
        $create_login = '{
						"page_id":"",
						"site_id":"' . $site_id . '",
						"page_title_es":"Login",
						"page_title_en":"Login",
						"page_description":"",
						"menu_id":"",
						"default_page":"login",
						"content_configuration":[
												{
													"size_x":"1",
													"size_y":"1",
													"col":"1",
													"row":"1",
													"content_id":"-1",
													"module_type_id":"22",
													"module_title":"Login Box"
												 }
												 ]
						}';


        createPageDataBase(json_decode($create_login, true));


        //CADA VEZ QUE SE CREA UN SITIO SE CREA DEFAUL UNA PAGINA DE REGISTRO
        $create_register = '{
						"page_id":"",
						"site_id":"' . $site_id . '",
						"page_title_es":"Register",
						"page_title_en":"Register",
						"page_description":"",
						"menu_id":"",
						"default_page":"register",
						"content_configuration":[
												{
													"size_x":"4",
													"size_y":"2",
													"col":"1",
													"row":"1",
													"content_id":"-1",
													"module_type_id":"21",
													"module_title":"Register Box"
												 }
												 ]
						}';

        createPageDataBase(json_decode($create_register, true));


        //CADA VEZ QUE SE CREA UN SITIO SE CREA DEFAUL UNA PAGINA PARA EL GEOLOCALIZADOR
        $create_geolocalizador = '{
						"page_id":"",
						"site_id":"' . $site_id . '",
						"page_title_es":"Geolocalizador",
						"page_title_en":"Geolocalizador",
						"page_description":"",
						"menu_id":"",
						"default_page":"geolocator",
						"content_configuration":[
												{
													"size_x":"2",
													"size_y":"2",
													"col":"1",
													"row":"1",
													"content_id":"-1",
													"module_type_id":"8",
													"module_title":"Geolocator Box"
												 }
												 ]
						}';

        createPageDataBase(json_decode($create_geolocalizador, true));
        updateHtaccess();

 
     $change_description="Se creo el sitio <b>". $db->sql_escape($parameters["title_es"]) ."</b>";
     notificacion($site_id,$change_description );


        return $parameters;
    }


    return false;
}


function synchronizeCorporationMenu($parameters=array()){
	global $site_id;
	require_once("funcs.modc.php");
	$parameters = array();
	$parameters["site_id"] = "NULL";//Todas las secciones con NULL serán seeciones compartidas por todos los sitios.
	$site_id = "NULL";
	$bar_list = listModCDataBase($parameters);
	$bar_list = $bar_list ["rows"];
	if($bar_list && sizeof($bar_list)>0){
		//INSERTAR LA NUEVA OPCION
	}else{
		$parameters = array();
		$parameters["title_es"] = "Corporación";
		$parameters["title_en"] = "Corporation";
		$parameters["icon_bar"] = "corporacionLOGO.png";
		$parameters["icon_vertical_menu"] = "logoMenuLateral.png";
		$parameters["link"] = "";
		$parameters["show_title"] = "0";
		createModCDataBase($parameters);
		$last_insertion_id = getLastInsertionModC();
		$parameters = array();
		$parameters['module_id'] = $last_insertion_id;
		//INSERTAR LA NUEVA OPCION
	}
	return $bar_list;
}

//You can update sites
function updateSiteDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name,$loggedInUser;;
	
    if (strtolower($parameters["background_light_color"]) == "#40baeb") {
        $parameters["background_light_color"] = "";
    }
    if (strtolower($parameters["background_middle_color"]) == "#033d78") {
        $parameters["background_middle_color"] = "";
    }
    if (strtolower($parameters["background_dark_color"]) == "#022b51") {
        $parameters["background_dark_color"] = "";
    }
	
	//country_id = '" . $db->sql_escape($parameters["country_id"]) . "',
	

    $sql = "UPDATE [" . $db_name . "].[dbo].[site]  SET 
					title_en_edit = '" . $db->sql_escape($parameters["title_en"]) . "',
					title_es_edit = '" . $db->sql_escape($parameters["title_es"]) . "',
					alias_edit = '" . $db->sql_escape(strtolower($parameters["alias"])) . "',
					description_edit = '" . $db->sql_escape($parameters["description"]) . "',
					background_light_color_edit = '" . $db->sql_escape($parameters["background_light_color"]) . "',
					background_middle_color_edit = '" . $db->sql_escape($parameters["background_middle_color"]) . "',
					background_dark_color_edit = '" . $db->sql_escape($parameters["background_dark_color"]) . "',
                    foreground_color_edit = '" . $db->sql_escape($parameters["foreground_color"]) . "',
					local_currency_id_edit = '" . $db->sql_escape($parameters["local_currency_id"]) . "',
					foreing_currency_id_edit = '" . $db->sql_escape($parameters["foreing_currency_id"]) . "',
					show_exchange_rate_edit = '" . $db->sql_escape(intval($parameters["show_exchange_rate"])) . "',
					country_id_edit = '" . $db->sql_escape($parameters["country_id"]) . "',
					country_group_id_edit = '" . $db->sql_escape(intval($parameters["country_group_id"])) . "'
			WHERE id = " . $db->sql_escape($parameters["id"]) . ";";
    //return $sql;
			
    $result = $db->sql_query($sql);
    //insertTags($parameters);

    	$sql =" Select title_es from [".$db_name."].[dbo].[".$db_table_prefix."site] where id= ".$db->sql_escape($parameters["id"]) ."" ;

        $result_title = $db->sql_query($sql);
        $result_title= $db->sql_fetchrow($result_title);
      
      $change_description="Se actualizo el sitio <b>".$result_title['title_es']."</b>";
      notificacion($db->sql_escape($parameters["id"]),$change_description );        





    updateHtaccess();
    $db->sql_close();


    return $result;
}

function getDefaultPages($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $site_id;
    $data = array();

    $sql = "SELECT id, alias, page_id
			FROM [" . $db_name . "].[dbo].[default_page] 
			WHERE site_id = " . $db->sql_escape($site_id) . " ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data = $rows;
    return $data;
}

//You can delete sites
function deleteSiteDataBase($parameters = array()) {
	global $db, $db_table_prefix, $db_name;


	$sql = "UPDATE   [".$db_name."].[dbo].[".$db_table_prefix."site] SET eliminado=1  WHERE id = " . $db->sql_escape($parameters["id"]);
	$result = $db->sql_query($sql);

		$sql ="SELECT title_es FROM [".$db_name."].[dbo].[".$db_table_prefix."site] WHERE id=".$db->sql_escape($parameters["id"]);
		$result_title = $db->sql_query($sql);
        $result_title= $db->sql_fetchrow($result_title);      

        
    $change_description="Se ha eliminado un sitio (<b>".$result_title["title_es"]."</b>)";
	notificacion($db->sql_escape($parameters["id"]),$change_description );

	return ($result);
}

function getAllSites() {
    global $db, $db_table_prefix, $db_name, $site_id;
    $data = array();
    $sql = "SELECT id, alias
			FROM [" . $db_name . "].[dbo].[site]  ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data = $rows;
    return $data;
}

function getAllCountries() {
    global $db, $db_table_prefix, $db_name, $site_id;
    $data = array();
    $sql = "SELECT DISTINCT c.id, c.alias
			FROM 
				[" . $db_name . "].[dbo].[country] c,
				[" . $db_name . "].[dbo].[site] s 
			WHERE
				c.id = s.country_group_id ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data = $rows;
    return $data;
}



function updateHtaccess() {
    $file = fopen("../../bisite/.htaccess", "w");

    $countries = getAllCountries();

	$list_alias = "";
    $content = 'RewriteEngine On
	';

    for ($i = 0; $i < count($countries); $i++) {
        $country = $countries[$i];
		if(str_replace(" ","",$country["alias"])!=""){
			if($i>0){
				$list_alias .= "|";
			}
			$list_alias .= $country["alias"];	
		}
    }
	
	/*$content.= '
RewriteRule ^('.$list_alias.')(/[a-zA-Z-]+)*(/*|/?)$ index.php?country=$1&site_alias=$2&file=$3 [QSA,NC,L]
RewriteRule ^('.$list_alias.')/([a-zA-Z-]+)(/([a-zA-Z-]+)).php$ index.php?country=$1&site_alias=$2&file=$3
RewriteRule ^('.$list_alias.')/([a-zA-Z-]+)(/*([a-zA-Z-]+)*)(/*)$ index.php?country=$1&site_alias=$2&page=$3
ErrorDocument 404 notfound.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . notfound.php';*/
	/*$content.= '
RewriteRule ^('.$list_alias.')(\/|\/\?)?$ index.php?country=$1 [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/[a-zA-Z-]+.php\??)$ index.php?country=$1&file=$2 [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/[a-zA-Z-]+|\/[a-zA-Z-]+\/|\/[a-zA-Z-]+\/\?)$ index.php?country=$1&site_alias=$2  [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/[a-zA-Z-]+\/)([a-zA-Z-]+.php\??)$ index.php?country=$1&site_alias=$2&file=$3 [QSA,NC,L]
ErrorDocument 404 notfound.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . notfound.php';*/
	$content.= '
RewriteRule ^('.$list_alias.')(\/|\/\?)?$ index.php?country=$1&rule=1 [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/)([a-zA-Z-]+.php)(\?)?$ index.php?country=$1&file=$3&rule=3 [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/)([a-zA-Z-]+)(\/|\/\?)?$ index.php?country=$1&site_alias=$3&rule=5 [QSA,NC,L]
RewriteRule ^('.$list_alias.')(\/)([a-zA-Z-]+)(\/)([a-zA-Z-]+.php)(\?)?$ index.php?country=$1&site_alias=$3&file=$5&rule=6 [QSA,NC,L]

ErrorDocument 404 notfound.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . notfound.php';


    if ($file) {
        $res = fwrite($file, $content);
        fclose($file);
    }

    return $content;
}


function updateHtaccessOld() {
    $file = fopen("../../bisite/.htaccess", "w");

    $sites = getAllSites();

    $content = 'RewriteEngine On
	';

    for ($i = 0; $i < count($sites); $i++) {
        $site = $sites[$i];
        $content .= '
RewriteRule ^(' . $site["alias"] . ')$ index.php?site_id=' . $site["id"] . '
RewriteRule ^(' . $site["alias"] . ')/?$ index.php?site_id=' . $site["id"] . '&page=$1 [QSA,NC,L]
RewriteRule ^(' . $site["alias"] . ')/$ index.php?site_id=' . $site["id"] . '
RewriteRule ^(' . $site["alias"] . ')/([0-9]+)$ index.php?site_id=' . $site["id"] . '&page=$2 
RewriteRule ^(' . $site["alias"] . ')/([0-9]+)/$ index.php?site_id=' . $site["id"] . '&page=$2
RewriteRule ^(' . $site["alias"] . ')/([a-zA-Z]+).php$ index.php?site_id=' . $site["id"] . '&file=$2';
		
    }
	
	/*$content.= '
RewriteRule ^('.$list_alias.')(/[a-zA-Z-]+)*(/*|/?)$ index.selva.php?country=$1&site_alias=$2&file=$3 [QSA,NC,L]
RewriteRule ^('.$list_alias.')/([a-zA-Z-]+)(/([a-zA-Z-]+)).php$ index.selva.php?country=$1&site_alias=$2&file=$3
RewriteRule ^('.$list_alias.')/([a-zA-Z-]+)(/*([a-zA-Z-]+)*)(/*)$ index.selva.php?country=$1&site_alias=$2&page=$3
RewriteRule ^('.$list_alias.')/([a-zA-Z-]+)(/*([a-zA-Z-]+)*)(/*)$ index.selva.php?country=$1&site_alias=$2&page=$3';*/


    if ($file) {
        $res = fwrite($file, $content);
        fclose($file);
    }

    return $content;
}

function validateAlias($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $site_id;
    //var_dump($parameters);
    $fieldvalue = $parameters['fieldvalue'];
    $found = false;
    if (isset($parameters['editid'])) {
        //alias existente
        $siteid = $parameters['editid'];
        $sql = "SELECT alias from site where id not in (" . $siteid . ")";
        $result = $db->sql_query($sql);
        $rows = $db->sql_fetchrowset($result);
        foreach ($rows as $row){
            error_log("Field:".$fieldvalue."==".$row['alias']);
            if($fieldvalue==$row['alias']){
                $found = true;
                break;
            }
        }
    } else {
        //nuevo alias
        $sql = "SELECT alias from site";
        $result = $db->sql_query($sql);
        $rows = $db->sql_fetchrowset($result);
        foreach ($rows as $row){
            error_log("Field:".$fieldvalue."==".$row['alias']);
            if($fieldvalue==$row['alias']){
                $found = true;
                break;
            }
        }
    }
    $data = array();
    if($found){
        $data[0]="Edit-alias";
        $data[1]=false;
    }else{
        $data[0]="Edit-alias";
        $data[1]=true;
    }
    return $data;
}


function siteInfo($parameters = array()){
	global $db, $db_table_prefix, $db_name, $site_id;
    $data = array();
    $sql = "SELECT
				alias site_alias,
				title_es,
				title_en,
				description,
				country_id
			FROM 
				[".$db_name."].[dbo].[site]
			WHERE 
				id = " .$db->sql_escape($site_id) . " ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);
    return $rows;
}




function ApprovedModuleSite($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['id_change'];


$sql="SELECT eliminado,agregado from [".$db_name."].[dbo].[".$db_table_prefix."site] where id=".$id;
    	$result_eliminado = $db->sql_query($sql);
		$result_eliminado= $db->sql_fetchrow($result_eliminado);


			if ($result_eliminado["eliminado"]=='1'){

			$sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."site] WHERE id=".$id ;


			}else{

            $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."site]  SET 
					title_en = title_en_edit, 
					title_es = title_es_edit, 
					alias =alias_edit, 
					description = description_edit , 
					background_light_color = background_light_color_edit, 
					background_middle_color = background_middle_color_edit, 
					background_dark_color = background_dark_color_edit, 
                    foreground_color = foreground_color_edit, 
					local_currency_id = local_currency_id_edit, 
					foreing_currency_id = foreing_currency_id_edit, 
					show_exchange_rate = show_exchange_rate_edit,
					country_id = country_id_edit, 
					country_group_id = country_group_id_edit, 
					agregado=0
			 WHERE id = " .$id. ";";

			}




    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;

}

function DisapprovedModuleSite($parameters){


	global $db,$db_table_prefix, $db_name; 
    $id = $parameters['id_change'];


    	$sql="SELECT eliminado,agregado from [".$db_name."].[dbo].[".$db_table_prefix."site] where id=".$id;
    	$result_eliminado = $db->sql_query($sql);
		$result_eliminado= $db->sql_fetchrow($result_eliminado);


    if ($result_eliminado["agregado"]=='1'){
		$sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."site] WHERE id=".$id ;
    }else{

    	if ($result_eliminado["eliminado"]=='1'){
    		$sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."site]  SET 
			 		eliminado = 0 
			 WHERE id = " .$id. ";";

    	}else{

    	
   $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."site]  SET 
					title_en_edit = title_en,
					title_es_edit = title_es,
					alias_edit =alias,
					description_edit = description ,
					background_light_color_edit = background_light_color,
					background_middle_color_edit = background_middle_color,
					background_dark_color_edit = background_dark_color,
                    foreground_color_edit = foreground_color,
					local_currency_id_edit = local_currency_id,
					foreing_currency_id_edit = foreing_currency_id,
					show_exchange_rate_edit = show_exchange_rate,
					country_id_edit = country_id,
					country_group_id_edit = country_group_id,
					eliminado=0
			 WHERE id = " .$id. ";";
			 }
		}

    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;


}

function notificacion($id,$change_description ){
	global $db, $db_table_prefix,$loggedInUser;

	$sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $id . "" 
            . ",@mod_type=4" 
            . ",@description='".$change_description."'"; 

	$result_procedure = $db->sql_query($sql); 

}

function infoSiteDataBase($parameters){
	global $db, $db_table_prefix,$loggedInUser;
	$data = array();

		$sql  ="select  c.alias as pref,s.alias_edit as alias from site s inner join ";
		$sql .="country c on c.id=s.country_group_id_edit where s.id=".$parameters['id'];

		    //error_log("La consulta>".$sql);
		//var_dump($sql);
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    
    $data["rows"] = $result;
    

    return $data;

}



?>