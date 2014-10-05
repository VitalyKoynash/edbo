<?php
    if( isset($_SERVER['HTTPS'] ) ) {
        header('Location: https://'.$_SERVER['SERVER_NAME'].'/edbo/edbo/services/edbo-update-data-from-asu-export-file2.php?'.$_SERVER['QUERY_STRING']);
    }else{
        header('Location: http://'.$_SERVER['SERVER_NAME'].'/edbo/edbo/services/edbo-update-data-from-asu-export-file2.php?'.$_SERVER['QUERY_STRING']);

    } 
?>

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

        <script>
            $(document).ready(function () {
                var dialog, form;
                var main_dialog;

                

                var winW = $(window).width() - 10;
                var winH = $(window).height() - 10;
                main_dialog = $("#main-dialog").dialog(
                    {
                        closeOnEscape: false,
                        height: winH,
                        width: winW,
                        
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
                    
                     $('#loading_01').html(
                                '<img src="../../image/filling-broken-ring.gif">');
                     dialog.dialog("close");
                    $.ajax({
                            type: 'POST',
                            url: "edbo-update-data-from-asu-export-file-module.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //dataType: 'json',
                            timeout: 3000000,
                            cache: false,
                            async: true,
                        }).done(function(data) {
                            //alert(data);
                            console.log(data);
                            setTimeout(function () { $('#loading_01').html(data);}, 500);
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

            <div id="main-dialog" title="Обновление данных в ЕДБО">
                <p>Модуль предназначен для обновления данных (код дела и ИНН) в ЕДБО для абитуриентов из данных файла в формате CSV. Файл может быть получен путем выгрузки данных из АСУ </p>
                <div align="center">
                <button id="load-file">Загрузить файл</button>
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

            </div>
        </div> 
    </body>
</html>
