<?php
//ADMIN
function createAdminHistoryDataBase($parameters){
    global $db, $db_table_prefix,$db_name,$loggedInUser;
    $data = array();

    $admin_id=$db->sql_escape($loggedInUser->user_id);
    $action_user=$db->sql_escape($parameters["action_user"]);
    $description=$db->sql_escape($parameters["description"]);
    
    $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."user_history]
        (
            admin_id, action, description, created_at
        )
    OUTPUT Inserted.ID
    VALUES(
        '".$admin_id."','".$action_user."','".$description."',getdate()
        )";         
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);

if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos insertados";
        $data['user_id']=$loggedInUser->user_id;
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron insertar los datos";
    }
    return $data;
}

//OBTIENE PROMOCIONES DEL BUSCADOR
function getAdminHistoryDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
    $filtros=array();
    $admin_id=$db->sql_escape($parameters['admin_id']);
    $action_user=$db->sql_escape($parameters['action_user']);
    $date1=$db->sql_escape($parameters['date1']);
    $date2=$db->sql_escape($parameters['date2']);

    $admin_id=str_replace('null', '',$admin_id);
    if(strcmp($admin_id,'')!=0){
        $filtros[]=" a.admin_id='".$admin_id."' ";
    }

    $action_user=str_replace('null', '',$action_user);
    if(strcmp($action_user,'')!=0){
        $filtros[]=" a.action='".$action_user."'";
    }

    if(strcmp($date1, '')!=0){
        $filtros[]=" a.created_at>=convert(datetime,'".$date1." 00:00:00',103) ";
    }

    if(strcmp($date2, '')!=0){
        $filtros[]=" a.created_at<=convert(datetime,'".$date2." 23:59:59',103) ";
    }
    $sql_where="";
    //Arma query agregando los intersect con los querys de los filtros
    if(count($filtros)>0){
        for ($i=0; $i < count($filtros) ; $i++) { 
            
                $sql_where.=" and ".$filtros[$i];
            
        }
    }

    $sql = "";
    if($parameters['jtpagesize'] != ''){
        $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
        $sql = "SELECT * FROM
                    (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting']." ,a.action ASC, a.created_at ASC) AS Row, a.id, b.name, a.action, a.description, convert(date,a.created_at,103) as created_at
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."user_history] a 
                    INNER JOIN
                        [".$db_name."].[dbo].[".$db_table_prefix."admin] b
                    ON
                        a.admin_id=b.id
                    where admin_id is not null ".$sql_where."
                    )
                    AS user_with_numbers
                WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
    }else{
        $sql = "SELECT a.id, b.name, a.action, a.description, convert(date,a.created_at,103) as created_at
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."user_history] a 
                    INNER JOIN
                        [".$db_name."].[dbo].[".$db_table_prefix."admin] b
                    ON
                        a.admin_id=b.id where admin_id is not null ".$sql_where;
    }
$result = $db->sql_query($sql);
$result= $db->sql_fetchrowset($result);
    
    $sql_count = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."user_history] a WHERE admin_id is not null ".$sql_where;
    //var_dump($sql); 
    $result_count = $db->sql_query($sql_count);
    $result_count= $db->sql_fetchrow($result_count);
    $data["result"] = $result;
    $data["count"] = $result_count['RecordCount'];
    $data["sql"]=$sql_count; 
    return $data;
}

function getAdminDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
            $sql  = " SELECT  id, name as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."admin] s order by description";
           
            $result = $db->sql_query($sql);
            $result = $db->sql_fetchrowset($result);

  return $result;
}

function getAdminActionDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
            
            $sql  = " SELECT action as id, action as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."user_history] where admin_id is not null group by action order by action";
        
            $result = $db->sql_query($sql);
            $result = $db->sql_fetchrowset($result);

  return $result;

}

  //User

//INSERTA PROMOCIONES DEL BUSCADOR
function createUserHistoryDataBase($parameters){
    global $db, $db_table_prefix,$db_name,$loggedInUser;
    $data = array();

    $user_id=$db->sql_escape($loggedInUser->user_id);
    $action_user=$db->sql_escape($parameters["action_user"]);
    $description=$db->sql_escape($parameters["description"]);
    
    $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."user_history]
        (
            user_id, action, description, created_at
        )
    OUTPUT Inserted.ID
    VALUES(
        '".$user_id."','".$action_user."','".$description."',getdate()
        )";         
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);

if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos insertados";
        $data['user_id']=$loggedInUser->user_id;
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron insertar los datos";
    }
    return $data;
}

//OBTIENE PROMOCIONES DEL BUSCADOR
function getUserHistoryDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'first_name ASC';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    if(strcmp($parameters['jtsorting'], 'name ASC')==0){
        $parameters['jtsorting']='first_name ASC'; 
    }

    if(strcmp($parameters['jtsorting'], 'name DESC')==0){
        $parameters['jtsorting']='first_name DESC'; 
    }
    //$parameters["id"] = (isset($parameters['id'])) ? $parameters['id'] : "1";
    $filtros=array();
    $user_id=$db->sql_escape($parameters['user_id']);
    $action_user=$db->sql_escape($parameters['action_user']);
    $date1=$db->sql_escape($parameters['date1']);
    $date2=$db->sql_escape($parameters['date2']);

    $user_id=str_replace('null', '',$user_id);
    if(strcmp($user_id,'')!=0){
        $filtros[]=" a.user_id='".$user_id."' ";
    }

    $action_user=str_replace('null', '',$action_user);
    if(strcmp($action_user,'')!=0){
        $filtros[]=" a.action='".$action_user."'";
    }

    if(strcmp($date1, '')!=0){
        $filtros[]=" a.created_at>=convert(datetime,'".$date1." 00:00:00',103) ";
    }

    if(strcmp($date2, '')!=0){
        $filtros[]=" a.created_at<=convert(datetime,'".$date2." 23:59:59',103) ";
    }
    $sql_where="";
    //Arma query agregando los intersect con los querys de los filtros
    if(count($filtros)>0){
        for ($i=0; $i < count($filtros) ; $i++) { 
            
                $sql_where.=" and ".$filtros[$i];
            
        }
    }

    $sql = "";
    if($parameters['jtpagesize'] != ''){
        $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
        $sql = "SELECT * FROM
                    (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting']." ,a.action ASC, a.created_at ASC) AS Row, a.id, concat(first_name, ' ',last_name) as name, a.action, a.description, convert(date,a.created_at,103) as created_at
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."user_history] a 
                    INNER JOIN
                        [".$db_name."].[dbo].[".$db_table_prefix."user] b
                    ON
                        a.user_id=b.id
                    where user_id is not null ".$sql_where."
                    )
                    AS user_with_numbers
                WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
    }else{
        $sql = "SELECT a.id,  CONCAT( first_name ,' ' ,last_name ) AS name , a.action, a.description, convert(date,a.created_at,103) as created_at
                    FROM 
                        [".$db_name."].[dbo].[".$db_table_prefix."user_history] a 
                    INNER JOIN
                        [".$db_name."].[dbo].[".$db_table_prefix."user] b
                    ON
                        a.user_id=b.id where user_id is not null ".$sql_where;
    }


$result = $db->sql_query($sql);
$result= $db->sql_fetchrowset($result);
    
    $sql_count = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."user_history] a WHERE user_id is not null ".$sql_where;
    
    $result_count = $db->sql_query($sql_count);
    $result_count= $db->sql_fetchrow($result_count);
    $data["result"] = $result;
    $data["count"] = $result_count['RecordCount'];
    $data["sql"]=$sql;
    return $data;
}

function getUserDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
            $sql  = " SELECT  id, concat(first_name, ' ',last_name) as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."user] s order by description";
           
            $result = $db->sql_query($sql);
            $result = $db->sql_fetchrowset($result);

  return $result;
}

function getActionDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
            
            
            $sql  = " SELECT action as id, action as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."user_history] where user_id is not null group by action order by action";
        
            $result = $db->sql_query($sql);
            $result = $db->sql_fetchrowset($result);

  return $result;
}

?>