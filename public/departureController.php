<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'root';
$password = 'root';

$conn = new PDO($dsn, $username, $password);
class departureController
{
    function findNextDeparturesByTime($time)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>:time');
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
