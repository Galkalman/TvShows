<?php
require_once("view.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tv Shows</title>
    <link rel="stylesheet" type="text/css" href="./BasicCSS.css">
    <link rel="icon" href="./favicon.png">
</head>

<body>
    <div id="Header">
        <?php echo $Header;?>
    </div>

    <div id="Content">
        <?php echo $output;?>
    </div>

    <div id="Footer">
        <?php echo $footer;?>
    </div>

</body>

</html>