<?php
 set_time_limit(600);

 /*
  * возвращает массив
  * $alreadyStudents[$PersonCodeU] = $tooltip;
  * $tooltip - имя Вуза, куда поступили
  */
        
class EntrantAnalytycs {
    public function getEntrantOtherVUZ(
            $sessionId, 
            $Id_Language,
            $Id_PersonRequestSeasons,
            $ep, 
            $dUniversityFacultetsRequests2) {
        
        
        $Id_PersonRequestSeasons = is_null($Id_PersonRequestSeasons)?
                $ep->getActualPersonRequestSeason($sessionId, $Id_Language):
                $Id_PersonRequestSeasons;
     
        //$dPersonRequestsIds = $res['dPersonRequestsIds'];
        $count = count($dUniversityFacultetsRequests2);
        
        $EntrantsPersons = array ();

        for ($i = 0; $i < $count; $i++) {

            $item = $dUniversityFacultetsRequests2[$i];
            $PersonCodeU = $item['PersonCodeU'];
            $EntrantsPersons[$PersonCodeU] = $item['FIO'];
        }

        $count = count($EntrantsPersons);
        
        $date_education_begin_entrant = 
                date_create_from_format ("Y-m-d H:i:s",date("Y")."-07-15 23:59:59"); 
        
        $count_entrant_student = 0;
        
        $alreadyStudents = array();
        foreach ($EntrantsPersons as $PersonCodeU => $value) {

            $res = $ep->PersonEducationsGet ($sessionId, getDateNow(), $Id_Language,
                    $PersonCodeU, 0, 3, "");
            if (count($res)==0) {
                continue;
            }
            
            $dPersonEducations = $res['dPersonEducations'];
			
			if (isset($dPersonEducations[0])) {
				$dPersonEducations = $dPersonEducations[count($dPersonEducations)-1];
			}
				
            
            if (!isset($dPersonEducations['PersonEducationDateBegin']))  continue;
            
            $PersonEducationDateBegin = date_create_from_format ("Y-m-d H:i:s",  
                    str_replace("T"," ",$dPersonEducations['PersonEducationDateBegin']));
            
            
            if (date_timestamp_get($PersonEducationDateBegin) > date_timestamp_get($date_education_begin_entrant)) {
                $tooltip = $dPersonEducations['UniversityFullName'].' '.
                        $dPersonEducations['SpecDirectionName'].' '.
                        $dPersonEducations['PersonEducationPaymentTypeName'];
                $tooltip = str_replace('"', "'", $tooltip);
                $alreadyStudents[$PersonCodeU] = $tooltip;
                        
                
            }
        }
     
        return $alreadyStudents;
    }
}
