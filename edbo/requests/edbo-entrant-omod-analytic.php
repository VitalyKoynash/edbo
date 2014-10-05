<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Анализ для ОМОД</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <script src="../../scripts/list.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                //var sessionId = $('#sessionId').val();
                $(function() {
                    $('#form').submit(function(e) {
                        $('#loading').html(
                            '<img src="http://preloaders.net/preloaders/287/Filling%20broken%20ring.gif"> loading...');
                        var sessionId = $('#sessionId').val();
                        alert(sessionId);
                        e.preventDefault();
                        data = new FormData($('#form')[0]);
                        console.log('Submitting');
                        $.ajax({
                            type: 'POST',
                            url: "edbo-entrant-omod-analytic-module.php",
                            data: data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //dataType: 'json',
                            timeout: 30000,
                            cache: false,
                            async: false,
                        }).done(function(data) {
                            alert('Ok!');
                            console.log(data);
                            setTimeout(function () { $('#loading').html(data);}, 1000);
                        }).fail(function(jqXHR,status, errorThrown) {
                            alert('Failed!');
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            setTimeout(function () { $('#loading').html('');}, 1000);
                        });
                    });
                });

            });
        </script>
    </head>
    <body>               
<?php
/*
        // put your code here
        set_time_limit(600);
        include '../edbo-provider/edbo-initsoap.php';
        include '../../utils/utils.php';
        include "EntrantAnalytycs.php";

        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);

        $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);

        echo '<div class="caption">' . $caption . '</div>';
        // запрос языка
        $res = $eg->{'LanguagesGet'}($sessionId);
        $eg->printLastError($sessionId);
        $dLanguages = $res['dLanguages'];
        $Id_Language = $dLanguages['Id_Language'];
        */
?>
        <div align = "center" >
            <form id="form" method="post" action="post.php" enctype="multipart/form-data">
            <input type="file" name="FileInput"/>
            <?php
             $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
             echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
            ?>
            <input type="submit" value="Upload" />
            </form>
            <div id="loading"></div>
        </div>
</body>
</html>
