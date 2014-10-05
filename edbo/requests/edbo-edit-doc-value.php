 <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Все заявки</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script src="../../scripts/list.js"></script>
        <!--script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script//-->
        <script>
		/*
		$(document).ready(function(){
			$('input[type=exam]').click(function() {
			   var selector = $(this).data('selector');
			   var color = $(this).data('color');

			   $(selector).css("color", color);
			});
		});
		*/
		</script>
        
        
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
        
        //$Id_PersonEducationForm = 1;
        //$Id_Qualification = 1;
        
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
            $UniversityKode = $item['UniversityKode'];
            
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
			if (($Id_PersonEducationForm == 2 && $Id_Qualification == 14) ||
				($Id_PersonEducationForm == 2 && $Id_Qualification == 1))			{
				$result[] = $row;
			} else {
				if (!isset($temp[$PersonCodeU])) {
					$result[] = $row;
					$temp[$PersonCodeU] = $PersonCodeU;
				}
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
            //$arrOriginalDocumentsAdd, SORT_DESC, 
            //$arrExistBenefitsPozacherg, SORT_DESC, 
            //$arrKonkursValue, SORT_DESC,
			$arrKonkursValue, SORT_DESC,
            $arrFIO_, SORT_ASC, 
			$arrExistBenefitsPershocherg, SORT_DESC,
			
            $arr_request);
        
        unset($arrSpecClasifierCode);
        unset($arrExistBenefitsPozacherg);
        unset($arrKonkursValue);
        unset($arrExistBenefitsPershocherg);
        ?>
        
        <?php
        
        echo '<input type="hidden" name="UniversityKode" id="UniversityKode" value="'.$UniversityKode.'" />';
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        ?>
        <div id="requests-edit-konkurs">
            <input type="text" onfocus="this.select();" onmouseup="return false;" class="search" placeholder="параметры поиска" />
            <table>
              <thead>
                <tr>
                    <!--th class="sort" data-sort="_req">ID</th//-->
                    <th class="sort" data-sort="_fio">ФИО</th>
                    <th class="sort" data-sort="_dir_code">Код</th>
                    <th class="sort" data-sort="_dir">Направление</th>
                    <th class="sort" data-sort="_doc_value">Балл документа</th>
                    <th class="sort" data-sort="_edit_doc_value">Редактировать</th>
                    <!--th class="sort" data-sort="_benefits">Вид конкурсу</th//-->
                    <!--th class="sort" data-sort="_data">&nbsp;</th//-->
                    <th colspan="2">
                    
                    <!--input type="text" class="search" placeholder="параметры поиска" /-->
                  </th>
                </tr>
              </thead>
              <tbody class="list">
            <!-- td class="id" style="display:none;">1</td //-->
    <?php
        // цикл позаявкам
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
            $SpecClasifierCode = $item['SpecClasifierCode'];
			
            // Флаг, показывающий, что персона подала оригинал документов
            $OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            // Код типа статуса заявки
            $PersonRequestStatusCode = $item['PersonRequestStatusCode'];
            // Шифр личного дела
            $CodeOfBusiness = $item['CodeOfBusiness'];
			
            // Конкурсный балл
            $KonkursValue = $item['KonkursValue'];
            
            $Id_PersonRequestStatusType = $item['Id_PersonRequestStatusType'];

            $UniversitySpecialitiesKode = $item['UniversitySpecialitiesKode'];
            $FIO = $item['FIO'];
            
            $ContactMobile = $item['ContactMobile'];
            
            //Идентификатор документа персоны для которого изменяется балл
            $Id_PersonDocument = $item['Id_PersonDocument'];
            $EntrantDocumentValue = $item['EntrantDocumentValue'];
			
			//Id_PersonEducationForm
			// 1 - дневное
			// 2 - зао
			
			//Id_Qualification
			// 1 - бак дневное
			// 11 -бак дневн ускор
			// 3 - спец дневное
			// 2 - магистры
			// 14 - бак зао усор
            $Id_Qualification = $item['Id_Qualification'];
            $Id_PersonEducationForm = $item['Id_PersonEducationForm'];
			
            $IsCheckForPaperCopy = $item['IsCheckForPaperCopy'];
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                первоочередного зачисления
             */
            $ExistBenefitsPershocherg = (int) $item['ExistBenefitsPershocherg'];
            
            $OriginalDocumentsAdd = $item['OriginalDocumentsAdd'];
            $IsNeedHostel  = $item['IsNeedHostel'];
            $IsBudget = $item['IsBudget'];
            $IsContract = $item['IsContract'];
            $IsHigherEducation = $item['IsHigherEducation'];
            $IsForeignWay = $item['IsForeignWay'];
            /*
             *  Флаг, указывающий на то, что в заявке 
                присутствуют льготы дающие допуск до 
                внеочередного зачисления
             */
			 
            
            
            
            $exam_arr =array();
            $ExistBenefitsPozacherg = $item['ExistBenefitsPozacherg'];
            $Id_PersonRequest = $item['Id_PersonRequest']; 
             
			
				
				
			
			// массив для отображения оценок
			$exam = array();
			//print("<br>$key<br>");
			
			//print("$sessionId, getDateNow(), $Id_Language, $Id_PersonRequest");
			// запрос оценок
			$res2 = $ep->PersonRequestExaminationsGet ($sessionId, getDateNow(), $Id_Language, $Id_PersonRequest);
			
			//print("<br>res2<br>");
			//print_r($res2);
			
			if (count($res2) != 0) {
				// нет экзаменов вообще
				$dPersonRequestExaminations = array();
				
				if (isset($res2['dPersonRequestExaminations']))
					$dPersonRequestExaminations = $res2['dPersonRequestExaminations'];
				else
					$dPersonRequestExaminations = $res2;
				
				if (count($dPersonRequestExaminations) == 0) {

				} elseif ( isset( $dPersonRequestExaminations[0]))	{
				// много экзаменов
					foreach ($dPersonRequestExaminations as $key => $value) {
						$exam[$value['Id_UniversitySpecialitiesSubject']] = $value['PersonRequestExaminationValue'];
						
					}
				} elseif (isset($dPersonRequestExaminations['Id_PersonRequestExamination'])) {
				// один экзамен
					$exam[$dPersonRequestExaminations['Id_UniversitySpecialitiesSubject']] = $dPersonRequestExaminations['PersonRequestExaminationValue'];
				}
				
			}
			
			//print("<br>exam<br>");
			//print_r($exam);
			 
			  $res = $eg->UniversityFacultetSpecialitiesSubjectsGet($sessionId,
               $Id_Language, getDateNow(), $UniversitySpecialitiesKode );
             
			// print("<br>UniversityFacultetSpecialitiesSubjectsGet<br>");
			//print_r($res);
			
             $dUniversityFacultetSpecialitiesSubjects = $res['dUniversityFacultetSpecialitiesSubjects'];
             //print_r($dUniversityFacultetSpecialitiesSubjects);
             if (isset($dUniversityFacultetSpecialitiesSubjects[0]))
             {// array
                 foreach ($dUniversityFacultetSpecialitiesSubjects as $key => $value) {
                     $exam_arr[$value['Id_UniversitySpecialitiesSubject']] = $value;
                 }
             } else { // object
                 $exam_arr[$dUniversityFacultetSpecialitiesSubjects['Id_UniversitySpecialitiesSubject']] =
                     $dUniversityFacultetSpecialitiesSubjects;
             }
             $exam_panel= '<td class="_exam" id="td_req"> 
                 <input type="hidden" class="count_exam" value="'.count($exam_arr).'">
                  ';
				  
			//	  print("<br>exam_arr<br>");
			//print_r($exam_arr);
			
             foreach ($exam_arr as $key => $value) {
				$Id_Subject = $value['Id_Subject'];
				
				if ($Id_Subject == 30 || $Id_Subject == 31 || $Id_Subject == 32 || $Id_Subject == 33)
				continue;
                 $id = $value['Id_Subject'];
                 $Id_UniversitySpecialitiesSubject  = $value['Id_UniversitySpecialitiesSubject'];
				 
				 $ex_val = 0;
				 
				 if (isset($exam[$Id_UniversitySpecialitiesSubject])) {
					$ex_val = $exam[$Id_UniversitySpecialitiesSubject];
				 }
				 
                 $cls = 'Id_UniversitySpecialitiesSubject_'.$Id_UniversitySpecialitiesSubject.
				 '_'.$Id_PersonRequest;
					
                 $exam_panel .= substr($value['SubjectName'],0,6). '                   
                    <input STYLE="background-color: rgb(200,200,200); text-align : center; border: 1px solid #d66;" onfocus="this.select();" onmouseup="return false;" type="text" class ="'.$cls.'" name="'.$cls.'" size="3" value = "'.
                     $ex_val.
                     '" >
					 <button   class="btn_ochange_exam" data-Id_UniversitySpecialitiesSubject="'.
					 $Id_UniversitySpecialitiesSubject.'" '. 
					 ' data-Id_PersonRequest="'.$Id_PersonRequest.'" '.
					 ' data-Id_Qualification="'.$Id_Qualification.'" '.
					 ' data-Id_PersonEducationForm="'.$Id_PersonEducationForm.'" '.
                                         ' data-OriginalDocumentsAdd="'.$OriginalDocumentsAdd.'" '.
                                            
                                         ' data-OriginalDocumentsAdd="'.$OriginalDocumentsAdd.'" '.
                                        ' data-IsNeedHostel="'.$IsNeedHostel.'" '.
                                        ' data-CodeOfBusiness="'.$CodeOfBusiness.'" '.
                                        ' data-IsBudget="'.$IsBudget.'" '.
                                        ' data-IsContract="'.$IsContract.'" '.
                                        ' data-IsHigherEducation="'.$IsHigherEducation.'" '.
                                        ' data-IsForeignWay="'.$IsForeignWay.'" '.
					 '>+</button>
                 ';
             }
   
             $exam_panel.= '
                    </td>';
             
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
                    <!--td class="SpecClasifierCode" style="display:none;">'.$SpecClasifierCode.'</td//-->
                    <td class="OriginalDocumentsAdd" style="display:none;">'.$OriginalDocumentsAdd.'</td>
                    <td class="UniversitySpecialitiesKode" style="display:none;">'.$UniversitySpecialitiesKode.'</td>
                    <td class="Id_PersonDocument" style="display:none;">'.$Id_PersonDocument.'</td>
                    <td class="IsCheckForPaperCopy" style="display:none;">'.$IsCheckForPaperCopy.'</td>
                    <td class="EntrantDocumentValue" style="display:none;">'.$EntrantDocumentValue.'</td>';
        
        
                    
					
					
        echo        '<!--td class="_req" id="td_req">'.$Id_PersonRequest.'</td//-->';
        
        // вставляем отображение вуза поступления при наличии
		$hrefFIO = '<a  class="persons" target="_blank" href="http://edbo.gov.ua/Views/PersonsViews.aspx?UIdP='.
		$PersonCodeU.
		'&Type=1">'.$FIO.'</a>';
        if ($tooltip) {
            echo        '<td class="_fio" id="td_req" title="'.$alreadyStudents[$PersonCodeU].'">'.$hrefFIO.'</td>';
        } else {
            echo        '<td class="_fio" id="td_req">'.$hrefFIO.'</td>';
        }
        
        // заполняем поля
        echo        '<td class="_dir_code" id="td_req">'.$SpecClasifierCode.'</td>
                    <td class="_dir" id="td_req">'.$SpecDirectionName.'</td>
                    <!--td class="_konkurs_value" id="td_req">'.$KonkursValue.'</td//-->
                    <td class="_edv" id="td_req">
                    <input onfocus="this.select();" onmouseup="return false;" type="text" class ="_EntrantDocumentValue" name="_EntrantDocumentValue" size="3" value = "'.$EntrantDocumentValue.'" >
                    <button class="btn_ochange_edv">Ok</button>
                    </td>
                  
                    <!--td class="_od_change_edv"><button class="btn_ochange_edv">Ok</button></td//-->
                    <!--td class="_data" id="td_req">'.'&nbsp;'.'</td//-->';
        echo $exam_panel;
        echo        '</tr>';         

	
        }
        
        ?>
                </tbody>
            </table>
            
            </div>
        <script src="../../scripts/action-edit-konkurs.js"></script>
     </body>
</html>