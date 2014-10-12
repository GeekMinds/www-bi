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

//You can create mod_c
function createModuleKDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;

    $sql = "INSERT INTO [".$db_name."].[dbo].[mod_k] (
			titulo_es_edit,
                        titulo_es,
			titulo_en_edit,
                        titulo_en,
			content_id
			)
			VALUES (
			'" . $db->sql_escape($parameters["titulo_es"]) . "',
                        '" . $db->sql_escape($parameters["titulo_es"]) . "',
			'" . $db->sql_escape($parameters["titulo_en"]) . "',
                        '" . $db->sql_escape($parameters["titulo_en"]) . "',
			'" . $db->sql_escape($parameters["content_id"]) . "'
			)";
	
    $result = $db->sql_query($sql);
   EditedContent($parameters["content_id"],"Se creo una Galeria de productos nueva");
    if ($result) {
        return $result;
    }
    return false;
}

//You can get the list countries with filter options
function listModuleKDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$limit = "";
	
	if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
       // $limit = " TOP " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
	    $limit = " TOP " .  $db->sql_escape($parameters['jtpagesize']);
    }

    $sql = "SELECT 	".$limit."
 					id,
					title_es, 
					title_en,
					description,
					content_id,
					created_at,
					title_es AS Value,
					title_es AS DisplayText			
			FROM [".$db_name."].[dbo].[module_search_questions] ";
			
			
    $sql_count = "SELECT COUNT(*) as count FROM [".$db_name."].[dbo].[module_search_questions]  ";


    if ($parameters['jtsorting'] != '') {
        $sql .= " ORDER BY " . $parameters['jtsorting'];
    }
   
    $result = $db->sql_query($sql);
    $result_cont = $db->sql_query($sql_count);

    $rows = $db->sql_fetchrowset($result);
    $count = $db->sql_fetchrow($result_cont);

    $data['rows'] = $rows;
    $data['count'] = $count['count'];
    $db->sql_close();
    return $data;
}


//You can get the list of buttons from A module
function getModuleKDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();

    $sql = "SELECT id, titulo_es_edit as titulo_es, titulo_en_edit as titulo_en 
			FROM [".$db_name."].[dbo].[mod_k] 
			WHERE id = " . $db->sql_escape($parameters["module_id"]) . " ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);

    $data = $rows;

    return $data;
}



//You can get the list of sub menu optiones from C module
function saveModuleKDataBase($parameters = array()) {
	$return = array();
	$result =  false;
    global $db, $db_table_prefix;
    $parameters["module_id"] = (isset($parameters['module_id'])) ? $parameters['module_id'] : "-1";
	

	if($parameters['module_id']=="-1"){
		$result = createModuleKDataBase($parameters);
		$return["module_id"] = getLastInsertion();
	}else{
		$result = updateModuleKDataBase($parameters);
		$return["module_id"] =  $parameters["module_id"];
	}
	
	if(!$result){
		return false;
	}
	
	$params = array();
	$params["mod_k_id"] = $return["module_id"];
	$params["list_products"] = $parameters["productos"];
	$return_insert = insertProductsINGalery($params);
	
	return $return_insert;
}


//You can update specific mod_c
function updateModuleKDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[mod_k]  SET 
					titulo_es_edit = '" . $db->sql_escape($parameters["titulo_es"]) . "', 
					titulo_en_edit = '" . $db->sql_escape($parameters["titulo_en"]) . "'
			WHERE id = " . $db->sql_escape($parameters["module_id"]) . ";";
	//return $sql;

    $result = $db->sql_query($sql);
    EditedContent($parameters["content_id"],"Se ha modificado el titulo de la Galeria");
    return $result;
}


//You can delete specific mod_c
function deleteModADataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $sql = "DELETE FROM " . $db_table_prefix . "mod_c WHERE id = " . $db->sql_escape($parameters["id"]);

    $result = $db->sql_query($sql);

    return ($result);
}


/*********************************************************************************************************
**	ADMIN QUESTIONS
***********************************************************************************************************/

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ QUESTIONS OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list countries with filter options
function listProductDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $site_id;
    $module_id = (isset($parameters['module_id'])) ? $parameters['module_id'] : "";
    $data = array();
	$limit = "";
	
	if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
       // $limit = " TOP " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
	    $limit = " TOP " .  $db->sql_escape($parameters['jtpagesize']);
    }

    $sql = "SELECT 	".$limit."
					q.id,
 					q.titulo_es,
 					q.titulo_en,
 					q.tags_es,
					q.tags_en,
					q.precio_q,
					q.precio_us,
					q.fecha_init,
					q.fecha_end,
					q.status,
					COUNT(gp.id) selected
			FROM 
				[".$db_name."].[dbo].[page_content] pc,
				[".$db_name."].[dbo].[page] p,
				[".$db_name."].[dbo].[mod_q] q
					LEFT OUTER JOIN galery_product gp
					ON q.id=gp.mod_q_id AND gp.mod_k_id=".$module_id." AND gp.estado_edit=1
			WHERE
				p.site_id = " . $db->sql_escape($site_id) . " AND 
				pc.page_id = p.id AND 
				q.content_id = pc.content_id 
			GROUP BY q.id,q.titulo_es,q.titulo_en,q.tags_es,q.tags_en,q.precio_q,q.precio_us,q.fecha_init,q.fecha_end,q.status";
	
      
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);

    $data['rows'] = $rows;
    $data['count'] = count($rows);
	$data['data'] = getModuleKDataBase($parameters);
	
    return $data;
}


//You can create mod_c
function createProductDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;

    $sql = "INSERT INTO [".$db_name."].[dbo].[question] (
			question_es,
			question_en,
			module_search_questions_id
			)
			VALUES (
			'" . $db->sql_escape($parameters["question_es"]) . "',
			'" . $db->sql_escape($parameters["question_en"]) . "',
			'" . $db->sql_escape($parameters["module_id"]) . "'
			)";
			

    $result = $db->sql_query($sql);
	
	$parameters["question_id"] = getLastInsertion();
	insertTags($parameters);
    if ($result) {
		$data = array();
		$data["question_id"] = getLastInsertion();
		$data["question_es"] = $parameters["question_es"];
		$data["question_en"] = $parameters["question_en"];
        return $data;
    }
    return false;
}

//You can update specific mod_c
function updateProductDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[question]  SET 
					question_es = '" . $db->sql_escape($parameters["question_es"]) . "', 
					question_en = '" . $db->sql_escape($parameters["question_en"]) . "'
			WHERE id = " . $db->sql_escape($parameters["question_id"]) . ";";
	//return $sql;
    $result = $db->sql_query($sql);
	
	insertTags($parameters);
    return $result;
}


//You can delete specific mod_c
function deleteProductDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;


    $sql = "DELETE FROM  [".$db_name."].[dbo].[question]  WHERE id = " . $db->sql_escape($parameters["question_id"]);

    $result = $db->sql_query($sql);

    return ($result);
}

function insertProductsINGalery($parameters=array()){
	global $db, $db_table_prefix, $db_name;
        $parameters["list_products"] = (isset($parameters['list_products'])) ? $parameters['list_products'] : array();
	$products = $parameters["list_products"];
	
	$sql = "Select mod_q_id as ID from [".$db_name."].[dbo].[galery_product] where mod_k_id=".$db->sql_escape($parameters["mod_k_id"]);
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);
        $column = $row;
        
        $id_list= array();
        foreach ($column as $key){
            array_push($id_list, $key["ID"]);
            
        }
        $res = "";
        
        for($i=0; $i<count($id_list); $i++){
            if(in_array( $id_list[$i],$products)){
                $sql = "UPDATE [".$db_name."].[dbo].[galery_product] set estado_edit=1 where mod_q_id=".$id_list[$i]." and mod_k_id=".$db->sql_escape($parameters["mod_k_id"]);
                $result = $db->sql_query($sql);
                
            }
            else{
                //$sql = "DELETE FROM [".$db_name."].[dbo].[galery_product] where estado = 0 and mod_q_id=".$products[$i]." and mod_k_id=".$db->sql_escape($parameters["mod_k_id"]);
                $res .= "No encontrado".$id_list[$i];
                $sql = "UPDATE [".$db_name."].[dbo].[galery_product] set estado_edit=0 where mod_q_id=".$id_list[$i]." and mod_k_id=".$db->sql_escape($parameters["mod_k_id"]);
                $result = $db->sql_query($sql);
                
            }
            $key = array_search($id_list[$i],$products);
            unset($products[$key]);
        }

        
	foreach ($products as $var1){
		 $sql = "INSERT INTO [".$db_name."].[dbo].[galery_product] (
				mod_q_id,
				mod_k_id,
                                estado,
                                estado_edit
				)
				VALUES (
				'" . $db->sql_escape($var1) . "',
				'" . $db->sql_escape($parameters["mod_k_id"]) . "',
                                0,
                                1 
				)";			
		$result = $db->sql_query($sql);
	}
        $sql = "delete from galery_product where estado_edit=0 and estado=0 and mod_k_id=". $db->sql_escape($parameters["mod_k_id"]);
        $db->sql_query($sql);
        
        EditedContent($parameters["content_id"],"Se han modificado productos en esta galeria");
        return $products;
}
function ApprovedModuleGaleryProduct ($parameters = array()){
    
    global $db, $db_table_prefix, $db_name;
    $content_id = $parameters['content_id'];
    //primero datos del modulo
    $sql= "update [".$db_name."].[dbo].[mod_k] set titulo_es=titulo_es_edit, titulo_en=titulo_en_edit where content_id=". $db->sql_escape($content_id);
    $db->sql_query($sql);
    //Ahora los hijos
    $sql = "update [".$db_name."].[dbo].[galery_product] set estado=estado_edit from mod_k k where k.content_id=". $db->sql_escape($content_id)." and galery_product.mod_k_id=k.id";
    $db->sql_query($sql);
    //Ahora borro los que quedaron eliminados de ambos lados
    $sql = "delete from [".$db_name."].[dbo].[galery_product] where estado_edit=0 and estado=0";
    $db->sql_query($sql);
}
function DisapprovedModuleGaleryProduct ($parameters = array()){
    global $db, $db_table_prefix, $db_name;
    $content_id = $parameters['content_id'];
    //primero datos del modulo
    $sql= "update [".$db_name."].[dbo].[mod_k] set titulo_es_edit=titulo_es, titulo_en_edit=titulo_en where content_id=". $db->sql_escape($content_id);
    $db->sql_query($sql);
    //Ahora los hijos
    $sql = "update [".$db_name."].[dbo].[galery_product] set estado_edit=estado from mod_k k where k.content_id=". $db->sql_escape($content_id)." and galery_product.mod_k_id=k.id";
    $db->sql_query($sql);
    //Ahora borro los que quedaron eliminados
    $sql = "delete from [".$db_name."].[dbo].[galery_product] where estado_edit=0 and estado=0";
    $db->sql_query($sql);
}
function EditedContent($content_id,$msg){
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}

?>