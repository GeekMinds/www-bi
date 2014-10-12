<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$action = $_POST["action"];
$data = array();
$data['MSG'] = $action;
echo json_encode($data);