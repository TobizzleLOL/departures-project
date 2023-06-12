<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'root';
$password = 'root';

$conn = new PDO($dsn, $username, $password);

class departureController
{
    public function findNextDeparturesByTime($time)
    {
        global $conn;
        $timePlusOne = date(' H:i:s', strtotime('now +1 hour'));
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>:time AND time<:timePlusOne');
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':timePlusOne', $timePlusOne);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
