<?php

function getModuleList() {
    global $db,$db_name;

    $data = array();

    $sql = "SELECT [id], [name]
	  ,[description], [created_at]
          ,[content_id], [state]
          FROM [".$db_name."].[dbo].[mod_g]
          WHERE [state] = 1";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $result_cont = count($rows);

    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $db->sql_close();

    return $data;
}

function getCategoriesList($parameters = array()) {
    global $db,$db_name;

    $data = array();

    $id = $parameters['modid'];
    $sql = "SELECT [id]
            ,[mod_g_id]
            ,[title_es_edit] as title_es
            ,[title_en_edit] as title_en
            ,[category_icon_edit] as category_icon
            ,[pin_icon_edit] as pin_icon
            ,[state_edit] as state
            FROM [".$db_name."].[dbo].[mod_g_category]
            WHERE [mod_g_id] = $id and [state_edit] = 1";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $result_cont = count($rows);

    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $db->sql_close();

    return $data;
}

function getLocationslist($parameters = array()) {
    global $db,$db_name;

    $data = array();

    $id = $parameters['category_id'];
    $sql = "SELECT [id]
                ,[category_id]
                ,[latitude_edit] as latitude
                ,[longitude_edit] as longitude
                ,[address_es_edit] as address_es
                ,[address_en_edit] as address_en
                ,[title_other_es_edit] as title_other_es
                ,[title_other_en_edit] as title_other_en
                ,[other_es_edit] as other_es
                ,[other_en_edit] as other_en
                ,[schedule_es_edit] as schedule_es
                ,[schedule_en_edit] as schedule_en
                ,[title_en_edit] as title_en
                ,[title_es_edit] as title_es
                ,[image_edit] as image
                ,[state_edit] as state
          FROM [".$db_name."].[dbo].[location]
          WHERE [category_id] = $id and [state_edit] = 1";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $result_cont = count($rows);

    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $db->sql_close();

    return $data;
}

function getModId($content_id) {
    global $db,$db_name;

    $data = array();

    $sql = "SELECT [id]
            FROM [".$db_name."].[dbo].[mod_g]
            WHERE [content_id] = $content_id";

    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $result_cont = count($rows);

    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $db->sql_close();

    return $data;
}

//------------------------------------------------

function createCategoryBD($parameters = array()) {
    global $db, $loggedInUser,$db_name;
    //ya
    $modId = $parameters["modid"];
    $category_icon = $parameters["category_icon"];
    $pin_icon = $parameters["pin_icon"];
    $titleES = $parameters["title_es"];
    $titleEN = isset($parameters["title_en"]) ? $parameters["title_en"] : $titleES;

    $query_insertCategory = "EXECUTE [dbo].[insertModG_Category] 
                            @mod_g_id = $modId
                           ,@title_es = N'$titleES'
                           ,@title_en = N'$titleEN'
                           ,@category_icon = N'$category_icon'
                           ,@pin_icon = N'$pin_icon'
                           ,@state = 1;";

    $NewCategoryID = $db->sql_fetchrowset($db->sql_query($query_insertCategory));

    $data = array();
    $data['rows'] = $NewCategoryID[0];
    $data['count'] = count($NewCategoryID);
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM mod_g WHERE id=" . $modId;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se creo una cateogoria (" . $titleES . "/" . $titleEN . ")'";
    $db->sql_query($sql);
    return $data;
}

function updateCategoryBD($parameters = array()) {
    global $db, $loggedInUser,$db_name;
    //ya
    $modId = $parameters["modid"];
    $Id = $parameters["id"];
    $category_icon = $parameters["category_icon"];
    $pin_icon = $parameters["pin_icon"];
    $titleES = $parameters["title_es"];
    $titleEN = isset($parameters["title_en"]) ? $parameters["title_en"] : $titleES;

    $query_insertCategory = "EXECUTE [dbo].[updateModG_Category]
                            @id = $Id
                           ,@mod_g_id = $modId
                           ,@title_es = N'$titleES'
                           ,@title_en = N'$titleEN'
                           ,@category_icon = N'$category_icon'
                           ,@pin_icon = N'$pin_icon';";

    $NewCategoryID = $db->sql_fetchrowset($db->sql_query($query_insertCategory));

    $data = array();
    $data['rows'] = $NewCategoryID[0];
    $data['count'] = count($NewCategoryID);
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id FROM mod_g WHERE id=" . $modId;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se cambio una cateogoria (" . $titleES . "/" . $titleEN . ")'";
    $db->sql_query($sql);
    return $data;
}

function deleteCategoryBD($parameters = array()) {
    global $db, $loggedInUser,$db_name;
    $Id = $parameters["id"];
    $query_insertCategory = "EXECUTE [dbo].[deleteModG_Category] @id = $Id;";
    $db->sql_fetchrowset($db->sql_query($query_insertCategory));
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT module.content_id as content_id, cat.title_es as titulo FROM mod_g_category cat,mod_g module WHERE  module.id=cat.mod_g_id AND cat.id=" . $Id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $oldtitle = $rows[0]['titulo'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se borro una categoria(" . $oldtitle . ")'";
    $db->sql_query($sql);
}

//------------------------------------------------

function createLocationBD($parameters = array()) {
    global $db,$loggedInUser,$db_name;

    $query_insertCategory = 'EXECUTE [dbo].[insertModG_Location]
            @category_id = ' . $parameters['category_id'] . '
           ,@latitude = ' . $parameters['latitude'] . '
           ,@longitude = ' . $parameters['longitude'] . '
           ,@address_es = N\'' . $parameters['address_es'] . '\'
           ,@address_en = N\'' . $parameters['address_en'] . '\'
           ,@title_other_es = N\'' . $parameters['title_other_es'] . '\'
           ,@title_other_en = N\'' . $parameters['title_other_en'] . '\'
           ,@other_es = N\'' . $parameters['other_es'] . '\'
           ,@other_en = N\'' . $parameters['other_en'] . '\'
           ,@schedule_es = N\'' . $parameters['schedule_es'] . '\'
           ,@schedule_en = N\'' . $parameters['schedule_en'] . '\'
           ,@title_en = N\'' . $parameters['title_en'] . '\'
           ,@title_es = N\'' . $parameters['title_es'] . '\'
           ,@image = N\'' . $parameters['image'] . '\'
           ,@state = 1';

    $NewCategoryID = $db->sql_fetchrowset($db->sql_query($query_insertCategory));

    $data = array();
    $data['rows'] = $NewCategoryID[0];
    $data['count'] = count($NewCategoryID);
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql =  "SELECT mod_g.content_id as content_id, location.address_es as address_es FROM  mod_g_category INNER JOIN mod_g ON mod_g_category.mod_g_id = mod_g.id INNER JOIN location ON mod_g_category.id = location.category_id WHERE location.id = ". $NewCategoryID[0]['id'];
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se agrego una ubicacion(" . $parameters['address_es']. ")'";
    $db->sql_query($sql);
    return $data;
}

function updateLocationBD($parameters = array()) {
    global $db,$loggedInUser,$db_name;

    $query_insertCategory = 'EXECUTE [dbo].[updateModG_Location]
                            @id = ' . $parameters['id'] . '
                           ,@category_id = ' . $parameters['category_id'] . '
                           ,@latitude = ' . $parameters['latitude'] . '
                           ,@longitude = ' . $parameters['longitude'] . '
                           ,@address_es = N\'' . $parameters['address_es'] . '\'
                           ,@address_en = N\'' . $parameters['address_en'] . '\'
                           ,@title_other_es = N\'' . $parameters['title_other_es'] . '\'
                           ,@title_other_en = N\'' . $parameters['title_other_en'] . '\'
                           ,@other_es = N\'' . $parameters['other_es'] . '\'
                           ,@other_en = N\'' . $parameters['other_en'] . '\'
                           ,@schedule_es = N\'' . $parameters['schedule_es'] . '\'
                           ,@schedule_en = N\'' . $parameters['schedule_en'] . '\'
                           ,@title_en = N\'' . $parameters['title_en'] . '\'
                           ,@title_es = N\'' . $parameters['title_es'] . '\'
                           ,@image = N\'' . $parameters['image'] . '\'
                           ,@state = 1';

    $NewCategoryID = $db->sql_fetchrowset($db->sql_query($query_insertCategory));

    $data = array();
    $data['rows'] = $NewCategoryID[0];
    $data['count'] = count($NewCategoryID);
    
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT mod_g.content_id as content_id, location.address_es as address_es FROM  mod_g_category INNER JOIN mod_g ON mod_g_category.mod_g_id = mod_g.id INNER JOIN location ON mod_g_category.id = location.category_id WHERE location.id = " . $parameters['id'];
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $oldaddress = $rows[0]['address_es'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se actualizo una ubicacion(" . $oldaddress. "->".$parameters['address_es'].")'";
    $db->sql_query($sql);
    return $data;
}

function deleteLocationBD($parameters = array()) {
    global $db,$loggedInUser,$db_name;
    $Id = $parameters["id"];
    $sql = "SELECT mod_g.content_id as content_id, location.address_es as address_es FROM  mod_g_category INNER JOIN mod_g ON mod_g_category.mod_g_id = mod_g.id INNER JOIN location ON mod_g_category.id = location.category_id WHERE location.id = " . $parameters['id'];
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $oldaddress = $rows[0]['address_es'];
    
    
    $query_insertCategory = "EXECUTE [dbo].[deleteModG_Location] @id = $Id;";
    $db->sql_fetchrowset($db->sql_query($query_insertCategory));
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se elimino una ubicacion(" . $oldaddress. ")'";
    $db->sql_query($sql);
    
}

function createModGBD($parameters = array()) {
    global $db,$db_name;
    $data = $parameters['data'];
    $query_insertCategory = 'EXECUTE [dbo].[insertModG] 
                                @name = N\'' . $data['name'] . '\'
                               ,@description = N\'' . $data['description'] . '\'
                               ,@content_id = ' . $data['content_id'] . ';';

    $NewCategoryID = $db->sql_fetchrowset($db->sql_query($query_insertCategory));

    $datas = array();
    $datas['rows'] = $NewCategoryID[0]['id'];
    $datas['count'] = count($NewCategoryID);
    return $datas;
}

function ApproveModuleG($parameters = array()){
     global $db,$db_name;
     $contentid = $parameters['content_id'];
     //copiar los campos de edit a normal
     $listadecategorias = "SELECT distinct mod_g_category.id FROM mod_g INNER JOIN mod_g_category ON mod_g.id = mod_g_category.mod_g_id WHERE (mod_g.content_id = ".$contentid.")";
     $listadelocations = "SELECT distinct location.id FROM mod_g INNER JOIN mod_g_category ON mod_g.id = mod_g_category.mod_g_id INNER JOIN location ON mod_g_category.id = location.category_id WHERE (mod_g.content_id = ".$contentid.")";
     //cuarda cambios de las categorias
     $sql = "UPDATE [dbo].[mod_g_category] SET [title_es] = [title_es_edit], [title_en]= [title_en_edit],[category_icon]=[category_icon_edit], [pin_icon] = [pin_icon_edit], [state] = [state_edit] WHERE id IN (".$listadecategorias.")";
     $db->sql_query($sql);
     //guarda cambios de los puntos
     $sql = "UPDATE [dbo].[location] SET [latitude] = [latitude_edit], [longitude] = [longitude_edit], [address_es] = [address_es_edit], [address_en] = [address_en_edit], [title_other_es] = [title_other_es_edit], [title_other_en] = [title_other_en_edit], [other_es] = [other_es_edit], [other_en] = [other_en_edit], [schedule_es] = [schedule_es_edit], [schedule_en] = [schedule_en_edit], [title_en] = [title_en_edit], [title_es] = [title_es_edit], [image] = [image_edit], [state] = [state_edit] WHERE [id] IN (".$listadelocations.")";
     $db->sql_query($sql);
     //poner 00 en los location si se elimino un
     $sql = "UPDATE [location] SET [state] = 0, [state_edit] = 0 WHERE [category_id] IN (".$listadecategorias." AND mod_g_category.[state]=0 AND mod_g_category.[state_edit]=0)";
     $db->sql_query($sql);
     $sql = "DELETE [dbo].[location] WHERE [state]=0 AND [state_edit] =0";
     $db->sql_query($sql);
     $sql = "DELETE [dbo].[mod_g_category] WHERE [state]=0 AND [state_edit] =0";
     $db->sql_query($sql);
}
function DisapproveModuleG($parameters = array()){
    global $db,$db_name;
     $contentid = $parameters['content_id'];
     //copiar los campos de edit a normal
     $listadecategorias = "SELECT distinct mod_g_category.id FROM mod_g INNER JOIN mod_g_category ON mod_g.id = mod_g_category.mod_g_id WHERE (mod_g.content_id = ".$contentid.")";
     $listadelocations = "SELECT distinct location.id FROM mod_g INNER JOIN mod_g_category ON mod_g.id = mod_g_category.mod_g_id INNER JOIN location ON mod_g_category.id = location.category_id WHERE (mod_g.content_id = ".$contentid.")";
     //cuarda cambios de las categorias
     $sql = "UPDATE [dbo].[mod_g_category] SET [title_es_edit] = [title_es], [title_en_edit]= [title_en],[category_icon_edit]=[category_icon], [pin_icon_edit] = [pin_icon], [state_edit] = [state] WHERE id IN (".$listadecategorias.")";
     $db->sql_query($sql);
     //guarda cambios de los puntos
     $sql = "UPDATE [dbo].[location] SET [latitude_edit] = [latitude], [longitude_edit] = [longitude], [address_es_edit] = [address_es], [address_en_edit] = [address_en], [title_other_es_edit] = [title_other_es], [title_other_en_edit] = [title_other_en], [other_es_edit] = [other_es], [other_en_edit] = [other_en], [schedule_es_edit] = [schedule_es], [schedule_en_edit] = [schedule_en], [title_en_edit] = [title_en], [title_es_edit] = [title_es], [image_edit] = [image], [state_edit] = [state] WHERE [id] IN (".$listadelocations.")";
     $db->sql_query($sql);
     //poner 00 en los location si se elimino un
     $sql = "UPDATE [location] SET [state] = 0, [state_edit] = 0 WHERE [category_id] IN (".$listadecategorias." AND mod_g_category.[state]=0 AND mod_g_category.[state_edit]=0)";
     $db->sql_query($sql);
     $sql = "DELETE [dbo].[location] WHERE [state]=0 AND [state_edit] =0";
     $db->sql_query($sql);
     $sql = "DELETE [dbo].[mod_g_category] WHERE [state]=0 AND [state_edit] =0";
     $db->sql_query($sql);
}
?>
