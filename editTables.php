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

    public function ShowTableToEdit($tblName)
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
                        </form>
                     </div>
                     <script type="text/javascript">
                        function changeWatched(name)
                        {
                            if (document.getElementById(name).value == "Watched") {
                                document.getElementById(name).value = "Unwatched"; // Changes the value of the button "name"
                            }
                            else {
                                document.getElementById(name).value = "Watched";
                            }
                        }
                     </script>';

        return $template;
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
            } elseif ($colValue == WATCHED_COLUMN) {
                $watchedInputName = "row" . $row . "col" . $colValue;
                $watchedValue = $res[$row][$colValue];
                if ($watchedValue == WATCHED) {
                    return '<td class="FormTableTd"><input type="button" value="Watched" id="' . $watchedInputName . '" name="' . $watchedInputName . '" onclick="changeWatched(\'' . $watchedInputName . '\');"></td>';
                } else {
                    return '<td class="FormTableTd"><input type="button" value="Unwatched" id="' . $watchedInputName . '" name="' . $watchedInputName . '" onclick="changeWatched(\'' . $watchedInputName . '\');"></td>';
                }
            } else {
                return '<td class="FormTableTd"><input id="editTableInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '"></td>';
            }
        }

        return "<td class='FormTableTd'> Should never happen :) </td>";
    }

    private function returnOptions($value)
    {
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
}