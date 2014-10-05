<?php
ob_start();
    set_time_limit(600);
    include '../edbo-provider/edbo-initsoap.php';
    include '../../utils/utils.php';
   
    $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        
    if (is_null($action)) {
        json_encode(array( 'error' => ob_get_contents()));
        return;
    }
    
       
if ($action == 'set_allow') {
   
    $data = array();
            
    // запрос языка
    $res = $eg->{'LanguagesGet'}($sessionId);
    //$eg->printLastError($sessionId);
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];
        
    // id вступительной компании
    //$Id_PersonRequestSeasons = filter_input(INPUT_GET, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
    //if (is_null($Id_PersonRequestSeasons)) {
        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
    //}


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
	$Id_PersonRequestStatusType1 = 1;//1;
    //    }
    //    $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType2)) {
	$Id_PersonRequestStatusType2 = 1;//4;
    //    }
    //     $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonRequestStatusType3)) {
	$Id_PersonRequestStatusType3 = 1;//5;
    //    }
 
    // запрос паарметров формы обучения
    //    $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
    //    if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 0;
    //    }

    // запрос паарметров квалификации
        //$Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        //if (is_null($Id_Qualification)) {
            $Id_Qualification = 0;
        //}
        
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
			$data['err'] = ob_get_contents();
			
            echo json_encode($data);
			ob_clean();
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
            //if (strlen($EntranceCodes) != 0)
             //   continue;
			if ($item['Id_PersonRequestStatusType'] != 1) {
				continue;
			}
            
           
			$Id_PersonRequestStatusType = 4;
			
			$res = $ep->PersonRequestsStatusChange($sessionId, $Id_PersonRequest, 
			$Id_PersonRequestStatusType, "", 0, -1, -1);
			
			$data[] = array (
            'Id_PersonRequest' => $Id_PersonRequest,
            'SpecDirectionName' => $SpecDirectionName,
            'SpecClasifierCode' => $SpecClasifierCode,
            'Id_Qualification' => $Id_Qualification,
            'QualificationName' => $QualificationName,
            'FIO' => $FIO,
            'EntranceCodes' => $EntranceCodes,
            'RequestEnteranseCodes' => $RequestEnteranseCodes, 
            'res' => $res,
			'res2' => $res>0?'Допущено':'Помилка',
			
            );
			
			//break;
			
        }
        
        $data['err'] = ob_get_contents();
			
            echo json_encode($data);
			ob_clean();
        return;

}
		
			
echo json_encode(
array(
	'err' => ob_get_contents()
	)
);
ob_clean();
return; 