<?php

//add header de gallery

function creategeneralgalleryDataBase($parameters = array()){
    
 global $db, $db_table_prefix,$db_name;
    $data = array();


    
    $title_es= trim($db->sql_escape($parameters['title_es']) );
    $title_en= trim ($db->sql_escape($parameters['title_en']) );
    $description_en=trim( $db->sql_escape($parameters['_description_en']) );
    $description_es=trim ( $db->sql_escape($parameters['_description_es']) );
    $rbt_segmento=$db->sql_escape($parameters['muestra_segmento']);
    $rbt_descripcion=$db->sql_escape($parameters['muestra_descripcion']);

    //no borrar content id
    $content_id=$db->sql_escape($parameters['content_id']); 

    $sql = "INSERT  INTO [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] ";
    $sql .= "( title_es_edit,title_en_edit,description_en_edit,description_es_edit, show_description_edit,segmented_edit,content_id,created_at )";
    $sql .= "OUTPUT Inserted.ID as id";
    $sql .=" VALUES( '{$title_es}','{$title_en}','{$description_en}','{$description_es}','{$rbt_descripcion}','{$rbt_segmento}','{$content_id}',getdate() )";         
    $result = $db->sql_query($sql);
    $row= $db->sql_fetchrow($result);
    $change_description="Se creo la galeria <b>(".$title_es.")<b>";
    
    
    
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos ingresados exitósamente";
        $data['general_gallery_id']=$row['id'];
        notificacion_gallery($content_id,$change_description);
        
    } else {
        $data['error'] = "100";
         $data['msj'] = "No se pudieron ingresar los datos";
   }

    return $data;

}
//end add  header gallery

//*********************************************************************//

//update gallery 


function updategeneralgalleryDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 


    $data1 = array();
    $parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
    
    $id=$db->sql_escape($_GET["general_gallery_id"]);
    $title_es= trim($db->sql_escape($parameters['title_es']) );
    $title_en= trim ($db->sql_escape($parameters['title_en']) );
    $description_en= trim ($db->sql_escape($parameters['_description_en']) );
    $description_es=trim (  $db->sql_escape($parameters['_description_es']) );
    $rbt_segmento=$db->sql_escape($parameters['muestra_segmento']);
    $rbt_descripcion=$db->sql_escape($parameters['muestra_descripcion']);
    $content_id=$db->sql_escape($parameters['content_id']);  


    $sql  = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] SET title_es_edit='{$title_es}',title_en_edit='{$title_en}',description_en_edit='{$description_en}', description_es_edit='{$description_es}',";
    $sql .=" show_description_edit='{$rbt_descripcion}',segmented_edit='{$rbt_segmento}',edit=1  WHERE content_id = ".$content_id." AND id=".$id;  

    $result = $db->sql_query($sql);
    //$result=true;
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";

    $sql ="SELECT ISNULL(title_es,title_es_edit ) as nombre FROM [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] WHERE id=".$id;
        $result_name = $db->sql_query($sql);
        $row_name = $db->sql_fetchrow($result_name);
         

        $change_description="Se realizo un cambio en la galeria <b>(".$row_name["nombre"].")</b >";
        notificacion_gallery($content_id,$change_description);
    } else {
        $data['error'] = "100";
        //$data['msj'] = "No se pudieron actualizar los datos";
        $data['msj']=$sql;
    }

    return $data;
}




//end update gallery


//*********************************************************************//


//get values of gallery 

function getGeneralGalleryDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $data = array();
    $sql = "SELECT id,title_en_edit as title_en, title_es_edit as title_es, description_es_edit as description_es,description_en_edit as description_en, show_description_edit as show_description,segmented_edit as segmented ,content_id  FROM [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] WHERE content_id = ".$db->sql_escape($parameters["content_id"]);

    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;

    return $data;
}


//end get values of gallery 


//**********************************************************************//

//get alls item 
function getcontentItemsDataBase($parameters){

    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'title_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($_GET["general_gallery_id"]);
    
     $data = array();
     $sql = "";
     $sql_size = "";

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,title_es_edit as title_es , title_en_edit as title_en , ";
               $sql .= " description_es_edit as description_es ,description_en_edit as description_en ,thumbnail_edit as thumbnail ,link_edit as link";
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE general_gallery_id=".$id." and delet=0)  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
               $sql = "SELECT id,title_es_edit as title_es , title_en_edit as title_en ,  description_es_edit as description_es ,description_en_edit as description_en ,thumbnail_edit as thumbnail ,link_edit as link FROM [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE general_gallery_id=".$id." AND delet=0";
             }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE general_gallery_id=".$id." and delet=0";
    

    //consulta los items
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrowset($result);

    //agregar los tags a cada item
     foreach ($result as &$tupla) {
        $tupla['tags'] = getGaleryItemTags($tupla['id']);
    }


    //retorna el count de items
    $result_count = $db->sql_query($sql_size);
    $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];


    return $data;



}
//end get alls item 

//***********************************************************//

// add item to gallery
function createItemsGalleryDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
    
    $data1 = array();
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
     $tags=trim($db->sql_escape($parameters['tags']) );
     $title_es= trim ($db->sql_escape($parameters['title_es']) );
     $title_en= trim($db->sql_escape($parameters['title_en']) );
     $description_es=trim ( $db->sql_escape($parameters['description_es']) );
     $description_en=trim($db->sql_escape($parameters['description_en']) );
     $thumbnail=trim( $db->sql_escape($parameters['thumbnail']) );
     $link=trim( $db->sql_escape($parameters['link']) );
     $id=$db->sql_escape($_GET["general_gallery_id"]);
     
    if (strlen (trim( $link))>0 ){
        $sql = " INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item]";
        $sql .="( title_es_edit, title_en_edit, description_es_edit, description_en_edit, thumbnail_edit,link_edit,general_gallery_id,created_at)";
        $sql .= "OUTPUT Inserted.ID as id";
        $sql .=" VALUES( '{$title_es}','{$title_en}','{$description_es}','{$description_en}','{$thumbnail}','{$link}',".$id." ,getdate())";         
            }else{
        $sql = " INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item]";
        $sql .="( title_es_edit, title_en_edit, description_es_edit, description_en_edit, thumbnail_edit,general_gallery_id,created_at)";
        $sql .= "OUTPUT Inserted.ID as id";
        $sql .=" VALUES( '{$title_es}','{$title_en}','{$description_es}','{$description_en}','{$thumbnail}',".$id." ,getdate())";         


            }

$result = $db->sql_query($sql);
$row= $db->sql_fetchrow($result);
   
  
    if ($tags != '') {
        $tags_explode = explode(",", $tags);

        foreach ($tags_explode as $tag) {
            $id_tag = insertTagItemGallery($tag);
            $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tag_general_gallery_item] (id_tag,general_gallery_id) VALUES({$id_tag},".$row['id'].")";
            $db->sql_query($sql);
        }
    }

     $sql_gallery ="SELECT ISNULL(title_es,title_es_edit) AS title_galeria, content_id FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] where id=".$id;
     $result_gallery = $db->sql_query($sql_gallery);
     $row_gallery= $db->sql_fetchrow($result_gallery);

     if (strlen (trim( $link))==0 ){
        $link='Sin link';

     }


    $change_description="Se creo el item <b>".$title_es."</b> en la galeria <b>".$row_gallery["title_galeria"]."</b>,imagen  <b>(".$thumbnail.")</b> y link  <b>(".$link.")</b> ";
    notificacion_gallery($row_gallery["content_id"],$change_description);



    $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE  id = (SELECT max(id) FROM [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] where general_gallery_id=".$id.") ";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;
    return $data;
}
//end add item  gallery

//**************************************************************//

// delete item to gallery 

function deleteItemDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
  
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
     $id=$db->sql_escape($parameters['id']);


     //se van a eliminar los tags cuando se apruebe la eliminacion del item 
    //$sql = "DELETE  FROM [".$db_name."].[dbo].[".$db_table_prefix."tag_general_gallery_item] WHERE general_gallery_id=".$id;
    //$db->sql_query($sql);
   
        //elimina poosteriormente los items 
     $sql = " UPDATE    [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] SET delet=1 WHERE id='".$id."'";
     $result = $db->sql_query($sql);





    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos eliminados exitósamente";

    $sql_gallery  ="SELECT gg.content_id,ISNULL(gg.title_es,gg.title_es_edit) as title_gallery, ISNULL(gi.title_es,gi.title_es_edit) as title_item,ISNULL(gi.thumbnail,gi.thumbnail_edit) as thumbnail,ISNULL(gi.link, ISNULL(gi.link_edit,'Sin link')) as link from [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] gg inner join  ";
    $sql_gallery .=" [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] gi on ";
    $sql_gallery .=" gg.id=gi.general_gallery_id where gi.id=".$id;

         $result_gallery = $db->sql_query($sql_gallery);
         $row_gallery= $db->sql_fetchrow($result_gallery);

            $change_description="Se dio de baja el item <b>".$row_gallery["title_item"]."</b> en la galeria <b>".$row_gallery["title_gallery"]."</b>,imagen  <b>(".$row_gallery["thumbnail"].")</b> y link  <b>(".$row_gallery["link"].")</b> ";
            notificacion_gallery($row_gallery["content_id"],$change_description);




    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron eliminar los datos";
            }

   
    return $data;

}
//end delete item to gallery 

//**************************************************************//

//update item
function updateItemDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $title_es=trim($db->sql_escape($parameters['title_es']) );
     $title_en=trim ($db->sql_escape($parameters['title_en']) );
     $description_es=trim ($db->sql_escape($parameters['description_es']) );
     $description_en=trim ($db->sql_escape($parameters['description_en']) );
     $thumbnail=trim ($db->sql_escape($parameters['thumbnail']) );
     $link= trim($db->sql_escape($parameters['link']) );
     $tags =trim( $db->sql_escape($parameters['tags']) );

     $update_extra="";

        if (strlen (trim( $link))>0 ){
            $update_extra=" link_edit='{$link}' ";
        }else{
            $update_extra=" ";
        }


    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] set ";
    $sql .=" title_es_edit ='{$title_es}',title_en_edit='{$title_en}',description_es_edit='{$description_es}', ";
    $sql .=" description_en_edit='{$description_en}',thumbnail_edit='{$thumbnail}' , edit=1".$update_extra;
    $sql .=" where id='{$id}'";
 
    $result = $db->sql_query($sql);
    //parte de los tags 

    $tags_explode = explode(",", $tags);
    $sql = "DELETE FROM tag_general_gallery_item WHERE general_gallery_id =" . $id;
    

    $db->sql_query($sql);
     foreach ($tags_explode as $tag) {
        
        $id_tag = insertTagItemGallery($tag);
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tag_general_gallery_item] (id_tag,general_gallery_id) VALUES({$id_tag},{$id})";
        $db->sql_query($sql);
    }

    //fin parte de los tags

    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";


$sql_gallery =" SELECT (CASE WHEN title_es LIKE  title_es_edit THEN 1 ELSE 0 END ) AS iguales_title_es, ISNULL(title_es,'0') as title_es, title_es_edit  ,";
$sql_gallery .=" (CASE WHEN title_en LIKE  title_en_edit THEN 1 ELSE 0 END ) AS iguales_title_en, ISNULL(title_en,'0') as title_en, title_en_edit  ,";
$sql_gallery .=" (CASE WHEN description_es LIKE  description_es_edit THEN 1 ELSE 0 END ) AS iguales_description_es,ISNULL( description_es,'0') as description_es, description_es_edit  ,";
$sql_gallery .=" (CASE WHEN description_en LIKE  description_en_edit THEN 1 ELSE 0 END ) AS iguales_descroption_en, ISNULL(description_en,'0') as description_en, description_en_edit  ,";
$sql_gallery .=" (CASE WHEN thumbnail LIKE  thumbnail_edit THEN 1 ELSE 0 END ) AS iguales_thubnail,ISNULL( thumbnail,'0') as thumbnail, thumbnail_edit  ,";
$sql_gallery .=" (CASE WHEN link LIKE  link_edit THEN 1 ELSE 0 END ) AS iguales_link, ISNULL(link,'0') as link, isnull(link_edit,'0'  ) as link_edit,";
$sql_gallery .=" (select content_id from mod_general_gallery where id=general_gallery_item.general_gallery_id) as content_id,";
$sql_gallery .="  (select ISNULL(mod_general_gallery.title_es,mod_general_gallery.title_es_edit) from mod_general_gallery where id=general_gallery_item.general_gallery_id) as galeria";
$sql_gallery .=" FROM general_gallery_item WHERE id=".$id;


         $result_gallery = $db->sql_query($sql_gallery);
         $row_gallery= $db->sql_fetchrow($result_gallery);



$change_description=" Se actualizo un item en la galeria <b>".$row_gallery["galeria"]. "</b>";
if ($row_gallery["iguales_title_es"]=='0'){

    if ( $row_gallery["title_es"]!='0' ){
        $change_description.=" titulo en español <b>( de ".$row_gallery["title_es"]."</b> a <b>".$row_gallery["title_es_edit"]." )</b> ";

    }else{
        $change_description.=" titulo en español <b>(".$row_gallery["title_es_edit"]." )</b> ";        
    }

}

if ($row_gallery["iguales_title_en"]=='0'){

        if ( $row_gallery["title_en"]!='0' ){
        $change_description.=" titulo en ingles <b>( de ".$row_gallery["title_en"]."</b> a <b>".$row_gallery["title_en_edit"]." )</b> ";

    }else{
        $change_description.=" titulo en ingles <b> (".$row_gallery["title_en_edit"]." )</b> ";        
    }

}

if ($row_gallery["iguales_description_es"]=='0'){

        if ( $row_gallery["description_es"]!='0' ){
       
        $change_description.=" descripción en español <b>( de ".$row_gallery["description_es"]."</b> a <b>".$row_gallery["description_es_edit"]." )</b> ";
    }else{
        $change_description.=" descripción en español  <b>(".$row_gallery["description_es_edit"]." )</b> ";
    }


}

if ($row_gallery["iguales_descroption_en"]=='0'){

    
        if ( $row_gallery["description_en"]!='0' ){

       $change_description.=" descripción en ingles <b>( de ".$row_gallery["description_en"]."</b> a <b>".$row_gallery["description_en_edit"]." )</b> ";
     }else{

        $change_description.=" descripción en ingles  <b> (".$row_gallery["description_en_edit"]." )</b> ";
     }

}

if ($row_gallery["iguales_thubnail"]=='0'){

        if ( $row_gallery["thumbnail"]!='0' ){

       $change_description.=" thumbnail <b>( de ".$row_gallery["thumbnail"]."</b> a <b>".$row_gallery["thumbnail_edit"]." )</b> ";
     }else{

        $change_description.=" thumbnail  <b> (".$row_gallery["thumbnail_edit"]." )</b> ";
     }

}

if ($row_gallery["iguales_link"]=='0'){

        if ( ($row_gallery["link"]!='0') &&  ($row_gallery["link_edit"]!='0') ){

      $change_description.=" link <b>( de ".$row_gallery["link"]."</b> a <b>".$row_gallery["link_edit"]." )</b> ";
     }else{
        if ($row_gallery["link_edit"]!='0'){
      $change_description.=" link <b> (".$row_gallery["link_edit"]." )</b> ";
            }
     }

}

    notificacion_gallery($row_gallery["content_id"] ,$change_description);
     
    } else {
        $data['error'] = "100";
        $data['msj'] =$sql;
     
    }

    return $data;


}
//end update item 


//add tags to items

function getAllTagsDataBase($parameters) {
     global $db, $db_table_prefix,$db_name;
    //$module_id=$parameters['module_id'];

    $sql = "SELECT tag FROM [".$db_name."].[dbo].[".$db_table_prefix."tag]";
    $result = $db->sql_query($sql);
  
     $result = $db->sql_fetchrowset($result);

     
    return $result;
}
//end tags to items


//get type tag 

function insertTagItemGallery($tag) {
     global $db, $db_table_prefix,$db_name;
    $sql = "SELECT id FROM [".$db_name."].[dbo].[".$db_table_prefix."tag] WHERE tag='" . $tag . "'";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    $id = intval($result['id']);



    return $id;
}
//ent get type tag 


//get tag unique item gallery 

function getGaleryItemTags($id_item_general_gallery) {
    global $db, $db_table_prefix,$db_name;
    $tags = "";
    $sql = "SELECT tag FROM [".$db_name."].[dbo].[".$db_table_prefix."tag] INNER JOIN
    [".$db_name."].[dbo].[".$db_table_prefix."tag_general_gallery_item] ON
    tag.id=tag_general_gallery_item.id_tag
    AND tag_general_gallery_item.general_gallery_id =" . $id_item_general_gallery;
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





function ApprovedModuleGallery($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];
    $sql ="SELECT id, delet, ISNULL(title_es,'0') AS gallery_name, edit from [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] where content_id=".$db->sql_escape($id) ;
       $result_gallery = $db->sql_query($sql);
       $result_gallery= $db->sql_fetchrow($result_gallery);

       $sql="";
 
       
     
        if ( $result_gallery["delet"]=='0' ){


                    if (  $result_gallery["gallery_name"]=='0' ){ 
                            $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] SET title_es  = title_es_edit, title_en = title_en_edit ,description_es=description_es_edit, description_en=description_en_edit,show_description=show_description_edit,segmented=segmented_edit,edit=0,delet=0  WHERE id=".$result_gallery["id"]." ".chr(13) ;
                        }


                    if (  $result_gallery["edit"]=='1' )  {
                             $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] SET title_es  = title_es_edit, title_en = title_en_edit ,description_es=description_es_edit, description_en=description_en_edit,show_description=show_description_edit,segmented=segmented_edit,edit=0,delet=0  WHERE id=".$result_gallery["id"]." ".chr(13) ;
                        }

        }else{

            if ($result_gallery["delet"]=='1' ){
            $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] WHERE  id=".$result_gallery["id"]." ".chr(13) ;
            }
        }






//va a consultar items de la gallery 
     $sql_item="SELECT id, edit ,delet,ISNULL(title_es,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE general_gallery_id=".$result_gallery["id"];   


         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);


    //agregar los tags a cada item
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //aprobo que se agregara
                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] SET title_es = title_es_edit, title_en = title_en_edit,description_es = description_es_edit, description_en=description_en_edit, thumbnail = thumbnail_edit,link=link_edit,edit=0,delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  
                    if ($tupla["delet"]=='1'){

                        $sql .= " DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item]   WHERE id=".$tupla["id"]." ".chr(13) ;
                    }

                    if ($tupla["edit"]=='1'){

                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] SET title_es = title_es_edit, title_en = title_en_edit,description_es = description_es_edit, description_en=description_en_edit, thumbnail = thumbnail_edit,link=link_edit,edit=0,delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
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

function DisapprovedModuleGallery($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];


//va a consultar datos de la gallery
    $sql ="SELECT id,delet, ISNULL(title_es,'0') AS gallery_name,edit from [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] WHERE content_id=".$db->sql_escape($id) ;
       $result_gallery = $db->sql_query($sql);
       $result_gallery= $db->sql_fetchrow($result_gallery);


       $sql="";
 
       if ( $result_gallery["gallery_name"]=='0' ){ 
            //desaprobo que se agregara
                $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] WHERE id=".$result_gallery["id"]." ".chr(13) ;
            }else{

                if ($result_gallery["delet"]=='1'){
                $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] SET delet=0  WHERE id=".$result_gallery["id"]." ".chr(13) ;

                }else{
                    if ($result_gallery["edit"]=='1'){

                $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_general_gallery] SET title_es_edit = title_es, title_en_edit = title_en,description_es_edit=description_es,description_en_edit=description_en,show_description_edit=show_description,segmented_edit=segmented,edit=0,delet=0  WHERE id=".$result_gallery["id"]." ".chr(13) ;
                    }
                }
        }

         

//va a consultar items de la gallery 
     $sql_item="SELECT id, edit ,delet,ISNULL(title_es,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE general_gallery_id=".$result_gallery["id"];   


         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);

    //agregar los tags a cada item
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //desaprobo que se agregara
                        $sql .= " DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  
                    if ($tupla["delet"]=='1'){

                        $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] SET delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }

                    if ($tupla["edit"]=='1'){

                        $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."general_gallery_item] SET title_es_edit = title_es, title_en_edit = title_en,description_es_edit = description_es, description_en_edit=description_en, thumbnail_edit = thumbnail,link_edit=link,edit=0,delet=0  WHERE id=".$tupla["id"]." ".chr(13) ;
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


function notificacion_gallery($id,$change_description ){
  global $db, $db_table_prefix,$loggedInUser;

   $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $id . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$change_description."'";


  $result_procedure = $db->sql_query($sql); 

}



?>