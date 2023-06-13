<!DOCTYPE html>
<?php
include("departureController.php");

date_default_timezone_set('Europe/Berlin');
$currentTime = date('H:i');

$departureController = new departureController();

?>

<html>
    <head>
        <title>Departures</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <meta http-equiv="refresh" content="5" >
    </head>
    <body>
    <h1>Abfahrten Apellhofplatz <?php echo $currentTime?></h1>
        <div class="table pull-left">
            <?php
                $departureController->view();
                $departureController->check();
            ?>
        </div>
    </body>
</html>





