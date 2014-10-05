<?php
session_start ();
include dirName(__FILE__).'/../edbo-provider/edbo-initsoap.php';

$login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);  
$sessionId = $eg->Login($login, $password, NULL, NULL );


unset($_SESSION['login']);

if (!$eg->check_guid($sessionId)) {
    header('Location: ./edbo-login.php?sessionId='.htmlspecialchars("$sessionId"));  
} else {
    $_SESSION['login'] = $login;
    //echo dirName(__FILE__).'/../edbo-main.php?sessionId='.htmlspecialchars($sessionId);
    //header('Location: '.dirName(__FILE__).'/../edbo-main.php?sessionId='.htmlspecialchars($sessionId)); 
    header('Location: ../edbo-main.php?sessionId='.htmlspecialchars($sessionId)); 
}

