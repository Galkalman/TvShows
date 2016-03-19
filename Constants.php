<?php
#TODO: Remove some from URI_LIST, and some other unnecessary constants
# -------------------- URI --------------------

define("URI_LIST", "/AlltvShows/TheFlash/Arrow/test/Grimm/AddSeason/InsertEpisodes/EditTables");
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
define("URI_EDIT_TABLES", "EditTables");


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

# -------------------- Tables --------------------

define("TVS_TABLE", 1);
define("SERIES_TABLE", 2);
define("SEASON_TABLE", 3);
define("TVS_COLUMNS", array(0, 1, 2));
define("SERIES_COLUMNS", array(0));
define("SEASON_COLUMNS", array(0, 1));
define("STATUS_COLUMN", 3);
define("WATCHED_COLUMN", 4);

# -------------------- Messages --------------------

define("EMPTY_ERROR", "You left something empty...");
define("SUCCESSFUL_INSERT", "The data has been saved to the DB");
define("SHOW_EXISTS", "This show already exists.");