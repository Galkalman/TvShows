<?php

require_once('Constants.php');
require_once('DB.php');

class Tables
{
    public function TvShow($showName)
    {
        $DB = new DB();
        $ShowSeasons = $DB->select($showName);
        $fullShowName = $DB->select("tvShows", array('ShowKey' => $showName));

        $template = $this->InformationOnShow($fullShowName, $ShowSeasons);

        for($i = 1; $i <= count($ShowSeasons); $i++) {
            $template .= $this->SeasonEpisodesTable($showName, $i, $fullShowName, $DB);
        }

        $template .= "<script>
                         $(function () {
                             $('input').on('click', function (b) {
                                 var button = $(this);
                                 var Status = $(this).val();
                                 var Table = $(b.currentTarget).attr('name');
                                 var Episode = $(b.currentTarget).attr('id');
                                 var Class = 'WatchedRow';
                                 if (Status == 'Watched') {
                                    Status = 'Unwatched';
                                    Class = 'UnwatchedRow';
                                 }
                                 else {
                                    Status = 'Watched';
                                 }
                                 $.ajax({url: './ajax.php', data: {changeWatched: Status, Table: Table, Episode: Episode}, type: 'post', async: false, success: function(){
                                     button.val(Status);
                                     button.removeClass().addClass(Class);
                                 }});
                             });
                         });
                     </script>";

        return $template;
    }

    public function InformationOnShow($fullShowName, $ShowSeasons)
    {
        $template = '<h1>' . $fullShowName[0]['Title'] . '</h1>
                    <table id="TVShowsTable">
                    <caption>
                        <h2 id="HeadLine">
                            Overview
                        </h2>
                    </caption>
                    <tr>
                        <th class="CenterAlignTd">Season</th>
                        <th class="CenterAlignTd"># of Episodes</th>
                        <th class="CenterAlignTd">First Aired</th>
                        <th class="CenterAlignTd">Last Aired</th>
                    </tr>';

        for($i = 0; $i < count($ShowSeasons); $i++)
        {
            $template .= '<tr>
                              <td class="CenterAlignTd">' . $ShowSeasons[$i]['SeasonNo'] . '</td>
                              <td class="CenterAlignTd">' . $ShowSeasons[$i]['NoEpisodes'] . '</td>
                              <td class="CenterAlignTd">' . $ShowSeasons[$i]['DateAired'] . '</td>
                              <td class="CenterAlignTd">' . $ShowSeasons[$i]['DateFinished'] . '</td>
                          </tr>';
        }

        $template = $template . "</table>";

        return $template;
    }

    public function SeasonEpisodesTable($showName, $seasonNum, $fullShowName, $DB)
    {
        $template = "";

        $thisSeason = $showName . "S" . str_pad($seasonNum, 2, "0", STR_PAD_LEFT);
        $seasonEpisodes = $DB->select($thisSeason);

        $template = '<table id="TVShowsTable">
                        <caption>
                            <h2 id="HeadLine">
                                ' . $fullShowName[0]['Title'] . " - Season " . $seasonNum . '
                            </h2>
                        </caption>
                        <tr>
                            <th class="CenterAlignTd">Overall Episode</th>
                            <th class="CenterAlignTd">Episode</th>
                            <th class="LeftAlignTd">Title</th>
                            <th class="CenterAlignTd">First Aired</th>
                            <th class="CenterAlignTd">Watched</th>
                        </tr>';

        for($k = 0; $k < count($seasonEpisodes); $k++)
        {
            $watched_str = ($seasonEpisodes[$k]['Watched'] != WATCHED) ? "Unwatched" : "Watched";

            $template .= '<tr>
                             <td class="CenterAlignTd">'   . $seasonEpisodes[$k]['OverAll']   . '</td>
                             <td class="CenterAlignTd">'   . $seasonEpisodes[$k]['EpisodeNo'] . '</td>
                             <td class="LeftAlignTd">'     . $seasonEpisodes[$k]['Title']     . '</td>
                             <td class="CenterAlignTd">'   . $seasonEpisodes[$k]['Date']      . '</td>';

            if ($watched_str == "Watched") {
                $template .= '<form action="" method="post">
                                 <td class="CenterAlignTd">
                                 <input type="button" value=' . $watched_str . ' id="' . $seasonEpisodes[$k]['OverAll'] . '" name="' . $thisSeason . '" class="WatchedRow">
                                 </td>
                             </form>';
            }
            else {
                $template .= '<form action="" method="post">
                                 <td class="CenterAlignTd">
                                 <input type="button" value=' . $watched_str . ' id="' . $seasonEpisodes[$k]['OverAll'] . '" name="' . $thisSeason . '" class="UnwatchedRow">
                                 </td>
                             </form>';
            }

            $template .= '</tr>';
        }

        $template .= "</table>";

        return $template;
    }

    public function AllTvShowsTable()
    {
        $DB = new DB();
        $allShows = $DB->select("tvShows");
        $firstLetters = $DB->firstLetter();

        $template = '<table id="TVShowsTable">
                    <caption>
                        <h1 id="HeadLine">
                            TV Shows
                        </h1>
                    </caption>
                    <tr>
                        <th class="CenterAlignTd">#A-Z</th>
                        <th class="LeftAlignTd">Title</th>
                        <th class="CenterAlignTd"># of Seasons</th>
                        <th class="LeftAlignTd">Status</th>
                    </tr>';

        # Will contain the number of the first letter of a show in the table tvShows
        $letters = array();

        # Will set letters to the right amounts
        foreach($firstLetters as $key => $value)
        {
            if (isset($letters[strtoupper($value['0'])]))
            {
                $letters[strtoupper($value['0'])] += 1;
            }
            else{
                $letters[strtoupper($value['0'])] = 1;
            }
        }

        $rowspan = '';
        $firstLetter = '';

        # Will go through all of the shows and add rowspan in the right places
        # it will keep the last first letter in $firstLetter to make sure there are no rowspan that are not needed
        for($i = 0; $i < count($allShows); $i++)
        {
            if (strtoupper(substr($allShows[$i]['Title'], 0, 1)) != strtoupper($firstLetter))
            {
                $firstLetter = strtoupper(substr($allShows[$i]['Title'], 0, 1));
                if (isset($letters[$firstLetter])) {
                    $rowspan = '<td class="CenterAlignTd" rowspan=' . $letters[$firstLetter] . '>' . $firstLetter . '</td>';
                }
            }
            else
            {
                $rowspan = '';
            }
            $status = RUNNING;
            if ($allShows[$i]['Status'] == '2')
            {
                $status = WINTER;
            }
            elseif ($allShows[$i]['Status'] == '3')
            {
                $status = FINISHED;
            }
            $template = $template . "<tr>";
            $template = $template . $rowspan .
            '<td class="LeftAlignTd" ><a href="' . $allShows[$i]['ShowKey'] . '">'      . $allShows[$i]['Title']       . '</td>
             <td class="CenterAlignTd">'         . $allShows[$i]['NoSeasons']           . '</td>
             <td class="LeftAlignTd">'           . $status                              . '</td>';
            $template = $template . "</tr>";
        }

        $template = $template . "</table>";
        return $template;
    }
}