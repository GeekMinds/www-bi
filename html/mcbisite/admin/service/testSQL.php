<?php
/*
//  cadena de conexión
$dsn = "Driver={SQL Server}; Server=donaweb.czek7k8d8d3f.us-west-2.rds.amazonaws.com:1433;
    Database=bisite02; Integrated Security=SSPI; Persist Security Info=False;";
// conexión con los datos especificados anteriormente
$conn = \odbc_connect($dsn, 'mncdona', 'MnCdona1213');
if (!$conn) {
    exit("Error al conectar: " . $conn);
}
// consulta que va a ejecutar
$sql = "SELECT * FROM dbo.page";
// Ejecutamos la consulta almacenamos los resultados
$rs = odbc_exec($conn, $sql);
if (!$rs) {
    exit("Query Error");
}
// Mostramos resultados
while (odbc_fetch_row($rs)) {
    $resultado = odbc_result($rs, "campo_delatabla");
    echo $resultado;
}
// Cerramos conexión
odbc_close($conn);

$serverName = "donaweb.czek7k8d8d3f.us-west-2.rds.amazonaws.com,1433"; //serverName\instanceName

// Puesto que no se han especificado UID ni PWD en el array  $connectionInfo,
// La conexión se intentará utilizando la autenticación Windows.
$connectionInfo = array( "Database"=>"bisite02", "UID"=>"mncdona", "PWD"=>"MnCdona1213");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ 	CURL SAMPLE
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

$args = array(
  'access_token' => $access_token,
  'id' => $uid,
  'tag_uid' => $uid
);

$url = "https://graph.facebook.com/{$idPhoto}/tags?access_token=".$access_token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$data = curl_exec($ch);*/

$link = mssql_connect('donaweb.czek7k8d8d3f.us-west-2.rds.amazonaws.com:1433', 'mncdona', 'MnCdona1213');

if (!$link || !mssql_select_db('bisite02', $link)) {
    die('Unable to connect or select database!');
}

// Do a simple query, select the version of 
// MSSQL and print it.
$version = mssql_query('SELECT @@VERSION');
$row = mssql_fetch_array($version);

echo $row[0];

// Clean up
mssql_free_result($version);

?>