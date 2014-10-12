<?php

//CREA NUEVO MODULO BUSCADOR
function createSearcherDataBase($parameters) {
    global $db, $db_table_prefix,$db_name;
    $data = array();
    //echo'llego hasta la funcion con el insert';
    //var_dump($parameters);
    $title_es=$db->sql_escape($parameters['title_es']);
    $title_en=$db->sql_escape($parameters['title_en']);
    $description=trim($db->sql_escape($parameters['_description']) );
    $content_id=$db->sql_escape($parameters['content_id']);  
    $sql = "
    INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."searcher]
    	(
			title_es_edit,title_en_edit,description_edit,content_id, created_at
		)
    OUTPUT Inserted.ID
	VALUES(
		'{$title_es}','{$title_en}','{$description}','{$content_id}',getdate()
		)";			
    $result = $db->sql_query($sql);
    $row= $db->sql_fetchrow($result);
    $data['module_id']=$row['ID'];
    //var_dump($result);
	if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos ingresados exitósamente";
        $change_description="Se creo el buscador <b>".$title_es."</b>";
        notificacion_searcher($content_id,$change_description);
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron ingresar los datos";
    }
 
    return $data;
}

//OBTIENE LOS DATOS DEL BUSCADOR
function getContentSearcherDataBase($parameters){
	global $db,$db_table_prefix, $db_name; 
	$data = array();
	$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";

	$sql = "SELECT [id], [title_es_edit] as title_es, [title_en_edit] as title_en, [description_edit] as description, [content_id] 
				FROM [".$db_name."].[dbo].[".$db_table_prefix."searcher]
				WHERE content_id = ".$db->sql_escape($parameters["content_id"]);

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$data = $row;
	error_log("CALLING WEB SERVICE");
	return $data;
}

//ACTUALIZA DATOS DE BUSCADOR
function updateSearcherDataBase($parameters){
	global $db,$db_table_prefix, $db_name; 


	$data1 = array();
	$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
	$sql1 = "SELECT [id] FROM [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE content_id = ".$db->sql_escape($parameters["content_id"]);
	$result1 = $db->sql_query($sql1);
	$row1 = $db->sql_fetchrow($result1);
	$id=$db->sql_escape($row1['id']);
	$title_es=$db->sql_escape($parameters['title_es']);
    $title_en=$db->sql_escape($parameters['title_en']);
    $description= trim( $db->sql_escape($parameters['_description']) );
    $content_id=$db->sql_escape($parameters['content_id']);  
    $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."searcher] SET title_es_edit='{$title_es}',title_en_edit='{$title_en}',description_edit='{$description}',edit=1 WHERE content_id = ".$content_id." AND id=".$id;	
    $result = $db->sql_query($sql);
    //$result=true;

	if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";

        //consulta para saber el nombre del buscador 
        $sql_buscador="SELECT ISNULL(title_es,title_es_edit) AS buscador_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE id=".$id;   
        $result_buscador = $db->sql_query($sql_buscador);
        $result_buscador = $db->sql_fetchrow($result_buscador);

        $change_description="Se actualizo el buscador <b>".$result_buscador["buscador_name"]."</b>";
        notificacion_searcher($content_id,$change_description);
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron actualizar los datos";
    }


	return $data;
}

//INSERTA PARAMETROS DEL BUSCADOR
function createParametersDataBase($parameters){
	global $db, $db_table_prefix,$db_name;
    $data = array();
    //echo'llego hasta la funcion con el insert';
    //var_dump($parameters);
    $data1 = array();
    $parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
    $module_id=$db->sql_escape($_GET["module_id"]);
    $parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
    $title_es=$db->sql_escape($_POST["title_es"]);
    $title_en=$db->sql_escape($_POST["title_en"]);
    $description= trim($db->sql_escape($_POST["description"]) );

    $sql = "
    INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."search_parameter]
        (
            search_id, parameters_type_id_edit, title_es_edit, title_en_edit, description_edit
        )
    OUTPUT Inserted.ID
    VALUES(
        '".$module_id."','".$parameters_type_id."','".$title_es."','".$title_en."','".$description."'
        )";         
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);
    


    //para consulta galeria y tipo de parametro 
    $sql =" select  s.content_id,isnull(s.title_es,s.title_es_edit) as name_buscador , spt.name as name_type ";
    $sql .=" from  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] sp";
    $sql .=" inner join  [".$db_name."].[dbo].[".$db_table_prefix."searcher] s ";
    $sql .=" on s.id=sp.search_id";
    $sql .=" inner join  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_type] spt ";
    $sql .="   ON spt.id = isnull(sp.parameters_type_id,sp.parameters_type_id_edit) ";
    $sql .=" where sp.id=".$result["ID"];

    
    

    $result_parameter = $db->sql_query($sql);
    $result_parameter= $db->sql_fetchrow($result_parameter);


        $change_description="Se creo el parametro <b>".$title_es."</b> al buscador <b>".$result_parameter["name_buscador"]."</b> de tipo <b>".$result_parameter["name_type"]."</b>";
        notificacion_searcher($result_parameter["content_id"],$change_description);


    //var_dump($result);

    //Get last inserted record (to return to jTable)
    $sql_get = "SELECT id,title_es_edit as title_es, title_en_edit as title_en , description_edit as description,parameters_type_id_edit as parameters_type_id 
                FROM [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] 
                WHERE id = '".$result['ID']."'";
                
    //var_dump($sql_get);
    $result_get = $db->sql_query($sql_get);
    $row = $db->sql_fetchrow($result_get);
    $data = $row;
	return $data;
}

//OBTIENE PARAMETROS DEL BUSCADOR
function getContentParametersDataBase($parameters){
		global $db,$db_table_prefix, $db_name; 
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'title_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";

$sql = "";
    $sql_size = "";
    if($parameters['jtpagesize'] != ''){
        $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
        $sql = "SELECT * FROM
                    (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,parameters_type_id_edit as parameters_type_id, title_es_edit as title_es,title_en_edit as title_en,description_edit as description 
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] 
                    WHERE search_id=".$_GET["module_id"]." AND delet=0)
                    AS user_with_numbers
                WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
    }else{
        $sql = "SELECT id,parameters_type_id_edit as parameters_type_id, title_es_edit as title_es,title_en_edit as title_en,description_edit as description 
        FROM 
            [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] 
            WHERE search_id=".$_GET["module_id"]." and delet=0";
    }


    
    $sql_count = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] WHERE search_id=".$_GET["module_id"]." and delet=0";
    
    $result = $db->sql_query($sql);
    $result_count = $db->sql_query($sql_count);
    $result= $db->sql_fetchrowset($result);
    $result_count= $db->sql_fetchrow($result_count);
    $data["result"] = $result;
    $data["count"] = $result_count['RecordCount'];
    
    return $data;
}

//NOS DEVUELVE TIPOS DE PARAMETROS PARA LLENAR SELECTED
function getParametersTypeDataBase($parameters){
	global $db,$db_table_prefix, $db_name; 
	$data = array();
    $sql = "SELECT [name] as DisplayText, [id] as Value FROM [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_type]";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrowset($result);
	$data = $row;
	error_log("CALLING WEB SERVICE");
	return $data;
}

//ACTUALIZA PARAMETROS DEL BUSCADOR
function updateParametersDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
    $title_es=$db->sql_escape($_POST["title_es"]);
    $title_en=$db->sql_escape($_POST["title_en"]);
    $description= trim($db->sql_escape($_POST["description"]) ); 
    //var_dump($parameters);
    $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET parameters_type_id_edit='".$parameters_type_id."',title_es_edit='".$title_es."',title_en_edit='".$title_en."',description_edit='".$description."',edit=1 WHERE search_id ='".$_GET["module_id"]."'  AND id='".$_POST["id"]."'";  
    //var_dump($sql);
    $result = $db->sql_query($sql);
    //$result=true;
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";


        $sql  =" SELECT (CASE WHEN title_es LIKE  title_es_edit THEN 1 ELSE 0 END ) AS iguales_title_es, ISNULL(title_es,'0') as title_es, title_es_edit  ,";
        $sql .=" (CASE WHEN title_en LIKE  title_en_edit THEN 1 ELSE 0 END ) AS iguales_title_en, ISNULL(title_en,'0') as title_en, title_en_edit  ,";
        $sql .=" (CASE WHEN description LIKE  description_edit THEN 1 ELSE 0 END ) AS iguales_description,ISNULL( description,'0') as description, description_edit  ,";
        $sql .=" (CASE WHEN parameters_type_id LIKE  parameters_type_id_edit THEN 1 ELSE 0 END ) AS iguales_type_parameter,";
        $sql .=" ISNULL((select name from search_parameter_type where id= ISNULL(parameters_type_id,'0')),'0') as parameters_type_id,";
        $sql .=" ISNULL((select name from search_parameter_type where id= parameters_type_id_edit),'SIN TIPO' ) AS parameters_type_id_edit,";
        $sql .=" (select searcher.content_id from [".$db_name."].[dbo].[".$db_table_prefix."searcher] where id=search_parameter.search_id) as content_id,";
        $sql .=" (select ISNULL(searcher.title_es,searcher.title_es_edit) from [".$db_name."].[dbo].[".$db_table_prefix."searcher] where id=search_parameter.search_id) as buscador";
        $sql .=" FROM [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] WHERE id='".$_POST["id"]."'";  
        



         $result_parameter = $db->sql_query($sql);
         $result_parameter= $db->sql_fetchrow($result_parameter);

         



                $change_description=" Se actualizo un parametro del buscador <b>".$result_parameter["buscador"]. "</b>";

                if ($result_parameter["iguales_title_es"]=='0'){

                    if ( $result_parameter["title_es"]!='0' ){
                        $change_description.=" titulo en español <b>( de ".$result_parameter["title_es"]."</b> a <b>".$result_parameter["title_es_edit"]." )</b> ";

                    }else{
                        $change_description.=" titulo en español <b>(".$result_parameter["title_es_edit"]." )</b> ";        
                    }

                }

                if ($result_parameter["iguales_title_en"]=='0'){

                        if ( $result_parameter["title_en"]!='0' ){
                        $change_description.=" titulo en ingles <b>( de ".$result_parameter["title_en"]."</b> a <b>".$result_parameter["title_en_edit"]." )</b> ";

                    }else{
                        $change_description.=" titulo en ingles <b> (".$result_parameter["title_en_edit"]." )</b> ";        
                    }

                }

                if ($result_parameter["iguales_description"]=='0'){

                        if ( $result_parameter["description"]!='0' ){
                        $change_description.=" la descripcion <b>( de ".$result_parameter["description"]."</b> a <b>".$result_parameter["description_edit"]." )</b> ";

                    }else{
                        $change_description.=" la descripcion <b> (".$result_parameter["description_edit"]." )</b> ";        
                    }

                }

                if ($result_parameter["iguales_type_parameter"]=='0'){

                        if ( $result_parameter["parameters_type_id"]!='0' ){
                        $change_description.=" el tipo <b>( de ".$result_parameter["parameters_type_id"]."</b> a <b>".$result_parameter["parameters_type_id_edit"]." )</b> ";

                    }else{
                        $change_description.=" el tipo <b> (".$result_parameter["parameters_type_id_edit"]." )</b> ";        
                    }

                }       

                    

                notificacion_searcher($result_parameter["content_id"],$change_description);



    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron actualizar los datos";
    }

    return $data;
}

//ELIMINA PARAMETROS DEL BUSCADOR
function deleteParametersDataBase($parameters){
    global $db,$db_table_prefix, $db_name;
    $id=$db->sql_escape($_POST["id"]);
    $sql = "UPDATE  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET delet=1 WHERE id='".$id."'";  
    //var_dump($sql);
    $result = $db->sql_query($sql);
    //$result=true;
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos eliminados exitósamente";


         $sql  =" select s.content_id,isnull(s.title_es,s.title_es_edit) as name_buscador, spt.name as name_type,isnull(sp.title_es,sp.title_es_edit) as name_parameter";
         $sql .=" from [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] sp ";
         $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."searcher] s on s.id=sp.search_id";
         $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_type] spt on spt.id= isnull(sp.parameters_type_id,sp.parameters_type_id_edit) where sp.id=".$id;


         $result = $db->sql_query($sql);
         $row= $db->sql_fetchrow($result);

            $change_description="Se dio de baja el parametro <b>".$row["name_parameter"]."</b> de tipo <b>".$row["name_type"]."</b> en el buscador <b>".$row["name_buscador"]."</b>";

            notificacion_searcher($row["content_id"],$change_description);


    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron eliminar los datos";
    }
    return $data;
}




function ApprovedModuleSearch($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];
    $sql ="SELECT id, delet, ISNULL(title_es,'0') AS sear_name, edit from [".$db_name."].[dbo].[".$db_table_prefix."searcher] where content_id=".$db->sql_escape($id) ;
       $result_search = $db->sql_query($sql);
       $result_search= $db->sql_fetchrow($result_search);

       $sql="";
 
       
     
        if ( $result_search["delet"]=='0' ){

                    if (  $result_search["sear_name"]=='0' ){ 
                            $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."searcher] SET title_es  = title_es_edit, title_en = title_en_edit ,description=description_edit , edit=0,delet=0  WHERE id=".$result_search["id"]." ".chr(13) ;
                        }


                    if (  $result_search["edit"]=='1' )  {
                            $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."searcher] SET title_es  = title_es_edit, title_en = title_en_edit ,description=description_edit , edit=0,delet=0  WHERE id=".$result_search["id"]." ".chr(13) ;
                        }

        }else{

            if ($result_search["delet"]=='1' ){
            $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE id=".$result_search["id"]." ".chr(13) ;
            }
        }






//va a consultar parametros para el buscador
     $sql_item="SELECT id, edit ,delet,ISNULL(title_es,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] WHERE search_id=".$result_search["id"];   


         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);

    
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //aprobo que se agregara
                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET title_es = title_es_edit, title_en = title_en_edit,description = description_edit,edit=0,delet=0,parameters_type_id=parameters_type_id_edit  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  
                    if ($tupla["delet"]=='1'){

                        $sql .= " DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."search_parameter]   WHERE id=".$tupla["id"]." ".chr(13) ;
                    }

                    if ($tupla["edit"]=='1'){

                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET title_es = title_es_edit, title_en = title_en_edit,description = description_edit,parameters_type_id=parameters_type_id_edit,edit=0,delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
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

function DisapprovedModuleSearch($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];


//va a consultar datos de la gallery
    $sql ="SELECT id,delet, ISNULL(title_es,'0') AS sear_name,edit from [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE content_id=".$db->sql_escape($id) ;
       $result_search = $db->sql_query($sql);
       $result_search= $db->sql_fetchrow($result_search);


       $sql="";
 
       if ( $result_search["sear_name"]=='0' ){ 
            //desaprobo que se agregara
                $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE id=".$result_search["id"]." ".chr(13) ;
            }else{

                if ($result_search["delet"]=='1'){
                $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."searcher] SET delet=0  WHERE id=".$result_search["id"]." ".chr(13) ;

                }else{
                    if ($result_search["edit"]=='1'){

                $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."searcher] SET title_es_edit = title_es, title_en_edit = title_en,description_edit=description,edit=0,delet=0  WHERE id=".$result_search["id"]." ".chr(13) ;
                    }
                }
        }

         

//va a consultar items de la gallery 
     $sql_item="SELECT id, edit ,delet,ISNULL(title_es,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] WHERE search_id=".$result_search["id"];   


         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);

    //agregar los tags a cada item
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //desaprobo que se agregara
                        $sql .= " DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  
                    if ($tupla["delet"]=='1'){

                        $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }

                    if ($tupla["edit"]=='1'){

                        $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] SET title_es_edit = title_es, title_en_edit = title_en,description_edit = description, parameters_type_id_edit = parameters_type_id ,edit=0  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
                        
    }



   
//var_dump($sql);


    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;


}


function notificacion_searcher($id,$change_description ){
  global $db, $db_table_prefix,$loggedInUser;

   $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $id . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$change_description."'";


  $result_procedure = $db->sql_query($sql); 

}

?>