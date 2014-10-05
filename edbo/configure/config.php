<?php

if (getenv('USERNAME') == 'SUNRISE$') {
    $cfg = array (
        'soapHostEDBOGuides' => "http://192.168.0.110:8080/EDBOGuides/EDBOGuides.asmx?WSDL",
        'soapHostEDBOPerson' => "http://192.168.0.110:8080/EDBOPerson/EDBOPerson.asmx?WSDL",
    );
} else {
    $cfg = array (
        'soapHostEDBOGuides' => "http://10.22.22.129:8080/EDBOGuides/EDBOGuides.asmx?WSDL",
        'soapHostEDBOPerson' => "http://10.22.22.129:8080/EDBOPerson/EDBOPerson.asmx?WSDL",
    );
}

$msg = array (
    'msg_err_edbosoap' => 'Error EDBO SOAP. CHECK SETTINGS', //
);

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if ( E_RECOVERABLE_ERROR===$errno ) {
        echo "FATAL ERROR service EDBO";
        throw new Exception($errstr, $errno, 0, $errfile, $errline);

    }
    return false;
}

set_error_handler('myErrorHandler');
register_shutdown_function('fatalErrorShutdownHandler');
function fatalErrorShutdownHandler()
{
    $last_error = error_get_last();
    if ($last_error['type'] === 1) { // E_ERROR
        // fatal error
        myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}
