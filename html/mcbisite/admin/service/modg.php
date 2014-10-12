<?php

//MODULO LOCALIZADOR

require_once("models/config.php");

require_once("models/funcs.modg.php");

header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listModG
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function listModG($parameters = array()) {
    //Get records from database
    $data = getModuleList();
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list Categories
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function listCategories($parameters = array()) {
    //Get records from database
    $data = getCategoriesList($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list Locations
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function listLocations($parameters = array()) {
    //Get records from database
    $data = getLocationslist($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Create Category
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function createCategory($parameters = array()) {
    //Get records from database
    $data = createCategoryBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data['rows'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Update Category
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function updateCategory($parameters = array()) {
    //Get records from database
    $data = updateCategoryBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data['rows'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Delete Category
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function deleteCategory($parameters = array()) {
    //Get records from database
    deleteCategoryBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Create Location
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function createLocation($parameters = array()) {
    //Get records from database
    $data = createLocationBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data['rows'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Update Location
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function updateLocation($parameters = array()) {
    //Get records from database
    $data = updateLocationBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data['rows'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Delete Location
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function deleteLocation($parameters = array()) {
    //Get records from database
    deleteLocationBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Create Module G
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function createModG($parameters = array()) {
    //Get records from database
    $data = createModGBD($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data['rows'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);



if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'list':
        $result = listModG($parameters);
        break;

    case 'createModG':
        $result = createModG($parameters);
        break;
    
    case 'createCategory':
        $result = createCategory($parameters);
        break;

    case 'updateCategory':
        $result = updateCategory($parameters);
        break;

    case 'deleteCategory':
        $result = deleteCategory($parameters);
        break;

    case 'createLocation':
        $result = createLocation($parameters);
        break;

    case 'updateLocation':
        $result = updateLocation($parameters);
        break;

    case 'deleteLocation':
        $result = deleteLocation($parameters);
        break;

    case 'listCategories':
        $result = listCategories($parameters);
        break;

    case 'listLocations':
        $result = listLocations($parameters);
        break;

    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;

    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>
