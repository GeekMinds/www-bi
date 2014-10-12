<?php

//get all tag

function getTagDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
        $sql  ="SELECT 0 as id , 'Todos los tag' as description UNION ALL ";
            $sql .= " SELECT  id, tag as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag]  order by description";


        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all tag option  



//You can get the list tags
function listCountDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;




  
    $data = array();
  $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'tag DESC ';
  $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
  $parameters['approval_id'] = (isset($parameters['approval_id'])) ? $parameters['approval_id'] : '0';    
  $id=intval( $db->sql_escape($parameters["id"]) );


  $sql = "";
  $sql_count = "";
  $condicion_extra="";
  $orde_by="";


  		if (strpos($parameters['jtsorting'],'tag')==false ) {


        if (strpos($parameters['jtsorting'],'ASC') ) {

        $parameters['jtsorting']= trim( str_replace("ASC","",  $parameters['jtsorting']  ) ) ;
        $concat_post =' ASC';
         
        }else{

        $parameters['jtsorting']= trim( str_replace("DESC","",  $parameters['jtsorting'] ) ) ;
        $concat_post =' DESC';

        }

}


switch ($parameters['jtsorting']) {
  case 'total_paginas':

    $parameters['jtsorting']="tag";
    $orde_by=" ORDER BY total_paginas".$concat_post;
  break;

  case 'total_imagenes':
    $parameters['jtsorting']="tag";
    $orde_by=" ORDER BY total_imagenes".$concat_post;
  break;

  case 'total_user':
    $parameters['jtsorting']="tag";
    $orde_by=" ORDER BY total_user".$concat_post;
  break;

  case 'total_interest':
    $parameters['jtsorting']="tag";
    $orde_by=" ORDER BY total_interest".$concat_post;
  break;
   
}





		if ($id==0){
				$condicion_extra="";
			}else{
				$condicion_extra="  where t.id=".$id;
		}


  if($parameters['jtpagesize'] != ''){
    $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
    $sql = " SELECT * FROM ( SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
    $sql .=" t.id,t.tag ,";                
    $sql .=" (select count(1) from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."page_tag] pt1 where pt1.tag_id=t.id) as 'total_paginas',";
    $sql .=" (select count(1) from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag_carrousel_content] tcc where tcc.id_tag=t.id) as 'total_imagenes',";
    $sql .=" ISNULL( (select total_user from ( ";
    $sql .=" (select  ti1.tag_id as ID, count(1) as 'total_user' ";
    $sql .=" from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."user_interest] ui ";
    $sql .=" left join [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag_interest] ti1 on ";
    $sql .=" ui.interest_id=ti1.interest_id  ";
    $sql .=" group by ti1.tag_id)";
    $sql .=" ) AS temp where ID=t.id),0) as 'total_user',";
    $sql .=" (select count(1) from  [tag_interest] ti where ti.tag_id=t.id ) as 'total_interest'";
    $sql .=" from  [tag] t ";
    $sql .=" ".$condicion_extra."";
    $sql .=" )";         
    $sql .=" AS user_with_numbers";
    $sql .=" WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."  ";
    $sql .= $orde_by; 

  }else{
    
    $sql ="";
    $sql .="  SELECT t.id,t.tag ,";                
    $sql .=" (select count(1) from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."page_tag] pt1 where pt1.tag_id=t.id) as 'total_paginas',";
    $sql .=" (select count(1) from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag_carrousel_content] tcc where tcc.id_tag=t.id) as 'total_imagenes',";
    $sql .=" ISNULL( (select total_user from ( ";
    $sql .=" (select  ti1.tag_id as ID, count(1) as 'total_user' ";
    $sql .=" from [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."user_interest] ui ";
    $sql .=" left join [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag_interest] ti1 on ";
    $sql .=" ui.interest_id=ti1.interest_id  ";
    $sql .=" group by ti1.tag_id)";
    $sql .=" ) AS temp where ID=t.id),0) as 'total_user',";
    $sql .=" (select count(1) from  [tag_interest] ti where ti.tag_id=t.id ) as 'total_interest'";
    $sql .=" from  [tag] t ";
    $sql .=" ".$condicion_extra."  ";   
    $sql .= $orde_by;
               
  }

  $sql_count = " ";
  $sql_count .="  SELECT COUNT(1) as total FROM  [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."tag] ".$condicion_extra." ";                


  //var_dump($sql_count);
  //var_dump($sql);


  $result = $db->sql_query($sql);
  $result_count = $db->sql_query($sql_count);

  $result= $db->sql_fetchrowset($result);
  $result_count= $db->sql_fetchrow($result_count);
  //dumpear($result);
  $data["result"] = $result;
  $data["count"] = $result_count['total'];
  
  return $data;
  
  
}





?>