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
}
