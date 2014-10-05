<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>EDBO Admin</title>
        <link rel="stylesheet" href="../../styles/edbo.css" type="text/css" media="screen" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        
         <script>
            $(document).ready(function () {
                var dialog = $( "#login-dialog" ).dialog({
                    resizable: false,
                    height: 'auto', //170
                    width: 'auto', //350
                    closeOnEscape: false,
                    beforeClose: function (event, ui) { return false; },
                    dialogClass: "noclose"
                });
            });
        </script>
        
    </head>
    <body>

        <?php
        session_start();
        include_once '../edbo-utils/edbo-utils.php';
        include_once '../../utils/http-utils.php';
        
        $sessionId = get_input_str("sessionId");
        if (check_guid($sessionId)) {  
            header('Location: ./edbo-logout.php?sessionId='.  htmlspecialchars($sessionId)); 
        } else if (!is_null($sessionId) && strlen($sessionId) > 0){
           // if (strlen($sessionId)) {
                //print '<div class="err" align="center"><h1>ERROR: '.$sessionId.'</h1></div>';  // print error
                echo ' 
<div class="ui-widget">
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <strong></strong>',$sessionId,'</p>
    </div>
</div>
                ';
            //}
        }
      
        ?>
	
        <div align="center" id="login-dialog" title="Вход в систему ЕДБО">
            <!--p>Login to EDBO servise</p-->
            <div>
            <form action="./edbo-registeruser.php" method="POST">
                <table class="login1">
                    <tr>
                        <td>Логин:</td> <td><input type="text" name="login" size="25" required placeholder="login" autocomplete="on"> </td>
                    </tr>
                    <tr>
                        <td>Пароль:</td> <td><input type="password" name="password" size="25" required placeholder="password" autocomplete="on"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit"  value="Вход"> <!--br-->
                            <a href="../configure/edbo-configure.php">Конфигурировать</a>
                        </td>
                    </tr>
                </table>
            </form>
            </div>
        </div>     
    </body>
</html>
