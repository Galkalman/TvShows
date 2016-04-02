<?php
require_once('DB.php');
require_once('Constants.php');

class EditTable
{
    public $DB;
    public $alert = "";

    public function __construct($db)
    {
        $this->DB = $db;
    }

    public function ShowTableToEdit($tblName, $err)
    {
        $res = $this->DB->select($tblName);

        $tableCols = $this->DB->select("INFORMATION_SCHEMA.COLUMNS", array("TABLE_SCHEMA" => "TVShowsSite", "TABLE_NAME" => $tblName), "COLUMN_NAME");

        $tableType = $this->findTableType($tblName);

        $template = '<div>
                        <form action="./EditTables" method="post" id="EditingTable">
                            <table id="FormTable">
                                <tr>';

        for ($i = 0; $i < count($tableCols); $i++) {
            $template .= '<td class="FormTableTd"><h4 class="CenterAlignTd">' . $tableCols[$i]["COLUMN_NAME"] . '</h4></td>';
        }

        $template .= '</tr>';

        for ($row = 0; $row < count($res); $row++) {
            $template .= '<tr>';

            for ($colValue = 0; $colValue < (count($res[$row]) / 2); $colValue++) {
                $template .= $this->getColumnValue($tableType, $res, $row, $colValue);
            }

            $template .= '</tr>';
        }

        $template .= '          <tr>
                                    <td colspan="' . count($tableCols) . '" class="FormTableTd" id="SubmitForm">
                                        <input type="submit" value="Submit Changes" name="sendEditedTable">
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="tblName" value="' . $tblName . '">
                        </form>
                     </div>
                     <script type="text/javascript">
                        function changeWatched(name)
                        {
                            if (document.getElementById(name.concat("-")).value == "Watched") {
                                document.getElementById(name).value = "Unwatched"; // Changes the value of the button "name"
                                document.getElementById(name.concat("-")).value = "Unwatched";
                            }
                            else {
                                document.getElementById(name).value = "Watched";
                                document.getElementById(name.concat("-")).value = "Watched";
                            }
                        }
                     </script>';

        return $template . "<div class=\"Error\"><p>" . $err . "</p></div>";
    }

    private function findTableType($tblName)
    {
        if (preg_match('/S[0-9]{2}/', $tblName)) {
            return SEASON_TABLE;
        } elseif ($tblName == "tvShows") {
            return TVS_TABLE;
        } else {
            return SERIES_TABLE;
        }
    }

    public function getColumnValue($tableType, $res, $row, $colValue)
    {
        if ($tableType == TVS_TABLE) {
            if (in_array($colValue, TVS_COLUMNS, true)) {
                return '<td class="FormTableTd"><input id="editTableReadonlyInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '" readonly></td>';
            } else {
                if ($colValue == STATUS_COLUMN) {
                    return '<td class="FormTableTd"><select name="row' . $row . 'col' . $colValue . '" form="EditingTable" required>' . $this->returnOptions($res[$row][$colValue]) . '</select>';
                }
                else {
                    return '<td class="FormTableTd"><input id="editTableInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '"></td>';
                }
            }
        } else if ($tableType == SERIES_TABLE) {
            if (in_array($colValue, SERIES_COLUMNS, true)) {
                return '<td class="FormTableTd"><input id="editTableReadonlyInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '" readonly></td>';
            } else {
                return '<td class="FormTableTd"><input id="editTableInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '"></td>';
            }
        } else if ($tableType == SEASON_TABLE) {
            if (in_array($colValue, SEASON_COLUMNS, true)) {
                return '<td class="FormTableTd"><input id="editTableReadonlyInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '" readonly></td>';
            }
            elseif ($colValue == WATCHED_COLUMN) {
                $watchedInputName = "row" . $row . "col" . $colValue;
                $watchedValue = $res[$row][$colValue];
                if ($watchedValue == WATCHED) {
                    return '<td class="FormTableTd"><input type="button" value="Watched" id="' . $watchedInputName . '-" name="' . $watchedInputName . '-" onclick="changeWatched(\'' . $watchedInputName . '\');"></td>
                            <input type="hidden" id="' . $watchedInputName . '" name="' . $watchedInputName . '" value="Watched">';
                } else {
                    return '<td class="FormTableTd"><input type="button" value="Unwatched" id="' . $watchedInputName . '-" name="' . $watchedInputName . '-" onclick="changeWatched(\'' . $watchedInputName . '\');"></td>
                            <input type="hidden" id="' . $watchedInputName . '" name="' . $watchedInputName . '" value="Unwatched">';
                }
            } else {
                return '<td class="FormTableTd"><input id="editTableInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '"></td>';
            }
        }

        return "<td class='FormTableTd'> Should never happen :) </td>";
    }

    private function returnOptions($value)
    {
        # TODO: Improve this with split and regex
        if ($value == '2') {
            return '<option value="1">' . RUNNING . '</option>
                    <option value="2" selected="selected">' . WINTER . '</option>
                    <option value="3">' . FINISHED . '</option>';
        } elseif ($value == '3') {
            return '<option value="1">' . RUNNING . '</option>
                    <option value="2">' . WINTER . '</option>
                    <option value="3" selected="selected">' . FINISHED . '</option>';
        } else {
            return '<option value="1" selected="selected">' . RUNNING . '</option>
                    <option value="2">' . WINTER . '</option>
                    <option value="3">' . FINISHED . '</option>';
        }
    }

    public function insertEditToDB($editInfo)
    {
        $tblName = $editInfo["tblName"];
        $tblType = $this->findTableType($tblName);

        if ($tblType == TVS_TABLE) { return $this->editTvsTable($editInfo, $tblName); }
        elseif ($tblType == SERIES_TABLE) { return $this->editSeriesTable($editInfo, $tblName); }
        elseif ($tblType == SEASON_TABLE) { return $this->editSeasonTable($editInfo, $tblName); }
        else { return "Should Never Happen :)"; }
    }

    public function editTvsTable($editInfo, $tblName)
    {
        $postKeys = array_keys($editInfo);

        for ($i = 0; $i < count($postKeys); $i++) {
            $postCell = $postKeys[$i];
            if ($postCell != "sendEditedTable" and $postCell != "tblName") {
                $row = explode("row", explode("col", $postCell)[0])[1];
                $col = explode("col", $postCell)[1];

                if ($col == "0") {
                    $this->DB->update($tblName, "Status", $editInfo["row" . $row . "col3"], array("ShowKey" => $editInfo[$postCell]));
                }

            }
        }

        return 'Success.';
    }

    public function editSeriesTable($editInfo, $tblName)
    {
        $postKeys = array_keys($editInfo);

        for ($i = 0; $i < count($postKeys); $i++)
        {
            $postCell = $postKeys[$i];
            if ($postCell != "sendEditedTable" and $postCell != "tblName") {
                $row = explode("row", explode("col", $postCell)[0])[1];
                $col = explode("col", $postCell)[1];

                if ($col == "0")
                {
                    if (count($this->DB->select($tblName . "S" . str_pad($editInfo[$postCell], 2, "0", STR_PAD_LEFT))) < intval($editInfo["row" . $row . "col1"])) {
                        $this->DB->update($tblName, "NoEpisodes", $editInfo["row" . $row . "col1"], array("SeasonNo" => $editInfo[$postCell]));
                        $this->DB->update($tblName, "DateAired", $editInfo["row" . $row . "col2"], array("SeasonNo" => $editInfo[$postCell]));
                        $this->DB->update($tblName, "DateFinished", $editInfo["row" . $row . "col3"], array("SeasonNo" => $editInfo[$postCell]));
                    }
                    else {
                        $_POST["editTable"] = true;
                        $_POST["tableToEdit"] = $tblName;
                        return "There are more episodes in the table then in the change you've made.";
                    }
                }

            }
        }

        return 'Success.';
    }

    public function editSeasonTable($editInfo, $tblName)
    {
        $postKeys = array_keys($editInfo);

        for ($i = 0; $i < count($postKeys); $i++) {
            $postCell = $postKeys[$i];
            if ($postCell != "sendEditedTable" and $postCell != "tblName") {
                $row = explode("row", explode("col", $postCell)[0])[1];
                $col = explode("col", $postCell)[1];

                if ($col == "0") {
                    $this->DB->update($tblName, "Title", $editInfo["row" . $row . "col2"], array("OverAll" => $editInfo[$postCell]));
                    $this->DB->update($tblName, "Date", $editInfo["row" . $row . "col3"], array("OverAll" => $editInfo[$postCell]));
                    if ($editInfo["row" . $row . "col4"] == "Watched") { $watched = 1; }
                    else { $watched = 0; }
                    $this->DB->update($tblName, "Watched", $watched, array("OverAll" => $editInfo[$postCell]));
                }

            }
        }

        return 'Success.';
    }
}