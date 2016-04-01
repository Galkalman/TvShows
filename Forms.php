<?php

require_once("Constants.php");
require_once("DB.php");
require_once("CreateSeries.php");
require_once("editTables.php");

class Forms
{
    public $DB;

    public function __construct($db)
    {
        $this->DB = $db;
        $this->CreateSeries = new CreateTvShow($this->DB);
        $this->EditTable = new EditTable($this->DB);
    }

    public function CreateSeriesForm($alert)
    {
        $template =  '<div>
                        <form action="./AddSeason" method="post" id="CreateForm">
                            <table id="FormTable">
                                <tr>
                                    <td class="FormTableTd">
                                        Title:
                                    </td>
                                    <td class="FormTableTd">
                                        <input type="text" name="Title">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="FormTableTd">
                                        Show Key:
                                    </td>
                                    <td class="FormTableTd">
                                        <input type="text" name="ShowKey">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="FormTableTd">
                                        # of Seasons:
                                    </td>
                                    <td class="FormTableTd">
                                        <input type="text" name="NoSeasons">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="FormTableTd">
                                        Status:
                                    </td>
                                    <td class="FormTableTd">
                                        <select name="Status" form="CreateForm" required>
                                            <option value="1">' . RUNNING  . '</option>
                                            <option value="2">' . WINTER   . '</option>
                                            <option value="3">' . FINISHED . '</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="FormTableTd" id="SubmitForm">
                                        <input type="submit" value="Create Series" name="createSeries">
                                    </td>
                                </tr>
                            </table>
                        </form>
                      </div>';

        if ($alert == EMPTY_ERROR)
        {
            $template .= '<div class="Error"><p>' . EMPTY_ERROR . '</p></div>';
        }
        elseif ($alert == SHOW_EXISTS)
        {
            $template .= '<div class="Error"><p>' . SHOW_EXISTS . '</p></div>';
        }
        elseif ($alert == SUCCESSFUL_INSERT)
        {
            $template .= '<div class="Success"><p>' . SUCCESSFUL_INSERT . '</p></div>';
        }
        elseif ($alert != "")
        {
            $template .= '<div class="Error"><p>' . $alert . '</p></div>';
        }

        return $template;
    }

    public function NumberOfSeasonsToAdd($error)
    {
        $allShows = $this->DB->select("tvShows");

        $template = '<div>
                        <form action="./AddSeason" method="post" id="AddSeasonsForm">
                            <table id="FormTable">
                                <tr>
                                    <td class="FormTableTd">
                                        <h4> Show Title </h4>
                                    </td>
                                    <td class="FormTableTd">
                                        <select name="titleToAddSeasonsTo" form="AddSeasonsForm" required>';

        for ($i = 0; $i < count($allShows); $i++)
        {
            $template .= '<option value="' . $allShows[$i]["ShowKey"] . '">' . $allShows[$i]["Title"]  . '</option>';
        }

        $template .= '                </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="FormTableTd">
                                        <h4> How many seasons would you like to add? </h4>
                                    </td>
                                    <td class="FormTableTd">
                                        <input type="text" name="seasonsToAdd">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="FormTableTd" id="SubmitForm">
                                        <input type="submit" value="Add Seasons" name="NumberOfSeasonsToAdd">
                                    </td>
                                </tr>
                            </table>
                        </form>
                     </div>';

        if ($error != "")
        {
            $template .= '<div class="Error"><p>' . $error . '</p></div>';
        }

        return $template;
    }

    public function AddSeasonsForm($seasonsToAdd, $showKey)
    {
        $title = $this->DB->select("tvShows", array("ShowKey"=>$showKey), "Title")[0]["Title"];

        $template = '<div>
                        <form action="./AddSeason" method="post">
                            <table id="FormTable">
                                <caption><h1>' . $title . '</h1></caption>';

        $seasonsNumberInTable = $this->DB->lineCount($showKey);

        for ($i = 1; $i <= $seasonsToAdd; $i++) {
            $template .= '<tr>
                            <td colspan="2" class="FormTableTd">
                                <h2> Season ' . ($i + $seasonsNumberInTable) . ':</h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                <input type="hidden" value="' . ($i + $seasonsNumberInTable) . '" name="SeasonNo' . ($i + $seasonsNumberInTable) . '">
                            </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                Number of Episodes:
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="NoEpisodes' . ($i + $seasonsNumberInTable) . '">
                            </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                Date Started:
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="DateAired' . ($i + $seasonsNumberInTable) . '">
                            </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                Date Finished:
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="DateFinished' . ($i + $seasonsNumberInTable) . '" value="TBA">
                            </td>
                        </tr>';
        }

        $template .= '<tr>
                        <td colspan="2" class="FormTableTd" id="SubmitForm">
                            <input type="submit" value="Add Season" name="AddSeason">
                        </td>
                      </tr></table>
                      <input type="hidden" value="' . $showKey . '" name="showKey">
                      <input type="hidden" value="' . $seasonsToAdd . '" name="seasonsToAdd">
                      </form></div>';

        return $template;
    }

    public function NumberOfEpisodesToAdd($error)
    {
        $allShows = $this->DB->select("tvShows");

        $template = '<div>
                        <form action="./InsertEpisodes" method="post" id="AddEpisodesForm">
                            <table id="FormTable">
                                <tr>
                                    <td class="FormTableTd">
                                        <h4> Show Title </h4>
                                    </td>
                                    <td class="FormTableTd">
                                        <select name="showToAddEpisodesTo" form="AddEpisodesForm" required>';

        for ($i = 0; $i < count($allShows); $i++)
        {
            $template .= '<option value="' . $allShows[$i]["ShowKey"] . '">' . $allShows[$i]["Title"]  . '</option>';
        }

        $template .= '            </select>
                             </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                <h4> Season Number </h4>
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="seasonToAddEpisodesTo">
                            </td>
                        </tr>
                        <tr>
                            <td class="FormTableTd">
                                <h4> How many episodes would you like to add? </h4>
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="noEpisodesToAdd">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="FormTableTd" id="SubmitForm">
                                <input type="submit" value="Add Episodes" name="NumberOfEpisodesToAdd">
                            </td>
                        </tr>
                    </table>
                </form>
             </div>';

        if ($error != "")
        {
            $template .= '<div class="Error"><p>' . $error . '</p></div>';
        }

        return $template;
    }

    public function AddEpisodesForm($showKey, $seasonNum, $noEpisodes)
    {
        $template = '<div>
                        <form action="./InsertEpisodes" method="post">
                            <table id="FormTable">
                                <caption><h1></h1></caption>
                                <tr>
                                    <td class="FormTableTd">
                                        Episode Number
                                    </td>
                                    <td class="FormTableTd">
                                        Title
                                    </td>
                                    <td class="FormTableTd">
                                        Air Date
                                    </td>
                                    <td class="FormTableTd">
                                        Watched
                                    </td>
                                </tr>';

        $seasonTable = $showKey . "S" . str_pad($seasonNum, 2, "0", STR_PAD_LEFT);
        $episodesAlreadyInTable = $this->DB->lineCount($seasonTable);

        $lastSeasonOverAll = $this->getOverAll($seasonNum, $seasonTable, $episodesAlreadyInTable, $showKey);

        $res = $this->DB->select($showKey, array("SeasonNo"=>$seasonNum));

        if(($episodesAlreadyInTable + $noEpisodes) > $res[0]["NoEpisodes"])
            return $this->NumberOfEpisodesToAdd("The number of episodes exceed the number of episodes in this season.");

        for ($i = 1; $i <= $noEpisodes; $i++) {
            $template .= '<tr>
                            <td class="FormTableTd">
                                ' . $seasonTable . 'E' . str_pad(($i + $episodesAlreadyInTable), 2, "0", STR_PAD_LEFT) . '
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="Title' . ($i + $episodesAlreadyInTable) . '" value="TBA">
                            </td>
                            <td class="FormTableTd">
                                <input type="text" name="Date' . ($i + $episodesAlreadyInTable) . '" value="TBA">
                            </td>
                            <td class="FormTableTd">
                                <input type="radio" name="Watched' . ($i + $episodesAlreadyInTable) . '" value="0" checked> Unwatched
                                <input type="radio" name="Watched' . ($i + $episodesAlreadyInTable) . '" value="1"> Watched
                            </td>
                            <td class="FormTableTd">
                            </td>
                            <td class="FormTableTd">
                            </td>
                            <input type="hidden" value="' . ($i + $episodesAlreadyInTable) . '" name="EpisodeNo' . ($i + $episodesAlreadyInTable) . '">
                            <input type="hidden" value="' . ($i + $lastSeasonOverAll) . '" name="OverAll' . ($i + $episodesAlreadyInTable) . '">
                        </tr>';
        }

        $template .= '<tr>
                        <td colspan="2" class="FormTableTd" id="SubmitForm">
                            <input type="submit" value="Insert" name="AddEpisodes">
                        </td>
                      </tr></table>
                      <input type="hidden" value="' . $showKey . '" name="showKey">
                      <input type="hidden" value="' . $seasonNum . '" name="seasonNumberToAddTo">
                      <input type="hidden" value="' . $noEpisodes . '" name="numberOfEpisodes">
                      </form></div>';

        return $template;
    }

    public function editTable()
    {
        $allShows = $this->DB->select("INFORMATION_SCHEMA.TABLES", array("TABLE_SCHEMA"=>"TVShowsSite"), "TABLE_NAME");

        $template = '<div>
                        <form action="./EditTables" method="post" id="EditTablesForm">
                            <table id="FormTable">
                                <tr>
                                    <td class="FormTableTd">
                                        <h4> Table Name </h4>
                                    </td>
                                    <td class="FormTableTd">
                                        <select name="tableToEdit" form="EditTablesForm" required>';

        for ($i = 0; $i < count($allShows); $i++)
        {
            if ($allShows[$i]["TABLE_NAME"] != "Users") {
                $template .= '<option value="' . $allShows[$i]["TABLE_NAME"] . '">' . $allShows[$i]["TABLE_NAME"] . '</option>';
            }
        }

        $template .= '                </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="FormTableTd" id="SubmitForm">
                                        <input type="submit" value="Edit Table" name="editTable">
                                    </td>
                                </tr>
                            </table>
                        </form>
                     </div>';

        return $template;
    }

    public function seasonsHandler($alert)
    {
        $error = $this->errorHandler($alert);

        if (isset($_POST['AddSeason'])) {
            $this->CreateSeries->AddRowToInfoTable(intval($_POST['seasonsToAdd']), $_POST['showKey']);
            header("Location: /tvShows");
        }

        if (isset($_POST['NumberOfSeasonsToAdd']))
        {
            return $this->AddSeasonsForm(intval($_POST['seasonsToAdd']), $_POST['titleToAddSeasonsTo']) . $error;
        }

        elseif (isset($_POST['createSeries']))
        {
            $seasonsToAdd = intval($_POST['NoSeasons']);
            $showKey = $_POST['ShowKey'];
            return $this->AddSeasonsForm($seasonsToAdd, $showKey) . $error;
        }

        else
        {
            return $this->NumberOfSeasonsToAdd($error);
        }
    }

    public function episodeHandler($alert)
    {
        $error = $this->errorHandler($alert);

        if (isset($_POST['AddEpisodes'])) {
            $this->CreateSeries->AddEpisodeToSeasonTable($_POST['showKey'], intval($_POST['seasonNumberToAddTo']), intval($_POST['numberOfEpisodes']));
            header("Location: /tvShows");
        }

        if (isset($_POST['NumberOfEpisodesToAdd'])) {
            $res = $this->DB->select('tvShows', array('ShowKey' => $_POST['showToAddEpisodesTo']));

            if ($res[0]["NoSeasons"] < intval($_POST['seasonToAddEpisodesTo'])) {
                return $this->NumberOfSeasonsToAdd("This season doesn't exist, Please add it first.");
            }
            else {
                return $this->AddEpisodesForm($_POST['showToAddEpisodesTo'], intval($_POST['seasonToAddEpisodesTo']), intval($_POST['noEpisodesToAdd'])) . $error;
            }
        }

        else
        {
            return $this->NumberOfEpisodesToAdd($error);
        }
    }

    public function editorHandler()
    {
        if (isset($_POST["sendEditedTable"]))
        {
            # TODO: Remember to make the change in the watched column. If it's watched send it as 1, else as 0.
            # TODO: type="date" data-date-inline-picker="true" -> Add this to every date input in the website
            print_r($_POST);
            return "Need to do the update to table";
        }

        if (isset($_POST["editTable"]))
        {
            return $this->EditTable->ShowTableToEdit($_POST["tableToEdit"]);
        }

        else {
            return $this->editTable();
        }
    }

    public function errorHandler($alert)
    {
        $error = "";

        if ($alert == EMPTY_ERROR)
        {
            return $this->CreateSeriesForm($alert);
        }
        elseif ($alert == SHOW_EXISTS)
        {
            return $this->CreateSeriesForm($alert);
        }
        elseif ($alert != "" and $alert != SUCCESSFUL_INSERT)
        {
            $error = '<div class="Error"><p>' . $alert . '</p></div>';
        }

        return $error;
    }

    private function getOverAll($seasonNum, $seasonTable, $episodesAlreadyInTable, $showKey)
    {
        $lastSeasonOverAll = 0;

        if ($seasonNum > 1 and $episodesAlreadyInTable == 0)
        {
            $lastSeasonTable = $showKey . "S" . str_pad(($seasonNum - 1), 2, "0", STR_PAD_LEFT);
            $lastSeasonOverAll = $this->DB->getOverAllNumber($lastSeasonTable);
        }
        elseif ($seasonNum > 1 and $episodesAlreadyInTable != 0)
        {
            $lastSeasonOverAll = $this->DB->getOverAllNumber($seasonTable);
        }
        elseif ($seasonNum == 1 and $episodesAlreadyInTable !=0)
        {
            $lastSeasonOverAll = $this->DB->getOverAllNumber($seasonTable);
        }
        elseif ($seasonNum == 1 and $episodesAlreadyInTable == 0)
        {
            $lastSeasonOverAll = 0;
        }

        return $lastSeasonOverAll;
    }
}