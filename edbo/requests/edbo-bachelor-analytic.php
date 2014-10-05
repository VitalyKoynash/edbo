<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Анализ заявок бакалавров</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="../../scripts/list.js"></script>
    </head>
    <body>
        <div id="requests">
            <table>
              <thead>
                <tr>
                    <th class="sort" data-sort="_fio">ФИО</th>
                    <th class="sort" data-sort="_univer">ВУЗ</th>
                    <th class="sort" data-sort="_dir">Направление</th>
                    <th class="sort" data-sort="_form">Форма</th>
                    <th colspan="2">
                    <input type="text" class="search" placeholder="поиск" />
                    </th>
                </tr>
              </thead>
              <tbody class="list">
                  
        <?php
        set_time_limit(600);
        include_once '../edbo-provider/edbo-initsoap.php';
        include_once '../../utils/utils.php';

        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
        $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
        
        echo '<div class="caption">'.$caption.'</div>';
        // запрос языка
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];
        
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
        /*
         * 1 - created Нова заява
         * 2 - denied Відмова
         * 3 - canceled Скасовано
         * 4 - confirmed Допущено
         * 5 - committed Рекомендовано
         * 6 - rejected Відхилено
         * 7 - submitted До наказу
         * 8 - sitereg Заява надійшла з сайту
         * 9 - delayed Затримано
         */
        $Id_PersonRequestStatusType1 = filter_input(INPUT_GET, "Id_PersonRequestStatusType1", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType1)) {
            $Id_PersonRequestStatusType1 = 1;
        }
        $Id_PersonRequestStatusType2 = filter_input(INPUT_GET, "Id_PersonRequestStatusType2", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType2)) {
            $Id_PersonRequestStatusType2 = 4;
        }
         $Id_PersonRequestStatusType3 = filter_input(INPUT_GET, "Id_PersonRequestStatusType3", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonRequestStatusType3)) {
            $Id_PersonRequestStatusType3 = 5;
        }
 
        /*
         *  1	Денна
            2	Заочна
            3	Екстернат
            4	Вечірня
            5	Дистанційна
            6	Інтернатура
         */
// запрос паарметров формы обучения
        $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonEducationForm)) {
            $Id_PersonEducationForm = 1;
        }

// запрос паарметров квалификации
        $Id_Qualification = filter_input(INPUT_GET, "Id_Qualification", FILTER_SANITIZE_STRING);
        if (is_null($Id_Qualification)) {
            $Id_Qualification = 1; // bachelor
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
        
        $EntrantsPersons = array ();

        for ($i = 0; $i < $count; $i++) {

            $item = $dUniversityFacultetsRequests2[$i];
            $PersonCodeU = $item['PersonCodeU'];
            $EntrantsPersons[$PersonCodeU] = $item['FIO'];
        }

        $count = count($EntrantsPersons);
        echo '<div>Кол-во абитуриентов: '.$count.'</div>';
        
        $Univer = array ();
        
        if (0){
        $Univer['bd77867a-ef08-45ab-a0d4-1d6ef1cc8557'] = 'Машинобудівний коледж Донбаської державної машинобудівної академії';
        $Univer['7e66bbdb-ce84-4e2c-9767-3346253175b0'] = 'Донбаська державна машинобудівна академія';
        $Univer['b8c1f2f4-7d31-4451-adc5-ca3893ca0776'] = 'Донецький національний технічний університет';
        $Univer['92ce2830-97a8-4724-b9d6-044bb2321ab0'] = 'Донецький національний університет';
        
        $Univer['0cecd9bb-4d82-476d-90da-ef102e503565'] = 'Національний технічний університет "Харківський політехнічний інститут"';
        $Univer['eea870d0-b71c-454e-b055-f98cc490b7e4'] = 'Харківський національний автомобільно-дорожній університет';
        $Univer['afb74b93-afbe-4bad-bc2f-4ddd5adfceb9'] = 'Харківський національний технічний університет сільського господарства імені Петра Василенка';
        $Univer['0e4328f5-a79f-482d-b7d3-d125ddd3a1bd'] = 'Національний аерокосмічний університет ім. М.Є. Жуковського "Харківський авіаційний інститут"';
        $Univer['64aba6f2-8b43-4b3c-8ebc-a199aba4f6ea'] = 'Харківський національний університет імені В.Н. Каразіна';
        $Univer['d8f56f9d-8874-4e8b-b586-e3121e63c3bf'] = 'Харківський національний університет радіоелектроніки';
        $Univer['2c2f0ca8-53ad-48b0-a53a-9843a79fffde'] = 'Харківський національний економічний університет імені Семена Кузнеця';
        $Univer['5a15d797-c6c2-462d-96c3-ca4a769b581f'] = 'Харківський інститут банківської справи Університету банківської справи Національного банку України (м. Київ)';
        $Univer['d928c405-e643-4780-a1c5-e6233376787c'] = 'Відкритий міжнародний університет розвитку людини "Україна"';
        $Univer['52c87402-f7b2-4a90-ac32-ed50b62476b9'] = 'Харківський національний університет міського господарства імені О.М. Бекетова';

        $Univer['216e13fc-3f94-41d3-bf18-6adc3296f6fe'] = 'Національний авіаційний університет';
        $Univer['36a8e3e3-bc65-454c-bdb4-27d0424fa937'] = 'Національний технічний університет України «Київський політехнічний інститут»';
        
        $Univer['9169ec74-6a3b-4f2f-8d4c-cf3e4fb4064c'] = 'Відокремлений підрозділ "Слов`янський технікум Луганського національного аграрного університету"';
        
        $Univer['400f8367-1226-4925-a900-49f0698ec240'] = 'Національний університет "Одеська юридична академія"';
        $Univer['8bcf5131-f0e5-4d84-b39c-575c6475dd33'] = 'Національний юридичний університет імені Ярослава Мудрого';
        $Univer['0cecd9bb-4d82-476d-90da-ef102e503565'] = 'Національний технічний університет "Харківський політехнічний інститут"';
        $Univer['a646b2ca-6543-4de6-9f3b-9726a69f3c8c'] = 'Державний вищий навчальний заклад "Український державний хіміко-технологічний університет"';
        $Univer['ae43dbfe-f16c-46a0-9cc5-73260358ac01'] = 'Запорізький національний технічний університет';
        $Univer['8befa85a-d493-4f84-a88c-d8875870a962'] = 'Полтавський національний технічний університет імені Юрія Кондратюка';
        $Univer['57f60d67-ad5a-4ff3-91e7-6d33a56de273'] = 'Дніпропетровський національний університет імені Олеся Гончара';
        $Univer['0176a9d2-e37c-4123-9688-a952e1374077'] = 'Львівський національний університет імені Івана Франка';
        $Univer['bd8b7449-e1b1-4597-801b-1a4dc56df1d3'] = 'Приватний вищий навчальний заклад "Вінницький фінансово-економічний університет"';
        $Univer['5a15d797-c6c2-462d-96c3-ca4a769b581f'] = 'Харківський інститут банківської справи Університету банківської справи Національного банку України (м. Київ)';
        $Univer['eea870d0-b71c-454e-b055-f98cc490b7e4'] = 'Харківський національний автомобільно-дорожній університет';
        $Univer['7741628f-7c2d-4aa7-b1b0-e188081de99f'] = 'Українська інженерно-педагогічна академія';
        
        }
        
        $date_education_begin_entrant = date_create_from_format ("Y-m-d H:i:s",date("Y")."-07-15 23:59:59"); 
        
        $count_entrant_student = 0;
        
        foreach ($EntrantsPersons as $PersonCodeU => $value) {
            /*
            $res = $ep-> PersonRequestsGet2 ($sessionId, getDateNow(), $Id_Language,
                    $PersonCodeU, $Id_PersonRequestSeasons, 0, "", 0, 1, "");
            
            print "<br>$value<br>";
            print_r($res);
            
            $dPersonRequests2 = $res['dPersonRequests2'];
            */
            //$ep->debug(TRUE);
            $res = $ep->PersonEducationsGet ($sessionId, getDateNow(), $Id_Language,
                    $PersonCodeU, 0, 3, "");
            if (count($res)==0) {
                continue;
            }
            
            
            
            $dPersonEducations = $res['dPersonEducations'];
            
            //print_r($dPersonEducations);
            
            if (!isset($dPersonEducations['PersonEducationDateBegin']))  continue;
            
            $PersonEducationDateBegin = date_create_from_format ("Y-m-d H:i:s",  
                    str_replace("T"," ",$dPersonEducations['PersonEducationDateBegin']));
            
            
            if (date_timestamp_get($PersonEducationDateBegin) > date_timestamp_get($date_education_begin_entrant)) {
                
                /*
                 
                 */
                print   '
                    <tr class="request_list" >
                    <td class="_fio" id="td_req">'.$value.'</td>
                    <td class="_univer" id="td_req">'.$dPersonEducations['UniversityFullName'].'</td>
                    <td class="_dir" id="td_req">'.$dPersonEducations['SpecDirectionName'].'</td>
                    <td class="_form" id="td_req">'.$dPersonEducations['PersonEducationPaymentTypeName'].'</td>
                    
                </tr>';  
                 /*      
                print "<br>$value";
                print "<br>".$dPersonEducations['UniversityFullName'];
                print "<br>".$dPersonEducations['PersonEducationPaymentTypeName'];
                print "<br>".$dPersonEducations['SpecDirectionName'];
                print '<br>';
                //print_r($dPersonEducations);
                print '<br>';
                  
                  */
                $count_entrant_student++;
            }
        }
        
        print "<br>Уже поступило - $count_entrant_student";
        ?>
                  
                </tbody>
        </table>

        </div>
        <script src="./scripts/action.js"></script>
    </body>
</html>
