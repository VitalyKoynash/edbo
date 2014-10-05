<?php
require_once 'Soap.php';
/**
 * Description of EDBOPerson
 *
 * @author VItaly
 */
class EDBOPerson {
    //put your code here
    private $soap = NULL;
    
    function __construct ($soapHostEDBOPerson)
    {
        //print '<div align="center"><p>EDBO soap initialize ... </p></div>';
        try {
            $this->soap = new EDBO\Soap ($soapHostEDBOPerson/*$_SESSION['soapHostEDBOPerson']*/);
        } catch (Exception $ex) {
            print "Exception by create EDBOPerson <br>\n";
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
        return preg_match("/([a-f\d]{8})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{4})-([a-f\d]{12})/i", $sessionId);
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
                return "Unknow EDBOPerson error";
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
    
    public function getActualPersonRequestSeason($sessionId, $Id_Language) {
        return 4;
        $date_cur = date_create_from_format ("Y-m-d H:i:s",date("Y")."-07-15 23:59:59"); 
        $res = $this->PersonRequestSeasonsGet($sessionId, 
                $Id_Language, 
                getDateNow(), 
               0, 0, 1);
        $dPersonRequestSeasons = $res['dPersonRequestSeasons'];
        
        for ($i = 0; $i < count($dPersonRequestSeasons); $i++)
        {
            $item = $dPersonRequestSeasons[$i];
            $date_begin = date_create_from_format ("Y-m-d H:i:s",  str_replace("T"," ",$item['DateBeginPersonRequestSeason']));
            $date_end = date_create_from_format ("Y-m-d H:i:s", str_replace("T"," ",$item['DateEndPersonRequestSeason']));
            
            //print $date_begin->format('Y-m-d').' '.$date_end->format('Y-m-d').'  '.$date_cur->format('Y-m-d').'<br>';
            if (($date_begin <= $date_cur) && ($date_end >= $date_cur)) {
                return $dPersonRequestSeasons[$i]['Id_PersonRequestSeasons'];
            }
            
        }
        return 0;
    }
    
        /*
     * Получения данных со справочника квалификационных уровней
     */
    public function  QualificationsGet($SessionGUID, $ActualDate, 
            $Id_Language) {
        $res = $this->soap->invoke ( "QualificationsGet", array (
            "SessionGUID" => $SessionGUID,
            "ActualDate" => $ActualDate,  
            "Id_Language" => $Id_Language, 
            

            ));

        return $res;
    }
    
    /*
     * Поиск  персон по номеру и серии аттестат
     */
    
    public function  PersonFindByAttestat2($SessionGUID, $AtestatSeries, $AtestatNumber) {
        
         $res = $this->soap->invoke ( "PersonFindByAttestat2", array (
                "SessionGUID" => $SessionGUID,
                "AtestatSeries" => $AtestatSeries,
                "AtestatNumber" => $AtestatNumber)
                 );

        return $res;
    }
    
    /*
     * Поиск персон
     */
    
    public function  PersonsFind2($SessionGUID, $ActualDate , $Id_Language, 
            $FIOMask, $DocumentSeries, $DocumentNumber, $Ids_DocumentTypes,
            $Hundred, $PersonCodeU, $Filters) {
        
         $res = $this->soap->invoke ( "PersonsFind2", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "FIOMask" => $FIOMask,
                "DocumentSeries" => $DocumentSeries,
                "DocumentNumber" => $DocumentNumber,
                "Ids_DocumentTypes" => $Ids_DocumentTypes,
                "Hundred" => $Hundred,
                "PersonCodeU" => $PersonCodeU,
                "Filters" => $Filters,
                 )
                 );

        return $res;
    }
    
    /*
     * Получение списка персон по массиву идентификаторов PersonCodeU.
     */
    
    public function  PersonsGet2($SessionGUID, $Id_Language,$UIds) {
        
         $res = $this->soap->invoke ( "PersonsGet2", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "UIds" => $UIds)
                 );

        return $res;
    }
    
    /*
     * Получение  идентификаторов  персоны.
     */
    
    public function  PersonGetId($SessionGUID, $Id_Person, $PersonCodeU) {
        
         $res = $this->soap->invoke ( "PersonGetId", array (
                "SessionGUID" => $SessionGUID,
                "Id_Person" => $Id_Person,
                "PersonCodeU" => $PersonCodeU)
                 );

        return $res;
    }
    
    /*
     * ПОлучение справочника полов
     */
    
    public function  PersonSexTypesGet($SessionGUID, $ActualDate , $Id_Language) {
        
         $res = $this->soap->invoke ( "PersonSexTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
   
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных    о статусе последнего изменения всех персон учебного 
     * заведения в разрезе вступительной компании.
     */
    
    public function  PersonsIdsGet($SessionGUID, $Id_Language,
            $Id_PersonRequestSeasons, $UniversityKode) {
        
         $res = $this->soap->invoke ( "PersonsIdsGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "UniversityKode" => $UniversityKode,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника  типов контактов.
     */
    
    public function  PersonContactTypesGet($SessionGUID, $ActualDate, $Id_Language) {
        
         $res = $this->soap->invoke ( "PersonContactTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных  обо  всех контактах персоны
     */
    
    public function  PersonContactsGet($SessionGUID, $ActualDate, $Id_Language,
            $PersonCodeU, $Id_PersonContact) {
        
         $res = $this->soap->invoke ( "PersonContactsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonContact" => $Id_PersonContact,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных  обо  всех адресах персоны
     */
    
    public function  PersonAddressesGet2($SessionGUID, $ActualDate, $Id_Language,
            $PersonCodeU,  $Id_PersonAddress ) {
        
         $res = $this->soap->invoke ( "PersonAddressesGet2", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonAddress" => $Id_PersonAddress ,

                 )
                 );

        return $res;
    }
    
    /*
     * Получение  страны  гражданства   персоны.
     */
    
    public function  PersonCountryGet($SessionGUID, $PersonCodeU) {
        
         $res = $this->soap->invoke ( "PersonCountryGet", array (
                "SessionGUID" => $SessionGUID,
                "PersonCodeU" => $PersonCodeU,
                 )
                 );

        return $res;
    }
    
    /*
     * Изменение  страны  гражданства  персоны
     */
    
    public function  PersonCountryChange($SessionGUID, $Id_Person, $Id_Country) {
        
         $res = $this->soap->invoke ( "PersonCountryChange", array (
                "SessionGUID" => $SessionGUID,
                "Id_Person" => $Id_Person,
                "Id_Country" => $Id_Country,
                 )
                 );

        return $res;
    }
    
    /*
     * Получение   справочника типов иностранных граждан 
     */
    
    public function  ForeignTypesGet($SessionGUID) {
        
         $res = $this->soap->invoke ( "ForeignTypesGet", array (
                "SessionGUID" => $SessionGUID,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника типов документов
     */
    
    public function  PersonDocumentTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonDocumentTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных обо всех документах персоны
     */
    
    public function  PersonDocumentsGet($SessionGUID, $ActualDate, $Id_Language,
            $PersonCodeU, $Id_PersonDocumentType, $Id_PersonDocument, 
            $UniversityKode, $IsEntrantDocument) {
        
         $res = $this->soap->invoke ( "PersonDocumentsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonDocumentType" => $Id_PersonDocumentType,
                "Id_PersonDocument" => $Id_PersonDocument,
                "UniversityKode" => $UniversityKode,
                "IsEntrantDocument" => $IsEntrantDocument,
                 )
                 );

        return $res;
    }
    /*
     * Получения данных со справочника типов образования.
     */
    
    public function  PersonEducationTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonEducationTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника форм образования.
     */
    
    public function  PersonEducationFormsGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonEducationFormsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника форм образования.
     */
    
    public function  PersonEducationPaymentTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonEducationPaymentTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника причин ухода в академический отпуск.
     */
    
    public function  AcademicLeaveTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "AcademicLeaveTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных об обучении персоны.
     */
    
    public function  PersonEducationsGet($SessionGUID, $ActualDate, $Id_Language,
            $PersonCodeU, $Id_PersonEducation, $Id_PersonEducationType, $Filters ) {
        
         $res = $this->soap->invoke ( "PersonEducationsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU, 
                "Id_PersonEducation" => $Id_PersonEducation, 
                "Id_PersonEducationType" => $Id_PersonEducationType, 
                "Filters" => $Filters

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника типов истории обучения обучения.
     */
    
    public function  PersonEducationHistoryTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonEducationHistoryTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения  данных  со справочника  причин отчисления студента.
     */
    
    public function  PersonEducationsCancelEducationTypesGet($SessionGUID ) {
        
         $res = $this->soap->invoke ( "PersonEducationsCancelEducationTypesGet", array (
                "SessionGUID" => $SessionGUID,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных об истории обучении персоны.
     */
    
    public function  PersonEducationHistoryGet($SessionGUID, $ActualDate, $Id_Language,
            $PersonCodeU,  $Id_PersonEducation,  $IsActiveOnly) {
        
         $res = $this->soap->invoke ( "PersonEducationHistoryGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonEducation" => $Id_PersonEducation,
                "IsActiveOnly" => $IsActiveOnly,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о льготах и поощрениях персоны.
     */
    
    public function  PersonBenefitsGet2($SessionGUID, $ActualDate, $Id_Language,
              $Id_Person) {
        
         $res = $this->soap->invoke ( "PersonBenefitsGet2", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate, 
                "Id_Language" => $Id_Language,
                "Id_Person" => $Id_Person,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных об  подготовительных курсах пе рсоны
     */
    
    public function  PersonCoursesGet($SessionGUID, $ActualDate, $Id_Language,
              $Id_Person, $Id_PersonRequestSeasons, $UniversityKode) {
        
         $res = $this->soap->invoke ( "PersonCoursesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate, 
                "Id_Language" => $Id_Language,
                "Id_Person" => $Id_Person,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "UniversityKode" => $UniversityKode,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных об  подготовительных курсах пе рсоны
     */
    
    public function  PersonCoursesAdd($SessionGUID, $Id_Language,
              $Id_Person, $UniversityCourseCode, $Id_Subject,
                $Id_PersonRequestSeasons, $PersonsCourseNote) {
        
         $res = $this->soap->invoke ( "PersonCoursesAdd", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_Person" => $Id_Person,
                "UniversityCourseCode" => $UniversityCourseCode,
                "Id_Subject" => $Id_Subject,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "PersonsCourseNote" => $PersonsCourseNote,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных  о поощрениях персоны,  полученных в  олимпиадах
     */
    
    public function  PersonOlympiadsAwardsGet($SessionGUID, $ActualDate, $Id_Language,
              $PersonCodeU, $Id_PersonRequestSeasons) {
        
         $res = $this->soap->invoke ( "PersonOlympiadsAwardsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate, 
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных со справочника вступительных компаний.
     */
    
    public function  PersonRequestSeasonsGet($SessionGUID, $Id_Language, $ActualDate, 
              $Id_PersonRequestSeasons, $Id_PersonEducationForm, $OnlyActive) {
        
         $res = $this->soap->invoke ( "PersonRequestSeasonsGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "ActualDate" => $ActualDate, 
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "Id_PersonEducationForm" => $Id_PersonEducationForm,
                "OnlyActive" => $OnlyActive,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о заявках персоны в разрезе вступительных компаний
     */
    
    public function  PersonRequestsGet2($SessionGUID, $ActualDate, $Id_Language,  
                $PersonCodeU,  $Id_PersonRequestSeasons, $Id_PersonRequest, 
                $UniversityFacultetKode, $Id_PersonEducationForm, 
                $Id_Qualification, $Filters) {
        
         $res = $this->soap->invoke ( "PersonRequestsGet2", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate, 
                "Id_Language" => $Id_Language,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "Id_PersonRequest" => $Id_PersonRequest,
                "UniversityFacultetKode" => $UniversityFacultetKode,
                "Id_PersonEducationForm" => $Id_PersonEducationForm,
                "Id_Qualification" => $Id_Qualification,
                "Filters" => $Filters,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных    о статусе последнего изменения всех заявок учебного 
     * заведения в разрезе вступительной компании
     */
    
    public function  PersonRequestsIdsGet($SessionGUID, $Id_Language,  
                  $Id_PersonRequestSeasons, $UniversityKode ) {
        
         $res = $this->soap->invoke ( "PersonRequestsIdsGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequestSeasons" => $Id_PersonRequestSeasons,
                "UniversityKode" => $UniversityKode,
                 )
                 );

        return $res;
    }
    
    
     /*
     * Изменение  статуса  заявки  на  поступление
     */
    
    public function  PersonRequestsStatusChange($SessionGUID, $Id_PersonRequest,  
                  $Id_PersonRequestStatusType, $Descryption, $Id_UniversityEntrantWave,
				  $IsBudejt, $IsContract ) {
        
         $res = $this->soap->invoke ( "PersonRequestsStatusChange", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_PersonRequestStatusType" => $Id_PersonRequestStatusType,
                "Descryption" => $Descryption,
                "Id_UniversityEntrantWave" => $Id_UniversityEntrantWave,
                "IsBudejt" => $IsBudejt,
                "IsContract" => $IsContract,
                 )
                 );

        return $res;
    }
   
    /*
     * Получения данных о всех типах статусов заявки персоны.
     */
    
    public function  PersonRequestStatusTypesGet($SessionGUID, $ActualDate, $Id_Language ) {
        
         $res = $this->soap->invoke ( "PersonRequestStatusTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о хронологии изме нения статуса указанной заявки
     */
    
    public function   PersonRequestsStatusesGet($SessionGUID, $Id_Language, 
            $Id_PersonRequest ) {
        
         $res = $this->soap->invoke ( "PersonRequestsStatusesGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о типах  поступлений  персон ы
     */
    
    public function   PersonEnteranceTypesGet($SessionGUID, $Id_Language) {
        
         $res = $this->soap->invoke ( "PersonEnteranceTypesGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о причинах поступления с помощью экзаменов
     */
    
    public function   PersonRequestExaminationCausesGet($SessionGUID, $Id_Language) {
        
         $res = $this->soap->invoke ( "PersonRequestExaminationCausesGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о предметах сертификатов ЗНО для заявки
     */
    
    public function   PersonRequestDocumentSubjectsGet($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest) {
        
         $res = $this->soap->invoke ( "PersonRequestDocumentSubjectsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                 )
                 );

        return $res;
    }
    
    /*
     * Автоматический поиск необходимых предметов из сертификатов ЗНО для заявки
     */
    
    public function   PersonRequestDocumentSubjectsFind($SessionGUID, $Id_Language,
            $ActualDate, $PersonCodeU, $UniversitySpecialitiesKode) {
        
         $res = $this->soap->invoke ( "PersonRequestDocumentSubjectsFind", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "ActualDate" => $ActualDate,
                "PersonCodeU" => $PersonCodeU,
                "UniversitySpecialitiesKode" => $UniversitySpecialitiesKode
                 )
                 );

        return $res;
    }
    
    /*
     * Добавление предмета из сертификата ЗНО персоны для заявки.
     */
    
    public function   PersonRequestDocumentSubjectsAdd($SessionGUID, $Id_Language,
            $Id_PersonRequest, $Id_PersonDocumentSubject) {
        
         $res = $this->soap->invoke ( "PersonRequestDocumentSubjectsAdd", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_PersonDocumentSubject" => $Id_PersonDocumentSubject,
                 )
                 );

        return $res;
    }
    
    /*
     * Добавление предмета из сертификата ЗНО персоны для заявки.
     */
    
    public function   PersonRequestDocumentSubjectsDel($SessionGUID, $Id_Language,
            $Id_PersonRequest, $Id_PersonDocumentSubject) {
        
         $res = $this->soap->invoke ( "PersonRequestDocumentSubjectsDel", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_PersonDocumentSubject" => $Id_PersonDocumentSubject,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о курсах которые   относятся к заявке персоны 
     */
    
    public function   PersonRequestCoursesGet($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest) {
        
         $res = $this->soap->invoke ( "PersonRequestCoursesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                 )
                 );

        return $res;
    }
    
    /*
     * Добавление данных о курсах для заявки персоны 
     */
    
    public function   PersonRequestCoursesAdd($SessionGUID, $Id_Language, 
            $Id_PersonRequest, $Id_PersonCourse, $PersonRequestCourseBonus) {
        
         $res = $this->soap->invoke ( "PersonRequestCoursesAdd", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_PersonCourse" => $Id_PersonCourse,
                "PersonRequestCourseBonus" => $PersonRequestCourseBonus,
                 )
                 );

        return $res;
    }
    
    /*
     * Удаление  данных о курсах для заявки персоны. 
     */
    
    public function   PersonRequestCoursesDel($SessionGUID, $Id_PersonRequestCourse) {
        
         $res = $this->soap->invoke ( "PersonRequestCoursesDel", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequestCourse" => $Id_PersonRequestCourse,
                 )
                 );

        return $res;
    }
    
    /*
     * Изменение флага подачи оригинальных документов для заявки персоны
     */
    
    public function   PersonRequestOriginalDocumentChange($SessionGUID, 
            $Id_PersonRequest, $OriginalDocumentsAdd) {
        
         $res = $this->soap->invoke ( "PersonRequestOriginalDocumentChange", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,
                "OriginalDocumentsAdd" => $OriginalDocumentsAdd,
                 )
                 );

        return $res;
    }
    
    /*
     * Изменение  шифра  дела  для  заявки.
     */
    
    public function   PersonRequestCodeOfBuisnessEdit($SessionGUID, 
            $Id_PersonRequest, $CodeOfBusiness) {
        
         $res = $this->soap->invoke ( "PersonRequestCodeOfBuisnessEdit", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,
                "CodeOfBusiness" => $CodeOfBusiness,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных об экзаменах заявки.
     */
    
    public function   PersonRequestExaminationsGet($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest ) {
        
         $res = $this->soap->invoke ( "PersonRequestExaminationsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                 )
                 );

        return $res;
    }
    
    /*
     * Добавление данных об  экзамене  к  заявке.
     */
    
    public function   PersonRequestExaminationsAdd($SessionGUID, $Id_Language,
            $Id_PersonRequest, $Id_UniversitySpecialitiesSubject,
            $PersonRequestExaminationValue) {
        
         $res = $this->soap->invoke ( "PersonRequestExaminationsAdd", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_UniversitySpecialitiesSubject" => $Id_UniversitySpecialitiesSubject,
                "PersonRequestExaminationValue" => $PersonRequestExaminationValue,
                 )
                 );

        return $res;
    }
    
    /*
     * Удаление  данных об экзамене  у  заявки.
     */
    
    public function   PersonRequestExaminationsDel($SessionGUID, $Id_PersonRequestExamination) {
        
         $res = $this->soap->invoke ( "PersonRequestExaminationsDel", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequestExamination" => $Id_PersonRequestExamination,
                 )
                 );

        return $res;
    }
    
    
    /*
     * Изменение балла полученного в результате сдачи экзамена.
     */
    
    public function   PersonRequestExaminationsValueChange($SessionGUID, 
            $Id_PersonRequestExamination, $PersonRequestExaminationValue) {
        
         $res = $this->soap->invoke ( "PersonRequestExaminationsValueChange", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequestExamination" => $Id_PersonRequestExamination,
                "PersonRequestExaminationValue" => $PersonRequestExaminationValue,
                 )
                 );

        return $res;
    }
    
    /*
     * Принудительный пересчет предметов сертификатов ЗНО для заявки.
     */
    
    public function   PersonRequestValueCalc($SessionGUID, $Id_PersonRequest) {
        
         $res = $this->soap->invoke ( "PersonRequestValueCalc", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о  льготах заявки.
     */
    
    public function   PersonRequestBenefitsGet2($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest) {
        
         $res = $this->soap->invoke ( "PersonRequestBenefitsGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,

                 )
                 );

        return $res;
    }
    
    /*
     * Получения данных о  льготах заявки.
     */
    
    public function   PersonRequestBenefitsAdd($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest, $Id_PersonBenefit) {
        
         $res = $this->soap->invoke ( "PersonRequestBenefitsAdd", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "Id_PersonBenefit" => $Id_PersonBenefit
                 )
                 );

        return $res;
    }
    
    /*
     * Удаление данных о льготах у заявки
     */
    
    public function   PersonRequestBenefitsDel($SessionGUID, $Id_PersonRequestBenefit) {
        
         $res = $this->soap->invoke ( "PersonRequestBenefitsDel", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequestBenefit" => $Id_PersonRequestBenefit,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения  справочника кодов условий поступления
     */
    
    public function   RequestEnteranceCodesGet($SessionGUID, $ActualDate,
            $Id_Language) {
        
         $res = $this->soap->invoke ( "RequestEnteranceCodesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                 )
                 );

        return $res;
    }
    
    /*
     * Получения  кодов условий поступления для заявки
     */
    
    public function   PersonRequestEnteranceCodesGet($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest) {
        
         $res = $this->soap->invoke ( "PersonRequestEnteranceCodesGet", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest
                 )
                 );

        return $res;
    }
    
    
    /*
     * Добавление данных о  специальных условиях поступления  к заявке
     */
    
    public function   PersonRequestEnteranceCodesAdd($SessionGUID, $ActualDate,
            $Id_Language, $Id_PersonRequest, $id_RequestEnteranceCodes ) {
        
         $res = $this->soap->invoke ( "PersonRequestEnteranceCodesAdd", array (
                "SessionGUID" => $SessionGUID,
                "ActualDate" => $ActualDate,
                "Id_Language" => $Id_Language,
                "Id_PersonRequest" => $Id_PersonRequest,
                "id_RequestEnteranceCodes" => $id_RequestEnteranceCodes
                 )
                 );

        return $res;
    }
    
    /*
     * Удаление данных о специальных условиях поступления у заявки
     */
    
    public function   PersonRequestEnteranceCodesDel($SessionGUID, 
            $id_RequestEnteranceCodes ) {
        
         $res = $this->soap->invoke ( "PersonRequestEnteranceCodesDel", array (
                "SessionGUID" => $SessionGUID,
                "id_RequestEnteranceCodes" => $id_RequestEnteranceCodes
                 )
                 );

        return $res;
    }
    
    /*
     * Изменение  приоритета  заявления
     */
    
    public function   PersonRequestsPriorityChange($SessionGUID, $Id_PersonRequest, 
            $RequestPriority ) {
        
         $res = $this->soap->invoke ( "PersonRequestsPriorityChange", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,
                "RequestPriority" => $RequestPriority
                 )
                 );

        return $res;
    }
    
    /*
     * Получение  данных справочника типов персон.
     */
    
    public function   PersonTypeDictGet($SessionGUID, $Id_Language) {
        
         $res = $this->soap->invoke ( "PersonTypeDictGet", array (
                "SessionGUID" => $SessionGUID,
                "Id_Language" => $Id_Language,
                 )
                 );

        return $res;
    }
    
    /*
     * Получение  истории изменения типов персоны.
     */
    
    public function   PersonTypeChange($SessionGUID, $PersonCodeU,
            $Id_PersonTypeDict) {
        
         $res = $this->soap->invoke ( "PersonTypeChange", array (
                "SessionGUID" => $SessionGUID,
                "PersonCodeU" => $PersonCodeU,
                "Id_PersonTypeDict" => $Id_PersonTypeDict,
                 )
                 );

        return $res;
    }
    
    /*
     * Изменение значение среднего балла аттестата
     */
    
    public function   EntrantDocumentValueChange($SessionGUID, $AttestatValue,
            $IsCheckForPaperCopy, $UniversityKode, $Id_PersonDocument) {
        
         $res = $this->soap->invoke ( "EntrantDocumentValueChange", array (
                "SessionGUID" => $SessionGUID,
                "AttestatValue" => $AttestatValue,
                "IsCheckForPaperCopy" => $IsCheckForPaperCopy,
                "UniversityKode" => $UniversityKode,
                "Id_PersonDocument" => $Id_PersonDocument,
                 )
                 );

        return $res;
    }
    
    public function   PersonRequestEdit2($SessionGUID, $Id_PersonRequest,
            $OriginalDocumentsAdd, $IsNeedHostel, $CodeOfBusiness,
            $IsBudget, $IsContract, $IsHigherEducation, $SkipDocumentValue,
            $Id_LanguageEx, $Id_ForeignType, $IsForeignWay) {
        
         $res = $this->soap->invoke ( "PersonRequestEdit2", array (
                "SessionGUID" => $SessionGUID,
                "Id_PersonRequest" => $Id_PersonRequest,
                "OriginalDocumentsAdd" => $OriginalDocumentsAdd,
                "IsNeedHostel" => $IsNeedHostel,
                "CodeOfBusiness" => $CodeOfBusiness,
                "IsBudget" => $IsBudget,
                "IsContract" => $IsContract,
                "IsHigherEducation" => $IsHigherEducation,
                "SkipDocumentValue" => $SkipDocumentValue,
                "Id_LanguageEx" => $Id_LanguageEx,
                "Id_ForeignType" => $Id_ForeignType,
                "IsForeignWay" => $IsForeignWay,
                 )
                 );

        return $res;
    }
    
    /*
     * Получение текущей активной фотографии персоны
     */
    public function   PersonSOAPPhotoGet($SessionGUID, $UniversityKode, $PersonCodeU) {

     $res = $this->soap->invoke ( "PersonSOAPPhotoGet", array (
            "SessionGUID" => $SessionGUID,
            "UniversityKode" => $UniversityKode,
            "PersonCodeU" => $PersonCodeU,
             )
             );

    return $res;
    }
    
}
/*
 * 
 * 195!!!!!!
 */