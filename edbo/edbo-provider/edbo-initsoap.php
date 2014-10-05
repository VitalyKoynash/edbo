<?php
if (!isset($_SESSION)) {
    session_start ();
}

global $eg;
global $ep;

require_once dirName(__FILE__).'/../configure/config.php';

foreach($cfg as $key => $value) {
    if (!isset($_SESSION[$key])  || is_null( $_SESSION[$key]) || strlen($_SESSION[$key])== 0) {
		$_SESSION[$key] = $value;
	}
};
        
require_once 'EDBOGuides.php';
require_once 'EDBOPerson.php';


if (is_null($eg)) { 
    try {
        $eg = new EDBOGuides ($_SESSION['soapHostEDBOGuides']);
    } catch (Exception $ex) {
        //header('Location: /edbo/edbo-configure.php?err='.  htmlspecialchars($msg['msg_err_edbosoap'])); 
        header('Location: ../login/edbo-login.php?sessionId='.htmlspecialchars($ex->getMessage()));  
        //throw $ex;
    }
    
}

if (is_null($ep)) { 
    try {
        $ep = new EDBOPerson ($_SESSION['soapHostEDBOPerson']);
    } catch (Exception $ex) {
        //header('Location: /edbo/edbo-configure.php?err='.  htmlspecialchars($msg['msg_err_edbosoap'])); 
        //header('Location: ./edbo-login.php?sessionId='.htmlspecialchars($sessionId));  
        header('Location: ../login/edbo-login.php?sessionId='.htmlspecialchars($ex->getMessage()));  
        //throw $ex;
    }
    
}

