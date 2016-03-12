<?php

require_once('Tables.php');
require_once('Forms.php');
require_once('Header.php');
require_once('route.php');
require_once('DB.php');
require_once('CreateSeries.php');

# Routing:
$route = new Route();
$routing = $route->submit();

# Connect to DB:
$DB = new DB();

# Header:
$headerInst = new Header();
$Header = $headerInst->publicHeader();

# Tables:
$table = new Tables();

# Forms:
$form = new Forms($DB);

# Create A New TV Show:
$createSeries = new CreateTvShow($DB);
if (isset($_POST['createSeries']))
{
    $createSeries->AddTvShowToMasterTable();
}

# Pages:
switch($routing){
    case URI_ALL_TVSHOWS:
        $output = $table->AllTvShowsTable();
        break;
    case THE_FLASH:
        $output = $table->TvShow(THE_FLASH);
        break;
    case ARROW:
        $output = $table->TvShow(ARROW);
        break;
    case GRIMM:
        $output = $table->TvShow(GRIMM);
        break;
    case TESTING:
        # TODO: Remove this after finishing tests
        $output = $form->CreateSeriesForm($createSeries->alert);
        break;
    case URI_ADD_SEASON:
        $output = $form->seasonsHandler($createSeries->alert);
        break;
    case URI_INSERT_EPISODES:
        $output = $form->episodeHandler($createSeries->alert);
        break;
    default:
        $output = "Create homepage";
}

# Footer:
$footer = $headerInst->Footer($createSeries->alert);