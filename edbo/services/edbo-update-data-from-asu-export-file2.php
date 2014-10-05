<!DOCTYPE html>
<!--
обновляет данные в ЕДБО на основании файла выгрузки из АСУ
на данный момент обновляет код дела и ИНН
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        
        <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" />
        
        <script src="//cdn.datatables.net/tabletools/2.2.1/js/dataTables.tableTools.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/css/dataTables.tableTools.min.css" />
        
        <link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls.swf" />
        <link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls_pdf.swf" />
        
<!--
    //cdn.datatables.net/tabletools/2.2.1/css/dataTables.tableTools.css
    //cdn.datatables.net/tabletools/2.2.1/css/dataTables.tableTools.min.css

images

    //cdn.datatables.net/tabletools/2.2.1/images/background.png
    //cdn.datatables.net/tabletools/2.2.1/images/collection.png
    //cdn.datatables.net/tabletools/2.2.1/images/collection_hover.png
    //cdn.datatables.net/tabletools/2.2.1/images/copy.png
    //cdn.datatables.net/tabletools/2.2.1/images/copy_hover.png
    //cdn.datatables.net/tabletools/2.2.1/images/csv.png
    //cdn.datatables.net/tabletools/2.2.1/images/csv_hover.png
    //cdn.datatables.net/tabletools/2.2.1/images/pdf.png
    //cdn.datatables.net/tabletools/2.2.1/images/pdf_hover.png
    //cdn.datatables.net/tabletools/2.2.1/images/print.png
    //cdn.datatables.net/tabletools/2.2.1/images/print_hover.png
    psd
        //cdn.datatables.net/tabletools/2.2.1/images/psd/collection.psd
        //cdn.datatables.net/tabletools/2.2.1/images/psd/copy document.psd
        //cdn.datatables.net/tabletools/2.2.1/images/psd/file_types.psd
        //cdn.datatables.net/tabletools/2.2.1/images/psd/printer.psd
    //cdn.datatables.net/tabletools/2.2.1/images/xls.png
    //cdn.datatables.net/tabletools/2.2.1/images/xls_hover.png

js

    //cdn.datatables.net/tabletools/2.2.1/js/dataTables.tableTools.js
    //cdn.datatables.net/tabletools/2.2.1/js/dataTables.tableTools.min.js

swf

    //cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls.swf
    //cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls_pdf.swf

-->

        

        <script>
            $(document).ready(function () {
                
                var dialog, form;
                var main_dialog;

                

                var winW = $(window).width() - 10;
                var winH = $(window).height() - 10;
                main_dialog = $("#main-dialog").dialog(
                {
                    height: winH,
                    width: winW,
                    closeOnEscape: false,
                    beforeClose: function (event, ui) { return false; },
                    dialogClass: "noclose"

                }
                );



                dialog = $("#select-file-form").dialog({
                    autoOpen: false,
                    height: 250,
                    width: 500,
                    modal: true,
                    buttons: {
                        "Загрузить": loadFile,
                        "Отмена": function () {
                            dialog.dialog("close");
                        }
                    },
                    close: function () {
                        //form[ 0 ].reset();
                        //allFields.removeClass("ui-state-error");
                    }
                });

                form = dialog.find("form").on("submit", function (event) {
                    event.preventDefault();
                    //addUser();
                    alert('form submit');
                });

                $("#load-file").button().on("click", function () {
                    dialog.dialog("open");
                });
                
                $("#show-file").button().on("click", function () {
                    showFile();
                });
                
                $("#set-inn").button().on("click", function () {
                    setINN();
                });

                //var win = $( window );
                /*
                 * $("#el1").position({
                 my: "right center",  // место на позиционируемом элементе
                 at: "right bottom",  // место на элементе относительно которого будет позиционирование
                 of: "#target"        // элемент относительно которого будет позиционирование
                 });
                 */
                //var target = $(".allwincontainer");
                //console.log(target);
                //$("#main-dialog")
                /*
                 main_dialog.position({
                 my: "left top",
                 at: "left top",
                 of: target
                 });
                 */
                function loadFile() {
                    console.log(form.find( "input[name='FileInput']" ));
                    form = dialog.find("form");
                    
                    $("#log").html('');
                    //console.log(form);
                    //data = new FormData(form[0]);//$('#form')[0]);
                    //console.log(form.find('sessionId').value);
                    var file_name = form.find( "input[name='FileInput']" ).val();
                    var sessionId = form.find( "input[name='sessionId']" ).val();
                    
                    var data = new FormData();
                    
                    //data.append('file', file_name);
                    data.append('sessionId', sessionId);
                    
                    var file = form.find( "input[name='FileInput']" ).get(0).files[0];
                    
                    if (file === undefined) {
                        alert ('Для загрузки необходимо выбрать файл!');
                        // dialog.dialog("close");
                        return;
                    }
                    data.append('csv_file',file);
                    data.append('action','set_cashe_csv');
                    data.append('cacheKey','csv_edbo-update-data-from-asu-export-file2');
                     $('#loading_01').html(
                                '<img src="../../image/filling-broken-ring.gif">');
                     dialog.dialog("close");
                    $.ajax({
                            type: 'POST',
                            url: "cashe_data.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            timeout: 1000*30,
                            cache: false,
                            async: true,
                        }).done(function(data) {
                            //alert(data);
                            console.log(data);
                            setTimeout(function () { $('#loading_01').html(data);}, 500);
                            //alert(data['error']['message']);
                            $("#err_message").html(data['error']['message']);
                            if (data['error']['code'] != 0) {
                                $("#div-dialog-warning").dialog('open');
                            }
                            //$('#loading').html(data);
                            
                            return false;
                            
                        }).fail(function(jqXHR,status, errorThrown) {
                            alert('Failed!');
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            setTimeout(function () { $('#loading_01').html('');}, 1000);
                            dialog.dialog("close");
                            return false;
                        });
                  //alert(1);
                };
                
                var t= 'Ошибка загрузки файла';
                $("#div-dialog-warning").dialog({
                    title: t,
                    resizable: false,
                    height: 400,
                    width: 500,
                    modal: true,
                    autoOpen: false,
                    buttons: {
                        "Ok" : function () {
                            $("#err_message").html('');
                            $(this).dialog("close");
                        }
                    }
                }).parent().addClass("ui-state-error");

           function showFile() {
                $("#table1").html('');
                /*
                    console.log(form.find( "input[name='FileInput']" ));
                    form = dialog.find("form");
                    //console.log(form);
                    //data = new FormData(form[0]);//$('#form')[0]);
                    //console.log(form.find('sessionId').value);
                    var file_name = form.find( "input[name='FileInput']" ).val();
                    var sessionId = form.find( "input[name='sessionId']" ).val();
                */
                    var data = new FormData();
                    
                    //data.append('file', file_name);
                    data.append('sessionId', sessionId);
                    
                    //var file = form.find( "input[name='FileInput']" ).get(0).files[0];
                    /*
                    if (file === undefined) {
                        alert ('Для загрузки необходимо выбрать файл!');
                        // dialog.dialog("close");
                        return;
                    }
                    data.append('csv_file',file);
                    */
                    data.append('action','get_table_data');
                    data.append('cacheKey','csv_edbo-update-data-from-asu-export-file2');
                     $('#loading_01').html(
                                '<img src="../../image/filling-broken-ring.gif">');
                     dialog.dialog("close");
                    $.ajax({
                            type: 'POST',
                            url: "cashe_data.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            timeout: 1000*30,
                            cache: false,
                            async: true,
                        }).done(function(data) {
                            //alert(data);
                            
                            
                            setTimeout(function () { $('#loading_01').html('');}, 500);
                            //console.log(data);
                            console.log(data['data']);
                            //alert(data['error']['message']);
                            //return;
                            if (data['error']['code'] != 0) {
                                $("#err_message").html(data['error']['message']);
                                $("#div-dialog-warning").dialog('open');
                            }
                            //$('#loading').html(data);
                            $("#table1").html('');
                            
                            
                            $('#table1').html( 
                                    '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example"></table>' 
                                    );
                            
                            $('#example').dataTable( {
                           
                           "data": data['data'],
                           "columns": data['data_title'],
                           "scrollX": true,
                           "dom": 'T<"clear">lfrtip',
                           "tableTools": {
                            "aButtons": [
                                "copy",
                                "print",
                                {
                                    "sExtends":    "collection",
                                    "sButtonText": "Save",
                                    "aButtons":    [ "csv", "xls", "pdf" ]
                                }
                            ]
                        }
        /*
                           "tableTools": {
                                "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                            }*/
                           /*[
                                { "title": "Engine" },
                                { "title": "Browser" },
                                { "title": "Platform" },
                                { "title": "Version", "class": "center" },
                                { "title": "Version", "class": "center" },
                                { "title": "Version", "class": "center" },
                                { "title": "Version", "class": "center" },
                                { "title": "Grade", "class": "center" }
                            ]*/
                            //"aaData": data['data'],
                            /*
                            "aoColumns": [{
                                "mData":"LastName",
                                "sTitle": "Фамилия"
                              },{
                                "mData":"FirstName",
                                "sTitle": "Имя"
                              },{
                                "mData":"INN",
                                "sTitle": "ИНН"
                              }/*,
                              
                              
                              {
                                "mData": "url",
                                "mRender": function ( url, type, full )  {
                                  return  '<a href="'+url+'">' + url + '</a>';
                                }
                              },{
                                "mData": "editor.name"
                              },{
                                "mData": "editor.phone"
                              },{
                                "mData":"editor",
                                "mRender": function(data){
                                  return data.email.join("<br>");
                                }
                              }
                          ]*/
        
                           
                             } );  
                             
                          
    
                            return false;
                            
                        }).fail(function(jqXHR,status, errorThrown) {
                            alert('Failed!');
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            setTimeout(function () { $('#loading_01').html('');}, 1000);
                            dialog.dialog("close");
                            return false;
                        });
                  //alert(1);
                };
                
                
                function setINN() {
                $("#log").html('');
                /*
                    console.log(form.find( "input[name='FileInput']" ));
                    form = dialog.find("form");
                    //console.log(form);
                    //data = new FormData(form[0]);//$('#form')[0]);
                    //console.log(form.find('sessionId').value);
                    var file_name = form.find( "input[name='FileInput']" ).val();
                   
                */
                    var sessionId = form.find( "input[name='sessionId']" ).val();
                    var data = new FormData();
                    
                    //data.append('file', file_name);
                    data.append('sessionId', sessionId);
                    
                    //var file = form.find( "input[name='FileInput']" ).get(0).files[0];
                    /*
                    if (file === undefined) {
                        alert ('Для загрузки необходимо выбрать файл!');
                        // dialog.dialog("close");
                        return;
                    }
                    data.append('csv_file',file);
                    */
                    data.append('action','get_without_inn');
                    data.append('cacheKey','csv_edbo-update-data-from-asu-export-file2');
                     $('#loading_01').html(
                                '<img src="../../image/filling-broken-ring.gif">');
                     dialog.dialog("close");
                    $.ajax({
                            type: 'POST',
                            url: "edbo-update-data-from-asu-export-file-module2.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            timeout: 1000*30,
                            cache: false,
                            async: true,
                        }).done(function(data) {
                            //alert(data);
                            
                            
                            setTimeout(function () { $('#loading_01').html('');}, 500);
                            //console.log(data);
                            console.log(data['data']);
                            //alert(data['error']['message']);
                            //return;
                            if (data['error']['code'] != 0) {
                                $("#err_message").html(data['error']['message']);
                                $("#div-dialog-warning").dialog('open');
                            }
                            //$('#loading').html(data);
                            $("#log").html('');
                            
                            
                            $('#log').html( 
                                    '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example"></table>' 
                                    );
                            
                            $('#example').dataTable( {
                           "scrollX": true,
                           "data": data['data'],
                           "columns": data['data_title'],
                           "dom": 'T<"clear">lfrtip',
                           "tableTools": {
                            "aButtons": [
                                "copy",
                                "print",
                                {
                                    "sExtends":    "collection",
                                    "sButtonText": "Save",
                                    "aButtons":    [ "csv", "xls", "pdf" ]
                                }
                            ]
                        }
                          
                             } );  
                             
                          
    
                            return false;
                            
                        }).fail(function(jqXHR,status, errorThrown) {
                            alert('Failed!');
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            setTimeout(function () { $('#loading_01').html('');}, 1000);
                            dialog.dialog("close");
                            return false;
                        });
                  //alert(1);
                };
                

            });
            
            

        </script>

    </head>
    <body>
        <div class="allwincontainer">

            <?php
            $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
            $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
            echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
            ?>

            <div id="main-dialog" title="Обновление данных в ЕДБО - 2">
                <p>Модуль предназначен для обновления данных (код дела и ИНН) в ЕДБО для абитуриентов из данных файла в формате CSV. Файл может быть получен путем выгрузки данных из АСУ </p>
                
                <div class="form_border">
                    <p>Этап 1 - Загрузка файла данных. Важно!!! Названия столбцов должны быть такими, как принято в ЕДБО!</p>
                </div>
                <div class="form_border">
                    <div align="center" class="transparent">
                    <button id="load-file">Загрузить файл</button>
                    <button id="show-file">Просмотреть файл</button>
                    <button id="set-inn">Загрузить ИНН абитуриентов в АСУ</button>
                    <div id="loading_01"></div>
                    </div>

                    <div id="select-file-form" title="Выбор файла">
                        <div class="ui-state-highlight">Файл должен иметь формат  *.csv.</div>

                        <form>
                            <fieldset>
                                <?php
                                $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
                                echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
                                ?>
                                <!--label for="filename">файл</label>
                                <input type="text" name="filename" id="filename" value="h" class="text ui-widget-content ui-corner-all"//-->

                                <input type="file" name="FileInput"/>

                                <!-- Allow form submission with keyboard without duplicating the dialog button -->
                                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                            </fieldset>
                        </form>
                    </div>
                    <div id="table1" >
                
                
                    </div>
                </div>
                
                <div id="div-dialog-warning">
                    <p> <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
                    <p id="err_message" ></p>
                </div>
            
            <div id="log" >
                
                
            </div>

            </div>
            
            
            
    
        </div> 
    </body>
</html>
