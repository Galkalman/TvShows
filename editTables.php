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

        $tableCols = $this->DB->select("INFORMATION_SCHEMA.COLUMNS", array("TABLE_SCHEMA"=>"TVShowsSite", "TABLE_NAME"=>$tblName), "COLUMN_NAME");

        $tableType = $this->findTableType($tblName);

        $template = '<div>
                        <form action="./EditTables" method="post" id="EditingTable">
                            <table id="FormTable">
                                <tr>';

        for ($i = 0; $i < count($tableCols); $i++)
        {
            $template .= '<td class="FormTableTd"><h4 class="CenterAlignTd">' . $tableCols[$i]["COLUMN_NAME"] . '</h4></td>';
        }

        $template .= '</tr>';

        for ($row = 0; $row < count($res); $row++)
        {
            $template .= '<tr>';

            for ($colValue = 0; $colValue < (count($res[$row])/2); $colValue++) {
                $template .= $this->getColumnValue();
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
                     </div>';

        return $template;
    }

    private function findTableType($tblName)
    {
        if (preg_match('/S[0-9]{2}/', $tblName)) {
            return SEASON_TABLE;
        }
        elseif ($tblName == "tvShows") {
            return TVS_TABLE;
        }
        else {
            return SERIES_TABLE;
        }
    }

'<td class="FormTableTd"><input id="editTableInput" type="text" value="' . $res[$row][$colValue] . '" name="row' . $row . 'col' . $colValue . '"></td>';
}