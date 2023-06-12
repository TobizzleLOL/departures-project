<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'db';
$password = 'db';

$conn = new PDO($dsn, $username, $password);
class departureController
{
    function findNextDepartures($time)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>:time LIMIT 10');
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function saveDeparturesFromJson(){
        global $conn;
        $data = json_decode(file_get_contents("Departures.json"));
        $stmt = $conn->prepare("INSERT INTO departure(station, time, line) VALUES(:station, :time, :line)");
        foreach($data as $departure) {
            $stmt->bindParam(':station', $departure->station);
            $stmt->bindParam(':time', $departure->time);
            $stmt->bindParam(':line', $departure->line);
            $stmt->execute();
        }
    }
}
