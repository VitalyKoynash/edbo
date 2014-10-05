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
    $Id_PersonRequestStatusType1 = 7; //1; 7 - До наказу
}
$Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
if (is_null($Id_PersonRequestStatusType2)) {
    $Id_PersonRequestStatusType2 = 0; //4;
}
$Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
if (is_null($Id_PersonRequestStatusType3)) {
    $Id_PersonRequestStatusType3 = 0; //5;
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

$Id_PersonEducationForm = 0;
$Id_Qualification = 0;

//GUID универа
$res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
$dUniversities = $res['dUniversities'];
$UniversityKode = $dUniversities['UniversityKode'];

$MinDate = "";
$Filters = "";

$timer = microtime(TRUE);
// получим все заявки
$res = $eg->UniversityFacultetsGetRequests2(
    $sessionId, $Id_PersonRequestSeasons, $UniversityFacultetKode, $UniversitySpecialitiesKode, $Id_Language, getDateNow(), $PersonCodeU, $Hundred, $MinDate, $Id_PersonRequestStatusType1, $Id_PersonRequestStatusType2, $Id_PersonRequestStatusType3, $Id_PersonEducationForm, $UniversityKode, $Id_Qualification, $Filters
);
$eg->printLastError($sessionId);


$dUniversityFacultetsRequests2 = $res['dUniversityFacultetsRequests2'];

$count = count($dUniversityFacultetsRequests2);

/*
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
 */

//print_r($alreadyStudents);

function GetDoc4EducationByEDBOId($id) {
    $res = array();
    $res['id'] = 0;
    $res['name'] = '';

    if ($id == 2) {
        $res['id'] = 2;
        $res['name'] = 'атестат про повну загальну освіту';
    } elseif ($id == 9) {
        $res['id'] = 3;
        $res['name'] = 'Диплом кваліфікованого робітника';
    } elseif ($id == 10) {
        $res['id'] = 5;
        $res['name'] = 'Диплом молодшого спеціаліста';
    } elseif ($id == 11) {
        $res['id'] = 6;
        $res['name'] = 'Диплом бакалавра';
    } elseif ($id == 12) {
        $res['id'] = 7;
        $res['name'] = 'Диплом спеціаліста';
    } elseif ($id == 13) {
        $res['id'] = 8;
        $res['name'] = 'Диплом магістра';
    }

    return $res;
}

$arr_request = array();
$request_PersonCodeU = array(); // заявки по персоне
$requests = array(); //заявки по ИД

$arr_request = $dUniversityFacultetsRequests2;

//$count = 5;
// цикл по заявкам
/*
  for ($i = 0; $i < $count; $i++) {

  $item = $dUniversityFacultetsRequests2[$i];

  $SpecClasifierCode = $item ['SpecClasifierCode'];
  $ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
  $KonkursValue = $item['KonkursValue'];
  $ExistBenefitsPershocherg = $item['ExistBenefitsPershocherg'];
  $FIO_  = $item['FIO'];
  $FIO  = $item['FIO'];

  if (ord($FIO_{1})==132) { //'Є'
  $FIO_ = str_replace('Є','Ея',$FIO_);
  } elseif (ord($FIO_{1}) == 134) { //'І'
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
 */

/*
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
 */
// получим специальности
$res = $eg->UniversityFacultetSpecialitiesGet($sessionId, "7e66bbdb-ce84-4e2c-9767-3346253175b0", "", "", $Id_Language, getDateNow(), $ep->getActualPersonRequestSeason($sessionId, $Id_Language), 0, "", "", "", "");

$dUniversityFacultetSpecialities = $res['dUniversityFacultetSpecialities'];

$spec = array();

foreach ($dUniversityFacultetSpecialities as $key => $value) {
    $spec[$value['Id_UniversitySpecialities']] = $value;
}
//print_r($spec);
//return;
//$max = 10;
$cur = 0;
// Id_PersonEducationForm 1 - денна 2 - заочна
// Id_Qualification 1 - бакалавры
//					3 - специалистам д/о
//					2 - магистрам д/о
//					11 - бакалавры ускор д/о
//					14 - Бакалавр (заочное ускор.)

$specialities = array();

$y = date('y');
//Id_PersonEducationForm
// д/о
$specialities[1] = array(
    //Id_Qualification - бакавры
    1 => array(//бакавры
        '6.030504' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП-' . $y . '-1'),
        '6.030508' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК-' . $y . '-1'),
        '6.030509' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА-' . $y . '-1'),
        '6.030601' => array(
            'name' => 'менеджмент',
            'group' => 'МН-' . $y . '-1'),
        '6.040303' => array(
            'name' => 'системний аналіз',
            'group' => 'СМ-' . $y . '-1'),
        '6.050101' => array(
            'name' => 'комп’ютерні науки',
            'group' => 'ІТ-' . $y . '-1'),
        '6.050202' => array(
            'name' => 'автоматизація та комп’ютерно-інтегровані технології',
            'group' => 'АВП-' . $y . '-1'),
        '6.050401' => array(
            'name' => 'металургія',
            'group' => 'ОМТ-' . $y . '-1'),
        '6.050402' => array(
            'name' => 'ливарне виробництво',
            'group' => 'ЛВ-' . $y . '-1'),
        '6.050502' => array(
            'name' => 'інженерна механіка',
            'group' => 'ІМ-' . $y . '-1'),
        '6.050503' => array(
            'name' => 'машинобудування',
            'group' => 'МАШ-' . $y . '-1'),
        '6.050504' => array(
            'name' => 'зварювання',
            'group' => 'ЗВ-' . $y . '-1'),
        '6.050702' => array(
            'name' => 'електромеханіка',
            'group' => 'ЕСА-' . $y . '-1'),
    ),
    11 => array(//бакавры
        '6.030504' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП-' . $y . '-1т'),
        '6.030508' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК-' . $y . '-1т'),
        '6.030509' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА-' . $y . '-1т'),
        '6.030601' => array(
            'name' => 'менеджмент',
            'group' => 'МН-' . $y . '-1т'),
        '6.040303' => array(
            'name' => 'системний аналіз',
            'group' => 'СМ-' . $y . '-1т'),
        '6.050101' => array(
            'name' => 'комп’ютерні науки',
            'group' => 'ІТ-' . $y . '-1т'),
        '6.050202' => array(
            'name' => 'автоматизація та комп’ютерно-інтегровані технології',
            'group' => 'АВП-' . $y . '-1т'),
        '6.050401' => array(
            'name' => 'металургія',
            'group' => 'ОМТ-' . $y . '-1т'),
        '6.050402' => array(
            'name' => 'ливарне виробництво',
            'group' => 'ЛВ-' . $y . '-1т'),
        '6.050502' => array(
            'name' => 'інженерна механіка',
            'group' => 'ІМ-' . $y . '-1т'),
        '6.050503' => array(
            'name' => 'машинобудування',
            'group' => 'МАШ-' . $y . '-1т'),
        '6.050504' => array(
            'name' => 'зварювання',
            'group' => 'ЗВ-' . $y . '-1т'),
        '6.050702' => array(
            'name' => 'електромеханіка',
            'group' => 'ЕСА-' . $y . '-1т'),
    ),
    3 => array(//специалистамд/о
        '7.03050401' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП-' . ($y - 4) . '-1'),
        '7.03050801' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК-' . ($y - 4) . '-1'),
        '7.03050901' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА-' . ($y - 4) . '-1'),
        '7.03060101' => array(
            'name' => 'менеджмент організацій і адміністрування',
            'group' => 'МН-' . ($y - 4) . '-1'),
        '7.04030302' => array(
            'name' => 'системи і методи прийняття рішень',
            'group' => 'СМ-' . ($y - 4) . '-1'),
        '7.05010102' => array(
            'name' => 'інформаційні технології проектування',
            'group' => 'ІТ-' . ($y - 4) . '-1'),
        '7.05020201' => array(
            'name' => 'автоматизоване управління технологічними процесами',
            'group' => 'АВП-' . ($y - 4) . '-1'),
        '7.05040104' => array(
            'name' => 'обробка металів тиском',
            'group' => 'ОМТ-' . ($y - 4) . '-1'),
        '7.05040201' => array(
            'name' => 'ливарне виробництво чорних та кольорових металів і сплавів',
            'group' => 'ЛВ-' . ($y - 4) . '-1'),
        '7.05050201' => array(
            'name' => 'технології машинобудування',
            'group' => 'ТМ-' . ($y - 4) . '-1'),
        '7.05050203' => array(
            'name' => 'обладнання та технології пластичного формування конструкцій машинобудування',
            'group' => 'ОТП-' . ($y - 4) . '-1'),
        '7.05050301' => array(
            'name' => 'металорізальні верстати та системи',
            'group' => 'МВС-' . ($y - 4) . '-1'),
        '7.05050302' => array(
            'name' => 'інструментальне виробництво',
            'group' => 'ІВ-' . ($y - 4) . '-1'),
        '7.05050308' => array(
            'name' => 'підйомно-транспортні, дорожні, будівельні, меліоративні машини і обладнання',
            'group' => 'ПТМ-' . ($y - 4) . '-1'),
        '7.05050311' => array(
            'name' => 'металургійне обладнання',
            'group' => 'МО-' . ($y - 4) . '-1'),
        '7.05050401' => array(
            'name' => 'технології та устаткування зварювання',
            'group' => 'ЗВ-' . ($y - 4) . '-1'),
        '7.05070204' => array(
            'name' => 'електромеханічні системи автоматизації та електропривод',
            'group' => 'ЕСА-' . ($y - 4) . '-1'),
    ),
    2 => array(//магістри д/в
        '8.03050401' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП-' . ($y - 4) . '-1м'),
        '8.03050801' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК-' . ($y - 4) . '-1м'),
        '8.03050901' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА-' . ($y - 4) . '-1м'),
        '8.03060101' => array(
            'name' => 'менеджмент організацій і адміністрування',
            'group' => 'МН-' . ($y - 4) . '-1м'),
        '8.04030302' => array(
            'name' => 'системи і методи прийняття рішень',
            'group' => 'СМ-' . ($y - 4) . '-1м'),
        '8.05010102' => array(
            'name' => 'інформаційні технології проектування',
            'group' => 'ІТ-' . ($y - 4) . '-1м'),
        '8.05020201' => array(
            'name' => 'автоматизоване управління технологічними процесами',
            'group' => 'АВП-' . ($y - 4) . '-1м'),
        '8.05040104' => array(
            'name' => 'обробка металів тиском',
            'group' => 'ОМТ-' . ($y - 4) . '-1м'),
        '8.05040201' => array(
            'name' => 'ливарне виробництво чорних та кольорових металів і сплавів',
            'group' => 'ЛВ-' . ($y - 4) . '-1м'),
        '8.05050201' => array(
            'name' => 'технології машинобудування',
            'group' => 'ТМ-' . ($y - 4) . '-1м'),
        '8.05050203' => array(
            'name' => 'обладнання та технології пластичного формування конструкцій машинобудування',
            'group' => 'ОТП-' . ($y - 4) . '-1м'),
        '8.05050301' => array(
            'name' => 'металорізальні верстати та системи',
            'group' => 'МВС-' . ($y - 4) . '-1м'),
        '8.05050302' => array(
            'name' => 'інструментальне виробництво',
            'group' => 'ІВ-' . ($y - 4) . '-1м'),
        '8.05050308' => array(
            'name' => 'підйомно-транспортні, дорожні, будівельні, меліоративні машини і обладнання',
            'group' => 'ПТМ-' . ($y - 4) . '-1м'),
        '8.05050311' => array(
            'name' => 'металургійне обладнання',
            'group' => 'МО-' . ($y - 4) . '-1м'),
        '8.05050401' => array(
            'name' => 'технології та устаткування зварювання',
            'group' => 'ЗВ-' . ($y - 4) . '-1м'),
        '8.05070204' => array(
            'name' => 'електромеханічні системи автоматизації та електропривод',
            'group' => 'ЕСА-' . ($y - 4) . '-1м'),
    )
);


$specialities[2] = array(
    //Id_Qualification - бакавры
    1 => array(//бакавры
        '6.030504' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП' . $y . '-1з'),
        '6.030508' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК' . $y . '-1з'),
        '6.030509' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА' . $y . '-1з'),
        '6.030601' => array(
            'name' => 'менеджмент',
            'group' => 'МН' . $y . '-1з'),
        '6.040303' => array(
            'name' => 'системний аналіз',
            'group' => 'СМ' . $y . '-1з'),
        '6.050101' => array(
            'name' => 'комп’ютерні науки',
            'group' => 'ІТ' . $y . '-1з'),
        '6.050202' => array(
            'name' => 'автоматизація та комп’ютерно-інтегровані технології',
            'group' => 'АВП' . $y . '-1з'),
        '6.050401' => array(
            'name' => 'металургія',
            'group' => 'ОМТ' . $y . '-1з'),
        '6.050402' => array(
            'name' => 'ливарне виробництво',
            'group' => 'ЛВ' . $y . '-1з'),
        '6.050502' => array(
            'name' => 'інженерна механіка',
            'group' => 'ІМ' . $y . '-1з'),
        '6.050503' => array(
            'name' => 'машинобудування',
            'group' => 'МАШ' . $y . '-1з'),
        '6.050504' => array(
            'name' => 'зварювання',
            'group' => 'ЗВ' . $y . '-1з'),
        '6.050702' => array(
            'name' => 'електромеханіка',
            'group' => 'ЕСА' . $y . '-1з'),
    ),
    11 => array(//бакавры
        '6.030504' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП' . $y . '-1зт'),
        '6.030508' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК' . $y . '-1зт'),
        '6.030509' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА' . $y . '-1зт'),
        '6.030601' => array(
            'name' => 'менеджмент',
            'group' => 'МН' . $y . '-1зт'),
        '6.040303' => array(
            'name' => 'системний аналіз',
            'group' => 'СМ' . $y . '-1зт'),
        '6.050101' => array(
            'name' => 'комп’ютерні науки',
            'group' => 'ІТ' . $y . '-1зт'),
        '6.050202' => array(
            'name' => 'автоматизація та комп’ютерно-інтегровані технології',
            'group' => 'АВП' . $y . '-1зт'),
        '6.050401' => array(
            'name' => 'металургія',
            'group' => 'ОМТ' . $y . '-1зт'),
        '6.050402' => array(
            'name' => 'ливарне виробництво',
            'group' => 'ЛВ' . $y . '-1зт'),
        '6.050502' => array(
            'name' => 'інженерна механіка',
            'group' => 'ІМ' . $y . '-1зт'),
        '6.050503' => array(
            'name' => 'машинобудування',
            'group' => 'МАШ' . $y . '-1зт'),
        '6.050504' => array(
            'name' => 'зварювання',
            'group' => 'ЗВ' . $y . '-1зт'),
        '6.050702' => array(
            'name' => 'електромеханіка',
            'group' => 'ЕСА' . $y . '-1зт'),
    ),
    14 => array(//бакавры
        '6.030504' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП' . $y . '-1зт'),
        '6.030508' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК' . $y . '-1зт'),
        '6.030509' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА' . $y . '-1зт'),
        '6.030601' => array(
            'name' => 'менеджмент',
            'group' => 'МН' . $y . '-1зт'),
        '6.040303' => array(
            'name' => 'системний аналіз',
            'group' => 'СМ' . $y . '-1зт'),
        '6.050101' => array(
            'name' => 'комп’ютерні науки',
            'group' => 'ІТ' . $y . '-1зт'),
        '6.050202' => array(
            'name' => 'автоматизація та комп’ютерно-інтегровані технології',
            'group' => 'АВП' . $y . '-1зт'),
        '6.050401' => array(
            'name' => 'металургія',
            'group' => 'ОМТ' . $y . '-1зт'),
        '6.050402' => array(
            'name' => 'ливарне виробництво',
            'group' => 'ЛВ' . $y . '-1зт'),
        '6.050502' => array(
            'name' => 'інженерна механіка',
            'group' => 'ІМ' . $y . '-1зт'),
        '6.050503' => array(
            'name' => 'машинобудування',
            'group' => 'МАШ' . $y . '-1зт'),
        '6.050504' => array(
            'name' => 'зварювання',
            'group' => 'ЗВ' . $y . '-1зт'),
        '6.050702' => array(
            'name' => 'електромеханіка',
            'group' => 'ЕСА' . $y . '-1зт'),
    ),
    3 => array(//специалистам
        '7.03050401' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП' . ($y - 4) . '-1з'),
        '7.03050801' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК' . ($y - 4) . '-1з'),
        '7.03050901' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА' . ($y - 4) . '-1з'),
        '7.03060101' => array(
            'name' => 'менеджмент організацій і адміністрування',
            'group' => 'МН' . ($y - 4) . '-1з'),
        '7.04030302' => array(
            'name' => 'системи і методи прийняття рішень',
            'group' => 'СМ' . ($y - 4) . '-1з'),
        '7.05010102' => array(
            'name' => 'інформаційні технології проектування',
            'group' => 'ІТ' . ($y - 4) . '-1з'),
        '7.05020201' => array(
            'name' => 'автоматизоване управління технологічними процесами',
            'group' => 'АВП' . ($y - 4) . '-1з'),
        '7.05040104' => array(
            'name' => 'обробка металів тиском',
            'group' => 'ОМТ' . ($y - 4) . '-1з'),
        '7.05040201' => array(
            'name' => 'ливарне виробництво чорних та кольорових металів і сплавів',
            'group' => 'ЛВ' . ($y - 4) . '-1з'),
        '7.05050201' => array(
            'name' => 'технології машинобудування',
            'group' => 'ІМ' . ($y - 4) . '-1з'),
        '7.05050203' => array(
            'name' => 'обладнання та технології пластичного формування конструкцій машинобудування',
            'group' => 'ІМ' . ($y - 4) . '-1з'),
        '7.05050301' => array(
            'name' => 'металорізальні верстати та системи',
            'group' => 'МАШ' . ($y - 4) . '-1з'),
        '7.05050302' => array(
            'name' => 'інструментальне виробництво',
            'group' => 'МАШ' . ($y - 4) . '-1з'),
        '7.05050308' => array(
            'name' => 'підйомно-транспортні, дорожні, будівельні, меліоративні машини і обладнання',
            'group' => 'МАШ' . ($y - 4) . '-1з'),
        '7.05050311' => array(
            'name' => 'металургійне обладнання',
            'group' => 'МАШ' . ($y - 4) . '-1з'),
        '7.05050401' => array(
            'name' => 'технології та устаткування зварювання',
            'group' => 'ЗВ' . ($y - 4) . '-1з'),
        '7.05070204' => array(
            'name' => 'електромеханічні системи автоматизації та електропривод',
            'group' => 'ЕСА' . ($y - 4) . '-1з'),
    ),
    2 => array(//специалистам
        '8.03050401' => array(
            'name' => 'економіка підприємства',
            'group' => 'ЕП' . ($y - 4) . '-1зм'),
        '8.03050801' => array(
            'name' => 'фінанси і кредит',
            'group' => 'ФК' . ($y - 4) . '-1зм'),
        '8.03050901' => array(
            'name' => 'облік і аудит',
            'group' => 'ОА' . ($y - 4) . '-1зм'),
        '8.03060101' => array(
            'name' => 'менеджмент організацій і адміністрування',
            'group' => 'МН' . ($y - 4) . '-1зм'),
        '8.04030302' => array(
            'name' => 'системи і методи прийняття рішень',
            'group' => 'СМ' . ($y - 4) . '-1зм'),
        '8.05010102' => array(
            'name' => 'інформаційні технології проектування',
            'group' => 'ІТ' . ($y - 4) . '-1зм'),
        '8.05020201' => array(
            'name' => 'автоматизоване управління технологічними процесами',
            'group' => 'АВП' . ($y - 4) . '-1зм'),
        '8.05040104' => array(
            'name' => 'обробка металів тиском',
            'group' => 'ОМТ' . ($y - 4) . '-1зм'),
        '8.05040201' => array(
            'name' => 'ливарне виробництво чорних та кольорових металів і сплавів',
            'group' => 'ЛВ' . ($y - 4) . '-1зм'),
        '8.05050201' => array(
            'name' => 'технології машинобудування',
            'group' => 'ІМ' . ($y - 4) . '-1зм'),
        '8.05050203' => array(
            'name' => 'обладнання та технології пластичного формування конструкцій машинобудування',
            'group' => 'ІМ' . ($y - 4) . '-1зм'),
        '8.05050301' => array(
            'name' => 'металорізальні верстати та системи',
            'group' => 'МАШ' . ($y - 4) . '-1зм'),
        '8.05050302' => array(
            'name' => 'інструментальне виробництво',
            'group' => 'МАШ' . ($y - 4) . '-1зм'),
        '8.05050308' => array(
            'name' => 'підйомно-транспортні, дорожні, будівельні, меліоративні машини і обладнання',
            'group' => 'МАШ' . ($y - 4) . '-1зм'),
        '8.05050311' => array(
            'name' => 'металургійне обладнання',
            'group' => 'МАШ' . ($y - 4) . '-1зм'),
        '8.05050401' => array(
            'name' => 'технології та устаткування зварювання',
            'group' => 'ЗВ' . ($y - 4) . '-1зм'),
        '8.05070204' => array(
            'name' => 'електромеханічні системи автоматизації та електропривод',
            'group' => 'ЕСА' . ($y - 4) . '-1зм'),
    )
);

// преобразует дату ЕДБО в формат EDUCATION
function getEducationDate($date) {
    if (strlen($date) < 10) {
        return '';
    }
    return substr($date, 8, 2) . '.' . substr($date, 5, 2) . '.' . substr($date, 0, 4);
}

$data = array();
$photo = array();
$path_foto = "c:\\studcards\\";
foreach ($arr_request as $key => $item) {
    //$item = $item_['data'];
    $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
    $PersonCodeU = $item['PersonCodeU'];    // код персоны
    // Идентификатор специальности ВУЗа
    $Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
    // Название направления специальности.
    $SpecDirectionName = $item['SpecDirectionName'];
    // Идентификатор специальности по классификатору МОН
    $UniversityFacultetFullName = $item['UniversityFacultetFullName'];

    // у заочки может не быть факультета
    if (strlen($UniversityFacultetFullName) == 0)
        $UniversityFacultetFullName = 'Факультет машинобудування';
    // Флаг, показывающий, что персона подала оригинал документов
    $OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
    // формируем только для оригиналов
    if ($OriginalDocumentsAdd == 0)
        continue;
    // Код типа статуса заявки
    $PersonRequestStatusCode = $item['PersonRequestStatusCode'];

    $Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];

    $Id_PersonEducationForm = $item['Id_PersonEducationForm'];
    $$Id_Qualification = $item['Id_Qualification'];
    // Идентификатор документа для заявления
    $Id_PersonDocument = $item['Id_PersonDocument'];
    // Конкурсный балл
    $KonkursValue = $item['KonkursValue'];

    $Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];

    $UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
    $FIO = $item['FIO'];

    $SpecClasifierCode = $item['SpecClasifierCode'];
    // получаем 
    $res = $ep->PersonSOAPPhotoGet($sessionId, $item['UniversityKode'], $PersonCodeU);

//print_r($res);
    if (count($res) > 0) {
        $dPersonSOAPPhoto = $res['dPersonSOAPPhoto'];
            $photo[$item['PersonCodeU']] = array(
                'PersonPhotoBase64String' => $dPersonSOAPPhoto['PersonPhotoBase64String'],
                'Id_PersonPhoto' => (int) $dPersonSOAPPhoto['Id_PersonPhoto'],
                'photo_student' => ($FIO) . '_' . $PersonCodeU . ".jpg",//GetInTranslit
            );
    }
    
    // данные персоны
    $res = $ep->PersonsGet2($sessionId, $Id_Language, "'" . $PersonCodeU . "'");
    $dPersonsGet2 = $res['dPersonsGet2'];

    $faculty_cur = $item['UniversityFacultetFullName'];

    $PersonEducationFormName = $item['PersonEducationFormName'];

    $last_name = $dPersonsGet2['LastName'];
    $first_name = $dPersonsGet2['FirstName'];
    $middle_name = $dPersonsGet2['MiddleName'];
    $sex = $dPersonsGet2['Id_PersonSex'];
    $birthday = $dPersonsGet2['Birthday'];

    $PersonEducationDateBegin = '';
    $PersonEducationDateEnd = '';

    $sex = $sex == 1 ? 'чоловік' : 'жінка';
    //1994-01-28T00:00:00 
    /*
      if (strlen($birthday) < 10) {
      $birthday = '';
      } else {
      $tmp = substr($birthday,5,2).'.'.substr($birthday,8,2).'.'.substr($birthday,0,4);
      $birthday = $tmp;
      }
     * */

    $birthday = getEducationDate($birthday);

    // ipn 5
    // passport - 3
    // Id_PersonDocumentType 1 - свидет о рождении
    $Id_PersonDocumentType = 5;

    // поиск паспорта и ИНН
    $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, $PersonCodeU, 0, 0, $UniversityKode, -1);


    $ipn = '';

    $person_document_seria = '';
    $person_document_number = '';
    $person_document_type = 1;

    // поиск документов
    if (count($res)) {
        $dPersonDocuments = $res['dPersonDocuments'];

        foreach ($dPersonDocuments as $key => $value) {
            if ($value['Id_PersonDocumentType'] == 5) {
                $ipn = $value['DocumentNumbers'];
            }
            if ($value['Id_PersonDocumentType'] == 1) {
                if ($value['Cancellad'] != 1) {
                    $person_document_seria = $value['DocumentSeries'];
                    $person_document_number = $value['DocumentNumbers'];
                    $person_document_type = 'Свідоцтво про народження'; //$value['Id_PersonDocumentType'];
                }
            }

            if ($value['Id_PersonDocumentType'] == 3) {
                if ($value['Cancellad'] != 1) {
                    $person_document_seria = $value['DocumentSeries'];
                    $person_document_number = $value['DocumentNumbers'];
                    $person_document_type = 'Паспорт'; //$value['Id_PersonDocumentType'];
                }
            }
        }
    }

    // поиск документа на основании которого было поступление


    $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, $PersonCodeU, 0, $item['Id_PersonDocument'], $UniversityKode, -1);
    $dPersonDocuments = $res['dPersonDocuments'];



    $prev_document_seria = $dPersonDocuments['DocumentSeries'];
    $prev_document_number = $dPersonDocuments['DocumentNumbers'];
    $prev_document_ID = $dPersonDocuments['Id_PersonDocumentType'];

    // дневное / заочное
    if (!isset($data[$PersonEducationFormName]))
        $data[$PersonEducationFormName] = array();


    if (!isset($data[$PersonEducationFormName][$UniversityFacultetFullName]))
        $data[$PersonEducationFormName][$UniversityFacultetFullName] = array();

    if (!isset($data[$PersonEducationFormName][$UniversityFacultetFullName][$Id_UniversitySpecialities]))
        $data[$PersonEducationFormName][$UniversityFacultetFullName][$Id_UniversitySpecialities] = array();

    $payment = "Бюджет";

    $doc = GetDoc4EducationByEDBOId($prev_document_ID);

    $data[$PersonEducationFormName][$UniversityFacultetFullName][$Id_UniversitySpecialities][] = array(
        'last_name' => $last_name,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'sex' => $sex,
        'birthday' => $birthday,
        'ipn' => $ipn,
        'person_document_ID' => $person_document_type,
        'person_document_number' => $person_document_number,
        'person_document_seria' => $person_document_seria,
        'prev_document_ID' => $doc['id'], //$prev_document_ID,
        'prev_document_number' => $prev_document_number,
        'prev_document_seria' => $prev_document_seria,
        'foreigner' => $item['Id_ForeignType'] == 1 ? "True" : "False",
        'payment' => $payment,
        'photo_student' => isset($photo[$item['PersonCodeU']]) ? $photo[$item['PersonCodeU']]['photo_student'] : "",
        'Id_PersonPhoto' => isset($photo[$item['PersonCodeU']]) ? $photo[$item['PersonCodeU']]['Id_PersonPhoto'] : 0,
        'item' => $item
    );

    $cur++;

    //if ($cur == 10)
     //   break;
}
//return;
//print_r($dPersonSOAPPhoto);

$errors = array();
// формируем файл

$newline = "\r\n";
$xml = array();
foreach ($data as $PersonEducationFormName => $value1) {
    $xml[$PersonEducationFormName] = array();
    foreach ($value1 as $UniversityFacultetFullName => $value2) {
        $xml[$PersonEducationFormName][$UniversityFacultetFullName] = array();
        foreach ($value2 as $Id_UniversitySpecialities => $value3) {
            $item = array();//$value3['item'];
            foreach ($value3 as $key => $stud) {
                $item = $stud['item'];
                break;
            }
            
            $us = $spec[$Id_UniversitySpecialities];

            // код специальностии или направления
            $spec_code = '';

            $SpecSpecialityClasifierCode = $us['SpecSpecialityClasifierCode'];
            $SpecClasifierCode = $us['SpecClasifierCode'];

            if (strlen($SpecSpecialityClasifierCode) == 0)
                $spec_code = $SpecClasifierCode;
            else
                $spec_code = $SpecSpecialityClasifierCode;
            //UniversityFacultetFullName
            $EducationDateBegin = getEducationDate($us['EducationDateBegin']);
            $EducationDateEnd = getEducationDate($us['EducationDateEnd']);

            $edu_form = $specialities[$item['Id_PersonEducationForm']];

            $edu_qual = $edu_form[$item['Id_Qualification']];

            $spec_ = $edu_qual[$spec_code];

            $group = $spec_['group'];


            ob_start();

            echo '<students>';
            echo '<order>';
            echo "$newline";
            echo "<time_education>$PersonEducationFormName</time_education> ";
            echo "$newline";
            echo '<faculty uk="' . $UniversityFacultetFullName . '"/>';
            echo "$newline";
            echo "<issued>$EducationDateBegin</issued>";
            echo "$newline";
            echo "<expired>$EducationDateEnd</expired>";
            echo "$newline";
            echo '<group uk="' . $group . '" />';
            echo "$newline";
            echo '</order>';
            echo "$newline";
            foreach ($value3 as $key => $stud) {
                
                $f = false;
                if (strlen($stud['ipn']) == 0) {
                    $f = true;
                    $errors[$stud['last_name'].' '.$stud['first_name'].' '.$stud['middle_name']."\t".$group ][] = 'нет идент. кода';
                }
                
                //echo $stud['last_name'].' '.$stud['first_name'].' '.$stud['middle_name']."<BR>";
                //echo $stud['PersonPhotoBase64String'];
                //echo '<BR><BR>';
                if ($stud['Id_PersonPhoto'] == 0) {
                    if (!$f)
                        $errors[$stud['last_name'].' '.$stud['first_name'].' '.$stud['middle_name']."\t".$group ][] = ' ';
                    $errors[$stud['last_name'].' '.$stud['first_name'].' '.$stud['middle_name']."\t".$group ][] = 'нет фото';
                  //  $errors[$stud['last_name'].' '.$stud['first_name'].' '.$stud['middle_name'] ][] = $stud['PersonPhotoBase64String'];
                }
                
                echo '<student>';
                echo "$newline";
                echo '<last_name uk="' . $stud['last_name'] . '" />';
                echo "$newline";
                echo '<first_name uk="' . $stud['first_name'] . '" />';
                echo "$newline";
                echo '<middle_name uk="' . $stud['middle_name'] . '" />';
                echo "$newline";
                echo '<sex>' . $stud['sex'] . '</sex>';
                echo "$newline";
                echo '<birthday>' . $stud['birthday'] . '</birthday>';
                echo "$newline";
                
                if (strlen($stud['ipn']) != 0) {
                    echo '<ipn>' . $stud['ipn'] . '</ipn>';
                }
                echo "$newline";
                echo '<person_document ID="' . $stud['person_document_ID'] .
                '" number="' . $stud['person_document_number'] . '" seria="' . $stud['person_document_seria'] . '"/>';
                echo "$newline";
                echo '<prev_document ID="' . $stud['prev_document_ID'] . '" foreigner="' .
                $stud['foreigner'] . '" number="' . $stud['prev_document_number'] . '" seria="' . $stud['prev_document_seria'] . '"/>';
                echo "$newline";
                if ($stud['Id_PersonPhoto'] != 0) {
                    echo '<photo>' . $path_foto . GetInTranslit($stud['photo_student']) . '</photo>';
                }
                echo "$newline";
                echo '<payment>' . $stud['payment'] . '</payment>';
                echo "$newline";
                echo "</student>";
                echo "$newline";
            }

            echo '</students>';
            $xml[$PersonEducationFormName][$UniversityFacultetFullName][$group] = ob_get_contents();
            ob_clean();
        }
    }
}
//return;
/*
  //создание zip архива
  $zip = new ZipArchive();
  //имя файла архива
  $fileName = "studcards.zip";
  if ($zip->open($fileName, ZIPARCHIVE::CREATE) !== true) {
  fwrite(STDERR, "Error while creating archive file");
  exit(1);
  }

  $zip->addFromString("studcards.xml" , $xml);
  //закрываем архив
  $zip->close();
 */
////////////////////

$error = "";

if (extension_loaded('zip')) {
    // проверяем выбранные файлы
    $zip = new ZipArchive(); // подгружаем библиотеку zip
    $zip_name = time() . ".zip"; // имя файла
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
        $error .= "* Sorry ZIP creation failed at this time";
    }


    foreach ($xml as $PersonEducationFormName => $value1) {
        foreach ($value1 as $UniversityFacultetFullName => $groups) {
            foreach ($groups as $group => $data) {
                //iconv('CP866', 'CP1251', 'Моя папка')
                $path = $PersonEducationFormName . '/' . $UniversityFacultetFullName . '/' . 'studcards_' . $group . '.xml';
                //$path = mb_convert_encoding($path, 'UTF-8', 'CP866');
                $path = iconv('utf-8', 'CP866//TRANSLIT//IGNORE', $path);
                $zip->addFromString($path, $data); //$xml
            }
        }
    }
    foreach ($photo as $key => $value) {
        if ($value['Id_PersonPhoto'] !=0) {
            $path = GetInTranslit($value['photo_student']);// iconv('utf-8', 'CP866//TRANSLIT//IGNORE', $value['photo_student']);
            $zip->addFromString($path, base64_decode($value['PersonPhotoBase64String']));
        }
    }
    
    // файл с ошибками выгрузки
    $str_err = '';
    foreach ($errors as $fio => $arr_err) {
        $str_err.="\r\n".$fio."\t";
        foreach ($arr_err as $key => $value) {
            $str_err.=$value."\t";
        }
    }
    
    
    //$str_err = iconv('utf-8', 'CP866//TRANSLIT//IGNORE', $str_err);
    
    if (strlen($str_err) > 0) {
        $zip->addFromString('errors.txt', $str_err);
    } 
    

    $zip->close();

    if (file_exists($zip_name)) {
        // отдаём файл на скачивание
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zip_name . '"');
        readfile($zip_name);
        // удаляем zip файл если он существует
        unlink($zip_name);
    }
} else
    $error .= "* You dont have ZIP extension";

//echo $error;

//echo $xml;
/////////////////////
        //echo htmlentities($xml);
        
        //$filename = 'studentscard.xml'; 
        //$filesave = 'myfile.txt'; 
        //header('Content-type: application/octet-stream'); 
        ///header('Content-Disposition: attachment; filename="'.$filename.'"'); 

        //readfile($filename); 
        //echo $xml;
/*
 * $source = "http://example.com/dir/picture.jpeg";
$dest    = "upload/picture.jpeg";
if (copy($source, $dest))
    echo "Всё ок";
else
    echo "Копирование не удалось";
 */