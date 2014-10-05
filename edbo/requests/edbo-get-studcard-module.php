 <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Все заявки</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
        <!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script//-->
        <!--script src="../../scripts/list.js"></script//-->
        <!--script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script//-->
        
        
        
    </head>
    <body>
	
 <?php
 
	set_time_limit(600);
	include '../edbo-provider/edbo-initsoap.php';
	include '../../utils/utils.php';
	include "EntrantAnalytycs.php";

	$sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
	$caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
        

	// запрос языка
	$res = $eg->{'LanguagesGet'}($sessionId);
	$eg->printLastError($sessionId);
	$dLanguages = $res['dLanguages'];
	$Id_Language = $dLanguages['Id_Language'];
        
	// обработка запрета выполнения анализа факта обучения в другом вузе
	$analytics = filter_input(INPUT_GET, "analytics", FILTER_SANITIZE_STRING);
	if (is_null($analytics)) {
		$analytics = 'true';
	}
        
	// id вступительной компании

	$Id_PersonRequestSeasons = filter_input(INPUT_GET, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
	if (is_null($Id_PersonRequestSeasons)) {
		$Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
	}


// запрос параметров факультета
	//$UniversityFacultetKode = filter_input(INPUT_GET, "UniversityFacultetKode", FILTER_SANITIZE_STRING);
	//if (is_null($UniversityFacultetKode)) {
		$UniversityFacultetKode = "";
	//}

// запрос параметров специальности
        //$UniversitySpecialitiesKode = filter_input(INPUT_GET, "UniversitySpecialitiesKode", FILTER_SANITIZE_STRING);
        //if (is_null($UniversitySpecialitiesKode)) {
            $UniversitySpecialitiesKode = "";
        //}

// запрос параметров персоны
        //$PersonCodeU = filter_input(INPUT_GET, "PersonCodeU", FILTER_SANITIZE_STRING);
        //if (is_null($PersonCodeU)) {
            $PersonCodeU = "";
        //}

// запрос сотни
        //$Hundred = filter_input(INPUT_GET, "Hundred", FILTER_SANITIZE_STRING);
        //if (is_null($Hundred)) {
            $Hundred = 1;
        //}

// Идентификаторы  статусов  заявок
        $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType1)) {
            $Id_PersonRequestStatusType1 = 0;//1;
        }
        $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType2)) {
            $Id_PersonRequestStatusType2 = 0;//4;
        }
         $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType3)) {
            $Id_PersonRequestStatusType3 = 0;//5;
        }
 
// запрос паарметров формы обучения
        $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 1;
        }

// запрос паарметров квалификации
        $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        if (is_null($Id_Qualification)) {
            $Id_Qualification = 1;
        }
        
		$Id_PersonEducationForm = 1;
		$Id_Qualification = 11;
        
        //GUID универа
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $MinDate = "";
        $Filters = "";
        
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
        $eg->printLastError($sessionId);
        
         
        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];
        
        $count = count($dUniversityFacultetsRequests2);
        
        $alreadyStudents = array ();
        

        // аналитика по фактам обучения
        $EA = new EntrantAnalytycs();
        $alreadyStudents = array();
        if ($analytics != 'false') {
            $alreadyStudents = $EA->getEntrantOtherVUZ(
                $sessionId, 
                $Id_Language,
                $Id_PersonRequestSeasons,
                $ep, 
                $dUniversityFacultetsRequests2);
        }
        
        //print_r($alreadyStudents);


        $arr_request = array();
        $request_PersonCodeU = array(); // заявки по персоне
		$requests = array(); //заявки по ИД
		
	//$count = 5;
        // цикл по заявкам
        for ($i = 0; $i < $count; $i++) {
			
            $item = $dUniversityFacultetsRequests2[$i];
            
            $SpecClasifierCode = $item ['SpecClasifierCode'];
            $ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
            $KonkursValue = $item['KonkursValue'];
            $ExistBenefitsPershocherg = $item['ExistBenefitsPershocherg'];
            $FIO_  = $item['FIO'];
            $FIO  = $item['FIO'];
            
            if (ord($FIO_{1})==132/*'Є'*/) {
                $FIO_ = str_replace('Є','Ея',$FIO_);
            } elseif (ord($FIO_{1}) == 134/*'І'*/) {
                $FIO_ = str_replace('І','К0',$FIO_);
            }
            
            
            $PersonCodeU = $item['PersonCodeU'];
            $Id_PersonRequest = $item['Id_PersonRequest'];
            $RequestPriority = (int)$item['RequestPriority'];

            //заявки по Id_PersonRequest
            $requests[$Id_PersonRequest] = $item;

            $arr_request[] = array(
                'Id_PersonRequest' => $Id_PersonRequest,
                'SpecClasifierCode' => $SpecClasifierCode,
                'ExistBenefitsPozacherg' => $ExistBenefitsPozacherg,
                'KonkursValue' => (int)$KonkursValue,
                'ExistBenefitsPershocherg' => $ExistBenefitsPershocherg,
                'FIO_' => $FIO_,
                'data' => $item,
                );
        }
        

	
        foreach ($arr_request as $key => $row) {
            $arrFIO_[$key]  = $row['FIO_'];
            $arrId_PersonRequest[$key] = $row['Id_PersonRequest'];
        }

        array_multisort($arrFIO_, SORT_ASC, $arrId_PersonRequest, SORT_ASC, $arr_request);
        
        unset($arrFIO_);
        unset($arrId_PersonRequest);
        
        //$arr_request = array_unique($arr_request);
        $result = array();
        $temp = array();
        foreach ($arr_request as $key => $row) {
            $PersonCodeU = $row['data']['PersonCodeU'];
            if (!isset($temp[$PersonCodeU])) {
                $result[] = $row;
                $temp[$PersonCodeU] = $PersonCodeU;
            }
        }
        
        $arr_request = $result;        
       
        
        foreach ($arr_request as $key => $row) {
            $data = $row['data'];
            $arrFIO_[$key]  = $row['FIO_'];
            //'Id_PersonRequest' => $Id_PersonRequest,
            $arrSpecClasifierCode[] = $data['SpecClasifierCode'];
            $arrExistBenefitsPozacherg[] = $data['ExistBenefitsPozacherg'];
            $arrKonkursValue[] = $data['KonkursValue'];
            $arrExistBenefitsPershocherg[] = $data['ExistBenefitsPershocherg'];
            $arrOriginalDocumentsAdd[] = $data['OriginalDocumentsAdd'];
        }

        array_multisort(
            $arrSpecClasifierCode, SORT_ASC, 
            $arrOriginalDocumentsAdd, SORT_DESC, 
            $arrExistBenefitsPozacherg, SORT_DESC, 
            $arrKonkursValue, SORT_DESC,
            $arrExistBenefitsPershocherg, SORT_DESC,
			$arrFIO_, SORT_ASC, 
            $arr_request);
        
        unset($arrSpecClasifierCode);
        unset($arrExistBenefitsPozacherg);
        unset($arrKonkursValue);
        unset($arrExistBenefitsPershocherg);
        ?>
        
        <?php
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        ?>
        
    <?php
    
    // получим все специальности
   

        $data = array();
			
        foreach ($arr_request as $key => $item_) {
            $item = $item_['data'];
            
            $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            $PersonCodeU = $item['PersonCodeU'];    // код персоны
            //$DateCreate = substr($item['DateCreate'],0,10);
            
            //$dPersonRequests2 = $res['dPersonRequests2'];
            
            // Идентификатор специальности ВУЗа
            $Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
            
            // Название направления специальности.
            $SpecDirectionName = $item['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
            $UniversityFacultetFullName = $item['UniversityFacultetFullName'];
			
            // Флаг, показывающий, что персона подала оригинал документов
            $OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            $PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            $CodeOfBusines = $item['CodeOfBusiness'];
			
            $Id_PersonEducationForm = $item['Id_PersonEducationForm'];

            // Идентификатор документа для заявления
            $Id_PersonDocument = $item['Id_PersonDocument'];
            // Конкурсный балл
            $KonkursValue = $item['KonkursValue'];
            
            $Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];

            $UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            $FIO = $item['FIO'];
            
            $ContactMobile = $item['ContactMobile'];
            
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                первоочередного зачисления
             */
            $ExistBenefitsPershocherg = (int) $item['ExistBenefitsPershocherg'];
            
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                внеочередного зачисления
             */
            $ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
            //$sessionId, "", $Id_Language, getDateNow(),
            //print "$sessionId, $Id_Language, $PersonCodeU";
            $res = $ep->PersonsGet2($sessionId, $Id_Language, "'".$PersonCodeU."'");

           
            
            $dPersonsGet2 = $res['dPersonsGet2'];
            //print_r ($res);
            //print '<br><br>';

            $faculty_cur = $item['UniversityFacultetFullName'];

            $PersonEducationFormName = $item['PersonEducationFormName'];

            $last_name = $dPersonsGet2['LastName'];
            $first_name = $dPersonsGet2['FirstName'];
            $middle_name = $dPersonsGet2['MiddleName'];
            $sex = $dPersonsGet2['Id_PersonSex'];
            $birthday = $dPersonsGet2['Birthday'];



            $PersonEducationDateBegin = '';
            $PersonEducationDateEnd = '';

            $sex = $sex==1?'чоловік':'жінка';
            //1994-01-28T00:00:00 
            if (strlen($birthday) < 10) {
                    $birthday = '';
            } else {
                    $tmp = substr($birthday,5,2).'.'.substr($birthday,8,2).'.'.substr($birthday,0,4);
                    $birthday = $tmp;
            }

            // ipn 5
            // passport - 3

            $Id_PersonDocumentType = 5;

            // поиск паспорта и ИНН
            $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, 
                                    $PersonCodeU, 0, 
                                    0, $UniversityKode, -1);
            
             
            $ipn = '';

            $person_document_seria = '';
            $person_document_number = '';
            $person_document_type = 1;
            
            if (count($res)) {
                    $dPersonDocuments = $res['dPersonDocuments'];

                    foreach ($dPersonDocuments as $key => $value) {
                            if ($value['Id_PersonDocumentType'] == 5) {
                                    $ipn = $value['DocumentNumbers'];
                            } elseif ($value['Id_PersonDocumentType'] == 3) {
                                    $person_document_seria = $value['DocumentSeries'];
                                    $person_document_number = $value['DocumentNumbers'];

                            }
                    }
            }
            
            // поиск документа на основании которого было поступление


            $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, 
                                    $PersonCodeU, 0, 
                                    $item['Id_PersonDocument'], $UniversityKode, -1);
            $dPersonDocuments = $res['dPersonDocuments'];
            
           
            
            $prev_document_seria = $dPersonDocuments['DocumentSeries'];
            $prev_document_number = $dPersonDocuments['DocumentNumbers'];
            $prev_document_ID = $dPersonDocuments['Id_PersonDocumentType'];
            
            if (!isset($data[$PersonEducationFormName]))
                    $data[$PersonEducationFormName] = array(); 
            

            if (!isset($data[$PersonEducationFormName][$UniversityFacultetFullName]))
                 $data[$PersonEducationFormName][$UniversityFacultetFullName] = array(); 
            
            $payment = "Бюджет";
            
            $data[$PersonEducationFormName][$UniversityFacultetFullName][] = array(
                'last_name' => $last_name,
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'sex' => $sex,
                'birthday' => $birthday,
                'ipn' => $ipn,
                'person_document_ID' => $person_document_type,
                'person_document_number' => $person_document_number,
                'person_document_seria' => $person_document_seria,
                'prev_document_ID' => $prev_document_ID,
                'prev_document_number' => $prev_document_number,
                'prev_document_seria' => $prev_document_seria,
                'foreigner' => $item['Id_ForeignType'],
                'payment' => $payment
            );
            //print_r($data);
            break;
        }
        
        // формируем файл
        ob_start();
        $newline='\n';
        echo '<students>';
        foreach ($data as $PersonEducationFormName => $value1) {
            foreach ($value1 as $UniversityFacultetFullName => $value2) {
                $group = 'Группа';
                echo '<order>';echo "$newline";
                echo "<time_education>$PersonEducationFormName</time_education> ";echo "$newline";
                echo '<faculty uk="'.$UniversityFacultetFullName.'"/>';echo "$newline";
                echo "<issued>01.10.2014</issued>";echo "$newline";
                echo "<expired>30.06.2015</expired>";echo "$newline";
                echo '<group uk="'.$group.'" />';echo "$newline";
                echo '<order>';echo "$newline";
                foreach ($value2 as $key => $stud) {
                    echo '<student>'; echo "$newline";
                    echo '<last_name uk="'.$stud['last_name'].'" />';echo "$newline";
                    echo '<first_name uk="'.$stud['first_name'].'" />' ;echo "$newline";
                    echo '<middle_name uk="'.$stud['middle_name'].'" />'; echo "$newline";
                    echo '<sex>'.$stud['$sex'].'</sex>'; echo "$newline";
                    echo '<birthday>'.$stud['birthday'].'</birthday>'; echo "$newline";
                    echo '<ipn>'.$stud['ipn'].'</ipn>';echo "$newline";
                    echo '<person_document ID="'.$stud['person_document_ID'].
                        '" number="'.$stud['person_document_number'].'" seria="'.$stud['person_document_seria'].'" />'; echo "$newline";
                    echo '<prev_document ID="'.$stud['prev_document_ID'].'" foreigner="'.
                        $stud['foreigner'].'" number="'.$stud['prev_document_number'].'" seria="'.$stud['prev_document_seria'].'" />'; echo "$newline";
                    echo '<payment>'.$stud['payment'].'</payment>'; echo "$newline";
                    echo "</student>";echo "$newline";
                }
            }
        }
        echo '</students>';
        $xml = ob_get_contents();
        
        ob_clean();
        
        //echo htmlentities($xml);
        
        $filename = 'studentscard.xml'; 
        //$filesave = 'myfile.txt'; 
        header('Content-type: application/octet-stream'); 
        header('Content-Disposition: attachment; filename="'.$filename.'"'); 

        //readfile($filename); 
        echo $xml;

        ?>
     </body>
</html>