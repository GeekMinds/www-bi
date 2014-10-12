<?php


function getChildren(&$node,&$db){
 	$id=$node["id"];
  	$sentencia = "
  	SELECT --TOP(7)
  	id,link, title_es 
  	FROM
  		mod_c_submenu
	where parent_submenu_id={$id}";

  	$result = $db->sql_query($sentencia);
  	$node["children"]= $db->sql_fetchrowset($result);

  	foreach ($node["children"] as &$child) {
    	getChildren($child,$db);
	}
}


function getFooterTreeDB(&$node,&$db){
 	$result=false;
 	$id=$node['id'];

  	$sentencia = "
  	SELECT 	--TOP(7) 
  	id,link, title_es 
  	FROM
  		mod_c_submenu
	where mod_c_id={$id}";

  	$result = $db->sql_query($sentencia);
  	$children= $db->sql_fetchrowset($result);
  	foreach ($children as &$child) {
    	getChildren($child,$db);
	}
  	$node['children']=$children;
}


function getFooterDB(){
	global $db, $db_table_prefix,$site_id;
 	$footer=false;
  	$sentencia = "
  	SELECT TOP(7)  
		bar.id, 
		bar.id AS Value, 
		bar.title_es AS DisplayText, 
		bar.title_es, 
		bar.title_en, 
		bar.icon_bar, 
		bar.icon_vertical_menu, 
		bar.link, 
		bar.content_id,
		bar.active 
	FROM 
		[mod_c] bar INNER JOIN [content] c 
			ON bar.content_id = c.id  
			AND bar.active=1
		INNER JOIN [header] h 
			ON c.id = h.content_id 
			AND  h.site_id ={$site_id}  
  	";

  	$footer= $db->sql_query($sentencia);
  	$footer= $db->sql_fetchrowset($footer);
  	foreach ($footer as &$nodo) {
    	getFooterTreeDB($nodo,$db);
	}

  	return $footer;

}











?>