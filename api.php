<?php

include 'includes/db.php';

// send available destinations
if (isset($_GET['destinations'])) {
    $destinations = [];
    $sth = $pdo->prepare("SELECT * FROM destinations");
    $sth->execute();
    foreach ($sth as $row) {
        array_push($destinations, [$row['id'], $row['name']]);
    }
    echo json_encode($destinations);
}

// handle independent destination data request
// sample output:
/*
{
  "airlines" : [
    {
      "label" : "Lufthanza",
      "tickets" : [215, 35, 120, 300]
    },
    {
      "label" : "Pegasus",
      "tickets" : [40, 150, 80, 220]
    },
    {
      "label" : "EVA Air",
      "tickets" : [150, 35, 120, 300]
    },
    {
      "label" : "Emirates",
      "tickets" : [215, 35, 120, 300]
    }
  ],
  "attractions" : [
    "museums" : 20,
    "forests / Parks" : 55,
    "Beaches" : 10,
    "Gardens / Zoos" : 15
  ],
  "accomodation" : [
    [13, 10, 20, 24, 18, 15, 10, 18, 15, 28, 19, 12],
    [15, 10, 12, 18, 23, 13, 16, 12, 8, 15, 22, 18]
  ]
}
*/

// send specific destination data
if (isset($_GET['destination'])) {
    $response = []; // initialize response object
    $colors = ["#011627", "#2ec5b6", "#e71d35", "#ff9f1c"]; // colors to use for airlines
    $doughnutColors = ["#f36234", "#2d294f", "#199b8c", "#e81b38", "#e7d44e"]; // colors to use for attractions

    $sth = $pdo->prepare("SELECT * FROM destinations WHERE id = :id");
    $id = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_NUMBER_INT);
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row) { // if valid destination requested
        $response['airlines'] = [];
        $sth = $pdo->prepare("SELECT * FROM airlines");
        $sth->execute();
        $counter = 0;
        foreach ($sth as $row) {
            $tickets = [];
            $sth2 = $pdo->prepare("SELECT * FROM tickets WHERE destination_id = :did AND airline_id = :aid ORDER BY `date_order` ASC");
            $did = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_NUMBER_INT);
            $aid = $row['id'];
            $sth2->bindParam(':did', $did, PDO::PARAM_INT);
            $sth2->bindParam(':aid', $aid, PDO::PARAM_INT);
            $sth2->execute();
            foreach ($sth2 as $row2) {
                array_push($tickets, (int) $row2['price']);
            }
            array_push($response['airlines'], ['label' => $row['name'], 'data' => $tickets, 'backgroundColor' => $colors[$counter]]);
            $counter++;
        }
        // populate attractions
        $response['attractions'] = [];
        $data = [];
        $backgroundColor = [];
        $labels = [];
        $sth3 = $pdo->prepare("SELECT * FROM attractions WHERE destination_id = :did");
        $did = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_NUMBER_INT);
        $sth3->bindParam(':did', $did, PDO::PARAM_INT);
        $sth3->execute();
        $counter3 = 0;
        foreach ($sth3 as $row3) {
          $data[] = (int) $row3['percentage'];
          $backgroundColor[] = $doughnutColors[$counter3];
          $labels[] = $row3['name'];
          $response['attractions'] = ["datasets" => [["data" => $data, "backgroundColor" => $backgroundColor]], "labels" => $labels];
          $counter3++;
        }
        // populate accommodation
        $response['accommodation'] = [];
        $sth4 = $pdo->prepare("SELECT * FROM accommodation WHERE destination_id = :did");
        $did = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_NUMBER_INT);
        $sth4->bindParam(':did', $did, PDO::PARAM_INT);
        $sth4->execute();
        $rows = $sth4->fetchAll(PDO::FETCH_ASSOC);
        $current = [];
        for ($i=0; $i < 12; $i++) { 
            array_push($current, (int) $rows[$i]['average_price']);
        }
        array_push($response['accommodation'], $current);
        $previous = [];
        for ($i=12; $i < 24; $i++) { 
            array_push($previous, (int) $rows[$i]['average_price']);
        }
        array_push($response['accommodation'], $previous);
    }
    echo(json_encode($response));
}