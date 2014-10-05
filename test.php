  <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TEST</title>
        <link rel="stylesheet" href="./styles/edbo.css" type="text/css" media="screen" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.js"></script>
        <style>
        select {
			width: 300px; /* Ширина списка в пикселах */
        }
       </style>
        
        <script>
        $( document ).ready(function() {
			var a;
			if (a == undefined) {
				console.log('a - undefined');
			}
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
        $array = array(
            'one' => 1,
            'two' => 2,
            );
        echo json_encode($array);
        ?>
        <div align="center" class="select_direction"> 
            <select id="directions" name="my_select">
                <option value="-1">&nbsp;</option>
            </select>
        </div>
        <div align="center" class="log"> 

        </div>
     </body>
</html>