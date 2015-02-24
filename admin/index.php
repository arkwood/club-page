<?php
require_once dirname(__FILE__) . '/../loader.php';
?>
<html>
    <head>
        <title>CLUBPAGE ADMIN</title>
        <?php  
        echo $GLOBAL[GLOBALSTATICCONTENT]; 
        // load google graph api if necessary
        if ($_SERVER["PHP_SELF"] == "/admin/visits.php") 
            echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';     
        ?>      
    </head>
    <body>
        <div class="container" id="admin">
            <?php
            if (!isset($GLOBALS[GLOBALSCRIPTCONTENT]))
                require_once DEFAULT_CONTENT_BACKEND;
            
            echo $GLOBALS[GLOBALSCRIPTCONTENT]; 
            ?>
        </div>
    </body>
</html>