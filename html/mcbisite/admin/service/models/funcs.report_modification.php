<?php

//get all sites

function getSitesDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
        $sql  ="SELECT 0 as id , 'Todos los Sitios' as description UNION ALL ";
            $sql .= " SELECT  id, title_es as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."site] s order by description";

        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all sites option  





//get all pages of site

function getPagesDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 

   $id_site=$db->sql_escape($parameters['id_site']);

        $sql  = " SELECT '000' AS id , 'Todas las paginas' AS description UNION ALL";
            $sql .= " SELECT  id, title_es as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."page] p where site_id=".$id_site." order by description";

        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all pages of site  



//get all pages of site

function getModuleDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 

            $sql  = " SELECT '000' AS id , 'Todos los modulos'  AS description  UNION ALL";
            $sql .= " SELECT  id, description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."module_list] order by description " ;

        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all pages of site  



//get all user admin

function getUserAdminDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 

            $sql  = " SELECT '000' as id , 'Todos los usuarios' as description UNION ALL ";
            $sql .= " SELECT id , name as description";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."admin] order by description " ;

        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all user admin

//________________________


//**********************************************************************//

//get alls item 
function listModificationDataBase($parameters){

    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 's.title_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';

    $num_filter =intval( $db->sql_escape($parameters["filter"]) );
    $site= intval($db->sql_escape($parameters["site"]) );
    $page= intval( $db->sql_escape($parameters["page"]));
    $module=intval( $db->sql_escape($parameters["module"]) );


    $data = array();
    $sql = "";
    $sql_size = "";
    $condition_ext ="";


    if ( $module >0) {
        $condition_ext .=" AND m.id=".$module;
    }

    if ($page>0){
        $condition_ext .=" AND pg.id=".$page;
    }

    if ($site>0){
        $condition_ext .=" AND s.id=".$site;
    }
    


if (strpos($parameters['jtsorting'],'s.')==false ) {


        if (strpos($parameters['jtsorting'],'ASC') ) {

        $parameters['jtsorting']= trim( str_replace("ASC","",  $parameters['jtsorting']  ) ) ;
        $concat_post =' ASC';
         
        }else{

        $parameters['jtsorting']= trim( str_replace("DESC","",  $parameters['jtsorting'] ) ) ;
        $concat_post =' DESC';

        }

}


switch ($parameters['jtsorting']) {
  case 'site_title':

    $parameters['jtsorting']="s.title_es".$concat_post;
  break;

  case 'title_pag':
    $parameters['jtsorting']="pg.title_es".$concat_post;
  break;

  case 'DisplayText':
    $parameters['jtsorting']="m.description".$concat_post;
  break;

  case 'status':
    $parameters['jtsorting']="ap.status".$concat_post;
  break;
   
}

switch ($num_filter) {

case 1:

$date=intval( $db->sql_escape($parameters["date"]) );
$user=intval( $db->sql_escape($parameters["user"]) );


            $condition_ext .="  AND ad.approval_m=ap.id";

      if ($date ==1){
        $condition_ext .=" AND ad.edit_date >= CONVERT(datetime, '". $db->sql_escape($parameters["date_ini"])." 00:00:00' ,103)";
      }
      if ($date ==2){
        $condition_ext .=" AND ad.edit_date BETWEEN CONVERT(datetime, '".$db->sql_escape($parameters["date_ini"])."' ,103)  AND  CONVERT(datetime,  '".$db->sql_escape($parameters["date_fin"])."' ,103)";
      }
      if ($date ==3){
        $condition_ext .=" AND ad.edit_date <=  CONVERT(datetime, '".$db->sql_escape($parameters["date_fin"])." 23:59:59' ,103)";
      }

      if ($user>0){
          $condition_ext .=" AND ad.editor_id=".$user;
      }




break;

case 2:
$date=intval( $db->sql_escape($parameters["date"]) );
$status=intval( $db->sql_escape($parameters["estatus_a_r"]) );
$user=intval( $db->sql_escape($parameters["user"]) );


if ($date ==1){
  $condition_ext .=" AND ap.approval_date >= CONVERT(datetime, '". $db->sql_escape($parameters["date_ini"])." 00:00:00' ,103)";
}
if ($date ==2){
  $condition_ext .=" AND ap.approval_date BETWEEN CONVERT(datetime, '".$db->sql_escape($parameters["date_ini"])."' ,103)  AND  CONVERT(datetime,  '".$db->sql_escape($parameters["date_fin"])."' ,103)";
}
if ($date ==3){
  $condition_ext .=" AND ap.approval_date <=  CONVERT(datetime, '".$db->sql_escape($parameters["date_fin"])." 23:59:59' ,103)";
}

if ($status>0){
    $condition_ext .=" AND ap.status=".$status;
}

if ($user>0){
    $condition_ext .="  AND ap.approved_by=".$user;
}




break;


}


    
    
     

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
               $sql .= "  ap.id Value, m.description DisplayText,m.name module_type, ap.id ,";

                if ($parameters['html']==1){
                     $sql .= "  CONCAT('Se realizaron cambios en un m&oacute;dulo  <b>',m.description, '</b>') message,";
                  }else{
                    $sql .= "  CONCAT('Se realizaron cambios en un m&oacute;dulo ',m.description) message,";
                }

                


               $sql .= "  ap.content_id, ap.approved_by, CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,";
               $sql .= "  pc.page_id,ap.status , pg.title_es as title_pag, s.title_es as site_title"; 
               $sql .= "  FROM  [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."approval_m] ap,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."content] c,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."module_list] m,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."page] pg,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."site] s,";
               $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ad";
               $sql .= "  WHERE";
               $sql .= "  c.id = ap.content_id AND";
               $sql .= "  m.id = c.module_id AND";
               $sql .= "  pc.content_id = c.id  ";
               $sql .= "  AND pg.id=pc.page_id";
               $sql .= "  AND s.id=pg.site_id   ".$condition_ext."";
               $sql .= " GROUP BY ap.id,m.description,m.name , ap.id ,s.title_es,ap.content_id, ap.approved_by,approval_date, pc.page_id,ap.status , pg.title_es";
               $sql .= "  )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{

            $sql = " SELECT";
            $sql .= "  ap.id Value, m.description DisplayText,m.name module_type, ap.id ,";
            
              if ($parameters['html']==1){
                     $sql .= "  CONCAT('Se realizaron cambios en un m&oacute;dulo  <b>',m.description, '</b>') message,";
                  }else{
                    $sql .= "  CONCAT('Se realizaron cambios en un m&oacute;dulo ',m.description) message,";
                }

            $sql .= "  ap.content_id, ap.approved_by, CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,";
            $sql .= "  pc.page_id,ap.status , pg.title_es as title_pag, s.title_es as site_title";
            $sql .= "  FROM  [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."approval_m] ap,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."content] c,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."module_list] m,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."page] pg,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."site] s,";
            $sql .= "  [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ad";
            $sql .= "  WHERE";
            $sql .= "  c.id = ap.content_id AND";
            $sql .= "  m.id = c.module_id AND";
            $sql .= "  pc.content_id = c.id ";
            $sql .= "  AND pg.id=pc.page_id";
            $sql .= "  AND s.id=pg.site_id   ".$condition_ext."";
            $sql .= " GROUP BY ap.id,m.description,m.name , ap.id ,s.title_es,ap.content_id, ap.approved_by,approval_date, pc.page_id,ap.status , pg.title_es";


           }
            
            $sql_size = " SELECT";
            $sql_size .= "  ap.id Value, m.description DisplayText,m.name module_type, ap.id ,";
            $sql_size .= "  CONCAT('Se realizaron cambios en un m&oacute;dulo  <b>',m.description, '</b>') message,";
            $sql_size .= "  ap.content_id, ap.approved_by, CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,";
            $sql_size .= "  pc.page_id,ap.status , pg.title_es as title_pag, s.title_es as site_title";
            $sql_size .= "  FROM  [".$db_name."].[dbo].[".$db_table_prefix."".$db_table_prefix."approval_m] ap,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."content] c,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."module_list] m,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."page] pg,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."site] s,";
            $sql_size .= "  [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ad";
            $sql_size .= "  WHERE";
            $sql_size .= "  c.id = ap.content_id AND";
            $sql_size .= "  m.id = c.module_id AND";
            $sql_size .= "  pc.content_id = c.id ";
            $sql_size .= "  AND pg.id=pc.page_id";
            $sql_size .= "  AND s.id=pg.site_id  ".$condition_ext."";
            $sql_size .= " GROUP BY ap.id,m.description,m.name , ap.id ,s.title_es,ap.content_id, ap.approved_by,approval_date, pc.page_id,ap.status , pg.title_es";


            
//var_dump($sql);
//var_dump($sql_size);
              $result = $db->sql_query($sql);
              $result= $db->sql_fetchrowset($result);

              //retorna el count de items
              $result_count = $db->sql_query($sql_size);
              $result_count= $db->sql_fetchrowset($result_count);

        $data['result'] = $result;
        $data["count"]  = COUNT($result_count);
        $data["random"] =encrypt($sql);


    return $data;



}

//end get alls item 


//You can get the list sites with filter options
function listApprovalDetailDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
  
    $data = array();
  $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'ap.id DESC ';
  $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
  $parameters['approval_id'] = (isset($parameters['approval_id'])) ? $parameters['approval_id'] : '0';    
  $sql = "";
  $sql_size = "";
  if($parameters['jtpagesize'] != ''){
    $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
    $sql = "SELECT * FROM
          (
            SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
                ap.id as detail_id, 
                ap.description DisplayText,
                ap.description detail_description,
                (SELECT name FROM admin WHERE id=ap.editor_id) as name_edit,
                CONVERT(VARCHAR(19), ap.edit_date, 100) edit_date,
                am.approved_by,
                (SELECT name FROM admin WHERE id=am.approved_by) as name_aprovade,
                CONVERT(VARCHAR(19), am.approval_date, 100) aprov_date
                FROM 
                    approval_d ap
                    INNER JOIN approval_m am ON
                    am.id= ap.approval_m
                WHERE
                ap.approval_m = ".$db->sql_escape($parameters['approval_id'])."
              
          )
          AS user_with_numbers
        WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
  }else{
    $sql = "    
               SELECT ap.id as detail_id, 
                ap.description DisplayText,
                ap.description detail_description,
                (SELECT name FROM admin WHERE id=ap.editor_id) as name_edit,
                CONVERT(VARCHAR(19), ap.edit_date, 100) edit_date,
                am.approved_by,
                (SELECT name FROM admin WHERE id=am.approved_by) as name_aprovade,
                CONVERT(VARCHAR(19), am.approval_date, 100) aprov_date
                FROM 
                    [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ap
                    INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."approval_m] am ON
                    am.id= ap.approval_m
                WHERE
                ap.approval_m = ".$db->sql_escape($parameters['approval_id'])."";
  }


  
  $sql_count = "SELECT COUNT(*) total ";
  $sql_count .="  FROM 
                    [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ap
                    INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."approval_m] am ON
                    am.id= ap.approval_m
                WHERE
                ap.approval_m = ".$db->sql_escape($parameters['approval_id']).""; 
  

  //error_log("La consulta>".$sql);
  $result = $db->sql_query($sql);
  $result_count = $db->sql_query($sql_count);

  $result= $db->sql_fetchrowset($result);
  $result_count= $db->sql_fetchrow($result_count);
  //dumpear($result);
  $data["rows"] = $result;
  $data["count"] = $result_count['total'];
  
  return $data;
}


//You can get the list sites with filter options
function listApprovalDetailTotalDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
  
    $data = array();

  $sql = "";
  $sql = "    
               SELECT ap.id as detail_id, 
                ap.approval_m, 
                ap.description detail_description,
                (SELECT name FROM admin WHERE id=ap.editor_id) as name_edit,
                CONVERT(VARCHAR(19), ap.edit_date, 100) edit_date,
                am.approved_by,
                (SELECT name FROM admin WHERE id=am.approved_by) as name_aprovade,
                CONVERT(VARCHAR(19), am.approval_date, 100) aprov_date
                FROM 
                    [".$db_name."].[dbo].[".$db_table_prefix."approval_d] ap
                    INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."approval_m] am ON
                    am.id= ap.approval_m
                WHERE
                ap.approval_m IN( ".$db->sql_escape($parameters['ids'])." )";
  


  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrowset($result);


  $data["result"] = $result;

  

  
  return $data;
}


function encrypt($string) {
$key="milkandcookies";


   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}



?>