<?php
session_start ();
global $ep;

if (!is_null($ep)) exit(); 

require_once './config.php';

foreach($cfg as $key => $value) {
    if (!isset($_SESSION[$key])  || is_null( $_SESSION[$key]) || strlen($_SESSION[$key])== 0) {
		$_SESSION[$key] = $value;
	}
};

require_once './EDBOPerson.php';

if (is_null($ep)) { 
    try {
        $ep = new EDBOPerson ($_SESSION['soapHostEDBOPerson']);
    } catch (Exception $ex) {
        //header('Location: /edbo/edbo-configure.php?err='.  htmlspecialchars($msg['msg_err_edbosoap'])); 
        //header('Location: ./edbo-login.php?sessionId='.htmlspecialchars($sessionId));  
        //header('Location: ./edbo-login.php?sessionId='.htmlspecialchars($ex->getMessage()));  
        echo 'is_null($ep)';
        return;
        //throw $ex;
    }
    
}

