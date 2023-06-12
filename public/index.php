<!DOCTYPE html>
<?php
include("departureController.php");
$departureController = new departureController();
$currentTime = date('H:i');
?>

<html>
<head>
    <title>Departures</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Abfahrten ab <?php echo $currentTime?></h1>
<ul>
<?php
foreach ($departureController->findNextDeparturesAfter($currentTime) as $departure)
{
    echo '<li>';
    echo $departure[1];
    echo " um ";
    echo $departure[2];
    echo "</li>";
}
?>
</ul>
</body>
</html>





