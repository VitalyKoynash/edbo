<?php

/* 
 * модуль безопасности системы
 */
include_once dirName(__FILE__).'/../edbo-provider/edbo-initsoap.php';
include_once dirName(__FILE__).'/../../utils/http-utils.php';

function check_security ($env) {
    
    global $eg;
    
    if (!is_null($env)) {
        $sessionId = $env['sessionId'];
    }else{
        $sessionId = get_input_str('sessionId');
    }
    
    $check_msg = array(
        'Неверный тип идентификатора сессии',
        'Неверный идентификатор сессии1',
        'Неверный идентификатор сессии2'
    );
    $check = array(FALSE, '');
    //print ($sessionId);
    if (!$eg->check_guid($sessionId)) {
        $check[1] = $check_msg[1]; 
        return $check;
    }
    
    $res = $eg-> LanguagesGet($sessionId);//GlobaliInfoGet GetLastError
    //print_r($res);
    if ($res == NULL) {
        $check[1] = $check_msg[1]; 
        return $check;
    }
    if (count($res) == 0) {
        $check[1] = $check_msg[2]; 
        return $check;
    }
    
    
    $check[0] = TRUE;//TRUE
    
    return $check;
}
//echo '123';
if (is_null($eg) || is_null($ep)) {
    header('Location: '.dirName(__FILE__).'/../login/edbo-login.php?sessionId='.'EDBOGuides or EDBOPersons initialization FAILED');  
    return;
}

$status = check_security (NULL);
//echo '456';
//print_r($status);
//echo 'st=',$status[0];
//return;
if (!$status[0]) {
    header('Location: '.dirName(__FILE__).'/../login/edbo-login.php?sessionId='.$status[1]);  
    return;
}