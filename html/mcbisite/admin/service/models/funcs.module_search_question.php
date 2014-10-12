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
function createModuleSearchQuestionsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;

    $sql = "INSERT INTO [".$db_name."].[dbo].[module_search_questions] (
			title_es_edit,
			title_en_edit,
			description_edit,
			content_id
			)
			VALUES (
			'" . $db->sql_escape($parameters["title_es"]) . "',
			'" . $db->sql_escape($parameters["title_en"]) . "',
			'" . $db->sql_escape($parameters["description"]) . "',
			'" . $db->sql_escape($parameters["content_id"]) . "'
			)";
			
	
    $result = $db->sql_query($sql);
	
    if ($result) {
        InsertNotification($parameters["content_id"],"Se ha creado un nuevo modulo: ".$db->sql_escape($parameters["title_es"]));
        return $result;
    }
    return false;
}

//You can get the list countries with filter options
function listModuleSarchQuestionsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$limit = "";
	
	if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
       // $limit = " TOP " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
	    $limit = " TOP " .  $db->sql_escape($parameters['jtpagesize']);
    }

    $sql = "SELECT 	".$limit."
 					id,
					title_es_edit as title_es, 
					title_en_edit as title_en,
					description_edit as description,
					content_id,
					created_at,
					title_es_edit AS Value,
					title_es_edit AS DisplayText			
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
function getModuleSearchQuestionsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();

    $sql = "SELECT id, title_es_edit as title_es, title_en_edit title_en, description_edit as description, content_id, created_at 
			FROM [".$db_name."].[dbo].[module_search_questions] 
			WHERE id = " . $db->sql_escape($parameters["module_id"]) . " ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrow($result);

    $data = $rows;

    return $data;
}



//You can get the list of sub menu optiones from C module
function saveModuleSearchQuestionsDataBase($parameters = array()) {
	$return = array();
	$result =  false;
    global $db, $db_table_prefix;
    $parameters["module_id"] = (isset($parameters['module_id'])) ? $parameters['module_id'] : "-1";
	

	if($parameters['module_id']=="-1"){
		$result = createModuleSearchQuestionsDataBase($parameters);
		$return["module_id"] = getLastInsertion();
	}else{
		$result = updateModuleSearchQuestionsDataBase($parameters);
		$return["module_id"] =  $parameters["module_id"];
	}
	
	if(!$result){
		return false;
	}
	
	return $return;
}


//You can update specific mod_question
function updateModuleSearchQuestionsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[module_search_questions]  SET 
					title_es_edit = '" . $db->sql_escape($parameters["title_es"]) . "', 
					title_en_edit = '" . $db->sql_escape($parameters["title_en"]) . "', 
					description_edit = '" . $db->sql_escape($parameters["description"]) . "'
			WHERE id = " . $db->sql_escape($parameters["module_id"]) . ";";
	
	
	//return $sql;

    $result = $db->sql_query($sql);
    
    InsertNotification($parameters["content_id"],"Se ha modificado la informacion basica de: ".$db->sql_escape($parameters["title_es"]));
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
function listQuestionsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$limit = "";
	
	if ($parameters['jtstartindex'] != '' && $parameters['jtpagesize'] != '') {
       // $limit = " TOP " . $parameters['jtstartindex'] . ", " . $parameters['jtpagesize'];
	    $limit = " TOP " .  $db->sql_escape($parameters['jtpagesize']);
    }

    $sql = "SELECT 	".$limit."
 					id question_id,
					question_es_edit as question_es,
					question_en_edit as question_en,
					id list_tags		
			FROM 
				[".$db_name."].[dbo].[question]
			WHERE 
				active_edit = 1 AND module_search_questions_id = " . $db->sql_escape($parameters["module_id"]);
	
			
    $sql_count = "SELECT COUNT(*) as count FROM [".$db_name."].[dbo].[question] WHERE active_edit=1 AND module_search_questions_id = " . $db->sql_escape($parameters["module_id"]);


	if($parameters["question_es"]!=""){
		$sql .= " AND question_es LIKE '%".$db->sql_escape($parameters["question_es"])."%' ";	
	}

    if ($parameters['jtsorting'] != '') {
        $sql .= " ORDER BY " . $parameters['jtsorting'];
    }
      
    $result = $db->sql_query($sql);
    $result_cont = $db->sql_query($sql_count);

    $rows = $db->sql_fetchrowset($result);
    $count = $db->sql_fetchrow($result_cont);

	for($i=0; $i<sizeof($rows); $i++){
		$params = array();
		$params["question_id"] = $rows[$i]["question_id"];
		$tags = listTagsDataBase($params);
		$rows[$i]["list_tags"] = $tags['tags'];
		$rows[$i]["list_tags_ids"] = $tags['tagsid'];
	}

    $data['rows'] = $rows;
    $data['count'] = $count['count'];
	
    $db->sql_close();
    return $data;
}



function listTagsDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$limit = "";

    $sql = "SELECT 	".$limit."
					tq.tag_id,
 					tq.question_id,
					t.tag		
			FROM 
				[".$db_name."].[dbo].[tag_question] tq,
				[".$db_name."].[dbo].[tag] t
			WHERE 
				tq.question_id = " . $db->sql_escape($parameters["question_id"]) . " AND
				t.id = tq.tag_id ";
	
      
    $result = $db->sql_query($sql);

    $rows = $db->sql_fetchrowset($result);

    $data['rows'] = $rows;
    $data['count'] = count($rows);
	
	$tags_str = "";
	$tagsid_str = "";
	for($i=0; $i<count($rows); $i++){
		if($i>0){
			$tags_str .=",";
			$tagsid_str .=",";	
		}
		$tags_str .= $rows[$i]["tag"]; 
		$tagsid_str .= $rows[$i]["tag_id"]; 
	}
    //$db->sql_close();
	$res = array();
	$res["tags"] = $tags_str;
	$res["tagsid"] = $tagsid_str;
    return $res;
}



//You can create mod_c
function createQuestionDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
        error_log("----------[CREATE]----->".json_encode($parameters));
    $sql = "INSERT INTO [".$db_name."].[dbo].[question] (
			question_es_edit,
			question_en_edit,
                        active,
                        active_edit,
			module_search_questions_id
			)
			VALUES (
			'" . $db->sql_escape($parameters["question_es"]) . "',
			'" . $db->sql_escape($parameters["question_en"]) . "',
                        0,1,
			'" . $db->sql_escape($parameters["module_id"]) . "'
			)";
			

    $result = $db->sql_query($sql);
	
	$parameters["question_id"] = getLastInsertion();
	insertTags($parameters);
    if ($result) {
                $sql = "select content_id,title_es_edit from [".$db_name."].[dbo].[module_search_questions] where id=". $db->sql_escape($parameters["module_id"]);
		$result2 = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result2);
                InsertNotification($row['content_id'],"Se agrego la pregunta <b>". $db->sql_escape($parameters["question_es"]) ." </b> al modulo buscador: ".$row['title_es_edit']);
                $data = array();
		$data["question_id"] = getLastInsertion();
		$data["question_es"] = $parameters["question_es"];
		$data["question_en"] = $parameters["question_en"];
        return $data;
    }
    return false;
}

//You can update specific mod_c
function updateQuestionDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    error_log("----------[UPDATE]----->".json_encode($parameters));
    $sql = "UPDATE [".$db_name."].[dbo].[question]  SET 
					question_es_edit = '" . $db->sql_escape($parameters["question_es"]) . "', 
					question_en_edit = '" . $db->sql_escape($parameters["question_en"]) . "'
			WHERE id = " . $db->sql_escape($parameters["question_id"]) . ";";
	//return $sql;
    $result = $db->sql_query($sql);
	
	insertTags($parameters);
        
        $sql = " select content_id,title_es_edit from [".$db_name."].[dbo].[module_search_questions] mq,[".$db_name."].[dbo].[question] q where  q.id=".$db->sql_escape($parameters["question_id"])." AND mq.id=q.module_search_questions_id";
		$result2 = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result2);
                InsertNotification($row['content_id'],"Se modifico la pregunta : ".$db->sql_escape($parameters["question_es"])." del buscador: ".$row['title_es_edit']);
    
                return $result;
}


//You can delete specific mod_c
function deleteQuestionDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;


    $sql = "UPDATE  [".$db_name."].[dbo].[question] set active_edit=0  WHERE id = " . $db->sql_escape($parameters["question_id"]);

    $result = $db->sql_query($sql);
     $sql = " select mq.content_id,mq.title_es_edit,q.question_es_edit from [".$db_name."].[dbo].[module_search_questions] mq,[".$db_name."].[dbo].[question] q where  q.id=".$db->sql_escape($parameters["question_id"])." AND mq.id=q.module_search_questions_id";
		$result2 = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result2);
                InsertNotification($row['content_id'],"Se elimino la pregunta : ".$row['question_es_edit']." del buscador: ".$row['title_es_edit']);
    
    return ($result);
}



function insertTags($parameters=array()){
	global $db, $db_table_prefix, $db_name;
	
	
    $parameters["list_tags_ids"] = (isset($parameters['list_tags_ids'])) ? $parameters['list_tags_ids'] : "";
	$tags = explode(",", $parameters["list_tags_ids"]);
	
	
	if($parameters["question_id"]!=""){
        $sql = "UPDATE   [".$db_name."].[dbo].[tag_question]  set active_edit=0 WHERE question_id = " . $db->sql_escape($parameters["question_id"]);
		
        $result = $db->sql_query($sql);
        
        for($i=0; $i<count($tags); $i++){
		 $sql = "UPDATE [".$db_name."].[dbo].[tag_question] set active_edit=1 where tag_id=".$db->sql_escape($tags[$i])." AND question_id = ".$db->sql_escape($parameters["question_id"]);	
		$result = $db->sql_query($sql);
	}
        
        
    	$sql = "DELETE FROM  [".$db_name."].[dbo].[tag_question]  WHERE active_edit=0  AND active=0 AND question_id = " . $db->sql_escape($parameters["question_id"]);
		
        $result = $db->sql_query($sql);
	}
	
	for($i=0; $i<count($tags); $i++){
		 $sql = "INSERT INTO [".$db_name."].[dbo].[tag_question] (
				tag_id,
				question_id,
                                active,
                                active_edit
				)
				VALUES (
				'" . $db->sql_escape($tags[$i]) . "',
				'" . $db->sql_escape($parameters["question_id"]) . "',
                                0,1
				)";
				
		$result = $db->sql_query($sql);
	}
}

function InsertNotification($content_id,$msg){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModuleBuscador($parameters = array()){
    global $db,$db_name;
    $content_id = $parameters['content_id'];
    
    $sql = "UPDATE [".$db_name."].[dbo].[module_search_questions] title_en=title_en_edit,title_es=title_es_edit,description=description_edit where content_id=".$content_id;
    $db->sql_query($sql);
    
    $sql = "UPDATE question set question_en=question_en_edit,question_es=question_es_edit from module_search_questions where content_id=".$content_id." and module_search_questions.id=module_search_questions_id";
    $db->sql_query($sql);
    
    $sql = "UPDATE [".$db_name."].[dbo].[tag_question] set tag_question.active=tag_question.active_edit from  question q , module_search_questions mq where mq.content_id=".$content_id." AND q.module_search_questions_id=mq.id AND tag_question.question_id=q.id ";
    $db->sql_query($sql);
    
    $sql = "DELETE FROM [".$db_name."].[dbo].[tag_question] WHERE active=0 AND active_edit=0";
    $db->sql_query($sql);
    
    $sql = "DELETE FROM question where active=0 AND active_edit=0";
    $db->sql_query($sql);
}
function DisapprovedModuleBuscador($parameters = array()){
    global $db,$db_name;
    $content_id = $parameters['content_id'];
    
    $sql = "UPDATE [".$db_name."].[dbo].[module_search_questions] title_en_edit=title_en,title_es_edit=title_es,description_edit=description where content_id=".$content_id;
    $db->sql_query($sql);
    
    $sql = "UPDATE question set question_en_edit=question_en,question_es_edit=question_es from module_search_questions where content_id=".$content_id." and module_search_questions.id=module_search_questions_id";
    $db->sql_query($sql);
    
    $sql = "UPDATE [".$db_name."].[dbo].[tag_question] set tag_question.active_edit=tag_question.active from  question q , module_search_questions mq where mq.content_id=".$content_id." AND q.module_search_questions_id=mq.id AND tag_question.question_id=q.id ";
    $db->sql_query($sql);
    
    $sql = "DELETE FROM [".$db_name."].[dbo].[tag_question] WHERE active=0 AND active_edit=0";
    $db->sql_query($sql);
    
    $sql = "DELETE FROM question where active=0 AND active_edit=0";
    $db->sql_query($sql);
}
?>