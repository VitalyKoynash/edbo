<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include_once '../../utils/utils.php';
//session_start();

$sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

if (check_guid($sessionId) ) {
    include '../edbo-provider/edbo-initsoap.php';
    try {
        $eg->Logout ($sessionId);
    } catch (Exception $ex) {
        
    }
}

header('Location: ../login/edbo-login.php'); 


