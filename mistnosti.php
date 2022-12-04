
<?php
require_once "inc/dbconnection.php";
require_once "inc/functions.php";


if(!isset($_GET['poradi']))  $order = "name DESC";
else  $order = GetOrder($_GET['poradi']);
if($order === "normal")  $order = "name DESC";


$stmt = $pdo->prepare("SELECT *  from room order by $order" );

$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(404);
} else {
    NadpisStranky("Seznam místností");
    Nadpis("Název", "nazev");
    Nadpis("Číslo", "mistnostCislo");
    Nadpis("Telefon", "mistnostTelefon");
    echo "</tr>";
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td> <a href='mistnost.php?roomID=$row->room_id'> $row->name</a></td><td>$row->no</td> <td>$row->phone</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>
</body>
</html>
