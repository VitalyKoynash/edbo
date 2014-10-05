<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test code EDBOPerson</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
    </head>
    <body>
        <?php
        set_time_limit(120);
        include '../edbo-provider/edbo-initsoap.php';
        include '../../utils/utils.php';
        
        $ep->debug (true);
        
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
        
        //print '<p class = "soap_debug_selmethod">LanguagesGet</p>';
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        /*
        print '<p class = "soap_debug">';
        print_r($dLanguages);
        print '</p>';
         */
        
        $Id_Language = $dLanguages['Id_Language'];
        
        print '<p class = "soap_debug_selmethod">PersonFindByAttestat2</p>';
        $res = $ep->PersonFindByAttestat2($sessionId, "НК", "46377199");
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonsFind2</p>';
        $res = $ep->PersonsFind2($sessionId, getDateNow(), $Id_Language,
                "Павін", "", "", "1,2,3,4", 1, "", "");
        $ep->printLastError($sessionId);
        $dPersonsFind2 = $res['dPersonsFind2'];
        
        print '<p class = "soap_debug_selmethod">PersonSexTypesGet</p>';
        $res = $ep->PersonSexTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        /*
        print '<p class = "soap_debug_selmethod">PersonsIdsGet</p>';
        $res = $ep->PersonsIdsGet($sessionId, $Id_Language, 4,
                "7e66bbdb-ce84-4e2c-9767-3346253175b0");
        $ep->printLastError($sessionId);
        */
        
        print '<p class = "soap_debug_selmethod">PersonContactTypesGet</p>';
        $res = $ep->PersonContactTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonAddressesGet2</p>';
        $res = $ep->PersonAddressesGet2($sessionId, getDateNow(), $Id_Language,
                $dPersonsFind2['PersonCodeU'], 0);
        $ep->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">PersonCountryGet</p>';
        $res = $ep->PersonCountryGet($sessionId, $dPersonsFind2['PersonCodeU']);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">ForeignTypesGet</p>';
        $res = $ep->ForeignTypesGet($sessionId);
        $ep->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">PersonDocumentTypesGet</p>';
        $res = $ep->PersonDocumentTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonDocumentsGet</p>';
        $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language,
                $dPersonsFind2['PersonCodeU'], 0, 0, "", -1);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationTypesGet</p>';
        $res = $ep->PersonEducationTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationFormsGet</p>';
        $res = $ep->PersonEducationFormsGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationPaymentTypesGet</p>';
        $res = $ep->PersonEducationPaymentTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">AcademicLeaveTypesGet</p>';
        $res = $ep->AcademicLeaveTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationsGet</p>';
        $res = $ep->PersonEducationsGet($sessionId, getDateNow(), $Id_Language,
                 $dPersonsFind2['PersonCodeU'], 0, 3, "");
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationHistoryTypesGet</p>';
        $res = $ep->PersonEducationHistoryTypesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationsCancelEducationTypesGet</p>';
        $res = $ep->PersonEducationsCancelEducationTypesGet($sessionId);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonEducationHistoryGet</p>';
        $res = $ep->PersonEducationHistoryGet($sessionId, getDateNow(), $Id_Language,
                $dPersonsFind2['PersonCodeU'],0/*4020047*/,0);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonCoursesGet</p>';
        $res = $ep->PersonCoursesGet($sessionId, getDateNow(), $Id_Language,
                $dPersonsFind2['Id_Person'],4, "0e4328f5-a79f-482d-b7d3-d125ddd3a1bd");
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonRequestSeasonsGet</p>';
        $res = $ep->PersonRequestSeasonsGet($sessionId, $Id_Language, "15.08.".date("Y")." 00:00:00"/*getDateNow()*/, 
               0, 0, 1);
        $ep->printLastError($sessionId);
        
        print '<br>Current season - '. $ep->getActualPersonRequestSeason($sessionId, $Id_Language).'<br>';
        
        
        print '<p class = "soap_debug_selmethod">PersonRequestsIdsGet</p>';
        $res = $ep->PersonRequestsIdsGet($sessionId, $Id_Language,  
               4, "7741628f-7c2d-4aa7-b1b0-e188081de99f"); // УИПА 7e66bbdb-ce84-4e2c-9767-3346253175b0
        $ep->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">PersonRequestStatusTypesGet</p>';
        $res = $ep->PersonRequestStatusTypesGet($sessionId, $Id_Language,  
               4, "7e66bbdb-ce84-4e2c-9767-3346253175b0");
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonRequestExaminationsGet</p>';
        $res = $ep->PersonRequestExaminationsGet($sessionId, getDateNow(),  
               $Id_Language, 6305917);
        $ep->printLastError($sessionId);
        /*
        print '<p class = "soap_debug_selmethod">PersonEnteranceTypesGet</p>';
        $res = $ep->PersonEnteranceTypesGet($sessionId, $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonRequestExaminationCausesGet</p>';
        $res = $ep->PersonRequestExaminationCausesGet($sessionId, $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">RequestEnteranceCodesGet</p>';
        $res = $ep->RequestEnteranceCodesGet($sessionId, getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">PersonTypeDictGet</p>';
        $res = $ep->PersonTypeDictGet($sessionId, $Id_Language);
        $ep->printLastError($sessionId);
        
         
         */
        
        /*
        print '<p class = "soap_debug_selmethod">QualificationsGet</p>';
        $res = $ep->QualificationsGet($sessionId,  getDateNow(), $Id_Language);
        $ep->printLastError($sessionId);
         * 
         */
        
        print '<p class = "soap_debug_selmethod">PersonRequestEnteranceCodesGet</p>';
        $res = $ep->PersonRequestEnteranceCodesGet($sessionId, getDateNow(),  
               $Id_Language, 6775800);
        $ep->printLastError($sessionId);
        
        ?>
    </body>
</html>
