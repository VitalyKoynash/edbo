<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Все заявки</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="../../scripts/list.js"></script>
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
        
        echo '<div class="caption">'.$caption.'</div>';
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
        $UniversityFacultetKode = filter_input(INPUT_GET, "UniversityFacultetKode", FILTER_SANITIZE_STRING);
        if (is_null($UniversityFacultetKode)) {
            $UniversityFacultetKode = "";
        }

// запрос параметров специальности
        $UniversitySpecialitiesKode = filter_input(INPUT_GET, "UniversitySpecialitiesKode", FILTER_SANITIZE_STRING);
        if (is_null($UniversitySpecialitiesKode)) {
            $UniversitySpecialitiesKode = "";
        }

// запрос параметров персоны
        $PersonCodeU = filter_input(INPUT_GET, "PersonCodeU", FILTER_SANITIZE_STRING);
        if (is_null($PersonCodeU)) {
            $PersonCodeU = "";
        }

// запрос сотни
        $Hundred = filter_input(INPUT_GET, "Hundred", FILTER_SANITIZE_STRING);
        if (is_null($Hundred)) {
            $Hundred = 1;
        }

// Идентификаторы  статусов  заявок
        $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType1)) {
            $Id_PersonRequestStatusType1 = 0;
        }
        $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType2)) {
            $Id_PersonRequestStatusType2 = 0;
        }
         $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType3)) {
            $Id_PersonRequestStatusType3 = 0;
        }
 
// запрос паарметров формы обучения
        $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 0;
        }

// запрос паарметров квалификации
        $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        if (is_null($Id_Qualification)) {
            $Id_Qualification = 0;
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
        
        //$dPersonRequestsIds = $res['dPersonRequestsIds'];
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
        
        // цикл по заявкам
        for ($i = 0; $i < $count; $i++) {

            $item = $dUniversityFacultetsRequests2[$i];
            $FIO = $item['FIO'];

            /* этот кусок кода нужен для правильной сортировки
                    украинских букв, так как Є и І лезут вперед
            */
            if (ord($FIO{1})==132/*'Є'*/) {
                    $FIO = str_replace('Є','Ея',$FIO);
            } elseif (ord($FIO{1}) == 134/*'І'*/) {
                    $FIO = str_replace('І','К0',$FIO);
            }

            $Id_PersonRequest = $item['Id_PersonRequest'];
            $UniversityFacultetsRequests[$FIO.$Id_PersonRequest] = $item;
        }
            //setlocale(LC_COLLATE , 'uk_UA');
            //$col = new \Collator('uk_UA');
            //$oldLocale=setlocale(LC_COLLATE, "0");
            //setlocale(LC_COLLATE, 'uk_UA.KOI8-U');// uk_UA.KOI8-U uk_UA.CP1251
            //$SORT_LOCALE_STRING = 3;
        
            // сортировка по ФИО
            ksort($UniversityFacultetsRequests);//, $SORT_LOCALE_STRING);
            //setlocale(LC_COLLATE, $oldLocale);
        ?>
        
        <?php
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        ?>
        <div id="requests">
            <table>
              <thead>
                <tr>
                    <th class="sort" data-sort="_req">ID</th>
                    <th class="sort" data-sort="_fio">ФИО</th>
                    <th class="sort" data-sort="_dir">Направление</th>
                    <th class="sort" data-sort="_dir_code">Код</th>
                    <th class="sort" data-sort="_date_enter">Дата ввода</th>
                    <th class="sort" data-sort="_code_busines">№ дела</th>
                    <th class="sort" data-sort="_konkurs_value">Балл</th>
                    <th class="sort" data-sort="_od">ОД</th>
                    <th class="sort" data-sort="_data">&nbsp;</th>
                    <th colspan="2">
                    <input type="text" class="search" placeholder="параметры поиска" />
                    <!--input type="text" class="search" placeholder="параметры поиска" /-->
                  </th>
                </tr>
              </thead>
              <tbody class="list">
            <!-- td class="id" style="display:none;">1</td //-->
    <?php
        // цикл позаявкам
        foreach ($UniversityFacultetsRequests as $key => $item) {
            $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            $PersonCodeU = $item['PersonCodeU'];    // код персоны
            $DateCreate = substr($item['DateCreate'],0,10);
            
            //$dPersonRequests2 = $res['dPersonRequests2'];
            
            // Идентификатор специальности ВУЗа
            $Id_UniversitySpecialities = $item['Id_UniversitySpecialities'];
            
            // Название направления специальности.
            $SpecDirectionName = $item['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
            $SpecClasifierCode = $item['SpecClasifierCode'];
			
            // Флаг, показывающий, что персона подала оригинал документов
            $OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            $PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            $CodeOfBusines = $item['CodeOfBusiness'];
			
            // Конкурсный балл
            $KonkursValue = $item['KonkursValue'];
            
            $Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];

            $UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            $FIO = $item['FIO'];
            
        // обработка факта обучения в другом ВУЗе
        $tooltip = FALSE;    
        if ($Id_PersonRequestStatusType == 2 || $Id_PersonRequestStatusType == 3) {
            echo   '<tr class="request_list_bad_status" >';
        } elseif (isset ($alreadyStudents[$PersonCodeU])) {
            echo   '<tr class="request_list_already_student" >';
            $tooltip = TRUE;
        }   else { 
            echo   '<tr class="request_list" >';
        }
        
        // вносим скрытые поля-данные
        echo        '<td class="Id_PersonRequest" style="display:none;">'.$Id_PersonRequest.'</td>
                    <td class="PersonCodeU" style="display:none;">'.$PersonCodeU.'</td>
                    <td class="SpecClasifierCode" style="display:none;">'.$SpecClasifierCode.'</td>
                    <td class="OriginalDocumentsAdd" style="display:none;">'.$OriginalDocumentsAdd.'</td>
                    <td class="UniversitySpecialitiesKode" style="display:none;">'.$UniversitySpecialitiesKode.'</td>
                    <td class="CodeOfBusines" style="display:none;">'.$CodeOfBusines.'</td>';
				  
        echo        '<td class="_req" id="td_req">'.$Id_PersonRequest.'</td>';
        
        // вставляем отображение вуза поступления при наличии
		
		$hrefFIO = '<a class="persons" target="_blank" href="http://edbo.gov.ua/Views/PersonsViews.aspx?UIdP='.
		$PersonCodeU.
		'&Type=1">'.$FIO.'</a>';
		
        if ($tooltip) {
            echo        '<td class="_fio" id="td_req" title="'.$alreadyStudents[$PersonCodeU].'">'.$hrefFIO.'</td>';
        } else {
            echo        '<td class="_fio" id="td_req">'.$hrefFIO.'</td>';
        }
        
        // заполняем поля
        echo        '<td class="_dir" id="td_req">'.$SpecDirectionName.'</td>
                    <td class="_dir_code" id="td_req">'.$SpecClasifierCode.'</td>
                    <td class="_code_busines" id="td_req">'.$DateCreate.'</td>
                    <td class="_date_enter" id="td_req">'.$CodeOfBusines.'</td>
                    <td class="_konkurs_value" id="td_req">'.$KonkursValue.'</td>
                    <td class="_od" id="td_req">'.($OriginalDocumentsAdd==0?'Копия':'Оригинал').'</td>
                    <td class="_od_change"><button class="btn_od_change">Изменить</button></td>
                    <td class="_data" id="td_req">'.'&nbsp;'.'</td>
                     <td class="UniversitySpecialitiesKode" style="">'.$UniversitySpecialitiesKode.'</td>
                </tr>';           

        }
        
        ?>
                </tbody>
            </table>
            
            </div>
        <script src="../../scripts/action.js"></script>
        
        <?php
	$timer = microtime(TRUE) - $timer;	

        echo '<div>Кол-во записей: '.$count.'</div>';
        echo '<div>Время выполнения запросаt:'.$timer.' ms</div>';

        ?>
		
		<!--script data-main="../../dist/libs/app_edbo_requests" src="../../dist/libs/require.js"></script//-->
        
    </body>
</html>
