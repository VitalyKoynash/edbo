<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
	<meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Expires" content="Wed, 26 Feb 1999 08:21:57 GMT">
        <meta http-equiv="Pragma" content="no-cache">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        
        <?php 
            require_once '../../utils/mobile-detect/Mobile_Detect.php';
            $detect = new Mobile_Detect;
            $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
            $scriptVersion = $detect->getScriptVersion();
            
            if ($deviceType == 'computer') {
                echo ''; 
            }else{
                echo '
                <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css" />
                <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script>
    
                ';            
}
        ?>
        
        <!--link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls.swf" />
        <link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/swf/copy_csv_xls_pdf.swf" /-->
        
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script> 
                
        <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" />

        <script src="//cdn.datatables.net/tabletools/2.2.1/js/dataTables.tableTools.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/tabletools/2.2.1/css/dataTables.tableTools.min.css" />
                
        <link rel="stylesheet" href="../swf/copy_csv_xls.swf" />
        <link rel="stylesheet" href="../swf/copy_csv_xls_pdf.swf" />
              
        <script>
            $(document).ready(function () {
                
                var dialog, form;
                var main_dialog;

                

                var winW = $(window).width() - 10;
                var winH = $(window).height() - 10;
                main_dialog = $("#main-dialog-koatuu").dialog(
                    {
                        height: 'auto',//winH,
                        width: 'auto',//winW,
                        closeOnEscape: false,
                        beforeClose: function (event, ui) { return false; },
                        dialogClass: "noclose",
                        position: ['center',20]
                        
                    }
                );
                //dialog = main_dialog;
                function showResultKOATUU() {
                    
                    var form = main_dialog.find("form");
                    
                    var sessionId = form.find( "input[name='sessionId']" ).val();
                    var search = form.find( "input[name='search_city']" ).val();
                    
                    if (search == "") {
                        alert("Введите название насчеленного пункта!");
                        return;
                    }
                    
                    $('#table1').html('<img src="../../image/filling-broken-ring.gif">');
                    
                    
                    var form_data = new FormData();
                    form_data.append('sessionId', sessionId);
                    form_data.append('search', search);
                    //var file = form.find( "input[name='FileInput']" ).get(0).files[0];
                    form_data.append('action','search_koatuu');
                    form_data.append('cacheKey','koatuu');
                    
                    //data.append('cacheKey','csv_edbo-update-data-from-asu-export-file2');
                    //console.log(form_data); 
                    //dialog.dialog("close");
                    $.ajax({
                            type: 'POST',
                            url: "edbo-koatuu-search_module.php",
                            data: form_data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            timeout: 1000*30,
                            cache: false,
                            async: true,
                        }).done(function(data) {
                            //alert('Ready');
                            //return;
                            //setTimeout(function () { $('#loading_01').html('');}, 500);
                            //console.log(data);
                            $('#table1').html('');
                            
                            if (data == undefined) {
                                alert('No get data');
                                return false;
                            }
                            
                            
                            if (data['error']['code'] != 0) {
                                $("#err_message").html(data['error']['message']);
                                $("#div-dialog-warning").dialog('open');
                               return false;
                            }
                           // $("#table1").html('');
                            $('#table1').html( 
                                    '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example"></table>' 
                                    );
                            var table = $('#example').DataTable( {
                           
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
                            });  

                            table_evt(table);
                            return false;
                            
                        }).fail(function(jqXHR,status, errorThrown) {
                            //alert('Ошибка загрузки! status ='+jqXHR.status+' '+jqXHR.responseText);
                            console.log(errorThrown);
                            console.log(jqXHR.responseText);
                            console.log(jqXHR.status);
                            //setTimeout(function () { $('#table1').html('');}, 1000);
                            $('#table1').html('');
                            $("#err_message").html(jqXHR.responseText);
                            $("#div-dialog-warning").dialog('open');
                            //dialog.dialog("close");
                            return false;
                        });
               
                };
                
                $("#btn_koatuu_search").button().on("click", function () {
                    showResultKOATUU();
                });
                
                
                var t= 'Ошибка поиска';
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
                
            });
            
            function table_evt(table) {
                
                $('#example tbody').on( 'click', 'tr', function () {
                    
                    if ( $(this).hasClass('selected') ) {
                        //alert(3);
                        $(this).removeClass('selected');
                    }
                    else {
                        //alert(4);
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                        //console.log($(this));
                    }
                    //alert( table.fnGetData(this));
                    //console.log($('#example').find('.selected'));
                    //console.log( table.rows('.selected').data());
                    //console.log(this);
                    console.log( table.row(this).data());
                    //console.log( $('#example').rows());
                } );
            }
    
        </script>
        
    </head>
    <body>
	<?php
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        echo '<input type="hidden" name="deviceType" id="sessionId" value="'.$deviceType.'" />';
        
	?>
        
        <div class="allwincontainer">

            <?php
            $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
            $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
            echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
            ?>

            <div id="main-dialog-koatuu" title="Поиск кодов КОАТУУ">
                <p>Поиск может быть выполнен по маске, например Крама* </p>
                
                <div align="center" >
                    <table>
                        <tr> <td>
                   <form style="display: inline; ">
                        <fieldset style="display: inline; border: none">
                            <?php
                            $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
                            echo '<input type="hidden" name="sessionId" id="sessionId" value="' . $sessionId . '" />';
                            ?>
                            
                            <table class="CSS_Table_default" align="center">
                                <tr>
                                    <td>Населенный пункт:</td> <td><input type="text" id="search" name="search_city" size="25" required placeholder="поиск" autocomplete="on"> </td>
                                </tr>
                                
                            </table>
                            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                        </fieldset>
                    </form>
                                </td>
                                <td>
                    <button id="btn_koatuu_search">Найти</button>
                        </td>
                    </tr>
                    </table>
                    <div id="table1" ></div>
                    
                </div>
                <div id="div-dialog-warning">
                    <p> <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
                    <p id="err_message" ></p>
                </div>
            
                <div id="log" >


                </div>

            </div>
        </div> 
        <!-- script src="./scripts/koatuu_search.js"></script -->
        <!--script data-main="dist/libs/app" src="dist/libs/require.js"></script -->
    </body>
</html>
