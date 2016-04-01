<?php

require_once("DB.php");

if (isset($_POST["changeWatched"])) {
    $DB = new DB();
    $value = ($_POST["changeWatched"] == "Watched") ? 1 : 0;
    $DB->update($_POST["Table"], "Watched", $value, array("OverAll"=>$_POST["Episode"]));
    $_POST["changeWatched"] = null;
}