<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Все заявки</title>
        <link rel="stylesheet" href="./styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="./styles/edbo.css" type="text/css" media="print" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="./scripts/list.js"></script>
        
    </head>
    <body>
        <?php
        set_time_limit(600);
        include './edbo-initsoap.php';
        include './utils/utils.php';

        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
        $Id_PersonEducationForm = filter_input(INPUT_GET, "Id_PersonEducationForm", FILTER_SANITIZE_STRING);
        if (is_null($Id_PersonEducationForm))
            $Id_PersonEducationForm = 0;

       
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];
        
        $res = $eg->UniversitiesGet($sessionId, "", $Id_Language, getDateNow(), "");
        $dUniversities = $res['dUniversities'];
        
        //GUID универа
        $UniversityKode = $dUniversities['UniversityKode'];
        
        $timer_PersonRequestsIdsGet = microtime(TRUE);
        // получим все заявки
        $res = $ep->PersonRequestsIdsGet($sessionId, $Id_Language,   
                4, $UniversityKode);
        $eg->printLastError($sessionId);
        $timer_PersonRequestsIdsGet = microtime(TRUE) - $timer_PersonRequestsIdsGet;
         
        // id вступительной компании
        $Id_PersonRequestSeasons = 4;
        
        $dPersonRequestsIds = $res['dPersonRequestsIds'];
        $count = count($dPersonRequestsIds);
        
        
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
                    <th class="sort" data-sort="_code_busines">№ дела</th>
                    <th class="sort" data-sort="_konkurs_value">Балл</th>
                    <th class="sort" data-sort="_od">ОД</th>
                    <th colspan="2">
                    <input type="text" class="search" placeholder="параметры поиска" />
                  </th>
                </tr>
              </thead>
              <tbody class="list">
                  <!-- td class="id" style="display:none;">1</td //-->
    <?php
        //$count = 10;
        $timer_PersonRequestsGet2 = 0;
        $timer_tmp = 0;
        for ($i = 0; $i < $count; $i++) {
            $item = $dPersonRequestsIds[$i];
            //print_r($item);
            $Id_PersonRequest = $item['Id_PersonRequest'];  //id заявки
            $PersonCodeU = $item['PersonCodeU'];    // код персоны
            $DateLastChange = $item['DateLastChange'];
            
            //$ep->debug(TRUE);
            $timer_tmp = microtime(TRUE);
            $res = $ep->PersonRequestsGet2($sessionId, getDateNow(), $Id_Language,
                    $PersonCodeU, $Id_PersonRequestSeasons, $Id_PersonRequest,
                    "", 0, 0, "");
            $timer_tmp = microtime(TRUE) - $timer_tmp;
            $timer_PersonRequestsGet2+=$timer_tmp;
            //print_r($res);
            
            $dPersonRequests2 = $res['dPersonRequests2'];
            
            // Идентификатор специальности ВУЗа
            $Id_UniversitySpecialities = $dPersonRequests2['Id_UniversitySpecialities'];
            
			// Название направления специальности.
            $SpecDirectionName = $dPersonRequests2['SpecDirectionName'];
            // Идентификатор специальности по классификатору МОН
			$SpecClasifierCode = $dPersonRequests2['SpecClasifierCode'];
			
			// Флаг, показывающий, что персона подала оригинал документов
            $OriginalDocumentsAdd = $dPersonRequests2['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            $PersonRequestStatusCode = $dPersonRequests2['PersonRequestStatusCode'];
            // Шифр личного дела
            $CodeOfBusines = $dPersonRequests2['CodeOfBusiness'];
			
			// Конкурсный балл
			$KonkursValue = $dPersonRequests2['KonkursValue'];
            
            // получим персону
            //print "<br><br>";
            $res = $ep->PersonsGet2($sessionId, $Id_Language, "'".$PersonCodeU."'");
            
            //print_r($res);
            
            $dPersonsGet2 = $res['dPersonsGet2'];
            
            $LastName = $dPersonsGet2['LastName'];
            $FirstName = $dPersonsGet2['FirstName'];
            $MiddleName = $dPersonsGet2['MiddleName'];
            
            
        print   '<tr class="request_list" >
                    <td class="Id_PersonRequest" style="display:none;">'.$Id_PersonRequest.'</td>
                    <td class="PersonCodeU" style="display:none;">'.$PersonCodeU.'</td>
                    <td class="SpecClasifierCode" style="display:none;">'.$SpecClasifierCode.'</td>
                    <td class="OriginalDocumentsAdd" style="display:none;">'.$OriginalDocumentsAdd.'</td>
                    <td class="CodeOfBusines" style="display:none;">'.$CodeOfBusines.'</td>
				  
                    <td class="_req" id="td_req">'.$Id_PersonRequest.'</td>
                    <td class="_fio" id="td_req">'.$LastName.' '.$FirstName.' '.$MiddleName.'</td>
                    <td class="_dir" id="td_req">'.$SpecDirectionName.' ('.$SpecClasifierCode.')'.'</td>
                    <td class="_dir_code" id="td_req">'.$SpecClasifierCode.'</td>
                    <td class="_code_busines" id="td_req">'.$CodeOfBusines.'</td>
                    <td class="_konkurs_value" id="td_req">'.$KonkursValue.'</td>
                    <td class="_od" id="td_req">'.($OriginalDocumentsAdd==0?'Копия':'Оригинал').'</td>
                    <td class="_od_change"><button class="btn_od_change">Изменить</button></td>
                </tr>';           

        }
        
        ?>
                <!-- tr>
                  <td class="id" style="display:none;">1</td>
                  <td class="id" >1</td>
                  <td class="name">Jonny</td>
                  <td class="dir">27</td>
                  <td class="od">Stockholm</td>
                  <td class="swith"><button class="edit-item-btn">Switch</button></td>
                  
                </tr//-->
                
              </tbody>
            </table>
            
            <!--table>
              <td class="id">
                <input type="hidden" id="id-field" />
                <input type="text" id="id-field" placeholder="ID" />
              </td>
              <td class="name">
                <input type="text" id="name-field" placeholder="Name" />
              </td>
              <td class="dir">
                <input type="text" id="dir-field" placeholder="diraection" />
              </td>
              <td class="od">
                <input type="text" id="od-field" placeholder="ОД" />
              </td>
              <td class="switch">
                <button id="switch-btn">switch</button>
                
              </td>
            </table//-->

            
          </div>
        <script src="./scripts/action.js"></script>
        
        <?php
        echo '<div><p>Статистика: </p> </div>';
        echo '<div><p>Time run PersonRequestsIdsGet:'.$timer_PersonRequestsIdsGet.' ms </p> </div>';
        echo '<div><p>Time run PersonRequestsGet2:'.$timer_PersonRequestsGet2.' ms </p> </div>';
        ?>
        
    </body>
</html>
