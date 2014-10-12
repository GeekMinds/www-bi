<?php

/*
 * Author: Javier Cifuentes
 */
include_once 'settings.php';

function get($url) {
    global $curlTimeOut;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $curlTimeOut);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $code = curl_exec($ch);
    $data = array();
    if (curl_errno($ch)) {
        $data["Result"] = false;
        $data["Message"] = "Error de CURL: " . curl_error($ch);
    } else {
        $json = json_decode($code);
        if ($json == null) {
            $data["Result"] = false;
            $data["Message"] = "El servidor no devolvio un JSON valido";
            $data["JSON"] = $code;
        } else {
            $data["Result"] = true;
            $data["JSON"] = $json;
        }
    }
    curl_close($ch);
    return $data;
}

function post($url, $parameters) {
    global $curlTimeOut;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($parameters));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $curlTimeOut);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, convertParams($parameters));
    $code = curl_exec($ch);
    $data = array();
    if (curl_errno($ch)) {
        $data["Result"] = false;
        $data["Message"] = "Error de CURL: " . curl_error($ch);
    } else {
        $json = json_decode($code);
        if ($json == null) {
            $data["Result"] = false;
            $data["Message"] = "El servidor no devolvio un JSON valido";
            $data["JSON"] = $code;
        } else {
            $data["Result"] = true;
            $data["JSON"] = $json;
        }
    }
    curl_close($ch);
    return $data;
}

function convertParams($parameters) {
    $str_params = "";
    foreach ($parameters as $parameter => $value) {
        $str_params .= $parameter . "=" . $value . "&";
    }
    return rtrim($str_params, '&');
}
