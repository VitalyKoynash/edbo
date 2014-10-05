<?php
//session_start();

include_once './utils/utils.php';

$sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

if (!check_guid($sessionId)) {
    //Перенаправление на главную страницу
    header('Location: ./edbo/login/edbo-login.php?sessionId='.  htmlspecialchars($sessionId)); 
    exit ();
}
// ok - redirect to main admin panel
header('Location: ./edbo/edbo-main.php?sessionId='.htmlspecialchars($sessionId)); 


