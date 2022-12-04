<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<?php
$id = filter_input(INPUT_GET,
    'roomID',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);

if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
}
else{
    require_once('inc/dbconnection.php');
    $stmt = $pdo->prepare("SELECT no, name, phone, room_id FROM room WHERE room_id=:roomID");
    $stmt->execute(['roomID' => $id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
    } else {
        $status = "OK";

        $peopleSTMT = $pdo->prepare("SELECT employee_id, name, surname, room FROM employee WHERE room = :roomID");
        $peopleSTMT->execute(['roomID' => $id]);

        $roomsStm = $pdo->prepare("Select employee.name as 'name', employee.surname as 'surname', employee.employee_id as 'employeeID' from `key` klice JOIN employee ON klice.employee = employee.employee_id WHERE klice.room =:humanID ORDER BY employee.surname");
        $roomsStm->execute(['humanID' => $id]);

        while ($row = $stmt->fetch()) {
            $phoneNumber = $row->phone === null? "—" : $row->phone;

            echo "<title>Karta místnosti č. $row->no</title>";
            echo "<div class='container'>";
            echo "<h1>Místnost č. $row->no</h1>" ;
            echo("<dl class='dl-horizontal'>");
            echo "<dt>Číslo</dt> <dd>$row->no</dd>";
            echo "<dt>Název</dt> <dd>$row->name</dd>";
            echo "<dt>Telefon</dt><dd>$phoneNumber </dd>";

            if($peopleSTMT->rowCount()===0){
                echo "<dt>Lidé</dt> <dd>—</dd>";
                echo "<dt>Průměrná mzda</dt> <dd>—</dd>";
            }
            else{
                echo "<dt>Lidé:</dt>";
                while ($peopleRow = $peopleSTMT->fetch()){
                    $name =  $peopleRow ->name;
                    $surname = $peopleRow ->surname;
                    echo "<dd><a href='clovek.php?humanID=$peopleRow->employee_id'>$surname $name[0]. </a></dd>";
                }
                $wageSTMT= $pdo-> prepare("SELECT AVG(wage) as 'prumerna' FROM employee WHERE room=:roomID");
                $wageSTMT->execute(['roomID' => $id]);

                while ($wageRow = $wageSTMT->fetch()){
                    $wage = number_format($wageRow->prumerna, 2, '.', ',');
                    echo "<dt>Průměrná mzda</dt> <dd>$wage</dd>";
                }
            }
            echo "<dt>Klíče</dt>";
            while ($roomRow=$roomsStm->fetch()){
                $name = $roomRow->name;
                $surname = $roomRow->surname;
                echo "<a href='clovek.php?humanID=$roomRow->employeeID'><dd>$surname $name[0].</dd></a> ";
            }
            echo "</dl>";
            echo "<div class='end'><a href='mistnosti.php'> <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> Zpět na seznam místností</a></div></div></div>";

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
