<?php

set_time_limit(60*60);
ob_start();

$sessionId = filter_input(INPUT_POST, "sessionId", FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$cacheKey = filter_input(INPUT_POST, "cacheKey", FILTER_SANITIZE_STRING);

include '../edbo-provider/edbo-initsoap.php';
include '../../utils/utils.php';

function get_all_requests($sessionId, $cacheKey, $updateCache) {
        global $eg, $ep;
        
        $params = array();
        $params['cacheDir'] = "../../cache/";
        
        if (!is_null($cacheKey) && $updateCache != true) {
            $res = get_from_cache ($cacheKey,  $params);
            if ($res === null) {
            }else 
                return $res;
        }
        
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];

        $Id_PersonEducationForm = 0;
        $Id_Qualification = 0;
        // обработка запрета выполнения анализа факта обучения в другом вузе

        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
        $UniversityFacultetKode = "";
        $UniversitySpecialitiesKode = "";
        $PersonCodeU = "";
        $Hundred = 1;
        $Id_PersonRequestStatusType1 = 0; //1;
        $Id_PersonRequestStatusType2 = 0; //4;
        $Id_PersonRequestStatusType3 = 0; //5;

        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];

        $MinDate = "";
        $Filters = "";
        // получим все заявки
        $res = $eg->UniversityFacultetsGetRequests2(
            $sessionId, $Id_PersonRequestSeasons, $UniversityFacultetKode, $UniversitySpecialitiesKode, $Id_Language, getDateNow(), $PersonCodeU, $Hundred, $MinDate, $Id_PersonRequestStatusType1, $Id_PersonRequestStatusType2, $Id_PersonRequestStatusType3, $Id_PersonEducationForm, $UniversityKode, $Id_Qualification, $Filters
        );
        //$eg->printLastError($sessionId);


        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];
        $res = save_to_cache ($dUniversityFacultetsRequests2, $cacheKey,  $params);
        return $dUniversityFacultetsRequests2;
    };
    
$result['input']['action'] = $action;
$result['input']['cacheKey'] = $cacheKey;
$result['error']['code'] = 0;
$result['error']['message'] = '';

if ($action == 'get_without_inn') {
     $requests = get_all_requests($sessionId);
     echo json_encode($requests);
     return;
     
     $EDBO_INN = array();
     foreach ($requests as $key => $item) {
         $PersonCodeU = $item['PersonCodeU'];
         $UniversityKode = $item['UniversityKode'];
         
         if (isset($EDBO_INN_[$PersonCodeU]))             continue;
         
         $EDBO_ALL_REQUESTS[$PersonCodeU] = $item;
         
         $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, 
                                    $PersonCodeU, 5, 
                                    0, $UniversityKode, -1);
         if (!is_null($res) && count($res) > 0) {
            $dPersonDocuments = $res['dPersonDocuments'];
            
            $ipn = $dPersonDocuments['DocumentNumbers'];
         
            $EDBO_INN[$PersonCodeU] = $ipn;
         }
         
     }
     
     $title[] = array("title" => 'ФИО');
     $title[] = array("title" => 'ИНН');
     $result['data_title'] = $title;
     $table = array();
     
     foreach ($EDBO_ALL_REQUESTS as $key => $item) {
         
         if (!isset($EDBO_INN[$item['PersonCodeU']])) {
             $table_item = array();
             $table_item[] = $item['FIO'];
             $table_item[] = '';
             
             $table[] = $table_item;
         }
     }
    $result['echo'] = ob_get_clean();
    echo json_encode($result);
    
   

    
     
     
}
return;

$Data = array();
if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == UPLOAD_ERR_OK) {
    $fn = $_FILES["csv_file"]['tmp_name'];
    $f = @fopen($fn, "r") or die("Could not open \"$fn\" - " . $php_errormsg);
    while ($list = File_FGetCSV::fgetcsv($f, 65536, ";")) {
        $Data[] = $list;
    }

    //print_r($Data);

    $shema = array();

    foreach ($Data as $idx => $value) {
        foreach ($value as $idx2 => $name_column) {
            $shema[mb_trim($name_column)] = $idx2;
        }
        break;
    }

    $data_by_fio_key = array();
    //print_r($shema);
    //var_dump($shema);
    
    //echo $shema['LastName'], '<br>';
    //echo '<br>';
    $idx = -1;
    foreach ($Data as $key => $value) {
         $idx++;
        if ($idx == 0) { // skip headers
            continue;
        }
        $FIO_key = $Data[$idx][$shema['LastName']].' '.
                        $Data[$idx][$shema['FirstName']].' '.
                        $Data[$idx][$shema['MiddleName']];
        //$FIO_key = GetInTranslit(mb_ereg_replace('\s{1,}','',$FIO_key));
        $data_by_fio_key[$FIO_key] = $value;
        
        //echo $FIO_key,' ',mb_detect_encoding ($FIO_key, "auto"),'<br>';
        //mb_str_re
       //print_r($shema);
       //echo '<br>';
        //echo $shema['desc'], '<br>';
    }
    unset($idx);
    
    //print_r($data_by_fio_key);

    //return;
 
    function get_all_requests($sessionId) {
        global $eg, $ep;
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];

        $Id_PersonEducationForm = 0;
        $Id_Qualification = 0;
        // обработка запрета выполнения анализа факта обучения в другом вузе

        $Id_PersonRequestSeasons = $ep->getActualPersonRequestSeason($sessionId, $Id_Language);
        $UniversityFacultetKode = "";
        $UniversitySpecialitiesKode = "";
        $PersonCodeU = "";
        $Hundred = 1;
        $Id_PersonRequestStatusType1 = 0; //1;
        $Id_PersonRequestStatusType2 = 0; //4;
        $Id_PersonRequestStatusType3 = 0; //5;

        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        $UniversityKode = $dUniversities['UniversityKode'];

        $MinDate = "";
        $Filters = "";
        // получим все заявки
        $res = $eg->UniversityFacultetsGetRequests2(
            $sessionId, $Id_PersonRequestSeasons, $UniversityFacultetKode, $UniversitySpecialitiesKode, $Id_Language, getDateNow(), $PersonCodeU, $Hundred, $MinDate, $Id_PersonRequestStatusType1, $Id_PersonRequestStatusType2, $Id_PersonRequestStatusType3, $Id_PersonEducationForm, $UniversityKode, $Id_Qualification, $Filters
        );
        //$eg->printLastError($sessionId);


        $dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];

        return $dUniversityFacultetsRequests2;
    };
    
    $sessionId = filter_input(INPUT_POST, "sessionId", FILTER_SANITIZE_STRING);
    
    $requests = get_all_requests($sessionId);
    
    echo '<br>';
    foreach ($requests as $key => $item) {
        $CodeOfBusiness = $item['CodeOfBusiness'];
        $FIO = $item['FIO'];
        $Id_PersonRequest = $item['Id_PersonRequest'];
        //$FIO = GetInTranslit(mb_ereg_replace('\s{1,}','',$FIO));
        
        if (mb_strlen($CodeOfBusiness) == 0) {
            
            $data = @$data_by_fio_key[$FIO];
            
            if (is_null($data))                continue;
            
            $new_CodeOfBusiness = @$data[$shema['CodeOfBusiness']];
            
            if (is_null($new_CodeOfBusiness)) {
                echo "$FIO номер дела не найден ";   
            } else {
            
                if ($ep->PersonRequestCodeOfBuisnessEdit($sessionId, $Id_PersonRequest, $new_CodeOfBusiness) == 1) {
            
                //echo "$FIO ", is_null($data)?'Не нашлось':'Нашлось';//,' ',mb_detect_encoding ($FIO_key, "auto");
                    echo "$FIO установлен номер дела ", $data[$shema['CodeOfBusiness']];
                } else {
                    echo "$FIO ошибка установки номер дела ", $data[$shema['CodeOfBusiness']];
                }
            }
            //print_r($data);
            echo '<br>';
        }
    }
    

} else {
    echo 'Ошибка загрузки файла!';
    return;
    //die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
