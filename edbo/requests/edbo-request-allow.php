  <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Автоматическая простановка кодов</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="print" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.js"></script>
        <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <style>
        select {
			width: 300px; /* Ширина списка в пикселах */
        }
       </style>

        <script>
        $(document).ready(function() {
	        var sessionId = $('#sessionId').val();
           
            $("#btn_request_allow").button();
            $("#btn_request_allow").click(function (evt) {
    
                $.ajax({
                type: "GET",
                url: "./edbo-request-allow-module.php",
                data: "action=set_allow&sessionId="+sessionId,
                dataType: 'json',
                timeout: 30000,
                cache: false,
                async: false,
                // Выводим то что вернул PHP
                success: function(res) {
                    //предварительно очищаем нужный элемент страницы
                    $(".log").empty();
                    console.log(res);	
                    //return;
                    for (var idx in res) {
                        var data = res[idx] ;
                        var Id_PersonRequest = data['Id_PersonRequest'];
                        var FIO = data['FIO'];
						var status = data['res2'];
						var QualificationName = data['QualificationName'];
						
						var SpecDirectionName  = data['SpecDirectionName'];
                        //var Id_PersonRequestEnteranceCode = data['Id_PersonRequestEnteranceCode'];
                        
						$(".log").append('<br>'+Id_PersonRequest+' ; '+FIO+' ; status = '
                                +status + ' ; ' + SpecDirectionName + ' ; '+ QualificationName);
 
                    };
                    
                },
                errrep:true,//отображение ошибок error если true
                error: function(jqXHR, exception) {
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
			});
        });
        </script>
        
    </head>
    <body>
        <?php
        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        
        $caption = filter_input(INPUT_GET, "caption", FILTER_SANITIZE_STRING);
        
        echo '<div class="caption">'.$caption.'</div>';
        
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        ?>
        
        
       
        <div align="center" class="request_allow"> 
             <div id="btn_request_allow">
                <strong>Допустить все новые заявки</strong>
            </div>
        </div>
        <div align="center" class="log"> 

        </div>
     </body>
</html>