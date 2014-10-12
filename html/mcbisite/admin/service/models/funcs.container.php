<?php
function getContainerDistributionDataBase($parameters=array())
{
	global $db,$db_table_prefix,$loggedInUser; 
	$page_id = isset($parameters['page_id']) ? $parameters['page_id'] : '0';
	$data = array();
	
	$parameters['cli'] = (isset($parameters['cli'])) ? $parameters['cli'] : '';//que módulo del administrador solicita la información
	$parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : '';//Admin group_id
	
	error_log(json_encode($parameters));
	if($parameters['cli']=="permissions"){//Se solicita ver el listado de sitios por el módulo de permisos
		if($parameters['group_id']!=""){//Admin group_id
			$group_id = (int)$parameters['group_id'];
			switch($group_id){
				case SUPER_ADMINISTRADOR:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todos los contenidos";
					$data[] = $registro;
					return $data;
				break;
				case ADMINISTRADOR_REGIONAL:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todos los contenidos";
					$data[] = $registro;
					return $data;
				break;
				case GESTOR:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Todos los contenidos";
					$data[] = $registro;
					return $data;
				break;
				case ANALISTA:
					$registro = array();
					$registro["Value"] = "0";
					$registro["DisplayText"] = "Solamente permisos a estadísticas";
					$data[] = $registro;
					return $data;
				break;
			}
		}
	}
	
	
	$page_id = $db->sql_escape($page_id);

	$sql = "SELECT 
				content.id AS Value, 
				CONCAT(mod.description, '::',content.title_es) AS DisplayText,
				page.title_es,
				page.title_en,
				mod.name as module_name,
				mod.description as module_description,
				config.size_x_edit as size_x,
				config.size_y_edit as size_y,
				config.col_edit as col,
				config.row_edit as row,
				config.status,
				content.id as content_id,
				content.module_id as module_type_id";
				if($loggedInUser->group_id==EDITOR){
					$sql.=",(SELECT 
 					CASE
 					WHEN count(ep.content_id) > 0
 					THEN '1'
 					ELSE '0'
					END AS permission
    				FROM 
    				page_content pc
    				INNER JOIN
    				editor_permission ep
    				ON
    				ep.content_id=pc.content_id
    				INNER JOIN
    				admin_role ar
    				ON
    				ar.role_id=ep.role_id AND ar.admin_id='".$loggedInUser->user_id."'AND ep.content_id=content.id) AS module_permission";
				}
				
			$sql.=" FROM 
				page_content AS pc, 
				page, 
				content, 
				content_configuration AS config, 
				module_list as mod
			WHERE 
				page.id = pc.page_id AND
                                pc.status_edit=1 AND
				content.id = pc.content_id AND
				config.page_content_id = pc.id AND
				mod.id = content.module_id AND
				pc.page_id =  " . $page_id . " 
			ORDER BY config.row , config.col";
				
	//$sql = "SELECT * FROM ".$db_table_prefix."mod_c WHERE content_id = 3 ";
	//echo($sql);

	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	
	$data = $rows;
	//$data=$sql;
	return $data;
}


function getModuleInfoDataBase($parameters=array()){
	global $db,$db_table_prefix; 
	$module_name = isset($parameters['module_name']) ? $parameters['module_name'] : 'module_carrousel';
	$content_id = isset($parameters['content_id']) ? $parameters['content_id'] : '8';
	$sql = "SELECT * FROM ".$module_name. " WHERE content_id = ".$content_id;
	
	///error_log($sql);
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrow($result);
	$data = $rows;
	return $data;
}

function getContainerOFDefaultGeolocator($parameters=array()){
	 global $db, $db_table_prefix, $db_name, $site_id;
	 $sql = "SELECT 			
				df.site_id,
				df.page_id,
	 			pc.content_id content_id,
				g.id module_id
			FROM 
				default_page df,
				content c,
				page_content pc
				LEFT JOIN mod_g g
					ON pc.content_id=g.content_id
			WHERE 
				df.site_id = ".$site_id." AND
				pc.page_id = df.page_id AND
				c.id = pc.content_id AND
				c.module_id = 8 AND
				df.alias = 'geolocator'  ";
				
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrow($result);	
	return $rows;
}

?>