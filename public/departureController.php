<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'db';
$password = 'db';

$conn = new PDO($dsn, $username, $password);
class departureController
{
    function findNextDeparturesAfter($time)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>:time');
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
