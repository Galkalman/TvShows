<?php
#TODO: Remove some from URI_LIST, and some other unnecessary constants
# -------------------- URI --------------------

define("URI_LIST", "/AlltvShows/TheFlash/Arrow/test/Grimm/AddSeason/InsertEpisodes");
define("REDIRECT_HOME", "Location: /TvShows/");

# -------------------- DB --------------------

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "TVShowsSite");

# -------------------- PAGES --------------------

define("URI_HOMEPAGE", "/TvShows/");
define("URI_ALL_TVSHOWS", "AlltvShows");
define("URI_ADD_SEASON", "AddSeason");
define("URI_INSERT_EPISODES", "InsertEpisodes");

# -------------------- TvShow Status --------------------

define("FINISHED", "Finished 😭");
define("WINTER", "Winter Break");
define("RUNNING", "Running");

# -------------------- Shows --------------------

define("ARROW", "Arrow");
define("GRIMM", "Grimm");
define("THE_FLASH", "TheFlash");

# -------------------- Basics --------------------

define("WATCHED", 1);
define("TESTING", "test");

# -------------------- Messages --------------------

define("EMPTY_ERROR", "You left something empty...");
define("SUCCESSFUL_INSERT", "The data has been saved to the DB");
define("SHOW_EXISTS", "This show already exists.");