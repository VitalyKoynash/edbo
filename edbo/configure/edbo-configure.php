<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Expires" content="Wed, 26 Feb 1999 08:21:57 GMT">
        <meta http-equiv="Pragma" content="no-cache">
         <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        
         <script>
            $(document).ready(function () {
                var dialog = $( "#configure-dialog" ).dialog({
                    //"option", "position", { my: "left top", at: "left bottom", of: button } ,
                    resizable: false,
                    height: 'auto', //170
                    width: 'auto', //350
                    closeOnEscape: false,
                    beforeClose: function (event, ui) { return false; },
                    dialogClass: "noclose"
                });
                
                
            });
        </script>
        <title>EDBO settings</title>
    </head>
    <body>
	
        <?php
	session_start();
        require_once './config.php';

        $err = filter_input(INPUT_GET, "err", FILTER_SANITIZE_STRING);
        if (isset($err) && strlen($err) > 0) {
            echo '<div align="center"> <p>'.$err.'</p></div>';
        }
        
        
        echo '<div id="configure-dialog" title="Настройки SOAP ЕДБО">
            
            <form action="./edbo-applyconfigure.php" method="POST">
                <table>';
        //var_dump($cfg);
        foreach ($cfg as $key => $value) {
            if (isset($_SESSION[$key])) {
                $value = $_SESSION[$key];
            } else {
                $_SESSION[$key] = $value;
            }
            echo '<tr>
                        <td>'.$key.'</td> <td><input type="text" name="'.$key.'" value="'.$value.'" size="50" required> </td>
                    </tr>';
            }
                   
        echo '                <td >&nbsp;</td><td align="center">
                            <input type="submit"  value="Применить">
                        </td>
                    </tr>
                </table>
            </form>
        </div>';     
                                
        ?>
    </body>
</html>
