<!DOCTYPE html>
<?php
include("departureController.php");
    $departureController = new departureController();
?>

<html>
<head>
    <title>Departures</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Abfahrten</h1>
    <?php
    //var_dump($departureController->findNextDeparturesByTime("11:03:00"));
    foreach ($departureController->findNextDeparturesByTime("11:03:00") as $departure)
    {
        echo $departure[1];
        echo " um ";
        echo $departure[2];
        echo "<br>";

    }

    ?>
</body>
</html>





