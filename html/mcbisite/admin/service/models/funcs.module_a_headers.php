<?php

header('Access-Control-Allow-Origin: *');



function addheadersDB($title_es,$title_en,$link,$site_id,$sequence){
	global $db, $db_table_prefix,$loggedInUser;
	$sql = " INSERT INTO " . $db_table_prefix . "mod_a (
	    title_es_edit,
	    title_en_edit,
            sequence_edit,
            site_id,
	    link_edit
		)"
	    . "VALUES('" .$db->sql_escape($title_es)."'". 
	    ""  .",". 
	    "'" .   $db->sql_escape($title_en) . "',". 
            "'" .   $db->sql_escape($sequence) . "',". 
            "'" .   $db->sql_escape($site_id) . "',". 
	    "'" .   $db->sql_escape($link) . "'".  
	      
	    ")"
	;
	

	$db->sql_query($sql);
	//error_log("Hola".$sql);
	global $db, $db_table_prefix;
	$sql2 = "SELECT id,title_es_edit,title_en_edit,link_edit FROM " . $db_table_prefix . "mod_a " . " WHERE id=@@IDENTITY  ";

	$result = $db->sql_query($sql2);
	$result= $db->sql_fetchrow($result);
	
       $change_description=" Se creo la pestaña <b>".$db->sql_escape($title_es)."</b>";
       notificacion_pestaia($db->sql_escape($result["id"]), $change_description);
	   
       
	return $result;
}

function updateheadersDB($id,$title_es,$title_en,$link,$site_id,$sequence){

	global $db, $db_table_prefix,$loggedInUser;

	$change_description="Se actualizo una pestaña";

	$sql = " UPDATE " . $db_table_prefix . "mod_a SET
		"
	    . "title_es_edit='" .$db->sql_escape($title_es)."'".
	    ""  .",".
	    "title_en_edit='" .   $db->sql_escape($title_en) . "',".
            "site_id_edit='" .   $db->sql_escape($site_id) . "',".
            "sequence_edit='" .   $db->sql_escape($sequence) . "',".  
	    "link_edit='" .   $db->sql_escape($link) . "'".
	   
	    " WHERE id=".$db->sql_escape($id)
	;
	error_log("La consulta para update> " .$sql);
	$result =$db->sql_query($sql);


		notificacion_pestaia( $db->sql_escape($id) , $change_description);

        return $result;

}




function deleteheadersDB($id){
	
	global $db, $db_table_prefix,$db_name,$loggedInUser;
	
	$change_description=" Se elimino una pestaña";
	$sql2 = "UPDATE  [".$db_name."].[dbo].[".$db_table_prefix."mod_a] SET eliminado=1 WHERE id= ".$db->sql_escape($id);

	$result = $db->sql_query($sql2);
	notificacion_pestaia( $db->sql_escape($id)  , $change_description);


	return $result;
}


function getheadersDB($parameters=array()){
	global $db, $db_table_prefix,$db_name,$site_id;
	$data = array();
	$parameters['type'] = (isset($parameters['type'])) ? $parameters['type'] : '';
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
        $parameters['jtpagesize'] = (isset($parameters['jtpagesize'])) ? $parameters['jtpagesize'] : '';
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,title_es_edit as title_es, title_en_edit as title_en ,link_edit as link,sequence_edit as sequence 
				 		FROM [".$db_name."].[dbo].[mod_a]
						WHERE site_id=".$site_id."  and eliminado=0
					)
					AS user_with_numbers 
	 			 WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT id,title_es_edit as title_es, title_en_edit as title_en ,link_edit as link,sequence_edit as sequence FROM [".$db_name."].[dbo].[mod_a] WHERE site_id=".$site_id." and eliminado=0";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[mod_a] WHERE site_id=".$site_id." and eliminado=0";
	
	
	
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);
	$result_count= $db->sql_fetchrow($result_count);
	//dumpear($result);
	$data["result"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}




function dumpear($x){

ob_start();
var_dump($x);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);

}
function countInsert(){
    global $db,$db_name, $site_id;
    $sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[mod_a] WHERE site_id=".$site_id;
    $result_count = $db->sql_query($sql_count);
    $result_count= $db->sql_fetchrow($result_count);
    $data = $result_count['total'];
    
    return $data;
}

function ApprovedModuleA($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['id_change'];
    $sql ="SELECT eliminado, ISNULL(site_id_edit,0) AS site_id_edit from [".$db_name."].[dbo].[".$db_table_prefix."mod_a] where id=".$db->sql_escape($id) ;
       $result_eliminado = $db->sql_query($sql);
       $result_eliminado= $db->sql_fetchrow($result_eliminado);
 
       
     
 		if ( $result_eliminado["eliminado"]=='0' ){


 					if (  $result_eliminado["site_id_edit"]=='0' ){ 
							$sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_a] SET title_es = title_es_edit, title_en = title_en_edit, sequence=sequence_edit, site_id_edit=site_id,link=link_edit  WHERE id=".$db->sql_escape($id) ;
						}else{
							$sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_a] SET title_es = title_es_edit, title_en = title_en_edit, sequence=sequence_edit, site_id=site_id_edit,link=link_edit  WHERE id=".$db->sql_escape($id) ;
						}

		}else{

			if ($result_eliminado["eliminado"]=='1' ){
			$sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_a] WHERE id=".$db->sql_escape($id) ;
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

function DisapprovedModuleA($parameters){


	global $db,$db_table_prefix, $db_name; 
    $id = $parameters['id_change'];

    $sql ="SELECT eliminado, ISNULL(site_id_edit,0) AS site_id_edit from [".$db_name."].[dbo].[".$db_table_prefix."mod_a] where id=".$db->sql_escape($id) ;
       $result_eliminado = $db->sql_query($sql);
       $result_eliminado= $db->sql_fetchrow($result_eliminado);
 
       if ( $result_eliminado["eliminado"]=='0' && $result_eliminado["site_id_edit"]=='0' ){ 
			//desaprobo que se agregara
				$sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_a] WHERE id=".$db->sql_escape($id) ;
			}else{

				if ($result_eliminado["eliminado"]=='1'){
				$sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_a] SET eliminado=0  WHERE id=".$db->sql_escape($id) ;

				}else{

    			$sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_a] SET title_es_edit = title_es, title_en_edit = title_en, sequence_edit=sequence,site_id_edit= site_id,link_edit=link,eliminado=0  WHERE id=".$db->sql_escape($id) ;
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

function notificacion_pestaia($id,$change_description ){
	global $db, $db_table_prefix,$loggedInUser;

	$sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $id . "" 
            . ",@mod_type=1" 
            . ",@description='".$change_description."'"; 

	$result_procedure = $db->sql_query($sql); 

}




