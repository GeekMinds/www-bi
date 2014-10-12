<?php

function getTags($parameters){
  global $db, $db_table_prefix;
  $page_id=$parameters['page_id'];
  $tags="";

  $sql=
  "
  SELECT 
    tag 
  FROM 
    tag INNER JOIN page_tag
      ON tag.id= page_tag.tag_id
    INNER JOIN page
      ON page.id=page_tag.page_id
      AND page.id={$page_id}
  ";

  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrowset($result);
  if($result)$tags="";
  foreach ($result as &$tag) {
    $tags .= $tag['tag'].",";
  }
  $tags=rtrim($tags, ",");
  return $tags;
}



function saveTags($parameters){
  global $db, $db_table_prefix;
  $page_id=$parameters['page_id'];
  //Delete existing tags to update
  $sql="DELETE FROM page_tag where page_id={$page_id}"; 
  $db->sql_query($sql);
  $tags=$parameters['tags'];
  $tags = explode(",", $tags);
  foreach ($tags as &$tag){
      if (trim(strlen($tag))>0){
      $tag_id=getTagID($tag);
      $sql="INSERT INTO page_tag(tag_id,page_id) VALUES({$tag_id},{$page_id})";
      $db->sql_query($sql);
      }
  }
  return true;
}

function getTagID($tag){
  global $db, $db_table_prefix;
  $sql="SELECT id FROM tag WHERE tag='".$tag."'";
  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrow($result);
  $id=intval($result['id']);

//Esto es para crear tags y validar si ya existen
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

function getPageName($parameters){
  global $db, $db_table_prefix;
  $page_id=$parameters['page_id'];
  $sql="SELECT title_es as name FROM page where id={$page_id}";
  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrow($result); 
  return $result;
}


?>