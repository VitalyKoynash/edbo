<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Отчет для деканатов</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <!--script src="../../scripts/list.js"></script//-->
        <script type="text/javascript">
            $(document).ready(function () {
                var sessionId = $('#sessionId').val();

                $.ajax({
                    type: "GET",
                    url: "../requests/edbo-set-codes-all-module.php",
                    data: "action=get_formdata&sessionId=" + sessionId,
                    dataType: 'json',
                    timeout: 30000,
                    cache: false,
                    async: false,
                    // Выводим то что вернул PHP
                    success: function (res) {
                        //предварительно очищаем нужный элемент страницы
                        $("#log").empty();
                        console.log(res);
                        if (res == undefined)
                            return;

                        // заполняем коды поступления
                        var RequestEnteranceCodes = res['RequestEnteranceCodes'];
                        if (RequestEnteranceCodes == undefined)
                            return;
                        for (var idx in RequestEnteranceCodes) {
                            var code = RequestEnteranceCodes[idx];
                            $("#codes").append($('<option value="' + code['id_RequestEnteranceCodes'] + '">' +
                                    code['RequestEnteranceCodes'] + ' - ' + code['RequestEnteranceCodesName'] + '</option>'));
                        }
                        ;
                        // заполняем ОКР
                        var Qualifications = res['Qualifications'];
                        if (Qualifications == undefined)
                            return;
                        for (var idx in Qualifications) {
                            var qualification = Qualifications[idx];
                            $("#qualification").append(
                                    $('<option value="' + qualification['Id_Qualification'] + '">' +
                                            qualification['QualificationName'] + '</option>'));
                        }
                        ;

                    },
                    errrep: true, //отображение ошибок error если true
                    error: function (jqXHR, exception) {
                        if (jqXHR.status === 0) {
                            alert('Not connect.\n Verify Network.');
                        } else if (jqXHR.status == 404) {
                            alert('Requested page not found. [404]');
                        } else if (jqXHR.status == 500) {
                            alert('Internal Server Error [500].');
                        } else if (exception === 'parsererror') {
                            alert('Requested JSON parse failed.');
                        } else if (exception === 'timeout') {
                            alert('Time out error.');
                        } else if (exception === 'abort') {
                            alert('Ajax request aborted.');
                        } else {
                            alert('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    }
                });


                $(function () {

                    $('#form').submit(function (e) {
                        console.log($('loading'));

                        //alert('Start');
                        $('#loading').html(
                                '<img src="../../image/filling-broken-ring.gif">');

                        //var Id_Qualification = $("#qualification :selected").val();
                        var sessionId = $('#sessionId').val();
                        //alert(sessionId);
                        e.preventDefault();
                        data = new FormData($('#form')[0]);
                        console.log('Submitting');
                        $.ajax({
                            type: 'POST',
                            url: "edbo-report-dekanat-entrants-module.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //dataType: 'json',
                            timeout: 3000000,
                            cache: false,
                                    async: true,
                        }).done(function (data) {
                            //alert('Ok!');
                            //console.log(data);
                            $('#loading').html(data);
                            return false;
                            //setTimeout(function () { $('#loading').html(data);}, 1000);
                        }).fail(function (jqXHR, status, errorThrown) {
                            alert('Failed!');
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            setTimeout(function () {
                                $('#loading').html('');
                            }, 1000);
                            return false;
                        });
                    });
                });

            });
        </script>
    </head>
    <body>     
        <?php
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

        $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);

        echo '<div class="caption">' . $caption . '</div>';

        echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
        ?>


        <div align = "center" >
            <form id="form" method="post" action="" enctype="multipart/form-data">
                <div align="center" class="select_qualification"> 
                    <select id="qualification" name="qualification">
                        <option value="0">Усі форми ОКР</option>
                    </select>
                </div>
                <div align="center" class="select_qualification"> 
                    <select id="Id_PersonEducationForm" name="Id_PersonEducationForm">
                        <option value="0"> </option>
                        <option value="1" selected="selected">Денна форма </option>
                        <option value="2">Заочна форма </option>
                    </select>
                </div>

                <?php
                $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
                echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
                ?>
                <input type="submit" value="Сформировать" />
            </form>
            <div id="loading"></div>
        </div>
    </body>
</html>
