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
		//if (res.search("[FAILED]") == -1) {
				//console.log(RequestEnteranceCodes);
                  //  $("#log").append(res);
                  //return;  
                //}
                //$("#directions :selected").val();
                //http://www.webnotes.com.ua/index.php/archives/699
				
                /*
                 * add to table
                 * <table id="myTable">
  <tbody>
    <tr>...</tr>
    <tr>...</tr>
  </tbody>
  <tr>...</tr>
</table>
      requests/edbo-set-codes-all.php
                  $('#myTable tr:last').after('<tr>...</tr><tr>...</tr>');
                 */

                //$("#log").append(res);
		</script>
        <script>
        $(document).ready(function() {
		
            var sessionId = $('#sessionId').val();
            
            $.ajax({
            type: "GET",
            url: "./edbo-set-codes-all-module.php",
            data: "action=get_formdata&sessionId="+sessionId,
            dataType: 'json',
            timeout: 30000,
            cache: false,
            async: false,
            // Выводим то что вернул PHP
            success: function(res) {
                //предварительно очищаем нужный элемент страницы
                $("#log").empty();
		console.log(res);		
                if (res == undefined) return;
				
                // заполняем коды поступления
                var RequestEnteranceCodes = res['RequestEnteranceCodes'];
                if (RequestEnteranceCodes == undefined) return;
                for (var idx in RequestEnteranceCodes) {
                    var code = RequestEnteranceCodes[idx] ;
                    $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
						code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                };
                // заполняем ОКР
                var Qualifications = res['Qualifications'];
                if (Qualifications == undefined) return;
                for (var idx in Qualifications) {
                    var qualification = Qualifications[idx] ;
                    $("#qualification").append( 
					$('<option value="'+qualification['Id_Qualification']+'">'+
						qualification['QualificationName']+'</option>'));
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

            
            $("#codes" ).change(function() {
                //alert( $("#codes :selected").val() );
            });

            $("#qualification" ).change(function() {
                //alert( $("#qualification :selected").val() );
            });
            
            console.log( "document loaded" );

            /*
            var obj = $('.btn_set_code');
            var btn_set_code = $(obj.selector);
            btn_set_code.click(function() {	
                alert('apply code');
            });
            */
           
            $("#btn_apply_code").button();
            $("#btn_apply_code").click(function (evt) {
                
                var answer = confirm("Операция присвоения кодов в ЕДБО необратима. Продолжить?")
                if (answer){
                }
                else{
                    return;
                }

                // increment the value of output 
                //$('.output').html(++count);
                //console.log(evt);
                //alert ($("#codes :selected").val());
                var Id_Qualification = $("#qualification :selected").val();
                var id_RequestEnteranceCodes = $("#codes :selected").val();
                
                /*
                if (id_RequestEnteranceCodes == -1) {
                    alert ('Не выбран код условий поступления');
                    return;
                }
                */
               
                if (Id_Qualification == -1) {
                    alert ('Не выбран ОКР');
                    return;
                }
                if (id_RequestEnteranceCodes == -1) {
                    alert ('Не выбран код для установки');
                    return;
                }
                $.ajax({
                type: "GET",
                url: "./edbo-set-codes-all-module.php",
                data: "action=set_requests_code&sessionId="+sessionId+
                        "&Id_Qualification="+Id_Qualification+
                        "&id_RequestEnteranceCodes="+id_RequestEnteranceCodes,
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
                        var Id_PersonRequestEnteranceCode = data['Id_PersonRequestEnteranceCode'];
                        $(".log").append('<br>'+Id_PersonRequest+' ; '+FIO+' ; id_request_code = '
                                +Id_PersonRequestEnteranceCode);
                        /*
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                        */
                    };
                    
                    return;
                    if (res == undefined) return;

                    // заполняем коды поступления
                    var RequestEnteranceCodes = res['RequestEnteranceCodes'];
                    if (RequestEnteranceCodes == undefined) return;
                    for (var idx in RequestEnteranceCodes) {
                        var code = RequestEnteranceCodes[idx] ;
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                    };
                    // заполняем ОКР
                    var Qualifications = res['Qualifications'];
                    if (Qualifications == undefined) return;
                    for (var idx in Qualifications) {
                        var qualification = Qualifications[idx] ;
                        $("#qualification").append( 
                                            $('<option value="'+qualification['Id_Qualification']+'">'+
                                                    qualification['QualificationName']+'</option>'));
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
            
            $("#btn_show_entrant_without_code").button();
            $("#btn_show_entrant_without_code").click(function (evt) {
                // increment the value of output 
                //$('.output').html(++count);
                var Id_Qualification = $("#qualification :selected").val();
                var id_RequestEnteranceCodes = $("#codes :selected").val();
                
                /*
                if (id_RequestEnteranceCodes == -1) {
                    alert ('Не выбран код условий поступления');
                    return;
                }
                */
               
                if (Id_Qualification == -1) {
                    alert ('Не выбран ОКР');
                    return;
                }
                $.ajax({
                type: "GET",
                url: "./edbo-set-codes-all-module.php",
                data: "action=get_requests_without_codes&sessionId="+sessionId+
                        "&Id_Qualification="+Id_Qualification,
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
                        var EntranceCodes = data['EntranceCodes'];
                        var RequestEnteranseCodes = data['RequestEnteranseCodes'];
                        $(".log").append('<br>'+Id_PersonRequest+' ; '+FIO+' ; codes: '+EntranceCodes +
                                ' ('+RequestEnteranseCodes+')');
                        /*
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                        */
                    };
                    
                    return;
                    if (res == undefined) return;

                    // заполняем коды поступления
                    var RequestEnteranceCodes = res['RequestEnteranceCodes'];
                    if (RequestEnteranceCodes == undefined) return;
                    for (var idx in RequestEnteranceCodes) {
                        var code = RequestEnteranceCodes[idx] ;
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                    };
                    // заполняем ОКР
                    var Qualifications = res['Qualifications'];
                    if (Qualifications == undefined) return;
                    for (var idx in Qualifications) {
                        var qualification = Qualifications[idx] ;
                        $("#qualification").append( 
                                            $('<option value="'+qualification['Id_Qualification']+'">'+
                                                    qualification['QualificationName']+'</option>'));
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
			
			
			$("#btn_add_code_entrant").button();
            $("#btn_add_code_entrant").click(function (evt) {
                // increment the value of output 
                //$('.output').html(++count);
                var Id_Qualification = $("#qualification :selected").val();
                var id_RequestEnteranceCodes = $("#codes :selected").val();
                
                /*
                if (id_RequestEnteranceCodes == -1) {
                    alert ('Не выбран код условий поступления');
                    return;
                }
                */
               
                if (Id_Qualification == -1) {
                    alert ('Не выбран ОКР');
                    return;
                }
                $.ajax({
                type: "GET",
                url: "./edbo-set-codes-all-module.php",
                data: "action=get_requests_without_codes&sessionId="+sessionId+
                        "&Id_Qualification="+Id_Qualification,
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
                        var EntranceCodes = data['EntranceCodes'];
                        var RequestEnteranseCodes = data['RequestEnteranseCodes'];
                        $(".log").append('<br>'+Id_PersonRequest+' ; '+FIO+' ; codes: '+EntranceCodes +
                                ' ('+RequestEnteranseCodes+')');
                        /*
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                        */
                    };
                    
                    return;
                    if (res == undefined) return;

                    // заполняем коды поступления
                    var RequestEnteranceCodes = res['RequestEnteranceCodes'];
                    if (RequestEnteranceCodes == undefined) return;
                    for (var idx in RequestEnteranceCodes) {
                        var code = RequestEnteranceCodes[idx] ;
                        $("#codes").append( $('<option value="'+code['id_RequestEnteranceCodes']+'">'+
                                                    code['RequestEnteranceCodes']+' - '+code['RequestEnteranceCodesName']+'</option>'));
                    };
                    // заполняем ОКР
                    var Qualifications = res['Qualifications'];
                    if (Qualifications == undefined) return;
                    for (var idx in Qualifications) {
                        var qualification = Qualifications[idx] ;
                        $("#qualification").append( 
                                            $('<option value="'+qualification['Id_Qualification']+'">'+
                                                    qualification['QualificationName']+'</option>'));
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
			
            //console.log($("#btn_ok"))
            //$("#btn_ok").button();
			/*
            $(".ddd").button();
            
            $(".ddd").click(function (evt) {
                console.log(evt);
                alert(evt['currentTarget']['dataset']['id']);
            });
			*/
            //alert(456);
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
        
        <?php
        /*
        $array = array(
            'one' => 1,
            'two' => 2,
            );
        echo json_encode($array);
         
         */
        ?>
        <div align="center" class="select_direction"> 
            <select id="codes" name="codes">
                <option value="-1">Выбор кода</option>
            </select>
        </div>
        <div align="center" class="select_qualification"> 
            <select id="qualification" name="qualification">
                <option value="-1">Выбор ОКР</option>
            </select>
        </div>
        <br>
        <div align="center" class="apply_code"> 
            <!--button class="btn_set_code">Добавить код всем заявкам выбранного направления</button//-->
            <div id="btn_apply_code">
                <strong>Добавить код условий поступления всем заявкам выбранного направления</strong>
            </div>
            <br>
            <br>
            <div id="btn_show_entrant_without_code">
                <strong>Показать заявки без кодов условий поступления</strong>
            </div>
			
			<br>
            <br>
            <div id="btn_add_code_entrant">
                <strong>Добавить код персоне</strong>
				
            </div>
			
			<div align="center" class="select_for"> 
				<select id="person" name="person">
					<option value="-1">Выбор персоны</option>
				</select>
			</div>
			
			<div align="center" class="select_person"> 
				<select id="person" name="person">
					<option value="-1">Выбор персоны</option>
				</select>
			</div>

        </div>
        <div align="center" class="log"> 

        </div>
     </body>
</html>