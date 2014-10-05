<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../styles/edbo.css" type="text/css" media="screen" />
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
        <script>
            $(document).ready(function () {

                $( "#menu-dialog" ).dialog({
                    resizable: false,
                    height: 'auto', //170
                    width: 'auto', //350
                    closeOnEscape: false,
                    beforeClose: function (event, ui) { return false; },
                    dialogClass: "noclose",
                    position: ['center',20]
                });
                
                $( "#accordion" ).accordion({
                    heightStyle: "content",
                    autoHeight: false,
                    clearStyle: true,   
                  });
               
            });
        </script>
    </head>
    <body>
        <?php 
        session_start();
        
       
        //include_once '../utils/http-utils.php';
        include_once './security/security.php';
        $sessionId = get_input_str('sessionId');
        
        include_once './ui/AdminPanel.php';
        $panel = new AdminPanel();
         
        
        $panel->addMenu('Разработка', "PHPInfo()", './phpinfo.php', NULL);
        $panel->addMenu('Разработка', "PHP PDO Wrapper Class", '../ext/ppwc-1.0.2/php-pdo-wrapper-class/index.php', NULL);
        $panel->addMenu('Разработка', "Test code EDBOGuides", './testcode/edbo-testcode_edboguides.php?sessionId='.$sessionId, NULL);
        $panel->addMenu('Разработка', "Test code EDBOPerson", './testcode/edbo-testcode_edboperson.php?sessionId='.$sessionId, NULL);
        $panel->addMenu('Разработка', "ВУЗы", './testcode/edbo-universities.php?sessionId='.$sessionId,  NULL);
                    
        $panel->addMenu('Все заявки', "Все заявки", './requests/edbo-requests.php?sessionId='.$sessionId.'&caption=Все заявки', NULL);            
        
        $panel->addMenu('Заявки - дневное отделение', "Бакалавры", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=1&caption=Заявки дневное бакалавры', 
                NULL);
                    
        $panel->addMenu('Заявки - дневное отделение', "Бакалавр (ускор)", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=11&caption=Заявки дневное ускоренное бакалавры', 
                NULL);
                    
        $panel->addMenu('Заявки - дневное отделение', "Специалисты", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=3&caption=Заявки дневное специалисты', 
                NULL);
                    
        $panel->addMenu('Заявки - дневное отделение', "Магистры", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=2&caption=Заявки дневное магистры', 
                NULL, NULL, NULL);
        
        $panel->addMenu('Заявки - заочное отделение', "Бакалавры", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=2&Id_Qualification=1&caption=Заявки заочное бакалавры', 
                NULL);
                    
        $panel->addMenu('Заявки - заочное отделение', "Бакалавр (заочное ускор.)", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=2&Id_Qualification=14&caption=Заявки заочное ускоренное бакалавры', 
                NULL);


        $panel->addMenu('Заявки - заочное отделение', "Заявки заочное специалисты", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=2&Id_Qualification=3&caption=Заявки заочное специалисты', 
                NULL);

        $panel->addMenu('Заявки - заочное отделение', "Заявки заочное магистры", 
                './requests/edbo-requests.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=2&Id_Qualification=2&caption=Заявки заочное магистры', 
                NULL);
        
        $panel->addMenu('Аналитика', "Анализ поступивших в другие ВУЗы", 
                './requests/edbo-bachelor-analytic.php?sessionId='.$sessionId
                .'&caption=Анализ поступивших в другие ВУЗы', 
                NULL);
        
        
        $panel->addMenu('Аналитика', "Анализ поступления", 
                './requests/edbo-entrant-analytic.php?sessionId='.
                $sessionId.
                '&caption=Анализ поступления', 
                NULL);



        $panel->addMenu('Аналитика', "Анализ ОМОД", 
                './requests/edbo-entrant-omod-analytic.php?sessionId='.
                $sessionId.
                '&caption=Анализ поступивших для ОМОД', 
                NULL);
                    
                    
        
        $panel->addMenu('Справочная информация', "Дерево КОАТУУ", 
                './requests/edbo-koatuu.php?sessionId='.$sessionId
                .'&caption=Дерево КОАТУУ', 
                NULL);

        $panel->addMenu('Справочная информация', "Поиск КОАТУУ", 
                './koatuu/edbo-koatuu-search.php?sessionId='.$sessionId
                .'&caption=Поиск КОАТУУ', 
                NULL);
        
        
        $panel->addMenu('Отчеты', "Отчет 1 - первое направление (по алфавиту)", 
                './requests/edbo-get-first-direction.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=1&caption=Report 1', 
                NULL);

        $panel->addMenu('Отчеты', "Отчет 2 - первое направление (по алфавиту)", 
                './requests/edbo-get-first-direction2.php?sessionId='.$sessionId
                .'&Id_PersonEducationForm=1&Id_Qualification=1&caption=Report 1', 
                NULL);
        
        $panel->addMenu('Отчеты', 'Отчет для деканатов ', 
                './reports/edbo-report-dekanat-entrants.php?sessionId='.$sessionId
                .'&caption=Списки для деканатов', 
                NULL);
        
        $panel->addMenu('Редактировать', "Редактировать баллы бакалаврам д/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы бакалаврам д/о&Id_PersonEducationForm=1&Id_Qualification=1', 
                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы бакалаврам з/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы бакалаврам з/о&Id_PersonEducationForm=2&Id_Qualification=1', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы ускоренникам д/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы ускоренникам д/о&Id_PersonEducationForm=1&Id_Qualification=11', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы ускоренникам з/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы ускоренникам з/о&Id_PersonEducationForm=2&Id_Qualification=14', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы специалистам д/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы специалистам д/о&Id_PersonEducationForm=1&Id_Qualification=3', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы специалистам з/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы специалистам з/о&Id_PersonEducationForm=2&Id_Qualification=3', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы магистрам д/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы магистрам д/о&Id_PersonEducationForm=1&Id_Qualification=2', 

                NULL);

        $panel->addMenu('Редактировать', "Редактировать баллы магистрам з/о", 
                './requests/edbo-edit-doc-value.php?sessionId='.$sessionId
                .'&caption=Редактировать баллы магистрам з/о&Id_PersonEducationForm=2&Id_Qualification=2', 

                NULL);
        
        $panel->addMenu('Редактировать', "Установка кодов", 
                './requests/edbo-set-codes-all.php?sessionId='.
                $sessionId.
                '&caption=Установка кодов', 
                NULL);
        
        $panel->addMenu('Редактировать', 'Установка статуса "допустить" ', 
                './requests/edbo-request-allow.php?sessionId='.$sessionId
                .'&caption=Установка статуса "допустить" для новых заявлений', 
                NULL);

        $panel->addMenu('Формирование студенческих', "Students Card", 
                './requests/edbo-get-studcard.php?sessionId='.
                $sessionId.
                '&caption=Students Card',
                //.'&Id_PersonEducationForm=1', //&Id_Qualification=11
                NULL);

        $panel->addMenu('Формирование студенческих', "Students Card - 1 курс не по приказу", 
                './requests/edbo-get-studcard-1kurs.php?sessionId='.
                $sessionId.
                '&caption=Students Card- 1 курс не по приказу',
                //.'&Id_PersonEducationForm=1', //&Id_Qualification=11
                NULL);
        
        $panel->addMenu('ЕДБО - АСУ', 'Обновление данных в ЕДБО ', 
                './services/edbo-update-data-from-asu-export-file.php?sessionId='.$sessionId
                .'&caption=Обновление данных в ЕДБО', 
                NULL);
        
                            
        $panel->addMenu('Завершение сеанса', "Выход", 
                'login/edbo-logout.php?sessionId='.$sessionId, NULL);

                    
        ?>
        
  
        <div id="menu-dialog" title="Меню">
            <div><?php echo date("d.m.Y 24:00:00");?>  </div>
            <div><?php echo $_SESSION['login'];?></div>
            <p></p>
            <?php
            echo $panel->getPanel('Меню');
            ?>
        </div>
    </body>
</html>
