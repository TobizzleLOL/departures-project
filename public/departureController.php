<?php
$dsn = 'mysql:dbname=db;host=ddev-departures-db:3306';
$username = 'db';
$password = 'db';


$conn = new PDO($dsn, $username, $password);
date_default_timezone_set('Europe/Berlin');
class departureController
{

    public function update()
    {
            $domDoc = $this->getDeparturesFromWeb('https://www.kvb.koeln/generated/?aktion=show&code=7');
            $this->saveToFile($domDoc);
            $departureArray = $this->getDeparturesFromXmlFile();
            $this->saveDeparturesToDb($departureArray);
    }
    public function view()
    {
        global $currentTime;

        echo "<table>
    <tr>
        <th>Line</th>
        <th>Time</th>
        <th>Direction</th>
    </tr>";


        foreach ($this->findDepartures($currentTime) as $departure)
        {
            echo '<tr>';
            echo '<td>'. $departure[3] .'</td>';         //line
            echo '<td>'. substr((string)$departure[2], 0, -3) .'</td>'; //time
            echo '<td>'. $departure[4] .'</td>'; //direction
            echo '</tr>';
        }

    }
    public function check()
    {
        global $currentTime;
        if(count($this->findDepartures($currentTime))<10)
        {
            $this->update();
        }
    }
    public function findDepartures($time)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM departure WHERE time>=:time');
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getDeparturesFromWeb($url)
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

        return $domDoc;
    }

    private function getDeparturesFromXmlFile()
    {
        $rawXmlSting = file_get_contents('Departures.html');
        $xml = simplexml_load_string($rawXmlSting);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array['body']['div']['table'][1]['tr'];
    }
    private function saveToFile($object)
    {
        $object->save('Departures.html');
    }

    private function saveDeparturesToDb($data)
    {
        global $conn;
        $this->truncateDb('departure');
        $data[0] = ['td'=>['||','||','||']];

        $station = 'Appellhofplatz';
        $stmt = $conn->prepare("INSERT INTO departure(station, time, line, direction) VALUES(:station, :time, :line, :direction)");
        foreach($data as $departure) {

            $minutes = (int)substr($departure['td'][2], 0, -7);
            $time  = date('H:i', strtotime('+'.$minutes.' minutes'));
            $line = (int)substr($departure['td'][0], 2, -4);
            $direction = $departure['td'][1];
            $stmt->bindParam(':station', $station);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':line', $line);
            $stmt->bindParam(':direction', $direction);
            $stmt->execute();
        }
    }
    private function truncateDb($db)
    {
        global $conn;
        $stmt = $conn->prepare("TRUNCATE ". $db);
        $stmt->execute();
    }
}
