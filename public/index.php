<!DOCTYPE html>
<?php
include("departureController.php");

date_default_timezone_set('Europe/Berlin');
$currentTime = date('H:i');

$departureController = new departureController();
//$departureController->update();

?>

<html>
<head>
    <title>Departures</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Abfahrten Apellhofplatz <?php echo $currentTime?></h1>
<ul>
<?php
foreach ($departureController->findDepartures($currentTime) as $departure)
{
    echo '<li>';
    echo "linie ". $departure[3];         //line
    echo " at ". substr((String)$departure[2], 0, -3); //time
    echo '</li>';
}
?>
</ul>
</body>
</html>





