<?php

require_once 'Soap.php';
/**
 * Description of EDBOGuides
 *
 * @author VItaly
 */
class EDBOGuides {
    //put your code here
    
    private $soap = NULL;
    
    function __construct ($soapHostEDBOGuides)
    {
        //print '<div align="center"><p>EDBO soap initialize ... </p></div>';
        try {
            $this->soap = new EDBO\Soap ($soapHostEDBOGuides/*$_SESSION['soapHostEDBOGuides']*/);
        } catch (Exception $ex) {
            print "Exception by create EDBOGuides <br>\n";
            print "<br>\n";
            throw $ex;
        }
    }
    
    public function debug($val) {
        if (!is_null($this->soap)) {
            $this->soap->debug = $val;
        }
    }
    
    public function isOkSoap () {
        if (is_null($this->soap)) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function check_guid ($sessionId) {
        if (is_null($sessionId)) {
            return FALSE;
        }
      
        return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId)==0?FALSE:TRUE;
    }
        
    public function getDateNow () {
        return date ("d.m.Y h:i:s");
    }
    /*
    * Получение информации об ошибке при неудачном вызове всех методов web  сервиса, кроме  Login и Logout.
    */
    public function GetLastError($GUIDSession) {
        $res = $this->soap->invoke ( "GetLastError", array (
                "GUIDSession" => $GUIDSession));

        if ($res == NULL) {
            //error getting error
            $res = $this->soap->invoke ( "GetLastError", array (
                "SessionGUID" => $SessionGUID));
            if ($res == NULL) {
                return "Unknow EDBO error";
            }

        }
        return $res;
    }
    
    public function printLastError($GUIDSession) {
        $save = $this->soap->debug;
        $this->soap->debug = FALSE;
        $res = $this->GetLastError($GUIDSession);
        $res = $res['dLastError'];
        //print '@@@@@@@@@@@';
        //var_dump($res);
        if (is_string($res))
        {
            print $res;
        } elseif (is_array($res)) {
            if ((int)$res['LastErrorCode'] != 0) {
                print 'ErrorCode: '.@$res['LastErrorCode'].'  '.$res['LastErrorDescription'];
            }
        }
        $this->soap->debug = $save;
       
    }
    

    public function Logout ($SessionGUID) {
        $res = $this->soap->invoke ( "Logout", array (
                "SessionGUID" => $SessionGUID));
            
        if (strlen($res) == 0)
            return TRUE;

        return $res;
    }



    /*
     * Регистрация нового пользователя на  web  сервисе
     */
    // parameters: string  User,  string Password,  
    // int ClearPreviewSession, string  ApplicationKey
    // result: string GUID сессии пользователя 
    /*
     * В случае успеха, метод возвращает GUID сессии пользователя ,  который используется при 
вызове всех последующих методов.  Возвращаемый идентификатор имеет 
фиксированный размер  в 36 байт,  (на пример  4FD18E5D-AAF7-4522-84D6-03AC56C35D2F).
В случае ошибки,  метод возвращает строку с кодом и описанием ошибки. 
Ошибочным считается вызов метода,  который возвращает строку  длинной  не равной  36-и
байтам. 
     */
    public function Login($User, $Password, $ClearPreviewSession, $ApplicationKey ) {
        
        //print ("call __FUNCTION__ ($User, $Password, $ClearPreviewSession, $ApplicationKey)");
        
        if (!$this->isOkSoap()) {
            return NULL;
        }

        if (is_null($ClearPreviewSession)) { $ClearPreviewSession = 1; }

        if (is_null($ApplicationKey)) {
            $ApplicationKey = "YMT@ApFZP@7@y9+gqyTNy^rE&F+WufQKrWsbjeDQLm)jhYM8msUp-ez7up)(r8FJwxU0R)_m)cbn)gws(oKbLAMnxK5(jJy14L#8e!rrvJt)cm@n&2adyD-)9GChobbL";
        }

        $sessionId = $this->soap->invoke ( "Login", array (
                "User" => $User ,
                "Password"=>$Password, 
                "ClearPreviewSession"=>$ClearPreviewSession,
                "ApplicationKey"=>$ApplicationKey));


        return $sessionId;

/*
            $languages = $this->soap->invoke("LanguagesGet", array("SessionGUID"=>$this->sessionId));

            print_r($languages);
        } else {
            echo '</br>session ID</br>';
            print $this->sessionId;
        }*/
    }
    
    /*
     * Получения списка доступных языков используемых ЄДЕБО
     */
    public function LanguagesGet($SessionGUID) {
         $res = $this->soap->invoke ( "LanguagesGet", array (
                "SessionGUID" => $SessionGUID));

        return $res;
    }
    
    /*
     * Получения информации о системе
     */
    public function  GlobaliInfoGet($SessionGUID) {
         $res = $this->soap->invoke ( "GlobaliInfoGet", array (
                "SessionGUID" => $SessionGUID));

        return $res;
    }
  
    /*
     * Изменение пароля текущего пользователя
     */
    
    public function  ChangePassword($SessionGUID, $OldPassword, $NewPassword) {
        return;
         $res = $this->soap->invoke ( "ChangePassword", array (
                "SessionGUID" => $SessionGUID,
                "OldPassword" => $OldPassword,
                "NewPassword" => $NewPassword)
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника КОАТУУ
     */
    public function  KOATUUGet($SessionGUID, $ActualDate, $Id_Language, 
            $KOATUUCode, $Hundred,  $NameMask, $FullNameMask, $WitchRegions) {
         $res = $this->soap->invoke ( "KOATUUGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "KOATUUCode" => $KOATUUCode,
                "Hundred" => $Hundred,
                "NameMask" => $NameMask,
                "FullNameMask" => $FullNameMask,
                "WitchRegions" => $WitchRegions           
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника КОАТУУ  только 1-го уровня
     */
    public function  KOATUUGetL1($SessionGUID, $ActualDate, $Id_Language) {
         $res = $this->soap->invoke ( "KOATUUGetL1", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language      
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника КОАТУУ  только  2-го уровня
     */
    public function  KOATUUGetL2($SessionGUID, $ActualDate, $Id_Language, $KOATUUCodeL1) {
         $res = $this->soap->invoke ( "KOATUUGetL2", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "KOATUUCodeL1" => $KOATUUCodeL1
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника КОАТУУ  только  2-го уровня.
     */
    public function  KOATUUGetL3($SessionGUID, $ActualDate, $Id_Language, 
            $KOATUUCodeL2, $NameMask) {
         $res = $this->soap->invoke ( "KOATUUGetL3", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "KOATUUCodeL2" => $KOATUUCodeL2,
                "NameMask" => $NameMask
             
                 )
                 );

        return $res;
    }
    
    /*
     * Добавление данных в справочник КОАТУУ
     */
    public function  KOATUUAdd($SessionGUID, $KOATUUCode, $Type, 
           $KOATUUDateBegin, $KOATUUDateEnd, $Id_Language, $KOATUUName) {
        $res = $this->soap->invoke ( "KOATUUAdd", array (
               "SessionGUID" => $SessionGUID,
               "KOATUUCode" => $KOATUUCode,
               "Type" => $Type,
               "KOATUUDateBegin" => $KOATUUDateBegin,
               "KOATUUDateEnd" => $KOATUUDateEnd,
               "Id_Language" => $Id_Language,
               "KOATUUName" => $KOATUUName
                )
                );

        return $res;
    }
    
    /*
     * Изменение данных в справочнике  КОАТУУ
     */
    public function  KOATUUEdit($SessionGUID, $Id_KOATUU, $KOATUUCode, $Type, 
            $KOATUUDateBegin, $KOATUUDateEnd, $Id_KOATUUName, $Id_Language, $KOATUUName) {
        $res = $this->soap->invoke ( "KOATUUEdit", array (
               "SessionGUID" => $SessionGUID,
               "Id_KOATUU" => $Id_KOATUU,
               "KOATUUCode" => $KOATUUCode,
               "Type" => $Type,
               "KOATUUDateBegin" => $KOATUUDateBegin,
               "KOATUUDateEnd" => $KOATUUDateEnd,
               "Id_KOATUUName" => $Id_KOATUUName,
               "Id_Language" => $Id_Language,
               "KOATUUName" => $KOATUUName
                )
                );

        return $res;
    }
    
    /*
     * Удаление данных из справочника  КОАТУУ
     */
    public function  KOATUUDel($SessionGUID, $Id_KOATUU) {
       $res = $this->soap->invoke ( "KOATUUDel", array (
              "SessionGUID" => $SessionGUID,
              "Id_KOATUU" => $Id_KOATUU
               )
               );

        return $res;
    }
    
    /*
     * Получения справочников типов учебных заведений
     */
    public function  EducationTypesGet($SessionGUID, $Id_Language) {
      $res = $this->soap->invoke ( "EducationTypesGet", array (
             "SessionGUID" => $SessionGUID,
             "Id_Language" => $Id_Language
              )
              );

        return $res;
    }
    
    /*
     * Получения справочников  типов  улиц.
     */
    public function  StreetTypesGet($SessionGUID, $Id_Language) {
      $res = $this->soap->invoke ( "StreetTypesGet", array (
             "SessionGUID" => $SessionGUID,
             "Id_Language" => $Id_Language
              )
              );

        return $res;
    }
    
    /*
     * Получения списка редакций специальностей
     */
    public function  SpecRedactionsGet($SessionGUID) {
      $res = $this->soap->invoke ( "SpecRedactionsGet", array (
             "SessionGUID" => $SessionGUID
              )
              );

        return $res;
    }   
    
    
    /*
     * Получения справочников  специальностей указанной редакции
     */
    public function  SpecGet($SessionGUID, $SpecRedactionCode,  $SpecIndastryCode,  
            $SpecDirectionsCode,  $SpecSpecialityCode,  $SpecCode,  
            $SpecScecializationCode, $Id_Language, $ActualDate,  $SpecClasifierCode) {
      $res = $this->soap->invoke ( "SpecGet", array (
            "SessionGUID" => $SessionGUID,
            "SpecRedactionCode" => $SpecRedactionCode,  
            "SpecIndastryCode" => $SpecIndastryCode,  
            "SpecDirectionsCode" => $SpecDirectionsCode,  
            "SpecSpecialityCode" => $SpecSpecialityCode,  
            "SpecCode" => $SpecCode,  
            "SpecScecializationCode" => $SpecScecializationCode, 
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate,  
            "SpecClasifierCode" => $SpecClasifierCode
            ));

        return $res;
    }
    
    /*
     * Получения  справочников  школьных  предметов.
     */
    public function  SubjectsGet($SessionGUID, $Id_Language, $ActualDate) {
      $res = $this->soap->invoke ( "SubjectsGet", array (
            "SessionGUID" => $SessionGUID,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate,  
            ));

        return $res;
    }
    
    /*
     * Получения  рекомендуемых предметов для  направлений  специальностей
     * пропуск теста - не хватает данных параметров
     */ 
    public function  SpecDirectionsSubjectsGet($SessionGUID, $Id_Language, $ActualDate,
            $SpecDirectionsCode, $Id_PersonRequestSeasons, $SpecSpecialityCode,
            $Id_Qualification) {
      $res = $this->soap->invoke ( "SpecDirectionsSubjectsGet", array (
            "SessionGUID" => $SessionGUID,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate,  
            "SpecDirectionsCode" => $SpecDirectionsCode,
            "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
            "SpecSpecialityCode" => $SpecSpecialityCode,
            "Id_Qualification" => $Id_Qualification,
            ));

        return $res;
    }
    
    
    
    /*
     * Получения справочников школ
     */
    public function  SchoolsGet($SessionGUID, $KodeSchool, $Id_Language, $ActualDate,
            $SchoolName,  $Hundred,  $KOATUUCode, $KOATUUFullNameMask, 
            $Id_EducationClass , $MinDate) {
      $res = $this->soap->invoke ( "SchoolsGet", array (
            "SessionGUID" => $SessionGUID,
            "KodeSchool" => $KodeSchool,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate,  
            "SchoolName" => $SchoolName,
            "Hundred" => $Hundred,
            "KOATUUCode" => $KOATUUCode,
            "KOATUUFullNameMask" => $KOATUUFullNameMask,
            "Id_EducationClass" => $Id_EducationClass,
            "MinDate" => $MinDate,
            ));

        return $res;
    }
    
    /*
     * не все по школам доделано
     */
    
    public function  UniversityAcreditatinTypesGet($SessionGUID, $Id_Language) {
      $res = $this->soap->invoke ( "UniversityAcreditatinTypesGet", array (
            "SessionGUID" => $SessionGUID,
            "Id_Language" => $Id_Language, 
            ));

        return $res;
    }
    
    public function  UniversityGetRequestsStat($SessionGUID, $Id_PersonRequestSeasons,
            $UniversityKode, $UniversityFacultetKode, $Id_Language,  $ActualDate) {
      $res = $this->soap->invoke ( "UniversityGetRequestsStat", array (
            "SessionGUID" => $SessionGUID,
            "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
            "UniversityKode" => $UniversityKode,
            "UniversityFacultetKode" => $UniversityFacultetKode,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate,  
            
            ));

        return $res;
    }
    
    /*
     * UniversitiesGet
     */
    
    public function  UniversitiesGet($SessionGUID, $UniversityKode, 
            $Id_Language,  $ActualDate, $UniversityName) {
      $res = $this->soap->invoke ( "UniversitiesGet", array (
            "SessionGUID" => $SessionGUID,
            "UniversityKode" => $UniversityKode,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate, 
            "UniversityName" => $UniversityName,
            
            ));

        return $res;
    }
    
    /*
     * Получение  списка(дерева)   факультетов  (структурных подразделений)   учебного  заведения.
     */
    
    public function  UniversityFacultetsGet($SessionGUID, $UniversityKode, 
            $UniversityFacultetKode, $Id_Language,  $ActualDate, 
            $FacultetGetMode,  $Id_UniversityFacultetTypes , 
            $IsAvailableForReceiptOfRequest, $IsAvailableForBindStudentPersons,
            $Id_UniversityFacultet,  $IsAvailableForBindStaffPersons) {
      $res = $this->soap->invoke ( "UniversityFacultetsGet", array (
            "SessionGUID" => $SessionGUID, 
            "UniversityKode" => $UniversityKode, 
            "UniversityFacultetKode" => $UniversityFacultetKode, 
            "Id_Language" => $Id_Language,  
            "ActualDate" => $ActualDate, 
            "FacultetGetMode" => $FacultetGetMode,  
            "Id_UniversityFacultetTypes" => $Id_UniversityFacultetTypes , 
            "IsAvailableForReceiptOfRequest" => $IsAvailableForReceiptOfRequest,
            "IsAvailableForBindStudentPersons" => $IsAvailableForBindStudentPersons,
            "Id_UniversityFacultet" => $Id_UniversityFacultet, 
            "IsAvailableForBindStaffPersons" => $IsAvailableForBindStaffPersons
            
            ));

        return $res;
    }
    
    /*
     * Получения  списка заявок для указанного факультет а ВУЗа
     */
    public function  UniversityFacultetsGetRequests2($SessionGUID, 
            $Id_PersonRequestSeasons, $UniversityFacultetKode,  
            $UniversitySpecialitiesKode,  $Id_Language, $ActualDate,  
            $PersonCodeU, $Hundred,  $MinDate, $Id_PersonRequestStatusType1,  
            $Id_PersonRequestStatusType2,  $Id_PersonRequestStatusType3,  
            $Id_PersonEducationForm, $UniversityKode, $Id_Qualification, 
            $Filters ) {
      $res = $this->soap->invoke ( "UniversityFacultetsGetRequests2", array (
            "SessionGUID" => $SessionGUID, 
            "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons, 
            "UniversityFacultetKode" => $UniversityFacultetKode, 
            "UniversitySpecialitiesKode" => $UniversitySpecialitiesKode, 
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate, 
            "PersonCodeU" => $PersonCodeU, 
            "Hundred" => $Hundred, 
            "MinDate" => $MinDate,
            "Id_PersonRequestStatusType1" => $Id_PersonRequestStatusType1, 
            "Id_PersonRequestStatusType2" => $Id_PersonRequestStatusType2, 
            "Id_PersonRequestStatusType3" => $Id_PersonRequestStatusType3, 
            "Id_PersonEducationForm" => $Id_PersonEducationForm,
            "UniversityKode" => $UniversityKode,
            "Id_Qualification" => $Id_Qualification,
            "Filters" => $Filters
            ));

        return $res;
    }
    
    
    /*
     * Получения данных со справочника учебных годов
     */
    
    public function  AcademicYearsGet($SessionGUID, $Id_Language) {
        
         $res = $this->soap->invoke ( "AcademicYearsGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника учебных курсов.
     */
    
    public function  CoursesGet($SessionGUID, $Id_Language) {
        
         $res = $this->soap->invoke ( "CoursesGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    
    /*
     * UniversitiesGet
     */
    /*
    public function  UniversitiesGet($SessionGUID, $UniversityKode, 
            $Id_Language,  $ActualDate, $UniversityName) {
      $res = $this->soap->invoke ( "UniversitiesGet", array (
            "SessionGUID" => $SessionGUID,
            "UniversityKode" => $UniversityKode,
            "Id_Language" => $Id_Language, 
            "ActualDate" => $ActualDate, 
            "UniversityName" => $UniversityName,
            
            ));

        return $res;
    }
    */
    /*
     * Получение  списка предметов для специальности факультета
     */
    
    public function  UniversityFacultetSpecialitiesSubjectsGet($SessionGUID, $Id_Language, 
            $ActualDate,  $UniversitySpecialitiesKode) {
      $res = $this->soap->invoke ( "UniversityFacultetSpecialitiesSubjectsGet", array (
            "SessionGUID" => $SessionGUID, 
            "Id_Language" => $Id_Language,  
            "ActualDate" => $ActualDate, 
            "UniversitySpecialitiesKode" => $UniversitySpecialitiesKode,  
            
            ));

        return $res;
    }
    
    
    /*
     * Получение  списка предметов для специальности факультета
     */
    
    public function  UniversityCoursesGet($SessionGUID, $Id_Language, $ActualDate,
            $UniversityKode, $Id_PersonRequestSeasons) {
      $res = $this->soap->invoke ( "UniversityCoursesGet", array (
            "SessionGUID" => $SessionGUID, 
            "Id_Language" => $Id_Language,  
            "ActualDate" => $ActualDate, 
            "UniversityKode" => $UniversityKode, 
            "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
            ));

        return $res;
    }
    
    /*
     * Получения  списка специальностей ВУЗа.
     */
    public function  UniversityFacultetSpecialitiesGet($SessionGUID, $UniversityKode,
        $UniversityFacultetKode, $SpecCode, $Id_Language, $ActualDate, 
        $Id_PersonRequestSeasons, $Id_PersonEducationForm,
        $UniversitySpecialitiesKode, $SpecDirectionsCode, $SpecSpecialityCode,
        $Filters) {
      $res = $this->soap->invoke ( "UniversityFacultetSpecialitiesGet", array (
            "SessionGUID" => $SessionGUID, 
            "UniversityKode" => $UniversityKode, 
            "UniversityFacultetKode" => $UniversityFacultetKode,
            "SpecCode" => $SpecCode,
            "Id_Language" => $Id_Language,  
            "ActualDate" => $ActualDate, 
            "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
            "Id_PersonEducationForm" => $Id_PersonEducationForm,
            "UniversitySpecialitiesKode" => $UniversitySpecialitiesKode,
            "SpecDirectionsCode" => $SpecDirectionsCode,
            "SpecSpecialityCode" => $SpecSpecialityCode,
            "Filters" => $Filters
            ));

        return $res;
    }
    
}
