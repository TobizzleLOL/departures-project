<?php
    $dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
    $username = 'root';
    $password = 'root';

    $conn = new PDO($dsn, $username, $password);
class departure
{
    public $departureID;
    public $station;
    public $time;
    public $line;
}