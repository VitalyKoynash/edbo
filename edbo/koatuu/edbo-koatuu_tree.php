<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
	<meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Expires" content="Wed, 26 Feb 1999 08:21:57 GMT">
        <meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" href="dist/jstree-themes/default/style.min.css" />
        
    </head>
    <body>
        <?php
        
        set_time_limit(600);
        include './edbo-initsoap.php';
        include './utils/utils.php';

        $sessionId = filter_input(INPUT_GET, "sessionId", FILTER_SANITIZE_STRING);
        echo '<input type="hidden" name="sessionId" id="sessionId" value="'.$sessionId.'" />';
        ?>
        <div id="koatuu">...</div>
        <?php
        // put your code here
        ?>
        <script data-main="edbo/dist/libs/app" src="edbo/dist/libs/require.js"></script>
    </body>
</html>
