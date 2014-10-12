<?php

function createPageAndSaveContentsDB($parametersAll = array()) {
    global $db, $site_id;
    $parameters = $parametersAll['data'];
    $titleES = isset($parameters["title_es"]) ? "Pagina para el producto " . $parameters["title_es"] : "";
    $titleEN = isset($parameters["title_en"]) ? "Page for the product " . $parameters["title_en"] : $titleES;
    $description = "Pagina con los contenidos del producto";
    $query_insertPage = "EXEC [dbo].[insertPage]
		@titleES = N'$titleES',
		@titleEN = N'$titleEN',
		@descript = N'$description',
		@site_id = " . $db->sql_escape($site_id);
    $NewPageID = $db->sql_fetchrowset($db->sql_query($query_insertPage));
    $NewPageID = $NewPageID[0]['id'];
    if (isset($NewPageID)) {
        insertModule($NewPageID, "module_carrousel", $parametersAll);
        insertModule($NewPageID, "mod_c", $parametersAll);
        insertModule($NewPageID, "mod_s1", $parametersAll);
        insertModule($NewPageID, "mod_q", $parametersAll);
        insertModule($NewPageID, "mod_recommendation", $parametersAll);
        insertModule($NewPageID, "mod_p", $parametersAll);
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
        if (isset($parametersAll['beneficios'])) {
            createBene($parametersAll['beneficios'], $parameters);
        }
        if (isset($parametersAll['requisitos'])) {
            createReq($parametersAll['requisitos'], $parameters);
        }

        if (isset($parametersAll['directorio'])) {
            createDirec($parametersAll['directorio'], $parameters);
        }
        if (isset($parametersAll['carac'])) {
            createCarac($parametersAll['carac'], $parameters);
        }
        if (isset($parametersAll['intereses'])) {
            createInterests($parametersAll['intereses'], $parameters);
        }
        if (isset($parametersAll['data']['descrip_galES'])) {
            createGalery($parametersAll['data'], $parameters, $parametersAll);
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
                2,
                2,
                2,' . $page_content_id . '
                ,1';
            break;
        case 'mod_recommendation':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                3,
                1,
                3,
                2,' . $page_content_id . '
                ,1';
            break;
        case 'mod_p':
            $query_insertContentConfiguration = 'EXECUTE [dbo].[insertContentConf] 
                2,
                2,
                3,
                5,' . $page_content_id . '
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
    global $db, $site_id;
    $module_id = $varParam["id"];
    $sql = "SELECT 
                modq.id, 
                modq.titulo_es, 
                modq.titulo_en, 
                modq.descrip_es, 
                modq.descrip_en, 
                modq.tags_es, 
                modq.tags_en, 
                modq.precio_q, 
                modq.precio_us, 
                convert(varchar, modq.fecha_init, 120) as fecha_init, 
                convert(varchar, modq.fecha_end, 120) as fecha_end, 
                modq.creat_at, 
                modq.update_at, 
                modq.status, 
                modq.content_id 
            FROM 
                mod_q modq
            WHERE modq.id = '" . $db->sql_escape($module_id) . "' AND 
				  modq.status = 1 ";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $result_cont = count($rows);
    $data['rows'] = $rows;


    $sqlGal = "SELECT titulo_es, titulo_en, url_es, url_en, descript_es, descript_en, id_product, id_galery 
				FROM mod_q_galery Where id_product = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultGal = $db->sql_query($sqlGal);
    if ($resultGal) {
        $rowsc = $db->sql_fetchrowset($resultGal);
        $data['galery'] = $rowsc;
    }


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

    $sqlCar = "SELECT id_carac, name_es, name_en FROM mod_q_carac where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultCar = $db->sql_query($sqlCar);
    if ($resultCar) {
        $rowsq = $db->sql_fetchrowset($resultCar);
        $data['caracteristicas'] = $rowsq;
    }
    $sqlDir = "SELECT id_dir,type_dir,name_es,name_en,val FROM mod_q_dir where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultDir = $db->sql_query($sqlDir);
    if ($resultDir) {
        $rowsq = $db->sql_fetchrowset($resultDir);
        $data['directorio'] = $rowsq;
    }
    $data['intereses'] = '';
    $sqlInter = "SELECT * FROM mod_q_interest where id_producto = '" . $db->sql_escape($module_id) . "';";
    $resultInter = $db->sql_query($sqlInter);
    if ($resultInter) {
        $rowsq = $db->sql_fetchrowset($resultInter);
        $data['intereses'] = $rowsq;
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
            $querys = "UPDATE mod_q_carac SET status = 0 WHERE id_carac = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        } else {
            $queryUpdate = "UPDATE mod_q_carac SET name_es = '" . $requisitoes . "' , name_en   = '" . $requisitoen . "' WHERE id_carac = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        }
    }
    return 1;
}

function createDirec($arrayRequisitos = array(), $varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $rci = count($arrayRequisitos);
    $query_insertContent = "";
    for ($I = 0; $I < $rci; $I++) {
        $requisitoes = $arrayRequisitos[$I]['name_es'];
        $requisitoen = $arrayRequisitos[$I]['name_en'];
        $val = $arrayRequisitos[$I]['val'];
        $type = $arrayRequisitos[$I]['type'];
        $idreq = $arrayRequisitos[$I]['id'];
        $accion = $arrayRequisitos[$I]['accion'];

        if ($accion == '-1') {
            $query_insertContent = "EXEC [dbo].[insertModQDirec]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@titulo_es   = '" . $requisitoes . "'
                ,@val   = '" . $val . "'
                ,@type_dir   = '" . $type . "'
                ,@titulo_en   = '" . $requisitoen . "'
                ";
            $new_module_q_dir = $db->sql_fetchrowset($db->sql_query($query_insertContent));
            $new_module_q_dir[0]['id'];
        } elseif ($accion == '-2') {
            $queryUpdate = "UPDATE mod_q_dir SET status = 0 WHERE id_dir = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        } else {
            $queryUpdate = "UPDATE mod_q_dir SET type_dir = '" . $type . "' , name_es = '" . $requisitoes . "' , val   = '" . $val . "' , name_en   = '" . $requisitoen . "' WHERE id_dir = '" . $db->sql_escape($idreq) . "';";
            $resultUpdate = $db->sql_query($queryUpdate);
        }
    }
    return 1;
}

//Intereses
function createInterests($arrayIntereses = array(), $varParam = array()) {
    global $db;
    $module_id = $varParam["id"];
    $contador = sizeof($arrayIntereses);
    $query_insertContent = "";
    $queryDelete = "DELETE FROM dbo.mod_q_interest WHERE id_producto=" . $db->sql_escape($module_id) . ";";
    $resultDelete = $db->sql_query($queryDelete);


    error_log(json_encode($contador));
    for ($i = 0; $i < $contador; $i++) {
        $idinteres = $arrayIntereses[$i]['id'];

        $query_insertContent = "EXEC [dbo].[insertModQInterest]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@interest_id   = '" . $db->sql_escape($idinteres) . "'
                ";
        $new_module_q_req = $db->sql_fetchrowset($db->sql_query($query_insertContent));
        //return 1;
    }
}

//intereses
function createGalery($arrayRequisitos = array(), $varParam = array(), $varPP = array()) {
    global $db;
    $module_id = $varParam["id"];
    $varData = $varPP['action'];
    $rci = count($arrayRequisitos);
    $query_insertContent = "";
    $titulo_galeriaES = $arrayRequisitos['titulo_galeriaES'];
    $titulo_galeriaEN = $arrayRequisitos['titulo_galeriaEN'];
    $descrip_galES = $arrayRequisitos['descrip_galES'];
    $descrip_galEN = $arrayRequisitos['descrip_galEN'];
    $requisito_url_es = $arrayRequisitos['imagenes']['es'][0]['ret'][0];
    $requisito_url_en = $arrayRequisitos['imagenes']['en'][0]['ret'][0];

    $idreq = $arrayRequisitos['id'];
    //$accion = $arrayRequisitos['accion'];

    if ($varData == 'savecontent') {
        $query_insertContent = "EXEC [dbo].[isnertModQGalery]
                @product_id     = '" . $db->sql_escape($module_id) . "'
                ,@titulo_es     = '" . $titulo_galeriaES . "'
                ,@titulo_en     = '" . $titulo_galeriaEN . "'
                ,@url_es        = '" . $requisito_url_es . "'
                ,@url_en        = '" . $requisito_url_en . "'
                ,@des_es        = '" . $descrip_galES . "'
                ,@des_en        = '" . $descrip_galEN . "'
                ";
        $new_module_q_req = $db->sql_fetchrowset($db->sql_query($query_insertContent));
        $new_module_q_req[0]['id'];
    } elseif ($varData == '-2') {
        $queryUpdate = "UPDATE mod_q_galery SET status = 0 WHERE id_product = '" . $db->sql_escape($idreq) . "';";
        $resultUpdate = $db->sql_query($queryUpdate);
    } else {
        $queryUpdate = "UPDATE mod_q_galery SET titulo_es = '" . $db->sql_escape($titulo_galeriaES) . "' , titulo_en   = '" . $db->sql_escape($titulo_galeriaEN) . "' , descript_es= '" . $db->sql_escape($descrip_galES) . "' , descript_en='" . $db->sql_escape($descrip_galEN) . "' WHERE id_product = '" . $db->sql_escape($idreq) . "';";
        $resultUpdate = $db->sql_query($queryUpdate);
        if (strlen($requisito_url_en) > 0) {
            $queryUpdate = "UPDATE mod_q_galery SET url_en='" . $requisito_url_en . "' WHERE id_product = '" . $db->sql_escape($idreq) . "'";
            $db->sql_query($queryUpdate);
        }
        if (strlen($requisito_url_es) > 0) {
            $queryUpdate = "UPDATE mod_q_galery SET url_es='" . $requisito_url_es . "' WHERE id_product = '" . $db->sql_escape($idreq) . "'";
            $db->sql_query($queryUpdate);
        }
    }
    return 1;
}

function getPageProductDataBase($idProduct) {
    global $db;
    $idProduct = $idProduct;
    $sql = "EXECUTE [dbo].[getPageProduct] $idProduct";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;

    $db->sql_close();

    return $data;
}

function updateProductDB($parametersAll = array()) {
    $parameters = $parametersAll['data'];
    $idModqUpdate = createModuleProductoDataBase($parameters);
    $parameters['id'] = $idModqUpdate;
    if (isset($parametersAll['beneficios'])) {
        createBene($parametersAll['beneficios'], $parameters);
    }
    if (isset($parametersAll['directorio'])) {
        createDirec($parametersAll['directorio'], $parameters);
    }
    if (isset($parametersAll['requisitos'])) {
        createReq($parametersAll['requisitos'], $parameters);
    }
    if (isset($parametersAll['carac'])) {
        createCarac($parametersAll['carac'], $parameters);
    }
    if (isset($parametersAll['intereses'])) {
        createInterests($parametersAll['intereses'], $parameters);
    }
    if (isset($parametersAll['data']['descrip_galES'])) {
        createGalery($parametersAll['data'], $parameters, $parametersAll);
    }
    return true;
}

function ApproveModuleProduct($parameters) {
    
}

function DisapproveModuleProduct($parameters) {
    global $db, $db_table_prefix, $db_name;

    $sql = "update mod_q set titulo_es_edit=titulo_es,titulo_en_edit=titulo_en,descrip_es_edit=descrip_es,descrip_en_edit=descrip_en_edit,tags_es_edit=tags_es,tags_en_edit=tags_en,precio_q_edit=precio_q,precio_us_edit=precio_us,fecha_init_edit=fecha_init,fecha_end_edit=fecha_end,status_edit=[status]";
    $db->sql_query($sql);
}

function getProductsDataBaseList() {
    global $db, $site_id;
    $data = array();
    $sql = "SELECT 
                modq.id, 
                modq.titulo_es, 
                modq.titulo_en, 
                modq.descrip_es, 
                modq.descrip_en, 
                modq.tags_es, 
                modq.tags_en, 
                modq.precio_q, 
                modq.precio_us, 
                convert(varchar, modq.fecha_init, 120) as fecha_init, 
                convert(varchar, modq.fecha_end, 120) as fecha_end, 
                modq.creat_at, 
                modq.update_at, 
                modq.status, 
                modq.content_id 
            FROM 
                mod_q modq,
				page_content pc,
				page p
            WHERE modq.status = 1 AND
				  pc.content_id = modq.content_id AND
				  p.id = pc.page_id  AND
				  p.site_id = " . $db->sql_escape($site_id);


    //error_log("GALERIA DE PRODUCTOS DEL PAIS");

    $result = $db->sql_query($sql);
    $rowsP = $db->sql_fetchrowset($result);

    for ($i = 0; $i < count($rowsP); $i++) {
        $idProducto = $rowsP[$i]['id'];
        $data[] = queryModuleProductoDataBaseList(array('id' => $idProducto));
    }

    $db->sql_close();
    return $data;
}

function queryModuleProductoDataBaseList($varParam = array()) {
    global $db, $site_id;
    $module_id = $varParam["id"];
    $sql = "SELECT 
                modq.id, 
                modq.titulo_es, 
                modq.titulo_en, 
                modq.descrip_es, 
                modq.descrip_en, 
                modq.tags_es, 
                modq.tags_en, 
                modq.precio_q, 
                modq.precio_us, 
                convert(varchar, modq.fecha_init, 120) as fecha_init, 
                convert(varchar, modq.fecha_end, 120) as fecha_end, 
                modq.creat_at, 
                modq.update_at, 
                modq.status, 
                modq.content_id 
            FROM 
				mod_q modq
            WHERE modq.id = '" . $db->sql_escape($module_id) . "'  AND 
				  modq.status = 1 ";
    $result = $db->sql_query($sql);

    $row = $db->sql_fetchrow($result);
    $data['producto'] = $row;

    $sqlBen = "SELECT name_es, name_en FROM mod_q_bene where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultBene = $db->sql_query($sqlBen);



    if ($resultBene) {
        $rowsB = $db->sql_fetchrowset($resultBene);
        $data['beneficios'] = $rowsB;
    }
    $sqlReq = "SELECT name_es, name_en FROM mod_q_req where product_id = '" . $db->sql_escape($module_id) . "' AND status = 1;";
    $resultReq = $db->sql_query($sqlReq);
    if ($resultReq) {
        $rowsq = $db->sql_fetchrowset($resultReq);
        $data['requisitos'] = $rowsq;
    }


    // $db->sql_close();

    return $data;
}

//VERSION 2.0 del modulo
function getModuleQ($content_id) {
    global $db;
    $sql = "EXECUTE [dbo].[createModQ] @content_id = $content_id";
    $newModuleID = $db->sql_fetchrowset($db->sql_query($sql));
    return $newModuleID[0];
}

function getBeneficios($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT  id_bene,
                    name_es_edit as name_es,
                    name_en_edit as name_en,
                    subtitulo_edit as subtitulo
            FROM    mod_q_bene
            WHERE   (product_id = $module_id) AND [status_edit]=1";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function createBeneficio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    $sql = "EXEC [dbo].[insertModQBene]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@name_es   = '" . $db->sql_escape($text_es) . "'
                ,@name_en   = '" . $db->sql_escape($text_en) . "'
                ,@subtitulo = '" . $db->sql_escape($subtitulo) . "'";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $rows;
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $titulo_modulo = $rows[0]['titulo_es'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se agrego un beneficio  (" . $text_es . "/" . $text_en . ") al producto " . $titulo_modulo . "'";
    $db->sql_query($sql);
    return $jTableResult;
}

function updateBeneficio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $beneficio_id = $parameters['id_bene'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    //before update
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_bene WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_bene =" . $db->sql_escape($beneficio_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es_old = $rows[0]['name_es'];
    $text_en_old = $rows[0]['name_en'];
    //update
    $sql = "UPDATE mod_q_bene SET name_es_edit = '" . $db->sql_escape($text_es) . "', name_en_edit = '" . $db->sql_escape($text_en) . "', subtitulo_edit = '" . $db->sql_escape($subtitulo) . "' WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_bene = " . $db->sql_escape($beneficio_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se modifico un beneficio  (" . $text_es_old . "/" . $text_en_old . ")->(" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el beneficio";
    }

    return $jTableResult;
}

function deleteBeneficio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $beneficio_id = $parameters['id_bene'];
    //before delete
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_bene WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_bene =" . $db->sql_escape($beneficio_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es = $rows[0]['name_es'];
    $text_en = $rows[0]['name_en'];
    $sql = "UPDATE mod_q_bene SET status_edit=0 WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_bene = " . $db->sql_escape($beneficio_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se elimino un beneficio  (" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el beneficio";
    }
    return $jTableResult;
}

function getRequisitos($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT  id_req,
                    name_es_edit as name_es,
                    name_en_edit as name_en,
                    subtitulo_edit as subtitulo
            FROM    mod_q_req
            WHERE   (product_id = $module_id) AND [status_edit]=1";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function createRequisito($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    $sql = "EXEC [dbo].[insertModQReq]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@name_es   = '" . $db->sql_escape($text_es) . "'
                ,@name_en   = '" . $db->sql_escape($text_en) . "'
                ,@subtitulo = '" . $db->sql_escape($subtitulo) . "'";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $rows;
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $titulo_modulo = $rows[0]['titulo_es'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se agrego un requisito  (" . $text_es . "/" . $text_en . ") al producto " . $titulo_modulo . "'";
    $db->sql_query($sql);
    return $jTableResult;
}

function updateRequisito($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $requisito_id = $parameters['id_req'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    //before update
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_req WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_bene =" . $db->sql_escape($requisito_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es_old = $rows[0]['name_es'];
    $text_en_old = $rows[0]['name_en'];
    //update
    $sql = "UPDATE mod_q_req SET name_es_edit = '" . $db->sql_escape($text_es) . "', name_en_edit = '" . $db->sql_escape($text_en) . "', subtitulo_edit = '" . $db->sql_escape($subtitulo) . "' WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_req = " . $db->sql_escape($requisito_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se modifico un requisito  (" . $text_es_old . "/" . $text_en_old . ")->(" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el requisito";
    }

    return $jTableResult;
}

function deleteRequisito($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $requisito_id = $parameters['id_req'];
    //before delete
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_req WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_req =" . $db->sql_escape($requisito_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es = $rows[0]['name_es'];
    $text_en = $rows[0]['name_en'];
    $sql = "UPDATE mod_q_req SET status_edit=0 WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_req = " . $db->sql_escape($requisito_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se elimino un requisito  (" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el requisito";
    }
    return $jTableResult;
}

function getCaracteristicas($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT  id_carac,
                    name_es_edit as name_es,
                    name_en_edit as name_en,
                    subtitulo_edit as subtitulo
            FROM    mod_q_carac
            WHERE   (product_id = $module_id) AND [status_edit]=1";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function createCaracteristica($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    $sql = "EXEC [dbo].[insertModQCarc]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@titulo_es   = '" . $db->sql_escape($text_es) . "'
                ,@titulo_en   = '" . $db->sql_escape($text_en) . "'
                ,@subtitulo = '" . $db->sql_escape($subtitulo) . "'";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $rows;
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $titulo_modulo = $rows[0]['titulo_es'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se agrego una caracteristica  (" . $text_es . "/" . $text_en . ") al producto " . $titulo_modulo . "'";
    $db->sql_query($sql);
    return $jTableResult;
}

function updateCaracteristica($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $carac_id = $parameters['id_carac'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $subtitulo = $parameters['subtitulo'];
    //before update
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_carac WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_carac =" . $db->sql_escape($carac_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es_old = $rows[0]['name_es'];
    $text_en_old = $rows[0]['name_en'];
    //update
    $sql = "UPDATE mod_q_carac SET name_es_edit = '" . $db->sql_escape($text_es) . "', name_en_edit = '" . $db->sql_escape($text_en) . "', subtitulo_edit='" . $db->sql_escape($subtitulo) . "' WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_carac = " . $db->sql_escape($carac_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se modifico una caracteristica  (" . $text_es_old . "/" . $text_en_old . ")->(" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar la caracteristica";
    }

    return $jTableResult;
}

function deleteCaracteristica($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $carac_id = $parameters['id_carac'];
    //before delete
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_carac WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_carac =" . $db->sql_escape($carac_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es = $rows[0]['name_es'];
    $text_en = $rows[0]['name_en'];
    $sql = "UPDATE mod_q_carac SET status_edit=0 WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_carac = " . $db->sql_escape($carac_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se elimino una caracteristica  (" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar la caracteristica";
    }
    return $jTableResult;
}

function getDirectorios($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT  id_dir,
                    name_es_edit as name_es,
                    name_en_edit as name_en,
                    val_edit as val,
                    type_dir_edit as type_dir
            FROM    mod_q_dir
            WHERE   (product_id = $module_id) AND [status_edit]=1";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $rows;
    $jTableResult['TotalRecordCount'] = count($rows);
    return $jTableResult;
}

function createDirectorio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $type_dir = $parameters['type_dir'];
    $value = $parameters['val'];
    $sql = "EXEC [dbo].[insertModQDirec]        
                @product_id = '" . $db->sql_escape($module_id) . "'
                ,@titulo_es   = '" . $db->sql_escape($text_es) . "'
                ,@titulo_en   = '" . $db->sql_escape($text_en) . "'
                ,@type_dir   = '" . $db->sql_escape($type_dir) . "'
                ,@val   = '" . $db->sql_escape($value) . "'";
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $rows;
    // INSERTAR CAMBIOS 
    //obtener el content_id
    $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $titulo_modulo = $rows[0]['titulo_es'];
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='Se agrego una directorio  (" . $text_es . "/" . $text_en . ") al producto " . $titulo_modulo . "'";
    $db->sql_query($sql);
    return $jTableResult;
}

function updateDirectorio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $dir_id = $parameters['id_dir'];
    $text_es = $parameters['name_es'];
    $text_en = $parameters['name_en'];
    $type_dir = $parameters['type_dir'];
    $value = $parameters['val'];
    //before update
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_dir WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_carac =" . $db->sql_escape($carac_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es_old = $rows[0]['name_es'];
    $text_en_old = $rows[0]['name_en'];
    //update
    $sql = "UPDATE mod_q_dir SET name_es_edit = '" . $db->sql_escape($text_es) . "', name_en_edit = '" . $db->sql_escape($text_en) . "', type_dir_edit ='" . $db->sql_escape($type_dir) . "', val_edit='" . $db->sql_escape($value) . "' WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_dir = " . $db->sql_escape($dir_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se modifico un directorio  (" . $text_es_old . "/" . $text_en_old . ")->(" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el directorio";
    }

    return $jTableResult;
}

function deleteDirectorio($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $dir_id = $parameters['id_dir'];
    //before delete
    $sql = "SELECT name_es_edit as name_es, name_en_edit as name_en FROM mod_q_dir WHERE (product_id=" . $db->sql_escape($module_id) . ") AND (id_dir =" . $db->sql_escape($dir_id) . ")";
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $text_es = $rows[0]['name_es'];
    $text_en = $rows[0]['name_en'];
    $sql = "UPDATE mod_q_dir SET status_edit=0 WHERE  product_id = " . $db->sql_escape($module_id) . "  AND  id_dir = " . $db->sql_escape($dir_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se elimino un directorio  (" . $text_es . "/" . $text_en . ") del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo eliminar el directorio";
    }
    return $jTableResult;
}

function saveMainArea($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $titulo_es = $parameters['titulo_es'];
    $titulo_en = $parameters['titulo_en'];
    $descrip_es = $parameters['descrip_es'];
    $descrip_en = $parameters['descrip_en'];

    $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $content_id = $rows[0]['content_id'];
    $titulo_modulo_old = $rows[0]['titulo_es'];
    $sql = "UPDATE mod_q SET titulo_es_edit = '" . $db->sql_escape($titulo_es) . "', titulo_en_edit = '" . $db->sql_escape($titulo_en) . "', descrip_es_edit='" . $db->sql_escape($descrip_es) . "', descrip_en_edit='" . $db->sql_escape($descrip_en) . "' WHERE id=" . $db->sql_escape($module_id);
    $jTableResult = array();
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se actualizo la informacion del producto " . $titulo_modulo_old . "(" . $titulo_es . ")'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo actualizar el producto";
    }
    return $jTableResult;
}

function getMainArea($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT titulo_es_edit as titulo_es, titulo_en_edit as titulo_en, CAST(descrip_es_edit AS TEXT) as descrip_es, CAST(descrip_en_edit AS TEXT) as descrip_en FROM mod_q WHERE id=" . $module_id;
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $rows = $db->sql_fetchrowset($result);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $rows[0];
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo obtener el area principal del  producto";
    }
    return $jTableResult;
}

function saveGallery($parameters = array()) {
    global $db, $loggedInUser;
    $module_id = $parameters['module_id'];
    $titulo_es = $parameters['titulo_es'];
    $titulo_en = $parameters['titulo_en'];
    $desc_es = $parameters['descrip_es'];
    $desc_en = $parameters['descrip_en'];
    $img_es = $parameters['img_es'];
    $img_en = $parameters['img_en'];
    $sql = "EXEC [dbo].[insertModQGallery]
	@module_id ='" . $db->sql_escape($module_id) . "',
	@titulo_es ='" . $db->sql_escape($titulo_es) . "',
	@titulo_en ='" . $db->sql_escape($titulo_en) . "',
	@desc_es ='" . $db->sql_escape($desc_es) . "',
	@desc_en ='" . $db->sql_escape($desc_en) . "',
	@img_es ='" . $db->sql_escape($img_es) . "',
	@img_en ='" . $db->sql_escape($img_en) . "'";
    if ($db->sql_query($sql)) {
        $jTableResult['Result'] = "OK";
        // INSERTAR CAMBIOS 
        //obtener el content_id
        $sql = "SELECT content_id,titulo_es FROM mod_q WHERE id=" . $module_id;
        $rows = $db->sql_fetchrowset($db->sql_query($sql));
        $content_id = $rows[0]['content_id'];
        $titulo_modulo = $rows[0]['titulo_es'];
        $sql = "EXECUTE [dbo].[EditedContent]"
                . "@content =" . $db->sql_escape($content_id) . ""
                . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
                . ",@description='Se guardo la galeria  del producto " . $titulo_modulo . "'";
        $db->sql_query($sql);
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo guardar la galeria del producto";
    }
    return $jTableResult;
}

function getGallery($parameters = array()) {
    global $db;
    $module_id = $parameters['module_id'];
    $sql = "SELECT titulo_es_edit as titulo_es,titulo_en_edit as titulo_en,url_es_edit as url_es,url_en_edit as url_en,descript_es_edit as descript_es,descript_en_edit as descript_en FROM dbo.mod_q_galery WHERE status_edit=1 and id_product=" . $module_id;
    $result = $db->sql_query($sql);
    $jTableResult = array();
    if ($result) {
        $rows = $db->sql_fetchrowset($result);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $rows[0];
    } else {
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "No se pudo obtener la galeria producto";
    }
    return $jTableResult;
}

function ApproveModuleQ($parameters = array()) {
    global $db, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "SELECT id FROM mod_q WHERE content_id=" . $contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $module_id = $rows[0]['id'];
    //update al modulo padre
    $sql = "UPDATE mod_q SET titulo_es = titulo_es_edit, titulo_en = titulo_en_edit,descrip_es = descrip_es_edit, descrip_en = descrip_en_edit, status = status_edit WHERE content_id=" . $contentid;
    //update a la galeria
    $sql = "UPDATE mod_q_galery SET titulo_es = titulo_es_edit, titulo_en = titulo_en_edit, url_es = url_es_edit, url_en = url_en_edit,descript_es = descript_es_edit, descript_en = descript_en_edit, status = status_edit WHERE product_id=" . $module_id;
    //update beneficios
    $sql = "UPDATE mod_q_bene SET name_es = name_es_edit, name_en = name_en_edit, subtitulo = subtitulo_edit, status = status_edit WHERE product_id =" . $module_id;
    //update caracteristicas
    $sql = "UPDATE mod_q_carac SET name_es = name_es_edit, name_en = name_en_edit, subtitulo = subtitulo_edit, status = status_edit WHERE product_id =" . $module_id;
    //update requisitos
    $sql = "UPDATE mod_q_req SET name_es = name_es_edit, name_en = name_en_edit, subtitulo = subtitulo_edit, status = status_edit WHERE product_id =" . $module_id;
    //update directorio
    $sql = "UPDATE mod_q_dir SET name_es = name_es_edit, name_en = name_en_edit, val = val_edit, type_dir = type_dir_edit, status = status_edit WHERE product_id =" . $module_id;
    //DELETES
    //galeria
    $sql = "DELETE mod_q_galery WHERE status=0 AND status_edit = 0";
    //beneficios
    $sql = "DELETE mod_q_bene WHERE status=0 AND status_edit = 0";
    //caracteristicas
    $sql = "DELETE mod_q_carac WHERE status=0 AND status_edit = 0";
    //requisitos
    $sql = "DELETE mod_q_req WHERE status=0 AND status_edit=0";
    //directorio
    $sql = "DELETE mod_q_dir WHERE status=0 AND status_edit=0";
}

function DisapproveModuleQ($parameters = array()) {
    global $db, $db_name;
    $contentid = $parameters['content_id'];
    $sql = "SELECT id FROM mod_q WHERE content_id=" . $contentid;
    $rows = $db->sql_fetchrowset($db->sql_query($sql));
    $module_id = $rows[0]['id'];
    //update al modulo padre
    $sql = "UPDATE mod_q SET titulo_es_edit = titulo_es, titulo_en_edit = titulo_en,descrip_es_edit = descrip_es, descrip_en_edit = descrip_en, status_edit = status WHERE content_id=" . $contentid;
    //update a la galeria
    $sql = "UPDATE mod_q_galery SET titulo_es_edit = titulo_es, titulo_en_edit = titulo_en, url_es_edit = url_es, url_en_edit = url_en,descript_es_edit = descript_es, descript_en_edit = descript_en, status_edit = status WHERE product_id=" . $module_id;
    //update beneficios
    $sql = "UPDATE mod_q_bene SET name_es_edit = name_es, name_en_edit = name_en, subtitulo_edit = subtitulo, status_edit = status WHERE product_id =" . $module_id;
    //update caracteristicas
    $sql = "UPDATE mod_q_carac SET name_es_edit = name_es, name_en_edit = name_en, subtitulo_edit = subtitulo, status_edit = status WHERE product_id =" . $module_id;
    //update requisitos
    $sql = "UPDATE mod_q_req SET name_es_edit = name_es, name_en_edit = name_en, subtitulo_edit = subtitulo, status_edit = status WHERE product_id =" . $module_id;
    //update directorio
    $sql = "UPDATE mod_q_dir SET name_es_edit = name_es, name_en_edit = name_en, val_edit = val, type_dir_edit = type_dir, status_edit = status WHERE product_id =" . $module_id;
    //DELETES
    //galeria
    $sql = "DELETE mod_q_galery WHERE status=0 AND status_edit = 0";
    //beneficios
    $sql = "DELETE mod_q_bene WHERE status=0 AND status_edit = 0";
    //caracteristicas
    $sql = "DELETE mod_q_carac WHERE status=0 AND status_edit = 0";
    //requisitos
    $sql = "DELETE mod_q_req WHERE status=0 AND status_edit=0";
    //directorio
    $sql = "DELETE mod_q_dir WHERE status=0 AND status_edit=0";
}
