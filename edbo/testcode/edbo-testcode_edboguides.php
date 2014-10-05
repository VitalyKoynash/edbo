<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test code EDBOGuides</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
    </head>
    <body>
        <?php
        set_time_limit(120);
        include '../edbo-provider/edbo-initsoap.php';
        include '../../utils/utils.php';

        $eg->debug (true);
                
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

        print '<p class = "soap_debug_selmethod">LanguagesGet</p>';
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        print '<p class = "soap_debug">';
        print_r($dLanguages);
        print '</p>';
        
        $Id_Language = $dLanguages['Id_Language'];
        

        print '<p class = "soap_debug_selmethod">GlobaliInfoGet</p>';
        $res = $eg->GlobaliInfoGet($sessionId);
        $eg->printLastError($sessionId);
        $dGloabalInfo = $res['dGloabalInfo'];
        print '<p class = "soap_debug">';
        print_r($dGloabalInfo);
        print '</p>';
        
        print '<p class = "soap_debug_selmethod">KOATUUGet</p>';
        //print ($sessionId." , ".getDateNow()." , ".$Id_Language);
        $res = $eg->KOATUUGet($sessionId, getDateNow(), $Id_Language, "", 1, "Краматорськ", "", 0);
        $eg->printLastError($sessionId);
        /*$dGloabalInfo = $res['dGloabalInfo'];
        print '<p class = "soap_debug">';
        print_r($dGloabalInfo);
        print '</p>';*/
        
        /* слишком много инфы выводит*/
        print '<p class = "soap_debug_selmethod">KOATUUGetL1</p>';
        $res = $eg->KOATUUGetL1($sessionId, getDateNow(), $Id_Language);
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">KOATUUGetL2</p>';
        $res = $eg->KOATUUGetL2($sessionId, getDateNow(), $Id_Language, "0100000000");
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">KOATUUGetL3</p>';
        $res = $eg->KOATUUGetL3($sessionId, getDateNow(), $Id_Language, "0124700000","%");
        $eg->printLastError($sessionId);
        /*
        
        print '<p class = "soap_debug_selmethod">EducationTypesGet</p>';
        $res = $eg->EducationTypesGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">StreetTypesGet</p>';
        $res = $eg->StreetTypesGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">SpecRedactionsGet</p>';
        $res = $eg->SpecRedactionsGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">SpecGet</p>';
        $res = $eg->SpecGet($sessionId, $res['dSpecRedactions'][0]['SpecRedactionCode'],
                "", "", "", "", "", $Id_Language, getDateNow(), "");
        $eg->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">SubjectsGet</p>';
        $res = $eg->SubjectsGet($sessionId, $Id_Language, getDateNow());
        $eg->printLastError($sessionId);
        
         
        
        
        print '<p class = "soap_debug_selmethod">SpecDirectionsSubjectsGet</p>';
        $res = $eg->SpecDirectionsSubjectsGet($sessionId, $Id_Language, getDateNow(),
        "", 3, "", 0);
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">SchoolsGet</p>';
        $res = $eg->SchoolsGet($sessionId, "", $Id_Language, getDateNow(),
        "", 1, "", "%", 0, "");
        $eg->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">UniversityAcreditatinTypesGet</p>';
        $res = $eg->UniversityAcreditatinTypesGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
       
        
        print '<p class = "soap_debug_selmethod">UniversityGetRequestsStat</p>';
        $res = $eg->UniversityGetRequestsStat($sessionId, 4,  
                "7e66bbdb-ce84-4e2c-9767-3346253175b0", "",
                $Id_Language, getDateNow());
        $eg->printLastError($sessionId);
        
        */
        /*
        print '<p class = "soap_debug_selmethod">UniversitiesGet</p>';
        $res = $eg->UniversitiesGet($sessionId, "", 
                $Id_Language, getDateNow(),
                "");
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">UniversityFacultetsGet</p>';
        $res = $eg->UniversityFacultetsGet($sessionId, $res['dUniversities']['UniversityKode'], 
                "",  $Id_Language, getDateNow(), 0, "", -1, -1, 0, -1);
        $eg->printLastError($sessionId);
        
        
        
        print '<p class = "soap_debug_selmethod">AcademicYearsGet</p>';
        $res = $eg->AcademicYearsGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
        
        
        print '<p class = "soap_debug_selmethod">CoursesGet</p>';
        $res = $eg->CoursesGet($sessionId, $Id_Language);
        $eg->printLastError($sessionId);
        
       */
        
        /*
        print '<p class = "soap_debug_selmethod">UniversityFacultetsGetRequests2</p>';
        $res = $eg->UniversityFacultetsGetRequests2($sessionId,
                $ep->getActualPersonRequestSeason($sessionId, $Id_Language),
                "", "",   $Id_Language, getDateNow(), "", 1, "", 0,0,0, 0,
                "7e66bbdb-ce84-4e2c-9767-3346253175b0",
                0,""
                );
        $eg->printLastError($sessionId);
        */
        
        
        print '<p class = "soap_debug_selmethod">UniversityCoursesGet</p>';
        $res = $eg->UniversityCoursesGet($sessionId, $Id_Language, getDateNow(), 
                "7e66bbdb-ce84-4e2c-9767-3346253175b0", 
               $ep->getActualPersonRequestSeason($sessionId, $Id_Language)
                );
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">UniversityFacultetSpecialitiesSubjectsGet</p>';
        $res = $eg->UniversityFacultetSpecialitiesSubjectsGet($sessionId,
               $Id_Language, getDateNow(), "fc03e90b-64a4-40fa-91de-7a10a8a875d6"
                );
        
        //5fe38c18-5f30-4427-950c-1baf7e095410 ускор
        //fc03e90b-64a4-40fa-91de-7a10a8a875d6 бак
        $eg->printLastError($sessionId);
        
        print '<p class = "soap_debug_selmethod">UniversityFacultetSpecialitiesGet</p>';
        $res = $eg->UniversityFacultetSpecialitiesGet($sessionId, 
            "7e66bbdb-ce84-4e2c-9767-3346253175b0", 
            "", "",
            $Id_Language, getDateNow(), 
            $ep->getActualPersonRequestSeason($sessionId, $Id_Language), 
            0, "", "", "", "");
        $eg->printLastError($sessionId);
        
        ?>
    </body>
</html>
