<?php
//ob_start();
$arr = array(
            'one' => 1,
            'two' => 2,
            );


    set_time_limit(600);
    include '../edbo-provider/edbo-initsoap.php';
    include '../../utils/utils.php';
   
    $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        
    if (is_null($action)) {
        json_encode(array( 'error' => ob_get_contents()));
        return;
    }
    
       
if ($action == 'get_formdata') {
    // запрос языка
    $res = $eg->{'LanguagesGet'}($sessionId);
    //$eg->printLastError($sessionId);
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];
        
    // id вступительной компании
    $Id_PersonRequestSeasons = filter_input(INPUT_GET, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
    if (is_null($Id_PersonRequestSeasons)) {
        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
    }

   
    $form_data = array();
    
    $form_data['json'] = "true";
    
    $form_data['RequestEnteranceCodes'] = array();
    
    //echo '123';
    //echo json_encode($arr);
    //return; 
    
    //json_encode(array( "error" => ob_get_contents()));
    //return;
    
	// формируем массив кодов поступления
    $res = $ep->RequestEnteranceCodesGet ($sessionId, getDateNow(), $Id_Language);
    if (count($res) > 0) {
    
		$dRequestEnteranceCodes = $res['dRequestEnteranceCodes'];
		
		foreach ($dRequestEnteranceCodes as $key => $value) {
			$form_data['RequestEnteranceCodes'][] = array (
				'id_RequestEnteranceCodes' => $value['id_RequestEnteranceCodes'],
				'RequestEnteranceCodes' => $value['RequestEnteranceCodes'],
				'RequestEnteranceCodesName' => $value['RequestEnteranceCodesName'],
			);
		}
	}
    
	// формируем массив направлений обучения,
	// причем, выберем только те направления, которые
	// на которые были поданы заявки
    
    
    

    // запрос параметров факультета
    $UniversityFacultetKode = "";
    // запрос параметров специальности
    $UniversitySpecialitiesKode = "";

    // запрос параметров персоны
    //$PersonCodeU = filter_input(INPUT_GET, "PersonCodeU", FILTER_SANITIZE_STRING);
    //if (is_null($PersonCodeU)) {
        $PersonCodeU = "";
    //}

    $Hundred = 1;

    // Идентификаторы  статусов  заявок
    //    $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
    //   if (is_null($Id_PersonRequestStatusType1)) {
	$Id_PersonRequestStatusType1 = 0;//1;
    //    }
    //    $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType2)) {
	$Id_PersonRequestStatusType2 = 0;//4;
    //    }
    //     $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType3)) {
	$Id_PersonRequestStatusType3 = 0;//5;
    //    }
 
    // запрос паарметров формы обучения
    //    $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 0;
    //    }

    // запрос паарметров квалификации
    //    $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_Qualification)) {
            $Id_Qualification = 0;
    //    }
        
        //GUID универа
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $MinDate = "";
        $Filters = "";
    
		// для отслеживания времени выполнения
        $timer = microtime(TRUE);
        // получим все заявки
        $res = $eg->UniversityFacultetsGetRequests2(
                
            $sessionId, 
            $Id_PersonRequestSeasons,
            $UniversityFacultetKode,
            $UniversitySpecialitiesKode,
            $Id_Language,   
            getDateNow(),
            $PersonCodeU,
            $Hundred,
            $MinDate,
            $Id_PersonRequestStatusType1,
            $Id_PersonRequestStatusType2,
            $Id_PersonRequestStatusType3,
            $Id_PersonEducationForm,
            $UniversityKode,
            $Id_Qualification,
            $Filters
            );
        //$eg->printLastError($sessionId);
        
         
        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];
        
        //$count = count($dUniversityFacultetsRequests2);
        
        $Qualifications = array();
	//$sort_key = array();
        // цикл позаявкам
        foreach ($dUniversityFacultetsRequests2 as $key => $item) {
            
            //$Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            //$PersonCodeU = $item['PersonCodeU'];    // код персоны
            //$DateCreate = substr($item['DateCreate'],0,10);
            // Идентификатор специальности ВУЗа
            $Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
            // Название направления специальности.
            //$SpecDirectionName = $item['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
            //$SpecClasifierCode = $item['SpecClasifierCode'];
            // Флаг, показывающий, что персона подала оригинал документов
            //$OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            //$PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            //$CodeOfBusines = $item['CodeOfBusiness'];
            // Конкурсный балл
            //$KonkursValue = $item['KonkursValue'];
            //$Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];
            $UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            //$FIO = $item['FIO'];
            //$ContactMobile = $item['ContactMobile'];
			
            $Id_Qualification = $item['Id_Qualification'];
            $QualificationName = $item['QualificationName'];
	    
           
            $Qualifications[$Id_Qualification] = array (
            'Id_Qualification' => $Id_Qualification,
            'QualificationName' => $QualificationName
            );
            
            
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                первоочередного зачисления
             */
            //$ExistBenefitsPershocherg = (int) $item['ExistBenefitsPershocherg'];
            
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                внеочередного зачисления
             */
            //$ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
                  
        }
        
        ksort($Qualifications);
        
        $form_data['Qualifications'] = $Qualifications;

        echo json_encode($form_data);
        return;

} elseif ($action == 'get_requests_without_codes') {
    
    
   
    $data = array();
    
            
    // запрос языка
    $res = $eg->{'LanguagesGet'}($sessionId);
    //$eg->printLastError($sessionId);
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];
        
    // id вступительной компании
    $Id_PersonRequestSeasons = filter_input(INPUT_GET, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
    if (is_null($Id_PersonRequestSeasons)) {
        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
    }

   

    
    
//$data[] = $dRequestEnteranceCodes;
    
    

    // запрос параметров факультета
    $UniversityFacultetKode = "";
    // запрос параметров специальности
    $UniversitySpecialitiesKode = "";

    // запрос параметров персоны
    //$PersonCodeU = filter_input(INPUT_GET, "PersonCodeU", FILTER_SANITIZE_STRING);
    //if (is_null($PersonCodeU)) {
    $PersonCodeU = "";
    //}

    $Hundred = 1;

    // Идентификаторы  статусов  заявок
    //    $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
    //   if (is_null($Id_PersonRequestStatusType1)) {
	$Id_PersonRequestStatusType1 = 0;//1;
    //    }
    //    $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType2)) {
	$Id_PersonRequestStatusType2 = 0;//4;
    //    }
    //     $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType3)) {
	$Id_PersonRequestStatusType3 = 0;//5;
    //    }
 
    // запрос паарметров формы обучения
    //    $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 0;
    //    }

    // запрос паарметров квалификации
        $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        if (is_null($Id_Qualification)) {
            $Id_Qualification = 0;
        }
        //$Id_Qualification = 14;
        /*
        $RequestEnteranseCodes_add = filter_input(INPUT_GET, "RequestEnteranseCodes", FILTER_SANITIZE_NUMBER_INT);
        if (is_null($RequestEnteranseCodes_add)) {
            $RequestEnteranseCodes_add = 0;
        }
         * */
        
        //GUID универа
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $MinDate = "";
        $Filters = "";
    
        // для отслеживания времени выполнения
        $timer = microtime(TRUE);
        // получим все заявки
        $res = $eg->UniversityFacultetsGetRequests2(
            $sessionId, 
            $Id_PersonRequestSeasons,
            $UniversityFacultetKode,
            $UniversitySpecialitiesKode,
            $Id_Language,   
            getDateNow(),
            $PersonCodeU,
            $Hundred,
            $MinDate,
            $Id_PersonRequestStatusType1,
            $Id_PersonRequestStatusType2,
            $Id_PersonRequestStatusType3,
            $Id_PersonEducationForm,
            $UniversityKode,
            $Id_Qualification,
            $Filters
            );
        //$eg->printLastError($sessionId);
        
         
        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];
        
        $count = count($res);//$dUniversityFacultetsRequests2);
        
        
        if ($count == 0)
        {
            echo json_encode($data);
            return;
        }
        
        
        //$Qualifications = array();
	//$sort_key = array();
        // цикл позаявкам
        foreach ($dUniversityFacultetsRequests2 as $key => $item) {
            
            $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            //$PersonCodeU = $item['PersonCodeU'];    // код персоны
            //$DateCreate = substr($item['DateCreate'],0,10);
            // Идентификатор специальности ВУЗа
            //$Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
            // Название направления специальности.
            $SpecDirectionName = $item['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
            $SpecClasifierCode = $item['SpecClasifierCode'];
            // Флаг, показывающий, что персона подала оригинал документов
            //$OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            //$PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            //$CodeOfBusines = $item['CodeOfBusiness'];
            // Конкурсный балл
            //$KonkursValue = $item['KonkursValue'];
            //$Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];
            //$UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            $FIO = $item['FIO'];
            //$ContactMobile = $item['ContactMobile'];
			
            $Id_Qualification = $item['Id_Qualification'];
            $QualificationName = $item['QualificationName'];
            $EntranceCodes  = $item['EntranceCodes'];
            $RequestEnteranseCodes = $item['RequestEnteranseCodes'];
            //$RequestEnteranseCodesName = $item['RequestEnteranseCodes'];
            if (strlen($EntranceCodes) != 0)
                continue;
           
            
            $data[] = array (
            'Id_PersonRequest' => $Id_PersonRequest,
            'SpecDirectionName' => $SpecDirectionName,
            'SpecClasifierCode' => $SpecClasifierCode,
            'Id_Qualification' => $Id_Qualification,
            'QualificationName' => $QualificationName,
            'FIO' => $FIO,
            'EntranceCodes' => $EntranceCodes,
            'RequestEnteranseCodes' => $RequestEnteranseCodes, 
            'item' => $item
            );
        }
        
        /*
         * $data[]=$Id_Qualification;
        $data[]=$count;
        echo json_encode($data);
        return;
         */
        
        
        echo json_encode($data);
        return;

} elseif ($action == 'set_requests_code') {
    // запрос языка
    $res = $eg->{'LanguagesGet'}($sessionId);
    //$eg->printLastError($sessionId);
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];
        
    // id вступительной компании
    $Id_PersonRequestSeasons = filter_input(INPUT_GET, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
    if (is_null($Id_PersonRequestSeasons)) {
        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
    }

    //$dRequestEnteranceCodes = $ep->RequestEnteranceCodesGet($sessionId, getDateNow(), $Id_Language);
   
    
    
    //$data[] = $dRequestEnteranceCodes;

    // запрос параметров факультета
    $UniversityFacultetKode = "";
    // запрос параметров специальности
    $UniversitySpecialitiesKode = "";

    // запрос параметров персоны
    //$PersonCodeU = filter_input(INPUT_GET, "PersonCodeU", FILTER_SANITIZE_STRING);
    //if (is_null($PersonCodeU)) {
    $PersonCodeU = "";
    //}

    $Hundred = 1;

    // Идентификаторы  статусов  заявок
    //    $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
    //   if (is_null($Id_PersonRequestStatusType1)) {
	$Id_PersonRequestStatusType1 = 0;//1;
    //    }
    //    $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType2)) {
	$Id_PersonRequestStatusType2 = 0;//4;
    //    }
    //     $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType3)) {
	$Id_PersonRequestStatusType3 = 0;//5;
    //    }
 
    // запрос паарметров формы обучения
    //    $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 0;
    //    }

    // запрос паарметров квалификации
        $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        if (is_null($Id_Qualification)) {
            $Id_Qualification = 0;
        }
        
        $id_RequestEnteranceCodes_add = filter_input(INPUT_GET, "id_RequestEnteranceCodes", FILTER_SANITIZE_NUMBER_INT);
        if (is_null($id_RequestEnteranceCodes_add)) {
            $id_RequestEnteranceCodes_add = 0;
        }
        //GUID универа
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $MinDate = "";
        $Filters = "";
    
        // для отслеживания времени выполнения
        $timer = microtime(TRUE);
        // получим все заявки
        $res = $eg->UniversityFacultetsGetRequests2(
            $sessionId, 
            $Id_PersonRequestSeasons,
            $UniversityFacultetKode,
            $UniversitySpecialitiesKode,
            $Id_Language,   
            getDateNow(),
            $PersonCodeU,
            $Hundred,
            $MinDate,
            $Id_PersonRequestStatusType1,
            $Id_PersonRequestStatusType2,
            $Id_PersonRequestStatusType3,
            $Id_PersonEducationForm,
            $UniversityKode,
            $Id_Qualification,
            $Filters
            );
        //$eg->printLastError($sessionId);
        
         
        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];
        
        //$count = count($dUniversityFacultetsRequests2);
        
        $dRequestEnteranceCodes = $ep->RequestEnteranceCodesGet($sessionId, 
		getDateNow(), $Id_Language);
		
		$dRequestEnteranceCodes = $dRequestEnteranceCodes['dRequestEnteranceCodes'];
        $codes = array();
    
        foreach ($dRequestEnteranceCodes as $key => $value) {
            $codes[$value['id_RequestEnteranceCodes']] = $value['RequestEnteranceCodes'];
        }
    
        $str_codes = (string)$codes[$id_RequestEnteranceCodes_add];
        
        function check_null($param) {
            if (is_null($param)) return 'NULL';
            return $param;
        };
        //$data['str_codes'] = $str_codes;
        //$data['search_codes'] = strpos ("10-4,", $str_codes);
        //echo json_encode($data);
        //return;
        //$Qualifications = array();
	//$sort_key = array();
        $data = array();
        
        // цикл позаявкам
        foreach ($dUniversityFacultetsRequests2 as $key => $item) {
            
            $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            //$PersonCodeU = $item['PersonCodeU'];    // код персоны
            //$DateCreate = substr($item['DateCreate'],0,10);
            // Идентификатор специальности ВУЗа
            //$Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
            // Название направления специальности.
            $SpecDirectionName = $item['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
            $SpecClasifierCode = $item['SpecClasifierCode'];
            // Флаг, показывающий, что персона подала оригинал документов
            //$OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            //$PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            //$CodeOfBusines = $item['CodeOfBusiness'];
            // Конкурсный балл
            //$KonkursValue = $item['KonkursValue'];
            //$Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];
            //$UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            $FIO = $item['FIO'];
            //$ContactMobile = $item['ContactMobile'];
			
            $Id_Qualification = $item['Id_Qualification'];
            $QualificationName = $item['QualificationName'];
            $EntranceCodes  = $item['EntranceCodes'];
	   
            if (strpos ($EntranceCodes, $str_codes) === false) {
                
            } else {
                continue;
            }
                
            
            $res = 0;
            if ($id_RequestEnteranceCodes_add > 0) {
                $res = $ep->PersonRequestEnteranceCodesAdd($sessionId, getDateNow(),
                $Id_Language, $Id_PersonRequest, $id_RequestEnteranceCodes_add);
                if ($res == 0) {
                    $res = $ep->GetLastError($sessionId);
                }
            }
            
            $data[] = array (
            'Id_PersonRequest' => ($Id_PersonRequest),
            'SpecDirectionName' => ($SpecDirectionName),
            '$SpecClasifierCode' => ($SpecClasifierCode),
            'Id_Qualification' => ($Id_Qualification),
            'QualificationName' => ($QualificationName),
            'FIO' => ($FIO),
            'EntranceCodes' => ($EntranceCodes),
            'Id_PersonRequestEnteranceCode' => ($res),
            
            );

            //break;
                  
        }
        
        echo json_encode($data);
        return;

}

echo json_encode(array());
return; 