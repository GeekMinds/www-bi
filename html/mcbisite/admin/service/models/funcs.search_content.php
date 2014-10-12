<?php

//add header de content

function createContentSearchDataBase($parameters = array()){
    
 global $db, $db_table_prefix,$db_name;
    $data = array();
    
    $title_es=$db->sql_escape($parameters['title_es']);
    $title_en=$db->sql_escape($parameters['title_en']);
    $description_es_small=$db->sql_escape($parameters['description_es_small']);
    $description_en_small=$db->sql_escape($parameters['description_en_small']);
    $description_es_large=$db->sql_escape($parameters['description_es_large']);
    $description_en_large=$db->sql_escape($parameters['description_en_large']);
    $txt_thumbnail=$db->sql_escape($parameters['txt_thumbnail']);
    $search_id=$db->sql_escape($parameters['search_id']); 
    $names =$db->sql_escape($parameters['names']); 
    $values  =$db->sql_escape($parameters['values']); 
    //no borrar content id
    $content_id=$db->sql_escape($parameters['content_id']); 

        
    $sql = "INSERT  INTO [".$db_name."].[dbo].[".$db_table_prefix."search_content] ";
    $sql .= "(searcher_id,content_id,title_es_edit,title_en_edit,content_text_es_edit,content_text_en_edit,thumbnail_edit,created_at,content_text_large_es_edit,content_text_large_en_edit)";
    $sql .= "OUTPUT Inserted.ID as id";
    $sql .=" VALUES( '{$search_id}','{$content_id}','{$title_es}','{$title_en}','{$description_es_small}','{$description_en_small}','{$txt_thumbnail}',getdate(),'{$description_es_large}','{$description_en_large}' )";         
    $result = $db->sql_query($sql);
    $row= $db->sql_fetchrow($result);

    //parametros 

     $sql = "INSERT  INTO [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] ";
     $sql .=" (search_content_id,parameter_id,value_edit) values ";


        $list_names = explode("|", $names);
        $list_values = explode("|", $values);

        for($i = 0; $i < count($list_names)-1; $i++){
            $list_dates=explode("_", $list_names[$i]);

        $sql .="(".$row['id'].",".$list_dates[2].",'".$list_values[$i]."'),";
            
        }

        $sql =substr($sql,0,-1);
        $result = $db->sql_query($sql);
   
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos ingresados exitósamente";
        $data['general_gallery_id']=$row['id'];


        $sql="SELECT ISNULL( title_es, title_es_edit ) as name_search FROM [".$db_name."].[dbo].[".$db_table_prefix."searcher] WHERE id=".$search_id;
        
        $result_buscador = $db->sql_query($sql);
        $result_buscador = $db->sql_fetchrow($result_buscador);
        $change_description='Se creo el contenido <b>'.$title_es.'</b> para el buscador  <b>'.$result_buscador["name_search"].'</b>';

        notificacion_search_contenido($content_id,$change_description );
        

    } else {
        $data['error'] = "100";
         $data['msj'] = "No se pudieron ingresar los datos";
            }
  

    return $data;

}
//end add  header content

//*********************************************************************//

//update content 


function updateContentSearchDataBase($parameters){


         global $db, $db_table_prefix,$db_name;
    $data = array();

    $sql="";
    
    $title_es=$db->sql_escape($parameters['title_es']);
    $title_en=$db->sql_escape($parameters['title_en']);
    $description_es_small=$db->sql_escape($parameters['description_es_small']);
    $description_en_small=$db->sql_escape($parameters['description_en_small']);
    $description_es_large=$db->sql_escape($parameters['description_es_large']);
    $description_en_large=$db->sql_escape($parameters['description_en_large']);
    $txt_thumbnail=$db->sql_escape($parameters['txt_thumbnail']);
    $search_id=$db->sql_escape($parameters['search_id']); 
    $names =$db->sql_escape($parameters['names']); 
    $values  =$db->sql_escape($parameters['values']); 
    //no borrar content id
    $content_id=$db->sql_escape($parameters['content_id']);
    $module_id =$db->sql_escape($parameters['module_id']);

        
    $sql = "UPDATE   [".$db_name."].[dbo].[".$db_table_prefix."search_content] SET  ";
    $sql .= "title_es_edit='{$title_es}',title_en_edit='{$title_en}',content_text_es_edit='{$description_es_small}'";
    $sql .= ",content_text_en_edit='{$description_en_small}',thumbnail_edit='{$txt_thumbnail}',content_text_large_es_edit='{$description_es_large}', content_text_large_en_edit='{$description_en_large}',edit=1 where content_id='{$content_id}' and searcher_id='{$search_id}'  ;".chr(13);    
    $sql .=" ";
    
    
   // $result = $db->sql_query($sql);
   
  //  $row= $db->sql_fetchrowset($result);

    //parametros 

       $sql_id_search="select parameter_id from [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] where search_content_id='".$module_id."'  ";
       $result = $db->sql_query($sql_id_search);    
       $result = $db->sql_fetchrowset($result);
       
      
     

        $list_names = explode("|", $names);
        $list_values = explode("|", $values);
        $insert=false;

   for($i = 0; $i < count($list_names)-1; $i++){
        $insert=false;
            $list_dates=explode("_", $list_names[$i]);

                 foreach ($result as &$tupla) {
                    if ($list_dates[2]==$tupla['parameter_id']){
                        $insert=true;
                    }
                }

             if ($insert){
                     $sql .= "UPDATE  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] set";
                     $sql .=" value_edit='".$list_values[$i]."',edit=1 where  search_content_id='".$module_id."' and  parameter_id='".$list_dates[2]."';".chr(13);
                     $sql .=" ";

                }else
                {
                    $sql .= "INSERT  INTO [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] ";
                    $sql .=" (search_content_id,parameter_id,value_edit) values ";
                    $sql .="(".$module_id.",".$list_dates[2].",'".$list_values[$i]."');".chr(13);
                    $sql .=" ";


                }

    }



    $result = $db->sql_query($sql);

   if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";


        $sql =" SELECT  ISNULL( s.title_es, s.title_es_edit ) as name_search, ISNULL(sc.title_es,sc.title_es_edit) as name_content  FROM ";
        $sql .=" [".$db_name."].[dbo].[".$db_table_prefix."searcher]  s ";
        $sql .=" inner join search_content sc on sc.searcher_id=s.id ";
        $sql .=" WHERE s.id=".$search_id." and sc.content_id=".$content_id." ";
        

        $result_buscador = $db->sql_query($sql);
        $result_buscador = $db->sql_fetchrow($result_buscador);
        $change_description='Se actualizó el contenido <b>'.$result_buscador["name_content"].'</b>  del buscador <b>'.$result_buscador["name_search"].'</b>';

        notificacion_search_contenido($content_id,$change_description );


    } else {
        $data['error'] = "100";
        //$data['msj'] = "No se pudieron actualizar los datos";
        $data['msj']="Problemas al actualizar";
    }

    return $data;
  

}




//end update content


//*********************************************************************//


//get values of contente 

function getContentSearchDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $data = array();
    $id= $db->sql_escape($parameters["content_id"]);

    $sql = "SELECT  id,searcher_id,title_en_edit as title_en,title_es_edit as title_es,content_text_es_edit as  content_text_es,content_text_en_edit as content_text_en,content_text_large_en_edit as content_text_large_en,content_text_large_es_edit as content_text_large_es,thumbnail_edit as thumbnail   FROM [".$db_name."].[dbo].[".$db_table_prefix."search_content] WHERE content_id = ".$id;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;
    return $data;
}


//end get values of content 




//add tags to items

function getAllSearchDataBase($parameters) {
     global $db, $db_table_prefix,$db_name;
    //$module_id=$parameters['module_id'];

    $data = array();
    $sql ="select distinct s.id, s.title_es_edit as title_es from [".$db_name."].[dbo].[".$db_table_prefix."searcher] s";
    $sql .=" inner join search_parameter sp on sp.search_id=s.id";

   $result = $db->sql_query($sql);
   $result = $db->sql_fetchrowset($result);

     
    return $result;

    
}
//end tags to items







        function getcontentDataBase($parameters){
                 global $db, $db_table_prefix,$db_name, $site_id;
                 $contador=0;

            $html="";


        $html .="<div id='div_parameter'    class='contenedorFormulariofx'>";
     
   
          $id=$db->sql_escape($parameters['id']);
        $sql = "SELECT id,parameters_type_id_edit  , title_es_edit ,title_en_edit ,description_edit  
        FROM 
            [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] 
            WHERE search_id=".$id."";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);


        $html.="<div id='input_container'>";
        foreach ($row as $fila) {


            if ($contador==0){
                  $html .=" <h3> Parámetros</h3>";    
            }

            $title =  $fila["title_es_edit"] ;
            $tipo=$fila["parameters_type_id_edit"];
            $search_id=$parameters["id"];
            $parameter_id=$fila["id"];
            $id=$tipo."_".$search_id."_".$parameter_id."_";
            switch ((int)$fila["parameters_type_id_edit"]) {
                case '1':
                   

                    $html .="<div class='control-group'>                   
                        <label class='control-label' >".$title."*</label>
                        <div class='controls'>
                     

                                 <input type='text' id='I".$id."1' class='datepic' placeholder='dd/mm/yyyy'/><span class='error' > Fecha invalida.(mm/dd/yyyy)
</span>
                           
                        </div>
                    </div>";
                    break;
  
                case '2':
              

                    $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                  

                               <input type='text' id='I".$id."1' class='esflotante' placeholder='0.00'/>
                           
                        </div>
                    </div>";


                    break;

                case '3':


                     $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                            <input type='text' id='I".$id."1' placeholder='Cualquiera'/>
                        </div>
                    </div>";

                   
                    break;

                case '4':
                   $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                            <input type='text' id='I".$id."1' placeholder='".$title."'/>
                        </div>
                    </div>";
                    break;
                
                default:
                    //var_dump("es fecha");
                    break;
            }
            ++$contador;
        }


        if ($contador==0){
         $html .="<center> Seleccionar una opción valida </center>";            
        }

        $html .="<br>";
        $html .="</div>";
        $html.="</div>";
        $html.="<script src='../../js/formulas.js'>
                  </script>
                <script>
                        $(function() {
                            $('.datepic').datepicker({
                                dateFormat: 'dd/mm/yy',
                                inline: true
                            });

                        });
                        </script>";

         
              echo $html;


    }



           function getcontentEditDataBase($parameters){
                 global $db, $db_table_prefix,$db_name, $site_id;
                 $contador=0;
                 $id=$db->sql_escape($parameters['search_content_id']);
                 $search_id=$db->sql_escape($parameters['searcher_content_id']);


        $html="";
        $html .="<div id='div_parameter'    class='contenedorFormulariofx'>";
     
   
        
        $sql ="";  
        $sql .= "SELECT  sp.id,sp.parameters_type_id_edit, sp.title_es_edit,sp.title_en_edit,sp. description_edit ,spc.value_edit"; 
        $sql .= "  FROM     [".$db_name."].[dbo].[".$db_table_prefix."search_parameter] sp ";
        $sql .= " LEFT JOIN  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] spc on";
        $sql .= " spc.parameter_id=sp.id and spc.search_content_id=".$id;
        $sql .= " where sp.search_id=".$search_id;
        

        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);
        $html.="<div id='input_container'>";
        
        foreach ($row as $fila) {


            if ($contador==0){
                  $html .=" <h3> Parámetros</h3>";    
            }

            $title = $fila["title_es_edit"] ;
            
            $tipo=$fila["parameters_type_id_edit"];
            $value_input =$fila["value_edit"];



            $search_id=$parameters["id"];
            $parameter_id=$fila["id"];
            $id=$tipo."_".$search_id."_".$parameter_id."_";
            switch ((int)$fila["parameters_type_id_edit"]) {
                case '1':
                   

                    $html .="<div class='control-group'>                   
                        <label class='control-label' >".$title."*</label>
                        <div class='controls'>
                     

                                 <input type='text' id='I".$id."1' class='datepic' placeholder='dd/mm/yyyy' value=".$value_input."><span class='error' > Fecha invalida.(mm/dd/yyyy)
</span>
                           
                        </div>
                    </div>";
                    break;
  
                case '2':
              

                    $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                  

                               <input type='text' id='I".$id."1' class='esflotante' placeholder='0.00' value='".$value_input."' >
                           
                        </div>
                    </div>";


                    break;

                case '3':


                     $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                            <input type='text' id='I".$id."1' placeholder='Cualquiera' value='".$value_input."' />
                        </div>
                    </div>";

                   
                    break;

                case '4':
                   $html .="<div class='control-group'>                   
                        <label class='control-label'>".$title."*</label>
                        <div class='controls'>
                            <input type='text' id='I".$id."1' placeholder='".$title."' value='".$value_input."'/>
                        </div>
                    </div>";
                    break;
                
                default:
                    //var_dump("es fecha");
                    break;
            }
            ++$contador;
        }


        if ($contador==0){
         $html .="<center> Seleccionar una opción valida </center>";            
        }

        $html .="<br>";
        $html .="</div>";
        $html.="</div>";
        $html.="<script src='../../js/formulas.js'>
                  </script>
                <script>
                        $(function() {
                            $('.datepic').datepicker({
                                dateFormat: 'dd/mm/yy',
                                inline: true
                            });

                        });
                        </script>";

                               

               echo $html;


    }





function notificacion_search_contenido($id,$change_description ){
  global $db, $db_table_prefix,$loggedInUser;

   $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $id . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$change_description."'";


  $result_procedure = $db->sql_query($sql); 

}



function ApprovedModuleSearchContent($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];
    $sql ="SELECT id, ISNULL(title_es,'0') AS sear_name, edit from [".$db_name."].[dbo].[".$db_table_prefix."search_content] where content_id=".$db->sql_escape($id) ;


       $result_search = $db->sql_query($sql);
       $result_search= $db->sql_fetchrow($result_search);

       $sql="";
              if (  $result_search["sear_name"]=='0' ){ 
                   $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_content] SET  title_es=title_es_edit , title_en=title_en_edit, content_text_es=content_text_es_edit, content_text_en=content_text_en_edit, thumbnail=thumbnail_edit,  content_text_large_es=content_text_large_es_edit , content_text_large_en=content_text_large_en_edit  , edit=0  WHERE id=".$result_search["id"]." ".chr(13) ;
                 }


              if (  $result_search["edit"]=='1' )  {
                   $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_content] SET  title_es=title_es_edit , title_en=title_en_edit, content_text_es=content_text_es_edit, content_text_en=content_text_en_edit, thumbnail=thumbnail_edit,  content_text_large_es=content_text_large_es_edit , content_text_large_en=content_text_large_en_edit   , edit=0  WHERE id=".$result_search["id"]." ".chr(13) ;
                 }



//va a consultar parametros para el buscador
     $sql_item="SELECT id, edit ,ISNULL(value,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] WHERE search_content_id=".$result_search["id"];   

         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);

    
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //aprobo que se agregara
                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] SET value = value_edit ,edit=0  WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  
                    if ($tupla["edit"]=='1'){

                         $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] SET value = value_edit ,edit=0  WHERE id=".$tupla["id"]." ".chr(13) ;
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

function DisapprovedModuleSearchContent($parameters){


    global $db,$db_table_prefix, $db_name; 
    $id = $parameters['content_id'];


//va a consultar datos de la gallery
    $sql ="SELECT id, ISNULL(title_es,'0') AS sear_name,edit from [".$db_name."].[dbo].[".$db_table_prefix."search_content] WHERE content_id=".$db->sql_escape($id) ;
       $result_search = $db->sql_query($sql);
       $result_search= $db->sql_fetchrow($result_search);


       $sql="";
 
       if ( $result_search["sear_name"]=='0' ){ 
            //desaprobo que se agregara
                $sql = "DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_content] WHERE id=".$result_search["id"]." ".chr(13) ;
            }else{

              
                    if ($result_search["edit"]=='1'){

                $sql = "UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_content] SET    title_es_edit=title_es , title_en_edit=title_en, content_text_es_edit=content_text_es, content_text_en_edit=content_text_en, thumbnail_edit=thumbnail,  content_text_large_es_edit=content_text_large_es , content_text_large_en_edit=content_text_large_en  ,edit=0  WHERE id=".$result_search["id"]." ".chr(13) ;

                    }
                
        }

         

//va a consultar items de la gallery 
     $sql_item="SELECT id, edit ,ISNULL(value,'0') AS item_name FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] WHERE search_content_id=".$result_search["id"];   


         //consulta los items
    $result_items = $db->sql_query($sql_item);
    $result_items = $db->sql_fetchrowset($result_items);

    //agregar los tags a cada item
     foreach ($result_items as &$tupla) {
        

                    if ($tupla["item_name"]=='0'){
                        //desaprobo que se agregara
                        $sql .= " DELETE FROM  [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] WHERE id=".$tupla["id"]." ".chr(13) ;
                    }
  

                    if ($tupla["edit"]=='1'){

                        $sql .= " UPDATE [".$db_name."].[dbo].[".$db_table_prefix."search_parameter_content] SET value_edit = value ,edit=0  WHERE id=".$tupla["id"]." ".chr(13) ;
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





?>





