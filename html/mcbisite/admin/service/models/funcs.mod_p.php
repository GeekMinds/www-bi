<?php




function getSite(){
  global $site_id;
  return $site_id;
}

function createModuleDB($parameters){
  global $db,$db_table_prefix; 
  $result=false;

  $content_id=$db->sql_escape($parameters['content_id']);
  $name=$db->sql_escape($parameters['name']);
  $description=$db->sql_escape($parameters['description']);
  //mail_responsible
  $mail_responsible=$db->sql_escape($parameters['mail_responsible']);
  $sentencia="INSERT INTO mod_p (nombre_edit,content_id,description_edit,mail_responsible_edit) VALUES ('$name',$content_id,'$description','$mail_responsible')";
  $result=$db->sql_query($sentencia);  
  $sql="SELECT id FROM mod_p WHERE id=@@IDENTITY";
  $result=$db->sql_query($sql); 
  $result=$db->sql_fetchrow($result); 

  //inserta notificacion del insert

    $change_description="Se creo una caja de comentarios <b> (".$mail_responsible.")</b>";
    notificacion_comentario($content_id,$change_description);




  return $result;
}

function updateModuleDB($parameters){
  global $db,$db_table_prefix,$loggedInUser,$db_name; 
  $result=false;

  $name=$db->sql_escape($parameters['name']);
  $description=$db->sql_escape($parameters['description']);
  $mail_responsible=$db->sql_escape($parameters['mail_responsible']);
  $id=$parameters['id'];

  $sentencia="UPDATE mod_p SET "
    ."nombre_edit='{$name}',description_edit='{$description}',mail_responsible_edit='{$mail_responsible}' "
    ."WHERE id=$id"
  ;

   
  
        $sql =" Select nombre from [".$db_name."].[dbo].[".$db_table_prefix."mod_p] where id= ".$id ."" ;

        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrow($result);

        
 //inserta notificacion de actualizacion
    $change_description="Se actualizo una caja de comentarios <b>(".$mail_responsible.")</b>";
    $content_id=$db->sql_escape($parameters['content_id']);
    notificacion_comentario($content_id,$change_description);

    
    $db->sql_query($sql);  
    $result=$db->sql_query($sentencia);

    return $result;    

}

function readModuleDB($parameters){
  global $db,$db_table_prefix; 
  $result=false;
  $id=$parameters['id'];

  $sentencia="SELECT nombre_edit as name, description_edit as description, mail_responsible_edit as mail_responsible FROM mod_p where id={$id}";

  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrow($result);
  return $result; 
}
//SELECT id,title_es as nombre FROM PAGE

function readPages(){
  global $db,$db_table_prefix; 
  $site_id=getSite();
  $result=false;
  $sentencia=
  "
  SELECT 
  page.id,page.title_es as nombre

  FROM
  page INNER JOIN page_content
    ON page_content.page_id=page.id
    AND page.site_id={$site_id}
  INNER JOIN content
    ON page_content.content_id= content.id
    AND content.module_id=18
    
  ORDER BY
  page.id
  ";
  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrowset($result);
  return $result; 
}


function getCommentDB($parameters){
  global $db,$db_table_prefix; 
  $result=false;
  $id=$parameters['id'];
  $sentencia="SELECT value FROM comment WHERE id={$id}";
  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrow($result);
  return $result; 
}



function countComments(){
  global $db,$db_table_prefix; 
  $result=false;
  $site_id=getSite();
  $sentencia=
  "SELECT
    COUNT(comment.id) as [count]
  FROM 
    page p INNER JOIN page_content pc 
      ON p.site_id = {$site_id} AND pc.page_id=p.id
    INNER JOIN mod_p modp 
      ON modp.content_id = pc.content_id
    INNER JOIN comment 
      ON modp.id=comment.mod_p";
  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrow($result);
  return $result['count']; 
}

function updateCommentStateDB($parameters){
  global $db,$db_table_prefix; 
  $result=false;
  $id_comment=$parameters['id'];
  error_log(json_encode($parameters));
  $state=$parameters['state'];
  $site_id=getSite();
  $sentencia="UPDATE comment SET state={$state} WHERE id={$id_comment}";
  $db->sql_query($sentencia);

   $sentencia="SELECT
    comment.id,
    p.title_es pagina,
    modp.nombre modulo,
    modp.mail_responsible as responsable,
    comment.[value] as comentario,
    CONVERT(VARCHAR(19),comment.date_submit) as fecha ,
    CONCAT([user].first_name,' ',[user].last_name) as usuario,
    [user].email,
    comment.state
   FROM 
    page p INNER JOIN page_content pc 
      ON p.site_id = {$site_id} AND pc.page_id=p.id
    INNER JOIN mod_p modp 
      ON modp.content_id = pc.content_id
    INNER JOIN comment 
      ON modp.id=comment.mod_p   AND comment.id={$id_comment}      
    INNER JOIN [user]
      ON comment.user_id=[user].id
  ";
  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrow($result);
  return $result; 
}


function listCommentsDB($parameters){
  global $db,$db_table_prefix; 
  $result=false;

  $size=$parameters["jtpagesize"];
  $site_id=getSite();
  $offset=$parameters["jtstartindex"];
  $pageCondition="";
  $valueContidion="";
  if (isset($parameters["id_page"])){
    $id_page=$parameters["id_page"];
    if(strlen($id_page)>0)
      $pageCondition=" AND p.id={$id_page} ";
  }
  if (isset($parameters["value"])){
    $value=$parameters["value"];
    if(strlen($value)>0)
      $valueContidion=" AND comment.value LIKE '%{$value}%'  ";
  }


  $sentencia="SELECT
    comment.id,
    p.title_es pagina,
    p.id as idPagina,
    modp.nombre modulo,
    modp.mail_responsible as responsable,
    comment.[value] as comentario,
    CONVERT(VARCHAR(19),comment.date_submit) as fecha ,
    CONCAT([user].first_name,' ',[user].last_name) as usuario,
    [user].email,
    comment.state
   FROM 
    page p INNER JOIN page_content pc 
      ON p.site_id ={$site_id} AND pc.page_id=p.id {$pageCondition}
    INNER JOIN mod_p modp 
      ON modp.content_id = pc.content_id
    INNER JOIN comment 
      ON modp.id=comment.mod_p         
    INNER JOIN [user]
      ON comment.user_id=[user].id {$valueContidion}
    ORDER By comment.id
    OFFSET {$offset} ROWS
    FETCH NEXT {$size} ROWS ONLY"
  ;
  $result=$db->sql_query($sentencia);
  $result=$db->sql_fetchrowset($result);
  return $result; 
}



function DisapprovedModuleComentario($parameters){


  global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];

    $sql ="SELECT  id,ISNULL(mail_responsible,0) AS mail_responsible from [".$db_name."].[dbo].[".$db_table_prefix."mod_p] where content_id=".$db->sql_escape($id) ;
       $result_eliminado = $db->sql_query($sql);
       $result_eliminado= $db->sql_fetchrow($result_eliminado);
 
       if ( $result_eliminado["mail_responsible"]=='0'  ){ 
      //desaprobo que se agregara
        $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."mod_p] WHERE id=".$result_eliminado["id"] ;
      }else{

          $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_p] SET nombre_edit = nombre, description_edit = description, mail_responsible_edit=mail_responsible  WHERE id=".$result_eliminado["id"] ;
        
      }
    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;


}


function ApprovedModuleComentario($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];
 
       $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."mod_p] SET nombre = nombre_edit, description = description_edit, mail_responsible=mail_responsible_edit  WHERE content_id=".$id ;

    $data = array();
    if($db->sql_query($sql)){
        $data['Result'] = 'OK';
    }else{
        $data['Result'] = 'ERROR';
    }
    return $data;

}


function notificacion_comentario($id,$change_description ){
  global $db, $db_table_prefix,$loggedInUser;

   $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $id . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$change_description."'";


  $result_procedure = $db->sql_query($sql); 

}


?>