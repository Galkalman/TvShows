<?php

class Header
{
    public function publicHeader()
    {
        # TODO: Remove TESTING, URI_ADD_SEASON, URI_INSERT_EPISODES after finishing tests
        return '<a href="' . URI_HOMEPAGE . '" class="HeaderText">
                    <div id="Logo"></div>
                </a>
                <a href="' . URI_ALL_TVSHOWS . '" class="HeaderText">
                    <h2 class="HeaderText" id="TvShowsHeader"> TV Shows </h2>
                </a>
                <a href="' . TESTING . '" class="HeaderText">
                    <h2 class="HeaderText" id="TestHeader"> Testing Zone </h2>
                </a>
                <a href="' . URI_ADD_SEASON . '" class="HeaderText">
                    <h2 class="HeaderText" id="AddSeasons"> Add Seasons </h2>
                </a>
                <a href="' . URI_INSERT_EPISODES . '" class="HeaderText">
                    <h2 class="HeaderText" id="AddEpisodes"> Insert Episodes </h2>
                </a>
                <a href="' . URI_EDIT_TABLES . '" class="HeaderText">
                    <h2 class="HeaderText" id="EditTables"> Edit Tables </h2>
                </a>';
    }

    public function Footer($alert)
    {
        return "<p class='FooterText'> © All rights reserved to 'Gal Kalman'</p>";
    }
}