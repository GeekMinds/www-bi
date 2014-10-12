<?php

function createPageAndSaveContentsDB($parametersAll = array()) {
    global $db;
    $parameters = $parametersAll['data'];
    $titleES = isset($parameters["title_es"]) ? "Pagina para el producto " . $parameters["title_es"] : "";
    $titleEN = isset($parameters["title_en"]) ? "Page for the product " . $parameters["title_en"] : $titleES;
    $description = "Pagina con los contenidos del producto";
    $query_insertPage = "EXEC [dbo].[insertPage]
		@titleES = N'$titleES',
		@titleEN = N'$titleEN',
		@descript = N'$description',
		@site_id = 1";
    $NewPageID = $db->sql_fetchrowset($db->sql_query($query_insertPage));
    $NewPageID = $NewPageID[0]['id'];
    if (isset($NewPageID)) {
//        insertModule($NewPageID, "module_carrousel", $parametersAll);
//        insertModule($NewPageID, "mod_c", $parametersAll);
//        insertModule($NewPageID, "mod_s1", $parametersAll);
        insertModule($NewPageID, "mod_q", $parametersAll);
    }
    return $NewPageID;
}

function insertModule($idPage, $module_name, $parametersAll = array()) {
    global $db;
    $parameters = $parametersAll['data'];
    $titleES = isset($parameters["title_es"]) ? $parameters["title_es"] : "";
    $titleEN = isset($parameters["title_en"]) ? $parameters["title_en"] : $titleES;
    $queryIdModule = "SELECT id FROM module_list WHERE name = '" . $module_name . "'";
    $resultIdModule = $db->sql_query($queryIdModule);
    $moduleRow = $db->sql_fetchrow($resultIdModule);
    $query_insertContent = "EXEC [dbo].[insertContent]
		@title_es = N'" . $titleES . "',
		@title_en = N'" . $titleEN . "',
		@tags = N'',
		@module_id = " . $moduleRow['id'] . ";";
    $NewContentID = $db->sql_fetchrowset($db->sql_query($query_insertContent));
    if ($module_name === 'mod_c') {
        $newIdPageContent = insertNewPageContent($idPage, '416');
    } else {
        $newIdPageContent = insertNewPageContent($idPage, $NewContentID[0]['id']);
    }

    if ($module_name === 'mod_q') {
        $parameters["content_id"] = $NewContentID[0]['id'];
        $idModQInserted = createModuleProductoDataBase($parameters);
        $parameters['id'] = $idModQInserted;
//        if (isset($parametersAll['beneficios'])) {
//            createBene($parametersAll['beneficios'], $parameters);
//        }
//        if (isset($parametersAll['requisitos'])) {
//            createReq($parametersAll['requisitos'], $parameters);
//        }
        if (isset($parametersAll['carac'])) {
            createCarac($parametersAll['carac'], $parameters);
        }
    }
    return insertContentConfiguration($newIdPageContent, $module_name);
}

function insertNewPageContent($page_id, $content_id) {
    global $db;
    $query_insertPageContent = "EXEC [dbo].[insertPageContent]
		@page_id = $page_id,
		@content_id = $content_id";
    $NewPageContentID = $db->sql_fetchrowset($db->sql_query($query_insertPageContent));
    return $NewPageContentID[0]['id'];
}

function insertContentConfiguration($page_content_id, $module_name) {
    global $db;
    $query_insertContentConfiguration = "";
    switch ($module_name) {
        case 'module_carrousel':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                3,
                1,
                2,
                1,' . $page_content_id . '
                ,1';
            break;
        case 'mod_c':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                1,
                3,
                1,
                1,' . $page_content_id . '
                ,1';
            break;
        case 'mod_s1':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                2,
                2,
                1,
                3,' . $page_content_id . '
                ,1';
            break;
        case 'mod_q':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                3,
                3,
                2,
                2,' . $page_content_id . '
                ,1';
            break;
    }
    $NewContentConfigurationID = $db->sql_fetchrowset($db->sql_query($query_insertContentConfiguration));
    return $NewContentConfigurationID[0]['id'];
}

function createModuleProductoDataBase($varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $content_id = $varParam["content_id"];
    if ($module_id == '-1' || $module_id == 'undefined') {
        $query_insertContent = "EXEC [dbo].[insertModQ]
                 @titulo_es	= N'" . $db->sql_escape($varParam['title_es']) . "'
                ,@titulo_en	= N'" . $db->sql_escape($varParam['title_en']) . "'
                ,@descrip_es     = N'" . $db->sql_escape($varParam['descrip_es']) . "'
                ,@descrip_en     = N'" . $db->sql_escape($varParam['descrip_en']) . "'
                ,@tag_es         = N'" . $db->sql_escape($varParam['tags_es']) . "'
                ,@tag_en         = N'" . $db->sql_escape($varParam['tags_en']) . "'
                ,@precio_es	= N'" . $db->sql_escape($varParam['precio_es']) . "'
                ,@precio_en	= N'" . $db->sql_escape($varParam['precio_en']) . "'
                ,@date_init	= N'" . $db->sql_escape($varParam['date_init']) . "'
                ,@date_end	= N'" . $db->sql_escape($varParam['date_end']) . "'
                ,@created_at     = NULL
                ,@updated_at     = NULL
                ,@status         = N'1'
                ,@content_id     = " . $db->sql_escape($content_id) . "
                ";
        $new_module_q = $db->sql_fetchrowset($db->sql_query($query_insertContent));
        $new_module_q = $new_module_q[0]['id'];
    } else {
        $hoy = date('d-m-Y');
        $sql = "UPDATE mod_q SET 
            titulo_es = '" . $db->sql_escape($varParam['title_es']) . "', 
            titulo_en = '" . $db->sql_escape($varParam['title_en']) . "', 
            descrip_es = '" . $db->sql_escape($varParam['descrip_es']) . "', 
            descrip_en = '" . $db->sql_escape($varParam['descrip_en']) . "', 
            tags_es = '" . $db->sql_escape($varParam['tags_es']) . "', 
            tags_en = '" . $db->sql_escape($varParam['tags_en']) . "', 
            precio_q = '" . $db->sql_escape($varParam['precio_es']) . "', 
            precio_us = '" . $db->sql_escape($varParam['precio_en']) . "', 
            fecha_init = '" . $db->sql_escape($varParam['date_init']) . "', 
            fecha_end = '" . $db->sql_escape($varParam['date_end']) . "',
            update_at = '" . $db->sql_escape($hoy) . "',
            status = 1 
            WHERE id = '" . $db->sql_escape($module_id) . "';";
        $result = $db->sql_query($sql);
        //$db->sql_close();
        $new_module_q = $module_id;
    }
    return $new_module_q;
}

function queryModuleProductoDataBase($varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $sql = "SELECT 
                id, 
                titulo_es, 
                titulo_en, 
                descrip_es, 
                descrip_en, 
                tags_es, 
                tags_en, 
                precio_q, 
                precio_us, 
                convert(varchar, fecha_init, 120) as fecha_init, 
                convert(varchar, fecha_end, 120) as fecha_end, 
                creat_at, 
                update_at, 
                status, 
                content_id 
            FROM 
                mod_q
            WHERE id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $result_cont = count($rows);
    $data['rows'] = $rows;

    $data['count'] = $result_cont;

    $sqlBen = "SELECT id_bene, name_es, name_en FROM mod_q_bene where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultBene = $db->sql_query($sqlBen);



    if ($resultBene) {
        $rowsB = $db->sql_fetchrowset($resultBene);
        $data['beneficios'] = $rowsB;
    }
    $sqlReq = "SELECT id_req, name_es, name_en FROM mod_q_req where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultReq = $db->sql_query($sqlReq);
    if ($resultReq) {
        $rowsq = $db->sql_fetchrowset($resultReq);
        $data['requisitos'] = $rowsq;
    }


    $db->sql_close();

    return $data;
}

function createBene($arrayBeneficios = array(), $varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $rci = count($arrayBeneficios);
    $query_insertContent = "";
    for ($I = 0; $I < $rci; $I++) {
        $inbees = $arrayBeneficios[$I]['name_es'];
        $inbeen = $arrayBeneficios[$I]['name_en'];
        $idben = $arrayBeneficios[$I]['id'];
        $accion = $arrayBeneficios[$I]['accion'];
        if ($accion == '-1') {
            $query_insertContent = "EXEC [dbo].[insertModQBene]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@name_es   = '" . $inbees . "'
                ,@name_en   = '" . $inbeen . "'
                ";
            $new_module_q_ben = $db->sql_fetchrowset($db->sql_query($query_insertContent));
            $new_module_q_ben[0]['id'];
        } elseif ($accion == '-2') {
            $queryUpdate = "UPDATE mod_q_bene SET status = 0 WHERE id_bene = '" . $db->sql_escape($idben) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        } else {
            $queryUpdate = "UPDATE mod_q_bene SET name_es = '" . $db->sql_escape($inbees) . "' , name_en   = '" . $db->sql_escape($inbeen) . "' WHERE id_bene = '" . $db->sql_escape($idben) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        }
    }
    // $db->sql_close();

    return 1;
}

function createReq($arrayRequisitos = array(), $varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $rci = count($arrayRequisitos);
    $query_insertContent = "";
    for ($I = 0; $I < $rci; $I++) {
        $requisitoes = $arrayRequisitos[$I]['name_es'];
        $requisitoen = $arrayRequisitos[$I]['name_en'];
        $idreq = $arrayRequisitos[$I]['id'];
        $accion = $arrayRequisitos[$I]['accion'];

        if ($accion == '-1') {
            $query_insertContent = "EXEC [dbo].[insertModQReq]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@name_es   = '" . $requisitoes . "'
                ,@name_en   = '" . $requisitoen . "'
                ";
            $new_module_q_req = $db->sql_fetchrowset($db->sql_query($query_insertContent));
            $new_module_q_req[0]['id'];
        } elseif ($accion == '-2') {
            $queryUpdate = "UPDATE mod_q_req SET status = 0 WHERE id_req = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        } else {
            $queryUpdate = "UPDATE mod_q_req SET name_es = '" . $requisitoes . "' , name_en   = '" . $requisitoen . "' WHERE id_req = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        }
    }
    //$db->sql_close();
    return 1;
}



function createCarac($arrayRequisitos = array(), $varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $rci = count($arrayRequisitos);
    $query_insertContent = "";
    for ($I = 0; $I < $rci; $I++) {
        $requisitoes = $arrayRequisitos[$I]['name_es'];
        $requisitoen = $arrayRequisitos[$I]['name_en'];
        $idreq = $arrayRequisitos[$I]['id'];
        $accion = $arrayRequisitos[$I]['accion'];

        if ($accion == '-1') {
            $query_insertContent = "EXEC [dbo].[insertModQCarc]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@titulo_es   = '" . $requisitoes . "'
                ,@titulo_en   = '" . $requisitoen . "'
                ";
            $new_module_q_req = $db->sql_fetchrowset($db->sql_query($query_insertContent));
            $new_module_q_req[0]['id'];
        } elseif ($accion == '-2') {
            $queryUpdate = "UPDATE mod_q_req SET status = 0 WHERE id_req = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        } else {
            $queryUpdate = "UPDATE mod_q_req SET name_es = '" . $requisitoes . "' , name_en   = '" . $requisitoen . "' WHERE id_req = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        }
    }
    return 1;
}