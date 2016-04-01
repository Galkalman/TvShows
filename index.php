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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>

<body>
    <div id="Header">
        <?php echo $Header;
//        $seriesName = "The Flash";
//        $myXMLData = file_get_contents("http://thetvdb.com/api/GetSeries.php?seriesname=" . urlencode($seriesName));
//        $xml=new SimpleXMLElement($myXMLData) or die("Error: Cannot create object");echo "<br><br><br><br><br>";
//        for ($i = 0; $i < count($xml->Series); $i++){
//            echo ("<img src=\"http://thetvdb.com/banners/" . $xml->Series[$i]->banner . "\">");
//        };?>
    </div>

    <div id="Content">
        <?php echo $output;?>
    </div>

    <div id="Footer">
        <?php echo $footer;?>
    </div>

</body>

</html>