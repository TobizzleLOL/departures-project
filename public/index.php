<?php
require "../vendor/autoload.php";

date_default_timezone_set('Europe/Berlin');
$currentTime = date('H:i');
$departureController = new \Tobizzlelol\Departures\Classes\departureController(  'mysql:dbname=db;host=ddev-departures-db:3306','db','db');
$departureController->view();




