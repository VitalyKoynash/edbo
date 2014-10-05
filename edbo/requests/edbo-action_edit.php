<?php
$sessionId = filter_input(INPUT_POST, "sessionId", FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);




include '../edbo-provider/edbo-initsoap.php';
include '../../utils/utils.php';

ob_start();

if (is_null($ep)) {
    echo '[FAILED]';
    return;
} 

//Так как все данные приходят в кодировке UTF при необходимости
//их можно/нужно конвертировать в нужную, но мы этого делать не будем

//$data = iconv("utf-8", "windows-1251", $data);
//$data1 = iconv("utf-8", "windows-1251", $data1);

/*
тут можно делать все что угодно с полученными данными, а мы их просто выведем на печать.
*/

$res = $eg->{'LanguagesGet'}($sessionId);
    //$eg->printLastError($sessionId);
    $dLanguages = $res['dLanguages'];
    $Id_Language = $dLanguages['Id_Language'];
	
if (is_null($action) || is_null($sessionId)) {
    echo '[FAILED]';
    return;
}

// обработка действия - смена парметра оригинала
// $OriginalDocumentsAdd - старое значение
if (strcmp ($action, 'od_change')==0) {
    
    $PersonCodeU = filter_input(INPUT_POST, "PersonCodeU", FILTER_SANITIZE_STRING);
    $Id_PersonRequest = filter_input(INPUT_POST, "Id_PersonRequest", FILTER_SANITIZE_STRING);
    $OriginalDocumentsAdd = filter_input(INPUT_POST, "OriginalDocumentsAdd", FILTER_SANITIZE_STRING);
    $CodeOfBusines = filter_input(INPUT_POST, "CodeOfBusines", FILTER_SANITIZE_STRING);

    if (is_null($OriginalDocumentsAdd) || is_null ($Id_PersonRequest))
        echo '[FAILED]';

    $OriginalDocumentsAdd = (int) $OriginalDocumentsAdd;
    $Id_PersonRequest = (int) $Id_PersonRequest;


    $new_val = ($OriginalDocumentsAdd == 0) ? 1 : 0;

    try {
        $res = $ep->PersonRequestOriginalDocumentChange($sessionId, $Id_PersonRequest, $new_val);
    } catch (Exception $ex) {
        echo $OriginalDocumentsAdd;
        return;
    }
    
    if ($res == 1)
        echo $new_val;
    else
        echo $OriginalDocumentsAdd;
    return;
	
} else if (strcmp ($action, 'EntrantDocumentValue_change')==0) {
    
  
    // изменим средний балл документа
    $Id_PersonDocument = filter_input(INPUT_POST, "Id_PersonDocument", FILTER_SANITIZE_STRING);
    $UniversityKode = filter_input(INPUT_POST, "UniversityKode", FILTER_SANITIZE_STRING);
    $EntrantDocumentValue = filter_input(INPUT_POST, "EntrantDocumentValue", FILTER_SANITIZE_STRING);
    $IsCheckForPaperCopy = filter_input(INPUT_POST, "IsCheckForPaperCopy", FILTER_SANITIZE_STRING);
    
    if (is_null($Id_PersonDocument) || is_null ($UniversityKode) || is_null($EntrantDocumentValue)
        || is_null($IsCheckForPaperCopy))
        echo '[FAILED] 1';

    //$Id_PersonDocument = (int) $OriginalDocumentsAdd;
   // echo '[FAILED] ';
    //echo "$sessionId, $EntrantDocumentValue, $IsCheckForPaperCopy, $UniversityKode, $Id_PersonDocument";
    //return;
    try {
        $res = $ep->EntrantDocumentValueChange ($sessionId, $EntrantDocumentValue,
            $IsCheckForPaperCopy, $UniversityKode, $Id_PersonDocument);
    } catch (Exception $ex) {
        echo  '[FAILED] '.$ex->getMessage();
        return;
    }
    
    if ($res == 1)
        echo $EntrantDocumentValue;
    else
        echo '[FAILED] 2';
        $ep->printLastError($sessionId);
    return;
	
} elseif (strcmp ($action, 'od_change')==0) {
    
    $PersonCodeU = filter_input(INPUT_POST, "PersonCodeU", FILTER_SANITIZE_STRING);
    $Id_PersonRequest = filter_input(INPUT_POST, "Id_PersonRequest", FILTER_SANITIZE_STRING);
    $OriginalDocumentsAdd = filter_input(INPUT_POST, "OriginalDocumentsAdd", FILTER_SANITIZE_STRING);
    $CodeOfBusines = filter_input(INPUT_POST, "CodeOfBusines", FILTER_SANITIZE_STRING);

    if (is_null($OriginalDocumentsAdd) || is_null ($Id_PersonRequest))
        echo '[FAILED]';

    $OriginalDocumentsAdd = (int) $OriginalDocumentsAdd;
    $Id_PersonRequest = (int) $Id_PersonRequest;


    $new_val = ($OriginalDocumentsAdd == 0) ? 1 : 0;

    try {
        $res = $ep->PersonRequestOriginalDocumentChange($sessionId, $Id_PersonRequest, $new_val);
    } catch (Exception $ex) {
        echo $OriginalDocumentsAdd;
        return;
    }
    
    if ($res == 1)
        echo $new_val;
    else
        echo $OriginalDocumentsAdd;
    return;
	
} elseif (strcmp ($action, 'exam_change')==0) {
    
    $SkipDocumentValueByExam = array();
    // настройка учета балла документа
    // по Id_Qualification
    $SkipDocumentValueByExam[1] = 0; // Бакалавр (дневное)
    $SkipDocumentValueByExam[11] = 1; // Бакалавр (дневное ускор)
    $SkipDocumentValueByExam[3] = 1; // дневное специалисты
    $SkipDocumentValueByExam[2] = 1; // дневное магистры
    $SkipDocumentValueByExam[14] = 1; // Бакалавр (заочное ускор
    
    $OriginalDocumentsAdd = filter_input(INPUT_POST, "OriginalDocumentsAdd", FILTER_SANITIZE_STRING);
    $IsNeedHostel = filter_input(INPUT_POST, "IsNeedHostel", FILTER_SANITIZE_STRING);
    $IsBudget = filter_input(INPUT_POST, "IsBudget", FILTER_SANITIZE_STRING);
    $IsContract = filter_input(INPUT_POST, "IsContract", FILTER_SANITIZE_STRING);
    $IsHigherEducation = filter_input(INPUT_POST, "IsHigherEducation", FILTER_SANITIZE_STRING);
    $CodeOfBusiness = filter_input(INPUT_POST, "CodeOfBusiness", FILTER_SANITIZE_STRING);
    $IsForeignWay = filter_input(INPUT_POST, "IsForeignWay", FILTER_SANITIZE_STRING);
  
    // изменим средний балл документа
    $Id_PersonRequest = filter_input(INPUT_POST, "Id_PersonRequest", FILTER_SANITIZE_STRING);
    $Id_UniversitySpecialitiesSubject = filter_input(INPUT_POST, "Id_UniversitySpecialitiesSubject", FILTER_SANITIZE_STRING);
    $PersonRequestExaminationValue = filter_input(INPUT_POST, "PersonRequestExaminationValue", FILTER_SANITIZE_STRING);
    
    // для отключения учета баллов документа
    $Id_Qualification = (int) filter_input(INPUT_POST, "Id_Qualification", FILTER_SANITIZE_STRING);
    $Id_PersonEducationForm = filter_input(INPUT_POST, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
	
    // пробуем получить заявки
    $data = array();
    
     // настройка учета балла документа
    // по Id_Qualification
    $SkipDocumentValue = 1;
    $data['Id_Qualification'] = $Id_Qualification;
    
    $data['post'] = $_POST;
    
   //  echo json_encode($data);
	//	return;
                
    if (isset($SkipDocumentValueByExam[$Id_Qualification])) {
        $SkipDocumentValue = $SkipDocumentValueByExam[$Id_Qualification];

    }
	
	//if ($Id_Qualification)
        
    $ep->PersonRequestEdit2($sessionId, $Id_PersonRequest, $OriginalDocumentsAdd,
        $IsNeedHostel,  $CodeOfBusiness, $IsBudget, $IsContract,
        $IsHigherEducation, $SkipDocumentValue, 0, 0, $IsForeignWay);
    $data['err_PersonRequestEdit2'] = $ep->GetLastError($sessionId);


	
    if (is_null($Id_PersonRequest) || 
        is_null ($Id_UniversitySpecialitiesSubject) || 
        is_null($PersonRequestExaminationValue)){
       echo json_encode($data);
		return;
	}

    //$Id_PersonDocument = (int) $OriginalDocumentsAdd;
    //echo '[FAILED] ';
    //echo $sessionId,' , ', $Id_PersonRequest,' , ', $Id_UniversitySpecialitiesSubject,' , ', $PersonRequestExaminationValue;
    //return;
	

	
	$data['global'] = ob_get_contents();
	$res = $ep->PersonRequestExaminationsGet ($sessionId, getDateNow(), $Id_Language, $Id_PersonRequest);
	

	$data['res_PersonRequestExaminationsGet'] = $res;
	$data['err_PersonRequestExaminationsGet'] = $ep->GetLastError($sessionId);
	// echo json_encode($data);
    //    return;

	$dPersonRequestExaminations = '';
	$Id_PersonRequestExamination = 0;
	
	$dPersonRequestExaminations = array();
	
	if (count($res) != 0) {
		// нет экзаменов вообще
		$dPersonRequestExaminations = $res['dPersonRequestExaminations'];
	}
	
	$data['status'] = 'не было экзаменов';
	
	if (count($dPersonRequestExaminations) == 0) {
	// нет экзаменов
		$data['status'] = 'не было экзаменов';
	} elseif ( isset( $dPersonRequestExaminations[0]))	{
	// много экзаменов
		foreach ($dPersonRequestExaminations as $key => $value) {
			if ($value['Id_UniversitySpecialitiesSubject'] == $Id_UniversitySpecialitiesSubject) {
			// будем менять
				$Id_PersonRequestExamination = $value['Id_PersonRequestExamination'];
				$data['status'] = 'был экзамен';
				break;
			}
		}
	} elseif (isset($dPersonRequestExaminations['Id_PersonRequestExamination'])) {
	// один экзамен
		if ($dPersonRequestExaminations['Id_UniversitySpecialitiesSubject'] == $Id_UniversitySpecialitiesSubject) {
			// будем менять
			$Id_PersonRequestExamination = $dPersonRequestExaminations['Id_PersonRequestExamination'];
			$data['status'] = 'не было экзаменов';
		}
	}
	if ($Id_PersonRequestExamination == 0) {
	//add examen
		try {
			$res = $ep->PersonRequestExaminationsAdd($sessionId, $Id_Language, $Id_PersonRequest, 
			$Id_UniversitySpecialitiesSubject, $PersonRequestExaminationValue);
			
			$data['res_PersonRequestExaminationsAdd'] = $res;
			$data['err_PersonRequestExaminationsAdd'] = $ep->GetLastError($sessionId);
			$data['status'] = 'добавили экзамен';
			$data['global'] = ob_get_contents();
			echo json_encode($data);
			return;
	
		} catch (Exception $ex) {
			$data['err_PersonRequestExaminationsAdd'] = $ep->GetLastError($sessionId);
			$data['global'] = ob_get_contents();
			echo json_encode($data);
			return;
		}
	}
	
	// меняем экзамен
	try {
		$res = $ep->PersonRequestExaminationsValueChange (
		$sessionId, $Id_PersonRequestExamination, $PersonRequestExaminationValue);
		
		$data['status'] = 'обновили экзаменов';
		$data['new_val'] = $PersonRequestExaminationValue;
		
	} catch (Exception $ex) {
        $data['err_PersonRequestExaminationsAdd'] = $ep->GetLastError($sessionId);
		$data['global'] = ob_get_contents();
		echo json_encode($data);
		return;
        }
        /*
    $OriginalDocumentsAdd = filter_input(INPUT_POST, "OriginalDocumentsAdd", FILTER_SANITIZE_STRING);
    $IsNeedHostel = filter_input(INPUT_POST, "IsNeedHostel", FILTER_SANITIZE_STRING);
    $IsBudget = filter_input(INPUT_POST, "IsBudget", FILTER_SANITIZE_STRING);
    $IsContract = filter_input(INPUT_POST, "IsContract", FILTER_SANITIZE_STRING);
    $IsHigherEducation = filter_input(INPUT_POST, "IsHigherEducation", FILTER_SANITIZE_STRING);
	*/
        

     
    
    
    echo json_encode($data);
	return;
	
}
    


echo json_encode(
array(
'err'=>1
)
);
return;

