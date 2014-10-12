<?php

function listPagesDataBase($parameters = array()){
    global $db, $db_name;
    $site_id= (isset($parameters['site_id'])) ? $parameters['site_id'] : "0";
	
    $data = array();

    $sql = "SELECT [id],[title_es],[title_en],[description] FROM [".$db_name."].[dbo].[page] 
			WHERE 
				site_id = ".$db->sql_escape($site_id) ;
    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data = $rows;
    return $data;
}

function createCarrouselDataBase($parameters = array()) {
    global $db;

	deleteCarrousel($parameters);

    $NewCarrouselID = InsertNewCarrousel($parameters);

    $array_CarrouselContent = $parameters['carrouselcontent'];

    foreach ($array_CarrouselContent as $valor) {
        $content_id = InsertNewCarrouselContent($NewCarrouselID, $valor);
    }
    return $NewCarrouselID;
}

function InsertNewCarrousel($parameters = array()) {
    global $db;

    $carrousel_transition_id = $parameters["carrousel_transition_id"];
    $content_id = $parameters["content_id"];
    $titleES = $parameters["title_es"];
    $titleEN = isset($parameters["title_en"]) ? $parameters["title_en"] : $titleES;

    $query_insertContent = "EXECUTE [dbo].[insertCarrousel] 
        @carrousel_transition_id = $carrousel_transition_id
       ,@title_es = N'$titleES'
       ,@title_en = N'$titleEN'
       ,@created_at = null
       ,@content_id = $content_id;";
    $NewCarrouselID = $db->sql_fetchrowset($db->sql_query($query_insertContent));
    return $NewCarrouselID[0]['id'];
}

function InsertNewCarrouselContent($carrousel_id, $parameters = array()) {
    global $db;

    $content_type = $parameters["content_type"];
    $content_url = $parameters["content_url"];
    $link = $parameters["link"];
    $titleES = $parameters["title_es"];
    $titleEN = isset($parameters["title_en"]) ? $parameters["title_en"] : $titleES;

    $query_insertContent = "EXECUTE [dbo].[insertCarrouselContent] 
        @title_es = N'$titleES'
       ,@title_en = N'$titleEN'
       ,@link = N'$link'
       ,@content_url = N'$content_url'
       ,@content_type = N'$content_type'
       ,@module_carrousel_id = $carrousel_id";

       error_log('QUERY insert content: ' . $query_insertContent);
       $NewContentID = $db->sql_fetchrowset($db->sql_query($query_insertContent));
       return $NewContentID[0]['id'];
}

function deleteCarrousel($parameters=array()){
	global $db;
    $content_id = isset($parameters["content_id"]) ? $parameters["content_id"] : "";
	
	if($content_id==""){
		return;
	}
	
    $data = array();
    $sql = "DELETE FROM module_carrousel WHERE content_id = ".$content_id;
    $result = $db->sql_query($sql);
    return $data;
}

?>
