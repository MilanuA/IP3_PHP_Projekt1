<?php
function GetOrder($poradi){
    $order = "";
    switch ($poradi){
        case "prijmeni_down":
            $order = "employee.surname DESC";
            break;
        case "prijmeni_up":
            $order = "employee.surname ASC";
            break;
        case "mistnost_up":
            $order = "room.name ASC";
            break;
        case "mistnost_down":
            $order = "room.name DESC";
            break;
        case "pozice_up":
            $order = "employee.job ASC";
            break;
        case "pozice_down":
            $order = "employee.job DESC";
            break;
        case "telefon_up":
            $order = "room.phone ASC";
            break;
        case  "telefon_down":
            $order = "room.phone DESC";
            break;
        case "nazev_up":
            $order = "name ASC";
            break;
        case "nazev_down":
            $order = "name DESC";
            break;
        case  "mistnostTelefon_up":
            $order = "phone ASC";
            break;
        case  "mistnostTelefon_down":
            $order = "phone DESC";
            break;
        case  "mistnostCislo_down":
            $order = "no DESC";
            break;
        case  "mistnostCislo_up":
            $order = "no ASC";
            break;
        default:
            $order = "normal";
    }
    return $order;
}

function Nadpis($nadpis, $poradi){
    echo "<th>$nadpis <a href=?poradi={$poradi}_down><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></a> <a href=?poradi={$poradi}_up><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
}

function NadpisStranky($nadpis){
    echo " <!DOCTYPE html> <html> <head>  <meta charset='UTF-8'> <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'><title>$nadpis</title></head>";
    echo "<body class='container'>";
    echo "<h1 class='header'>$nadpis</h1>";
    echo "<table class='table'> <tr>";
}