<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="./styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="./styles/edbo.css" type="text/css" media="print" />
    </head>
    <body>
        <?php
        include '../edbo-provider/edbo-initsoap.php';
        include '../../utils/utils.php';
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];
        
        print '<p class = "soap_debug_selmethod">Universities</p>';
        
        $res = $eg->UniversitiesGet($sessionId, "", 
                $Id_Language, getDateNow(), "");
        $eg->printLastError($sessionId);
        $dUniversities = $res['dUniversities'];
        
        ?>
    </body>
</html>
