<?php
require_once('DB.php');
require_once('Constants.php');

class CreateTvShow
{
    public $DB;
    public $alert = "";

    public function __construct($db)
    {
        $this->DB = $db;
    }

    public function AddTvShowToMasterTable()
    {
        if (empty($_POST['Title']) or empty($_POST['ShowKey']) or empty($_POST['NoSeasons']) or empty($_POST['Status']))
        {
            $this->alert = EMPTY_ERROR;
        }
        else
        {
            $formData = array('Title' => $_POST['Title'], 'ShowKey' => $_POST['ShowKey'], 'NoSeasons' => $_POST['NoSeasons'], 'Status' => $_POST['Status']);

            $res = $this->DB->select('tvShows', array('ShowKey' => $_POST['ShowKey']));
            $this->alert = "This show already exists.";
            if (!count($res)) {
                $res = $this->DB->insert('tvShows', $formData);
                $this->alert = SUCCESSFUL_INSERT;
                $this->AddShowInfoTable($formData['ShowKey']);
                $this->AddSeasonTable($formData['ShowKey'], $formData['NoSeasons']);
            }
        }
    }

    private function AddShowInfoTable($showKey)
    {
        $res = $this->DB->create($showKey, array("SeasonNo"=>array("type"=>"INT", "size"=>0, "null"=>0, "default"=>"", "primary"=>1, "AI"=>0),
                                                 "NoEpisodes"=>array("type"=>"INT", "size"=>0, "null"=>0, "default"=>"", "primary"=>0, "AI"=>0),
                                                 "DateAired"=>array("type"=>"VARCHAR", "size"=>10, "null"=>0, "default"=>"", "primary"=>0, "AI"=>0),
                                                 "DateFinished"=>array("type"=>"VARCHAR", "size"=>10, "null"=>0, "default"=>"TBA", "primary"=>0, "AI"=>0)));
    }

    public function AddSeasonTable($showKey, $numSeasons, $startingSeason = 1)
    {
        for ($i = $startingSeason; $i < ($numSeasons + $startingSeason); $i++) {
            $tblName = $showKey . 'S' . str_pad($i, 2, "0", STR_PAD_LEFT);

            $res = $this->DB->select($tblName);

            if ($res=="Nothing found.") {

                $res = $this->DB->create($tblName, array("OverAll" => array("type" => "INT", "size" => 0, "null" => 0, "default" => "", "primary" => 0, "AI" => 0),
                    "EpisodeNo" => array("type" => "INT", "size" => 0, "null" => 0, "default" => "", "primary" => 1, "AI" => 1),
                    "Title" => array("type" => "VARCHAR", "size" => 50, "null" => 0, "default" => "TBA", "primary" => 0, "AI" => 0),
                    "Date" => array("type" => "VARCHAR", "size" => 10, "null" => 0, "default" => "TBA", "primary" => 0, "AI" => 0),
                    "Watched" => array("type" => "BIT", "size" => 1, "null" => 0, "default" => "b'0'", "primary" => 0, "AI" => 0)));
            }
        }
    }



    public function AddRowToInfoTable($noSeasons, $showKey)
    {
        $noSeasonsOfShow = $this->DB->lineCount($showKey);

        for ($i = $noSeasonsOfShow + 1; $i <= $noSeasons + $noSeasonsOfShow; $i++)
        {
            $info = array("SeasonNo" => $_POST["SeasonNo" . $i],
                          "NoEpisodes" => $_POST["NoEpisodes" . $i],
                          "DateAired" => $_POST["DateAired" . $i],
                          "DateFinished" => $_POST["DateFinished" . $i]);
            $res = $this->DB->insert($showKey, $info);
            $this->AddSeasonTable($showKey, $noSeasons, ($noSeasonsOfShow + 1));
            $this->DB->update("tvShows", "NoSeasons", $i, array("ShowKey"=>$showKey));
        }
    }

    public function AddEpisodeToSeasonTable($showKey, $seasonNum, $noEpisodes)
    {
        $seasonTable = $showKey . "S" . str_pad($seasonNum, 2, "0", STR_PAD_LEFT);

        $noEpisodesOfSeason = $this->DB->lineCount($seasonTable);

        for ($i = $noEpisodesOfSeason + 1; $i <= $noEpisodes + $noEpisodesOfSeason; $i++)
        {
            $info = array("OverAll" => $_POST["OverAll" . $i],
                "EpisodeNo" => $_POST["EpisodeNo" . $i],
                "Title" => $_POST["Title" . $i],
                "Date" => $_POST["Date" . $i],
                "Watched" => $_POST["Watched" . $i]);
            $res = $this->DB->insert($seasonTable, $info);
        }
    }
}