<?php

//You can create mod_c
function createModFormDataBase($parameters = array()) {
    global $db, $db_table_prefix;

    $form_field = $parameters['form_field'];
    $content_id = $parameters['content_id'];
    $form_mail = $parameters['form_mail'];
    $module_id = $parameters['module_id'];
    $form_name_es = $parameters['form_name_es'];
    $form_name_en = $parameters['form_name_en'];
    $crm_id = $parameters['crm_id'];
    $fields = $form_field['fields'];
    

    if($module_id == '' || $module_id == 'undefined' || $module_id == '-1'){
        $module_id = insertNewFormModule($parameters);
        
    }
    $version = pushVersion($parameters['content_id']);
    updateNameForm($form_name_es,$form_name_en,$module_id);
    updateAdressForm($form_mail,$module_id);
	if($crm_id!=""){
		updateCRM($crm_id, $module_id);	
	}
    
    for($i=0 ; $i < count($fields) ; $i++){

    	$field = $fields[$i];

        $id = $field['cid'];
    	$name = $field['label'];
        $name_en = $field['label_en'];
        $crm_field_id = (isset($field['crm_field_id'])) ? $field['crm_field_id'] : "";
        if(strlen(trim($name_en))>0){
            
        }
        else
        {
            $name_en = $field['label'];
        }
    	$field_type = $field['field_type'];
        $getvar = $field['getvar'];
    	$required = $field['required'];
    	$required = str_replace('true', '1', $required);
    	$required = str_replace('false', '0', $required);
    	$field_options = $field['field_options'];
        
		$minimo = (isset($field['minlength'])) ? $field['minlength'] : '0';
		$maximo = (isset($field['maxlength'])) ? $field['maxlength'] : '0';
           //$hidden = (isset($field['hidden'])) ? $field['hidden'] : '0';
        $hidden = $field['hidden'];
        $hidden = str_replace('true', '1', $hidden);
        $hidden = str_replace('false', '0', $hidden);
        
        $sql = '';
        $sql_field_option = '';
        
        error_log('ctype_digit   ---->>  ' . ctype_digit($id));
//        if(!ctype_digit($id)){ 
        //if($id == ''){
            error_log('INS -------->' . $id);
            $sql = "INSERT INTO field (
            name,
            name_en,
            required, 
            field_type_id, 
            form_id,
            enabled,
            sequence,
            mini,
            maxi,
            hidden,
            getvar,
            crm_field_id,
            ver
            )
            VALUES (
            '" . $db->sql_escape($name) ."',
            '" . $db->sql_escape($name_en) ."',
            '" . $db->sql_escape($required) . "', 
            '" . getFieldTypeId($field_type) . "', 
            '" . $db->sql_escape($module_id) . "',
            '1',
            '".($i+1)."',
            '". $db->sql_escape($minimo) ."',
            '". $db->sql_escape($maximo) ."',
            '". $db->sql_escape($hidden) ."',
            '". $db->sql_escape($getvar) ."',
            '". $db->sql_escape($crm_field_id) ."',
            ".$db->sql_escape($version)."
            )";

            $result = $db->sql_query($sql);
            $last = getLastInsertionModForm();
            
            if($field_type === 'radio'  || $field_type=== 'checkboxes' || $field_type=== 'dropdown'  ){
                
                $options = $field_options['options'];
                for($j=0 ; $j < count($options) ; $j++){
                    
                    
                    $option = $options[$j];
                    $checked = $option['checked'];
                    $label = $option['label'];
                    $label_en = $option['label_en'];
                    if($checked == false){
                        $checked = '0';
                    }else{
                        $checked = '1';
                    }
                    
                      $sql_field_option = "INSERT INTO option_field (
                            name,
                            checked,
                            id_field
                            )
                            VALUES (
                            '" . $label . "',
                            '" . $checked. "',
                            '" . $last. "'    
                            )";
                    $result = $db->sql_query($sql_field_option);
                }/*
                if(isset ($field_options['include_other_option']) & $field_options['include_other_option']==true)
                    {
                     $sql_field_option = "INSERT INTO option_field (
                            name,
                            checked,
                            id_field
                            )
                            VALUES (
                            'other',
                            '".$checked."',
                            '" . $last. "'    
                            )";
                    $result = $db->sql_query($sql_field_option);   
                    }
                    if(isset ($field_options['include_blank_option']) & $field_options['include_blank_option']==true)
                    {
                      $sql_field_option = "INSERT INTO option_field (
                            name,
                            checked,
                            id_field
                            )
                            VALUES (
                            ' ',
                            '".$checked."',
                            '" . $last. "'    
                            )";
                    $result = $db->sql_query($sql_field_option);     
                    }*/
            }

//        }else{
//
//            error_log('UP ------->>' . $id);
//            $sql = "UPDATE field SET 
//              name = '" . $db->sql_escape($name) . "',
//              name_en = '" . $db->sql_escape($name_en) . "',
//              required = '" . $db->sql_escape($required ) . "', 
//              field_type_id = '" . getFieldTypeId($field_type) . "' , 
//              form_id = '" . $db->sql_escape($module_id) . "' ,
//              sequence = '".($i+1). "',
//              mini = '".$minimo."',
//              maxi = '".$maximo."',
//              hidden = '".$hidden."',
//              getvar= '".$crm_field_id."',
//              crm_field_id= '".$crm_field_id."',
//              enabled = '1' WHERE id = '" . $db->sql_escape($id) . "';";
//
//              $result = $db->sql_query($sql);
//              $last = getLastInsertionModForm();
//
//              if($field_type === 'radio' || $field_type=== 'checkboxes' || $field_type=== 'dropdown'){
//                  //LIMPIAR VIEJOS VALORES SOLO EN CAMPOS QUE SE ESTAN ACTUALIZANDO UDAPTE SI NO HAY DATOS SOBRE ELLOS
//                $sql_field_clear = "DELETE FROM option_field where id_field=".$id;
//                      $result = $db->sql_query($sql_field_clear);
//                $options = $field_options['options'];
//                for($j=0 ; $j < count($options) ; $j++){
//                    $option = $options[$j];
//                    $checked = $option['checked'];
//                    $label = $option['label'];
//                    $label_en = $option['label_en'];
//
//                    if($checked == false){
//                        $checked = '0';
//                    }else{
//                        $checked = '1';
//                    }
//                      
//                      //INSERTAR VALORES
//                      $sql_field_option = "INSERT INTO option_field (
//                            name,
//                            checked,
//                            id_field
//                            )
//                            VALUES (
//                            '" . $label . "',
//                            '" . $checked. "',
//                            '" . $id. "'    
//                            )";
//                    $result = $db->sql_query($sql_field_option);
//                }/*
//                if(isset ($field_options['include_other_option']) & $field_options['include_other_option']==true)
//                    {
//                     $sql_field_option = "INSERT INTO option_field (
//                            name,
//                            checked,
//                            id_field
//                            )
//                            VALUES (
//                            'other',
//                            '".$checked."',
//                            '" . $last. "'    
//                            )";
//                    $result = $db->sql_query($sql_field_option);   
//                    }
//                    if(isset ($field_options['include_blank_option']) & $field_options['include_blank_option']==true)
//                    {
//                      $sql_field_option = "INSERT INTO option_field (
//                            name,
//                            checked,
//                            id_field
//                            )
//                            VALUES (
//                            ' ',
//                            '".$checked."',
//                            '" . $last. "'    
//                            )";
//                    $result = $db->sql_query($sql_field_option);     
//                    }*/
//                    
//            }
//
//
//        }

        //$result = $db->sql_query($sql);
    }


    EditedContent($content_id,"Se ha modificado el formulario ".$form_name_es);
    return $module_id;
}


function deleteFields($parameters = array()){
    global $db, $db_table_prefix;

    error_log('deleteFields  ><><> ');
    $module_id = $parameters['module_id'];

    $sql = 'DELETE FROM field WHERE enabled = 0 AND form_id = ' . $module_id . ' ;';

    error_log("DEL ---->>>>>> " . $sql);
    $result = $db->sql_query($sql);

    $sql = 'UPDATE field SET enabled = 0 WHERE form_id = ' . $module_id . ';';
    error_log('UP ---->>>>>>> ' . $sql);

    $result = $db->sql_query($sql);
}

function insertNewFormModule($parameters = array()){
	global $db, $db_table_prefix;
	$parameters['crm_id'] = (isset($parameters['crm_id'])) ? $parameters['crm_id'] : "";
	$sql = "INSERT INTO " . $db_table_prefix . "module_form (
				adress_edit,
                                adress,
                name_en_edit,
                name_en,
                name_es_edit,
                name_es,
				content_id,
				created_at,
				crm_id,
                                crm_id_edit,
                                ver,
                                ver_edit
		)
		VALUES ('" . $db->sql_escape($parameters['form_mail']) . "', 
                                '" . $db->sql_escape($parameters['form_mail']) . "', 
				'" . $db->sql_escape($parameters['form_name_en']) . "',
                                '" . $db->sql_escape($parameters['form_name_en']) . "',
				'" . $db->sql_escape($parameters['form_name_es']) . "',
                                '" . $db->sql_escape($parameters['form_name_es']) . "',
				'" . $db->sql_escape($parameters['content_id']) . "',
				CURRENT_TIMESTAMP ,
				'" . $db->sql_escape($parameters['crm_id']) . "',
                                '" . $db->sql_escape($parameters['crm_id']) . "',
                                0,
                                0
		)";

	$result = $db->sql_query($sql);
	$last_intertion = getLastInsertionModForm();
	return $last_intertion;
}




//function that gets the structure of a form created
function getFormDataBase($parameters=array())
{
  global $db,$db_table_prefix; 
  $data = array();
  $content_id = $parameters['content_id'];
  $module_id = $parameters['module_id'];
  $sql = "SELECT  mod.name_es_edit module_name_es,mod.name_en_edit module_name_en,mod.adress_edit module_mail,
  				  f.mini minlength,f.maxi maxlength, REPLACE(REPLACE(f.hidden, 1, 'true') ,0,'false') hidden,
				  f.getvar getvar,f.postvar  , f.id cid, f.name label,f.name_en label_en, 
				  REPLACE(REPLACE(f.required, 1, 'true') ,0,'false') required, ftype.name field_type,
				  f.crm_field_id crm_field_id
        FROM field f, field_type as ftype, module_form mod   
        WHERE f.field_type_id = ftype.id 
        AND f.ver = ".$db->sql_escape(getEditVersion($content_id))."
        AND mod.id = f.form_id
        AND form_id = " . $module_id."order by f.sequence ASC" ;

    if($module_id != ""){
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);
      
        $data = $row;
    }else{
        $data = false;
    }

  return $data;
}

//You can getOptions


//You can getLastInsertion
function getLastInsertionModForm() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	
    return $row['last_intertion'];
}


function getCRMID($id){
	global $db, $db_table_prefix;
	$sql = "SELECT crm_id_edit FROM module_form WHERE id  = '" . $id . "'";
	$result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);

	return $db->sql_escape($row['crm_id']);
}

function getFieldTypeId($field_type_name){
	global $db, $db_table_prefix;
	$sql = "SELECT id FROM field_type WHERE name  = '" . $field_type_name . "'";
	$result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);

	error_log('getFieldTypeId ---> ' . $row['id']);
	return $db->sql_escape($row['id']);
}
function updateNameForm($name_es,$name_en,$id)
{     global $db;
     $sql =  "Update module_form set name_es_edit='".$name_es."', name_en_edit='".$name_en."' where id =".$id;
     $result = $db->sql_query($sql);
}
function updateAdressForm($adress,$id)
{     global $db;
     $sql =  "Update module_form set adress_edit='".$adress."' where id =".$id;
     $result = $db->sql_query($sql);
}

function updateCRM($crm_id,$id)
{     global $db;
     $sql =  "Update module_form set crm_id_edit='".$crm_id."' where id =".$id;
     $result = $db->sql_query($sql);
}
function getOptionsDataBase($parameters = array())
{
 global $db,$db_table_prefix; 
    $data = array();
    $idField = $parameters['field_id'];
    $sql = "Select name,checked from option_field where id_field=".$idField;
    if($idField != ""){
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrowset($result);
      
        $data = $row;
    }else{
        $data = false;
    }
    
    return $data;

}
function loadXML($params = array()){
    require_once ('crm_parser.php');
    require_once ('lib/nusoap.php');
    $p = new crm_xml_parse();
    $varTT = $params['codigo']; //9490;
    $pol = '<EstructuraProceso xmlns="e_solutions.e_manager.WSGestion"><strXMLInput><![CDATA[<CRM.INPUT><session-fecha>2007/07/31</session-fecha><session-id.empleado>0</session-id.empleado> <session-id.sistema>25</session-id.sistema><TT>31</TT><empresa>-1</empresa> <grupo_proceso>-1</grupo_proceso><tipo_proceso>-1</tipo_proceso><proceso>'.$varTT.'</proceso> <parametro_proceso>-1</parametro_proceso><consulta_libre>1</consulta_libre></CRM.INPUT>]]></strXMLInput></EstructuraProceso>';
    $head0  = "POST /~ws/WsGestion/Gestion.asmx HTTP/1.1 Host: 10.10.14.167 User-Agent: NuSOAP/0.9.5 (1.123) Content-Type: text/xml; charset=utf-8 SOAPAction: \"e_solutions.e_manager.WSGestion/EstructuraProceso\"";
    $serv   = "http://10.10.14.167/~ws/WsGestion/Gestion.asmx";
    $cliente = new nusoap_client($serv,false);
    $err = $cliente->getError();
        if ($err)
        { 
        	$resNo = 98;
        	$resDes = $err;
                $toEncode['Query'] = $resNo;
                echo json_encode($toEncode);
                die();
        }
        else 
        {
            $resultado = $cliente->call("e_solutions.e_manager.WSGestion/EstructuraProceso",$pol,"CorpBI","e_solutions.e_manager.WSGestion/EstructuraProceso",$head0,null,"document");
            if ($cliente->fault) 
            {
                $toEncode['numero']  = 97;
                $toEncode['resultado_query'] = $resultado; 
                $toEncode['Query'] = $resNo;
                return $toEncode;
                //die();
            }
            else 
            {
                $err = $cliente->getError();
                if ($err) 
                {
                    $toEncode['numero'] = 96; 
                    $toEncode['error'] = $err; 
                    return $toEncode;
                }
                else 
                {
                    $toEncode =$p->parse($resultado['EstructuraProcesoResult']);      
                }
            }
        }
    //$toEncode['codigo'] = $params['codigo'];
    $toEncode['r'] = $toEncode;
    return $toEncode;
}
function pushVersion($content_id){
 global $db, $db_table_prefix;
    
 $sql= "select ver_edit as VER from module_form where content_id=". $db->sql_escape($content_id);
 $result = $db->sql_query($sql);
 $row = $db->sql_fetchrow($result);
 $number = intval($row['VER']);
 $number += 1;
 $sql = "update module_form set ver_edit=". $db->sql_escape($number)." where content_id=". $db->sql_escape($content_id);
 $db->sql_query($sql);
 return $number;
}
function getEditVersion($content_id){
    global $db;
  $sql= "select ver_edit as VER from module_form where content_id=". $db->sql_escape($content_id);
 $result = $db->sql_query($sql);
 $row = $db->sql_fetchrow($result);
 $number = intval($row['VER']); 
 return $number;
}
function getApprovalVersion($content_id){
    global $db;
    $sql= "select ver as VER from module_form where content_id=". $db->sql_escape($content_id);
 $result = $db->sql_query($sql);
 $row = $db->sql_fetchrow($result);
 $number = intval($row['VER']);
 return $number;
}
function ApprovedModuleForm ($parameters = array()){
    global $db;
    $content_id = $parameters['content_id'];
    $versionAprobada=  getApprovalVersion($content_id);
    $versionEdit = getEditVersion($content_id);
    for($i=$versionAprobada;$i<$versionEdit;$i++){
        //Borro los options
    $sql = "delete option_field where id in (Select opf.id from module_form mf,field f,option_field opf where mf.content_id = ".$content_id." and f.form_id=mf.id and f.ver=".$i." and f.id=opf.id_field)";
    $db->sql_query($sql);
    //Borro los Fields
    $sql = "delete from  field where id in (Select f.id from module_form mf,field f where mf.content_id = ".$content_id." and f.form_id=mf.id and f.ver=".$i.")";
    $db->sql_query($sql);  
    }
        
    //Ahora Actualizo la version
    $sql = "update module_form set name_es=name_es_edit, adress=adress_edit,name_en=name_en_edit,crm_id=crm_id_edit,ver=ver_edit where content_id=". $db->sql_escape($content_id);
    $db->sql_query($sql);
} 
function DisapprovedModuleForm ($parameters = array()){
    global $db;
    $content_id = $parameters['content_id'];
    $versionAprobada=  getApprovalVersion($content_id);
    $versionEdit = getEditVersion($content_id);
    for($i=$versionEdit;$i>$versionAprobada;$i--){
        //Borro los options
    $sql = "delete option_field where id in (Select opf.id from module_form mf,field f,option_field opf where mf.content_id = ".$content_id." and f.form_id=mf.id and f.ver=".$i." and f.id=opf.id_field)";
    $db->sql_query($sql);
    //Borro los Fields
    $sql = "delete from  field where id in (Select f.id from module_form mf,field f where mf.content_id = ".$content_id." and f.form_id=mf.id and f.ver=".$i.")";
    $db->sql_query($sql);  
    }
    $sql = "update module_form set name_es_edit=name_es, adress_edit=adress,name_en_edit=name_en,crm_id_edit=crm_id,ver_edit=ver where content_id=". $db->sql_escape($content_id);
    $db->sql_query($sql);
}
function EditedContent($content_id,$msg){
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedContent]"
            . "@content =" . $db->sql_escape($content_id) . ""
            . ",@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
?>