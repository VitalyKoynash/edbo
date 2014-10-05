<?php

include '../edbo-provider/edbo-initsoap.php';
include '../../utils/utils.php';



ob_start();
//ini_set('max_execution_time', '1800');
set_time_limit(60*60);
//set_time_limit(1800)
$sessionId = filter_input(INPUT_POST, "sessionId", FILTER_SANITIZE_STRING);
// запрос языка
$res = $eg->{'LanguagesGet'}($sessionId);
$eg->printLastError($sessionId);
$dLanguages = $res['dLanguages'];
$Id_Language = $dLanguages['Id_Language'];

// обработка запрета выполнения анализа факта обучения в другом вузе
//$analytics = filter_input(INPUT_GET, "analytics", FILTER_SANITIZE_STRING);
//if (is_null($analytics)) {
//    $analytics = 'true';
//}
// id вступительной компании

$Id_PersonRequestSeasons = filter_input(INPUT_POST, "Id_PersonRequestSeasons", FILTER_SANITIZE_STRING);
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
/*
 * 1	Нова заява	заяву (електронну або паперову) прийнято до розгляду у ВНЗ, заведено особову справу вступника з персональним номером та розпочато процес щодо прийняття рішення про допуск вступника до участі у конкурсному відборі
  2	Відмова	власника зареєстрованої заяви не допущено до участі у конкурсному відборі на підставі рішення приймальної комісії. У разі присвоєння заяві цього статусу ВНЗ зазначає причину відмови
  3	Скасовано	подана заява вважається такою, що не подавалась, а факт подачі – анулюється в ЄДЕБО, якщо: – електронну заяву скасовано вступником в особистому електронному кабінеті до моменту встановлення заяві статусу «Зареєстровано у вищому навчальному закладі» або «Потребує уточнення вступником»; – заяву анульовано ВНЗ за рішенням приймальної комісії (до моменту встановлення статусу «Рекомендовано до зарахування») за умови виявлення ВНЗ технічної помилки, зробленої під час внесення даних до ЄДЕБО, з обов’язковим зазначенням причини анулювання
  4	Допущено	власника зареєстрованої заяви допущено до участі у конкурсному відборі
  5	Рекомендовано	вступник пройшов конкурсний відбір та рекомендований до зарахування на навчання за кошти державного бюджету або за кошти фізичних та юридичних осіб
  6	Відхилено	вступник втратив право бути зарахованим на навчання до обраного ВНЗ у зв’язку з невиконанням вимог  Умов прийому або їх порушенням, зарахуванням на навчання до іншого навчального закладу тощо. При встановленні заяві такого статусу ВНЗ обов’язково зазначає причину виключення
  7	До наказу	наказом про зарахування на навчання вступника зараховано до ВНЗ
  8	Заява надійшла з сайту	підтвердження факту подання електронної заяви до обраного вступником ВНЗ. Подана електронна заява одразу відображається в ЄДЕБО на сторінці ВНЗ
  9	Затримано	електронну заяву прийнято до розгляду у ВНЗ, але дані стосовно вступника потребують уточнення. Після присвоєння електронній заяві цього статусу ВНЗ зобов`язаний невідкладно надіслати вступнику повідомлення з переліком даних, які потребують уточнення, та в який спосіб їх необхідно подати

 */
// Идентификаторы  статусов  заявок
//$Id_PersonRequestStatusType1 = filter_input(INPUT_POST, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
//if (is_null($Id_PersonRequestStatusType1)) {
    $Id_PersonRequestStatusType1 = 7; //1;
//}
//$Id_PersonRequestStatusType2 = filter_input(INPUT_POST, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
//if (is_null($Id_PersonRequestStatusType2)) {
    $Id_PersonRequestStatusType2 = 7; //4;
//}
//$Id_PersonRequestStatusType3 = filter_input(INPUT_POST, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
//if (is_null($Id_PersonRequestStatusType3)) {
    $Id_PersonRequestStatusType3 = 7; //5;
//}

// запрос паарметров формы обучения
$Id_PersonEducationForm = filter_input(INPUT_POST, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
if (is_null($Id_PersonEducationForm)) {
    $Id_PersonEducationForm = 0; //0
}

// запрос паарметров квалификации
$Id_Qualification = filter_input(INPUT_POST, "Id_Qualification", FILTER_SANITIZE_STRING);
if (is_null($Id_Qualification)) {
    $Id_Qualification = 0; //0
}

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

$alreadyStudents = array();

/*
  // аналитика по фактам обучения
  $EA = new EntrantAnalytycs();
  $alreadyStudents = array();
  if ($analytics != 'false') {
  $alreadyStudents = $EA->getEntrantOtherVUZ(
  $sessionId, $Id_Language, $Id_PersonRequestSeasons, $ep, $dUniversityFacultetsRequests2);
  }
 */
//print_r($alreadyStudents);


$arr_request = array();
//$request_PersonCodeU = array(); // заявки по персоне
//$requests = array(); //заявки по ИД
//$count = 5;
// цикл по заявкам
for ($i = 0; $i < $count; $i++) {

    $item = $dUniversityFacultetsRequests2[$i];

    // 1 11 14
    /*
      $Id_Qualification = $item['Id_Qualification'];
      if ($Id_Qualification == 1 || $Id_Qualification == 11 ||
      $Id_Qualification = 14) {

      } else {
      continue;
      }
     */

    $SpecClasifierCode = $item ['SpecClasifierCode'];
    $ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
    $KonkursValue = $item['KonkursValue'];
    $ExistBenefitsPershocherg = $item['ExistBenefitsPershocherg'];
    $FIO_ = $item['FIO'];
    $FIO = $item['FIO'];

    //if (ord($FIO_{1}) == 132/* 'Є' */) {
    //    $FIO_ = str_replace('Є', 'Ея', $FIO_);
    //} elseif (ord($FIO_{1}) == 134/* 'І' */) {
    //    $FIO_ = str_replace('І', 'К0', $FIO_);
    //}


    $PersonCodeU = $item['PersonCodeU'];
    $Id_PersonRequest = $item['Id_PersonRequest'];
    $RequestPriority = (int) $item['RequestPriority'];

    //заявки по Id_PersonRequest
    //$requests[$Id_PersonRequest] = $item;

    $arr_request[] = array(
        'Id_PersonRequest' => $Id_PersonRequest,
        'SpecClasifierCode' => $SpecClasifierCode,
        'ExistBenefitsPozacherg' => $ExistBenefitsPozacherg,
        'KonkursValue' => (int) $KonkursValue,
        'ExistBenefitsPershocherg' => $ExistBenefitsPershocherg,
        'FIO_' => $FIO_,
        'data' => $item,
    );
}

$err = ob_get_contents();
ob_clean();

echo ' <table> <tbody class="list">';

$createHeader = true;
foreach ($arr_request as $key => $item_) {
    $item = $item_['data'];

    $Id_PersonDocument = $item['Id_PersonDocument'];

    // поиск документов
    $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, $item['PersonCodeU'], 0, $item['Id_PersonDocument'], $item['UniversityKode'], -1);
    $dPersonDocuments_entrant = $res['dPersonDocuments'];


/*
    //поиск контактов
    $res = $ep->PersonContactsGet($sessionId, getDateNow(), $Id_Language, $item['PersonCodeU'], 0);
    $dPersonContacts = $res['dPersonContacts'];
*/
    // поиск адресов
    $res = $ep->PersonAddressesGet2($sessionId, getDateNow(), $Id_Language, $item['PersonCodeU'], 0);
    $dPersonAddresses = $res['dPersonAddresses2'];
    
    // поиск паспорта или свидетельства
    $res = $ep->PersonDocumentsGet($sessionId, getDateNow(), $Id_Language, $item['PersonCodeU'], 0, 0, $item['UniversityKode'], -1);
    $dPersonDocuments = $res['dPersonDocuments'];
    
    
    //break;
    $ipn = '';
    
    $passport = array();
    if (count($res)) {
        $dPersonDocuments = $res['dPersonDocuments'];

        foreach ($dPersonDocuments as $key => $value) {
            if ($value['Id_PersonDocumentType'] == 5) {
                $ipn = $value['DocumentNumbers'];
            } elseif ($value['Id_PersonDocumentType'] == 3) {//passport

                $passport = $dPersonDocuments[$key];
            } elseif ($value['Id_PersonDocumentType'] == 1) {//svid-vo o rojdenii
                
                $passport = $dPersonDocuments[$key];
            }
        }
    }
    

    //print_r(array($item, $dPersonDocuments_entrant, $dPersonAddresses, $dPersonContacts, array('ipn'=>$ipn), $passport);
    

    //$arrUniversity = array('University' => "");
    /*
      if (isset($alreadyStudents[$item['PersonCodeU']])) {
      $arrUniversity['University'] = $alreadyStudents[$item['PersonCodeU']];
      }
     */
   // $arr = array();
//$idx = 0;
/*
foreach ($dUniversityFacultetsRequests2 as $idx => $row) {
    $arr[$idx] = array();
    foreach ($row as $key => $value) {
    if (valid_keys($key))
        //unset($dUniversityFacultetsRequests2[$idx][$key]);
        $arr[$idx][$key = $value];
    }
}
*/
    
    echo buildTableRows(array($item,/* $dPersonDocuments_entrant, */$dPersonAddresses/*, $dPersonContacts,*/, array('ИНН'=>$ipn), $passport), $createHeader);
    $createHeader = false;
    //break;
}

echo '  </tbody>            </table>';

echo '<div><p>count=',count($arr_request),'</p><p>errors',$err,'</p></div>';