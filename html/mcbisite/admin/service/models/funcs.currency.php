<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list sites with filter options
function listCurrencyDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'currency asc ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id Value, currency DisplayText,id,currency FROM [".$db_name."].[dbo].[currency])
					AS currency_list
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  id Value, currency DisplayText, id, currency  FROM [".$db_name."].[dbo].[currency] ";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[currency] "; 
	
	if($parameters['jtsorting']!=""){
		$sql .=  " ORDER BY ".$parameters['jtsorting'];
	}	
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


//You can create site
function createCurrencyDataBase($parameters) {
    global $db, $db_table_prefix,$db_name;
    $currency=$db->sql_escape($parameters['currency']);
    $sql = "
    INSERT INTO [".$db_name."].[dbo].[currency]
    	(
			currency 
		)
	VALUES(
		'{$currency}'
		)";
			

    $result = $db->sql_query($sql);
	
	
    if ($result) 
    {
		$sentencia="SELECT id,currency FROM currency WHERE id=@@IDENTITY";
		$data = $db->sql_query($sentencia);
		$data = $db->sql_fetchrow($data);
        return $data;
    }
    return false;
}

//You can update sites
function updateCurrencyDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    $currency=$db->sql_escape($parameters['currency']);
	
    $sql = "
    UPDATE[{$db_name}].[dbo].[currency]
    SET
    	currency='{$currency}'
    WHERE
    	id={$id}
	";
    $result = $db->sql_query($sql);
    if($result){
    	$sql="SELECT id, currency FROM [{$db_name}].[dbo].[currency] WHERE id=".$id;
    	$result = $db->sql_query($sql);
    	$result = $db->sql_fetchrow($sql);
    }
    return $result;
}


//You can delete sites
function deleteCurrencyDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    $sql = "DELETE FROM [{$db_name}].[dbo].[currency] WHERE id={$id}";
    $result = $db->sql_query($sql);

    return ($result);
}

?>