<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'db';
$password = 'db';


$conn = new PDO($dsn, $username, $password);
date_default_timezone_set('Europe/Berlin');
class departureController
{
    function findDepartures($time)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>:time');
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getDeparturesFromWeb($url)
    {
        $context = [
            'http' => [
                'header' => 'User-Agent: Chrome/113.0.0.0 Safari/537.36 Edg/113.0.1774.50'
            ]
        ];

        $context = stream_context_create($context);
        $rawXmlSting = file_get_contents($url, false, $context);
        $domDoc = new DOMDocument();
        $domDoc->loadHTML($rawXmlSting, LIBXML_NOERROR);
        $domDoc->saveHTML();

        $this->saveToFile($domDoc);

        return $domDoc;
    }

    function getDeparturesFromXmlFile()
    {
        $rawXmlSting = file_get_contents('Departures.html');
        $xml = simplexml_load_string($rawXmlSting);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array['body']['div']['table'][1]['tr'];
    }
    function saveToFile($object){
        $object->save('Departures.html');
    }

    function saveDeparturesToDb($data){
        global $conn;
        $this->truncateDb('departure');
        $data[0] = ['td'=>['||','||','||']];

        $station = 'Appellhofplatz';
        $stmt = $conn->prepare("INSERT INTO departure(station, time, line) VALUES(:station, :time, :line)");
        foreach($data as $departure) {

            $minutes = (int)substr($departure['td'][2], 0, -7);
            $time  = date('H:i', strtotime('+'.$minutes.' minutes'));
            $line = (int)substr($departure['td'][0], 2, -4);
            $stmt->bindParam(':station', $station);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':line', $line);
            $stmt->execute();
        }
    }
    function truncateDb($db){
        global $conn;
        $stmt = $conn->prepare("TRUNCATE ". $db);
        $stmt->execute();
    }
}
