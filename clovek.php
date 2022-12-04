<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
</head>

<?php
$id = filter_input(INPUT_GET,
    'humanID',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);


if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
}
else{
    require_once('inc/dbconnection.php');
        $stmt = $pdo->prepare("SELECT employee.name as 'jmeno', employee.surname as 'prijmeni', employee.job as 'pozice', employee.wage as 'mzda', room.room_id as 'mistnostID', room.name as 'mistnost' 
    FROM employee
        JOIN room ON employee.room = room.room_id WHERE employee_id=:humanID");
    $stmt->execute(['humanID' => $id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
    } else {
        $status = "OK";
        $roomsStm = $pdo->prepare("Select room.name as 'mistnostNazev', room.room_id as 'mistnostID' from `key` klic JOIN room ON klic.room = room.room_id WHERE klic.employee =:humanID");
        $roomsStm->execute(['humanID' => $id]);


        echo("<div class='container'>");
        while ($row = $stmt->fetch()) {
            $name =  $row->jmeno;
            $surname = $row->prijmeni;
            $wage = number_format($row->mzda, 2, '.', ',');
            $room = $row->mistnost;
            echo "<title>Karta osoby: $surname $name[0].</title>";
            echo " <h1>Karta osoby: $surname $name[0].</h1>";
            echo "<dl class='dl-horizontal'>";
            echo "<dt>Jméno:</dt> <dd>$name</dd>";
            echo "<dt>Přijmení</dt> <dd>$surname</dd>";
            echo "<dt>Pozice:</dt> <dd>$row->pozice</dd>";
            echo "<dt>Mzda:</dt> <dd>$wage</dd>";
            echo "<dt>Místnost:</dt> <dd><a href='mistnost.php?roomID=$row->mistnostID'>$room</a></dd>";

            echo "<dt>Klíče: </dt> ";
            while ($roomRow = $roomsStm-> fetch())
                echo "<dd><a href='mistnost.php?roomID=$roomRow->mistnostID'>$roomRow->mistnostNazev </a></dd>";

            echo "</dl> <div class='end'><a href='lide.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam zaměstnanců </a></div></div> </div>";

        }
    }
}

?>

<body>
<?php
switch ($status) {
    case "bad_request":
        echo "<title>Error 400: Bad request</title>";
        echo "<h1>Error 400: Bad request</h1>";
        break;
    case "not_found":
        echo "<title>Error 404: Not found</title>";
        echo "<h1>Error 404: Not found</h1>";
        break;
}
?>
</body>
</html>