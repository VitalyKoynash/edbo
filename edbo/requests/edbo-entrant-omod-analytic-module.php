<?php
/*
echo levenshtein("Hello World","Hello World");
echo "<br>";
echo levenshtein("Hello World","ello World",10,20,30);
return;*/

set_time_limit(600);
include '../edbo-provider/edbo-initsoap.php';
include '../../utils/utils.php';
require_once "../../utils/FGetCSV.php";
include "EntrantAnalytycs.php";

$potential_entrants = array();
//print_r($_FILES);
$Data = array();
if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
    $fn = $_FILES["FileInput"]['tmp_name'];
    $f = @fopen($fn, "r") or die("Could not open \"$fn\" - ".$php_errormsg);
    while ($list = File_FGetCSV::fgetcsv($f, 65536, ";")) {
            $Data[] = $list;
    }
    
   //print_r($Data);
    
    
    
    $i = -1;
    foreach ($Data as $key => $value) {
        if ($i == -1) { $i++;  continue;         }
        //print_r($value);
        
        if (!isset($value[1]) || !isset($value[2]))            continue;
        $FIO = $value[0].' '.$value[1].' '.$value[2];
        
       // $FIO = mb_convert_case($FIO, MB_CASE_UPPER);
        
        $potential_entrants[$i] = array ('idx' => $i, 'FIO' => $FIO, 'data' => $value, 'eq' => array ()) ;
		
    }
    

}
else
{
    //die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}

foreach($Data as $key1 => $value1) {
	foreach($value1 as $key2 => $value2) {
		//mb_convert_case($FIO, MB_CASE_UPPER);
		
		//$Data[$key1]['encode1'] = mb_detect_encoding ($Data[$key1][$key2], "auto");
		//$Data[$key1][$key2] = mb_convert_encoding($value2, "ASCII", "auto");//ASCII
		$Data[$key1]['encode2'] = mb_detect_encoding ($Data[$key1][$key2], "auto");
	}
}

print_r($Data);
//return;

ob_start();
function get_entrants ($sessionId,
            $Id_PersonEducationForm,
            $Id_Qualification) {
  global  $eg, $ep;
  	$res = $eg->{'LanguagesGet'}($sessionId);
	$eg->printLastError($sessionId);
	$dLanguages = $res['dLanguages'];
	$Id_Language = $dLanguages['Id_Language'];
        
	// обработка запрета выполнения анализа факта обучения в другом вузе
        $analytics = 'true';
        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
        $UniversityFacultetKode = "";
        $UniversitySpecialitiesKode = "";
        $PersonCodeU = "";
        $Hundred = 1;
        $Id_PersonRequestStatusType1 = 0;//1;
        $Id_PersonRequestStatusType2 = 0;//4;
        $Id_PersonRequestStatusType3 = 0;//5;
 
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $MinDate = "";
        $Filters = "";
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
        
        $arr_request = array();
        //$request_PersonCodeU = array(); // заявки по персоне
        //$requests = array(); //заявки по ИД
		
$count = 5;
        // цикл по заявкам
        for ($i = 0; $i < $count; $i++) {
			
            $item = $dUniversityFacultetsRequests2[$i];
            
            //print_r($item);
            //return ob_get_contents();
            
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
           // $requests[$Id_PersonRequest] = $item;
               $fio_ = $FIO;//mb_convert_case($FIO, MB_CASE_UPPER);
            $arr_request[] = array(
                'Id_PersonRequest' => $Id_PersonRequest,
                //'SpecClasifierCode' => $SpecClasifierCode,
                //'ExistBenefitsPozacherg' => $ExistBenefitsPozacherg,
                //'KonkursValue' => (int)$KonkursValue,
                //'ExistBenefitsPershocherg' => $ExistBenefitsPershocherg,
                'FIO' => $fio_,
                'FIO_' => $FIO_,
                'data' => $item,
                'potentials' => array()
                );
        }
		
	$new_arr =array();
	foreach($arr_request as $key1 => $value1) {
	foreach($value1 as $key2 => $value2) {
		//mb_convert_case($FIO, MB_CASE_UPPER);
		
		//$Data[$key1]['encode1'] = mb_detect_encoding ($Data[$key1][$key2], "auto");
		//$arr_request[$key1][$key2] = mb_convert_encoding($value2, "ASCII", "UTF-8");//ASCII
		$arr_request[$key1]['encode2'] = mb_detect_encoding ($arr_request[$key1]['FIO'], "auto");
		$new_arr[$key1]['FIO'] = $arr_request[$key1]['FIO'];
		$new_arr[$key1]['encode'] = mb_detect_encoding ($new_arr[$key1]['FIO'], "auto");
	}
	}
	return $new_arr;


	
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
        
       
        /*
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
        */
        
        return $arr_request;
    
};



$sessionId = filter_input(INPUT_POST, "sessionId", FILTER_SANITIZE_STRING);
        
	//$caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
        
	//echo '<div class="caption">'.$caption.'</div>';
	

$entrants = get_entrants ($sessionId,
            1,
            1);


print_r($entrants);
return;
//return;

$arr_res = array();
$found = 0;
$id = '';
foreach ($entrants as $key1 => $item_entrant) {
    $FIO_entrant = $item_entrant['FIO'];
    
    foreach ($potential_entrants as $key2 => $item_pot_entrant) {
        $FIO_pot_entrant = $item_pot_entrant['FIO'];
        
        $res = levenshtein($FIO_entrant, $FIO_pot_entrant);
        if ($res < 6) {
            $found++;
            $entrants[$key1]['potentials'][] = $key2;
            $id.=','.$FIO_pot_entrant."($key2) # ";
        }
		
		if (!isset($arr_res[$key1])) 
			$arr_res[$key1] = -100000000000;
			
		$arr_res[$key1] = max($arr_res[$key1], $res);
    }
}

$res = array (
    'msg' => ob_get_contents(),
    'data' =>  $entrants,
    'found' => $found,
    'id' => $id,
	'res' => $arr_res
    );

ob_clean();

echo json_encode($res);
return;