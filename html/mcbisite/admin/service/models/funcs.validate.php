<?php
	function hasPermissionsSite($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		foreach ($parameters as $parameter) {
			if($loggedInUser->group_id==$parameter){
				return true;
			}
		}
		return false;
	}

	function hasPermissionsContent($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		switch ($loggedInUser->group_id) {
			case SUPER_ADMINISTRADOR:
				$sql="";
				break;

			case ADMINISTRADOR_REGIONAL:
				$sql="";
				break;

			case GESTOR:
				# code...
				break;

			case EDITOR:
				$sql=",(SELECT 
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
    				ar.role_id=ep.role_id AND ar.admin_id='20'AND ep.content_id=content.id)as permission";
				break;

			case ANALISTA:
				# code...
				break;
			
			default:
				# code...
				break;
		}
		return false;
	}

	function getPermissionSite($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$sql="";
		switch ($loggedInUser->group_id) {
			case SUPER_ADMINISTRADOR:
				$sql="";
				break;

			case ADMINISTRADOR_REGIONAL:
				$sql="SELECT rmp.site_id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."'";
				break;

			case GESTOR:
				# code...
				break;

			case EDITOR:
				$sql="SELECT s.id 
					FROM 
					[".$db_name."].[dbo].[site] s
    				INNER JOIN
    				[".$db_name."].[dbo].[page] p
    				ON p.site_id=s.id
    				INNER JOIN
    				[".$db_name."].[dbo].[page_content] pc
    				ON pc.page_id=p.id
    				INNER JOIN
    				[".$db_name."].[dbo].[editor_permission] ep
    				ON
    				ep.content_id=pc.content_id
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=ep.role_id AND ar.admin_id='".$loggedInUser->user_id."' group by s.id, ep.id";
				break;

			case ANALISTA:
				# code...
				break;
			
			default:
				# code...
				break;
		}
		return $sql;
	}

	function getPermissionNotification($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$sql="";
		switch ($loggedInUser->group_id) {
			case SUPER_ADMINISTRADOR:
				$sql="";
				break;
			case ADMINISTRADOR_REGIONAL:
				$sql="SELECT p.id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=rmp.role_id 
    				AND ar.admin_id='".$loggedInUser->user_id."' 
    				INNER JOIN
    				[".$db_name."].[dbo].[page] p
    				ON
    				p.site_id=rmp.site_id";
				break;

			case GESTOR:
				$sql="SELECT p.id 
    				FROM 
    				[".$db_name."].[dbo].[manager_permission] mp
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=mp.role_id 
    				AND ar.admin_id='".$loggedInUser->user_id."' 
    				INNER JOIN 
    				[".$db_name."].[dbo].[page] p 
    				ON 
    				p.id=mp.page_id";
				break;

			case EDITOR:
				$sql="";
				break;

			case ANALISTA:
				# code...
				break;
			
			default:
				# code...
				break;
		}
		return $sql;
	}

	function getPermissionNotificationForm($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$sql="";
		switch ($loggedInUser->group_id) {
			case SUPER_ADMINISTRADOR:
				# code...
				break;
			case ADMINISTRADOR_REGIONAL:
				$sql="SELECT p.id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=rmp.role_id 
    				AND ar.admin_id='".$loggedInUser->user_id."' 
    				INNER JOIN
    				[".$db_name."].[dbo].[page] p
    				ON
    				p.site_id=rmp.site_id";
				break;

			case GESTOR:
				# code...
				break;

			case EDITOR:
				# code...
				break;

			case ANALISTA:
				# code...
				break;
			
			default:
				# code...
				break;
		}
		return $sql;
	}

	function getCountNotification($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$where_notification="";
    	if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
    	$where_notification=" AND pc.page_id IN (".getPermissionNotification($loggedInUser->group_id).")";
    	}

    $sql_count_notification = "SELECT COUNT(*) total FROM  
        [".$db_name."].[dbo].[page_content] pc RIGHT JOIN [".$db_name."].[dbo].[content] c on  pc.content_id = c.id, 
		[".$db_name."].[dbo].[approval_m] ap, 
		[".$db_name."].[dbo].[module_list] m 
		WHERE 
		c.id = ap.content_id AND 
		m.id = c.module_id AND 
        ap.form = 0 AND 
		ap.approved_by IS NULL".$where_notification; 

		$result_count_notification = $db->sql_query($sql_count_notification);
		$result_count_notification= $db->sql_fetchrow($result_count_notification);
		$count_notification = (int)$result_count_notification['total'];


		$where_notification="";
    	if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
    	$where_notification=" AND ap.mod_type IN(2) AND ap.id_change IN (".getPermissionNotificationForm($loggedInUser->group_id).")";
    	}
    $sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[approval_m] ap WHERE form=1 AND ap.approved_by IS NULL".$where_notification; 
		$result_count = $db->sql_query($sql_count);
		$result_count= $db->sql_fetchrow($result_count);
		$count_notification_form = (int)$result_count['total'];

		$total=$count_notification+$count_notification_form;
		echo($total);
	}

function getFilterTableRol($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$sql="";
		switch ($parameters[0]) {
			case SUPER_ADMINISTRADOR:
				# code...
				break;
			case 'listCountries':
				$sql="SELECT s.country_id 
					FROM 
					[".$db_name."].[dbo].[regional_manager_permission] rmp 
					INNER JOIN 
					[".$db_name."].[dbo].[admin_role] ar 
					ON 
					ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."'  
					INNER JOIN 
					[".$db_name."].[dbo].[site] s 
					ON 
					rmp.site_id=s.id ";
				break;

			case 'listSites':
				$sql="SELECT rmp.site_id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp
    				INNER JOIN
    				[".$db_name."].[dbo].[admin_role] ar
    				ON
    				ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."'";
				break;

			case 'listPermissionGestor':
				$sql="SELECT DISTINCT p.id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp 
    				INNER JOIN 
    				[".$db_name."].[dbo].[admin_role] ar 
    				ON 
    				ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."' 
    				INNER JOIN 
    				[".$db_name."].[dbo].[page] p 
    				ON 
    				p.site_id=rmp.site_id";
				break;

			case 'listPermissionEditor':
				$sql="SELECT DISTINCT pc.content_id 
    				FROM 
    				[".$db_name."].[dbo].[regional_manager_permission] rmp 
    				INNER JOIN 
    				[".$db_name."].[dbo].[admin_role] ar 
    				ON 
    				ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."' 
    				INNER JOIN 
    				[".$db_name."].[dbo].[page] p 
    				ON 
    				rmp.site_id=p.site_id 
    				INNER JOIN 
    				[".$db_name."].[dbo].[page_content] pc 
    				ON 
    				p.id=pc.page_id group by pc.content_id";
				break;
			
			default:
				# code...
				break;
		}
		return $sql;
	}

	function PermissionDelete($parameters=array()){
		global $db, $db_table_prefix, $db_name,$loggedInUser;
		$sql="";
		$rol = $parameters[1];
    	$table = (int)$parameters[0];
		switch ($loggedInUser->group_id) {
			case SUPER_ADMINISTRADOR:
				return true;
				break;
			case ADMINISTRADOR_REGIONAL:
				if($table==1){

					$sql="SELECT CASE WHEN ((SELECT 
    					COUNT(*) 
						FROM 
    					[".$db_name."].[dbo].[manager_permission] mp 
    					INNER JOIN [".$db_name."].[dbo].[page] p 
        				ON mp.page_id = p.id INNER JOIN [".$db_name."].[dbo].[site] s 
        				ON p.site_id = s.id 
						WHERE 
    					mp.role_id = '".$rol."' AND p.id IN 
    					(SELECT p.id 
    					FROM 
    					[".$db_name."].[dbo].[regional_manager_permission] rmp 
    					INNER JOIN 
    					[".$db_name."].[dbo].[admin_role] ar 
    					ON 
    					ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."' 
    					INNER JOIN 
    					[".$db_name."].[dbo].[page] p 
    					ON 
    					p.site_id=rmp.site_id 
  						))=(SELECT COUNT(*) 
						FROM 
    					[".$db_name."].[dbo].[manager_permission] mp INNER JOIN [".$db_name."].[dbo].[page] p 
        				ON mp.page_id = p.id INNER JOIN [".$db_name."].[dbo].[site] s 
        				ON p.site_id = s.id 
						WHERE 
    					mp.role_id = '".$rol."')) THEN 1 ELSE 0 END AS resultado ";

				}elseif($table==2){

					$sql="SELECT CASE WHEN ((SELECT COUNT(*) 
						FROM 
    					[".$db_name."].[dbo].[editor_permission] ep INNER JOIN [".$db_name."].[dbo].[content] c 
        				ON ep.content_id = c.id INNER JOIN [".$db_name."].[dbo].[page_content] pc 
        				ON c.id = pc.content_id INNER JOIN [".$db_name."].[dbo].[page] p  
        				ON p.id = pc.page_id INNER JOIN [".$db_name."].[dbo].[site] s 
        				ON p.site_id = s.id INNER JOIN [".$db_name."].[dbo].[module_list] ml 
        				ON c.module_id = ml.id 
						WHERE 
    					ml.id NOT IN(7) 
    					AND role_id ='".$rol."' AND c.id IN( 
    						SELECT DISTINCT pc.content_id 
    						FROM 
    						[".$db_name."].[dbo].[regional_manager_permission] rmp 
    						INNER JOIN 
    						[".$db_name."].[dbo].[admin_role] ar 
    						ON 
    						ar.role_id=rmp.role_id AND ar.admin_id='".$loggedInUser->user_id."' 
    						INNER JOIN 
    						[".$db_name."].[dbo].[page] p 
    						ON 
    						rmp.site_id=p.site_id 
    						INNER JOIN 
    						[".$db_name."].[dbo].[page_content] pc 
    						ON 
    						p.id=pc.page_id group by pc.content_id 
    					))=(SELECT COUNT(*) 
    					FROM 
    					[".$db_name."].[dbo].[editor_permission] ep INNER JOIN [".$db_name."].[dbo].[content] c 
        				ON ep.content_id = c.id INNER JOIN [".$db_name."].[dbo].[page_content] pc 
        				ON c.id = pc.content_id INNER JOIN [".$db_name."].[dbo].[page] p  
        				ON p.id = pc.page_id INNER JOIN [".$db_name."].[dbo].[site] s 
        				ON p.site_id = s.id INNER JOIN [".$db_name."].[dbo].[module_list] ml 
        				ON c.module_id = ml.id 
						WHERE 
    					ml.id NOT IN(7) 
    					AND role_id ='".$rol."')) THEN 1 ELSE 0 END AS resultado";

				}
				$result = $db->sql_query($sql);
				$result = $db->sql_fetchrow($result);
				$valor = $result['resultado'];
				return $valor;
				
				break;
			case EDITOR:
				# code...
				break;
			
			default:
				# code...
				break;
		}
	}
?>