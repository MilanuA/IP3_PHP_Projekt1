<?php
require_once "inc/dbconnection.php";
require_once "inc/functions.php";

if(!isset($_GET['poradi'])) $order = "employee.surname DESC";
else  $order = GetOrder($_GET['poradi']);

if($order === "normal") $order = "employee.surname DESC";


$stmt = $pdo->prepare("SELECT employee.name as 'jmeno', employee.surname as 'prijmeni', employee.job as 'pozice', employee.employee_id as 'zamestnanecID', room.name as 'mistnost', room.phone as 'telefon' 
FROM employee 
    JOIN room ON employee.room = room.room_id ORDER BY $order" );

$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(404);
} else {
    NadpisStranky("Seznam zaměstnanců");
    Nadpis("Jméno", "prijmeni");
    Nadpis("Místnost", "mistnost");
    Nadpis("Telefon", "telefon");
    Nadpis("Pozice", "pozice");
    echo "</tr>";
    while ($row = $stmt->fetch()) {
        echo " <tr> <td> <a href='clovek.php?humanID=$row->zamestnanecID'> $row->prijmeni $row->jmeno </a></td><td>$row->mistnost</td> <td>$row->telefon</td> <td>$row->pozice</td> </tr>";
    }
    echo "</table>";
}
?>
</body>
</html>

