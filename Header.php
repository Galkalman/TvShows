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
                <a href="' . URI_EDITOR . '" class="HeaderText">
                    <h2 class="HeaderText" id="EditPage"> Editor </h2>
                </a>';
    }

    public function Footer($alert)
    {
        return "<p class='FooterText'> © All rights reserved to 'Gal Kalman'</p>";
    }
}