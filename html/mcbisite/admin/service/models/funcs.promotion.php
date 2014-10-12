<?php

//INSERTA PROMOCIONES DEL BUSCADOR
function createPromotionsDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();

    $name=$db->sql_escape($_POST["name"]);
    $starting_date=$db->sql_escape($_POST["starting_date"]);
    $end_date=$db->sql_escape($_POST["end_date"]);
    $priority=$db->sql_escape($_POST["priority"]);
    $tags=$db->sql_escape($parameters['tags']);
    if((boolean)$_POST["status"]){
        $status=1;
    }else{
        $status=0;
    }
    if((boolean)$_POST["segmented"]){
        $segmented=1;
    }else{
        $segmented=0;
    }

    $sql = "
    INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."promotion]
        (
            name, starting_date, end_date, priority, status, segmented, updated_at, created_at
        )
    OUTPUT Inserted.ID
    VALUES(
        '".$name."',convert(date,'".$starting_date."',103),convert(date,'".$end_date."',103),convert(int,'".$priority."'),'".$status."','".$segmented."',getdate(),getdate()
        )";         
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);

//Insertar Tags
    if ($tags != '') {
        $tags_explode = explode(",", $tags);

        foreach ($tags_explode as $tag) {
            $id_tag = insertPromotionTag($tag);
            $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."promotion_tag] (promotion_id, tag_id) VALUES(".$result['ID'].",{$id_tag})";
            $db->sql_query($sql);
        }
    }
//Fin tags

//Datos de archivo
    if($segmented==1){
        $cadena=$parameters["file"];
        $cadena=preg_replace('/\s/', '', $cadena);
        $cifs=explode(",",$cadena);
        if(count($cifs)>0){
            foreach ($cifs as $cif) {
                $sql_id="SELECT id FROM [".$db_name."].[dbo].[".$db_table_prefix."user] where cif='".$cif."'";
                $result_id = $db->sql_query($sql_id);
                $result_id= $db->sql_fetchrow($result_id);
                if($result_id){
                    $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."promotion_user] (promocion_id, user_id) VALUES(".$result["ID"].",".$result_id["id"].")";
                    $db->sql_query($sql);
                }
            }
        }
    }
//Fin datos de Archivo

    //Get last inserted record (to return to jTable)
    $sql_get = "SELECT id,name, convert(date,starting_date,103) as starting_date, convert(date,end_date,103) as end_date, priority, case when status=1 then 'true' else 'false' end as status, case when segmented=1 then 'true' else 'false' end as segmented
        FROM 
            [".$db_name."].[dbo].[".$db_table_prefix."promotion]
                WHERE id = '".$result['ID']."'";
    $result_get = $db->sql_query($sql_get);
    $row = $db->sql_fetchrow($result_get);
    $data = $row;
    return $data;
}

//OBTIENE PROMOCIONES DEL BUSCADOR
function getPromotionsDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";

$sql = "";
    $sql_size = "";
    if($parameters['jtpagesize'] != ''){
        $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
        $sql = "SELECT * FROM
                    (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id, name, convert(date,starting_date,103) as starting_date, convert(date,end_date,103) as end_date, priority, case when status=1 then 'true' else 'false' end as status, case when segmented=1 then 'true' else 'false' end as segmented
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."promotion] 
                    )
                    AS user_with_numbers
                WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
    }else{
        $sql = "SELECT id,name, convert(date,starting_date,103) as starting_date, convert(date,end_date,103) as end_date, priority, case when status=1 then 'true' else 'false' end as status, case when segmented=1 then 'true' else 'false' end as segmented
        FROM 
            [".$db_name."].[dbo].[".$db_table_prefix."promotion]";
    }
$result = $db->sql_query($sql);
$result= $db->sql_fetchrowset($result);
    //agregar los tags a cada item
     foreach ($result as &$tupla) {
        $tupla['tags'] = getPromotionTags($tupla['id']);
    }
    //fin tags
    
    $sql_count = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion]";
    //var_dump($sql); 
    $result_count = $db->sql_query($sql_count);
    $result_count= $db->sql_fetchrow($result_count);
    $data["result"] = $result;
    $data["count"] = $result_count['RecordCount'];
    
    return $data;
}

//ACTUALIZA PROMOCIONES DEL BUSCADOR
function updatePromotionsDataBase($parameters){
    //var_dump($parameters);
    global $db,$db_table_prefix, $db_name; 
    $name=$db->sql_escape($_POST["name"]);
    $starting_date=$db->sql_escape($_POST["starting_date"]);
    $end_date=$db->sql_escape($_POST["end_date"]);
    $priority=$db->sql_escape($_POST["priority"]);
    $tags=$db->sql_escape($parameters['tags']);
    $id=$_POST["id"];
    if((boolean)$_POST["status"]){
        $status=1;
    }else{
        $status=0;
    }
    if((boolean)$_POST["segmented"]){
        $segmented=1;
    }else{
        $segmented=0;
    }
    $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."promotion] SET name='".$name."',starting_date=convert(date,'".$starting_date."',103),end_date=convert(date,'".$end_date."',103),priority=convert(int,'".$priority."') ,status='".$status."' ,segmented='".$segmented."' ,updated_at=getdate() WHERE id='".$id."'";  
    //var_dump($sql);
    $result = $db->sql_query($sql);

//Tags
    $tags_explode = explode(",", $tags);
    $sql = "DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion_tag] WHERE promotion_id =" . $id;
    $db->sql_query($sql);
    foreach ($tags_explode as $tag) {
        
        $id_tag = insertPromotionTag($tag);
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."promotion_tag] (promotion_id, tag_id) VALUES(".$id.",{$id_tag})";
            $db->sql_query($sql);
    }
//Fin Tags

    //Archivo
    if($segmented==1){
        $cadena=$parameters["file"];
        $cadena=preg_replace('/\s/', '', $cadena);
        $cifs=explode(",",$cadena);
        if(count($cifs)>0){
            $sql = "DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion_user] WHERE promocion_id =" . $id;
            $db->sql_query($sql);
            
            foreach ($cifs as $cif) {
                $sql_id="SELECT id FROM [".$db_name."].[dbo].[".$db_table_prefix."user] where cif='".$cif."'";
                $result_id = $db->sql_query($sql_id);
                $result_id= $db->sql_fetchrow($result_id);
                if($result_id){
                    $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."promotion_user] (promocion_id, user_id) VALUES(".$id.",".$result_id["id"].")";
                    $db->sql_query($sql);
                }
            }
        }
    }
    //Fin archivo
    //$result=true;
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron actualizar los datos";
    }

    return $data;
}


//ELIMINA PROMOCIONES DEL BUSCADOR
function deletePromotionsDataBase($parameters){
    global $db,$db_table_prefix, $db_name;
    $id=$db->sql_escape($_POST["id"]);

    $sql_status="SELECT status FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion] WHERE id='".$id."'";
    $result_status = $db->sql_query($sql_status);
    $result_status = $db->sql_fetchrow($result_status);
    $status = $result_status['status'];
    
    $data=array(); 
    if($status!=1){
        $sql = "DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion] WHERE id='".$id."'";  
        $result = $db->sql_query($sql);
        $sql_delete = "DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion_tag] WHERE promotion_id =" . $id;
        $db->sql_query($sql_delete);
        $sql_cif = "DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."promotion_user] WHERE promocion_id =" . $id;
        $db->sql_query($sql_cif);
    if ($result) {
        $data['Result'] = "OK";
    }
    }else{
        $data['Result'] = "ERROR";
        $data['Message'] = "No se puede eliminar la promoción Status On cambiar antes";
    }
         
    return $data;
}

function getAllTagsDataBase($parameters) {
     global $db, $db_table_prefix,$db_name;
    //$module_id=$parameters['module_id'];

    $sql = "SELECT tag FROM [".$db_name."].[dbo].[".$db_table_prefix."tag]";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);
    return $result;
}

function insertPromotionTag($tag) {
     global $db, $db_table_prefix,$db_name;
    $sql = "SELECT id FROM [".$db_name."].[dbo].[".$db_table_prefix."tag] WHERE tag='".$tag."'";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    $id = intval($result['id']);
     if(!$id>0){
        $sql="INSERT INTO tag(tag) VALUES('{$tag}')";
        $result= $db->sql_query($sql);

        $sql="SELECT id FROM TAG where id=@@IDENTITY";
        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrow($result);
        $id=intval($result['id']);
    }
    return $id;
}

function getPromotionTags($promotion_id) {
    global $db, $db_table_prefix,$db_name;
    $tags = "";
    $sql = "SELECT tag FROM [".$db_name."].[dbo].[".$db_table_prefix."tag] INNER JOIN
    [".$db_name."].[dbo].[".$db_table_prefix."promotion_tag] ON
    tag.id=promotion_tag.tag_id
    AND promotion_tag.promotion_id =" . $promotion_id;
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

    if ($result)
        $tags = "";
    foreach ($result as $tag) {
        $tags .= $tag['tag'] . ",";
    }
    $tags = rtrim($tags, ",");
    return $tags;
}

function segmentedFilePromotion($name_file,$promotion_id){
    global $db, $db_table_prefix,$db_name;
    $texto="";
    $file_name="../../includes/promotion/file/".$name_file;
    $file=fopen($file_name,"r") or die("No se pudo abrir el archivo");
    //while(!feof($file)){
     //   echo fgets($file);
        //echo fgets($file)."<br/>";
    //}
    $texto= file_get_contents($file_name,FILE_USE_INCLUDE_PATH);
    fclose($file);
return $texto;
}

?>