<!DOCTYPE html>

<html lang = cs>
<head>
    <meta name = "viewport" content="width=device-width"  charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="stylerooms.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam místností</title>
</head>
<body class="container">
<h1>Seznam místností</h1>
<?php
require_once "inc/database.php";

$stmt = $pdo->query('SELECT * FROM room');

if ($stmt->rowCount() == 0) {
    echo "Záznam neobsahuje žádná data";
} else {
    echo "<table class='table table-striped'>";
    echo "<tr>";
    echo "<th>Název<a href='?poradi=name_up' class = sorted><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=name_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Číslo<a href='?poradi=cislo_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=cislo_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Telefon<a href='?poradi=telCislo_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=telCislo_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";

  
    switch($_GET['poradi']){

        case "name_down":
          $orderBy = "ORDER BY name Desc";
          break;
      
        case "name_up":
          $orderBy = "ORDER BY name Asc";
          break;

        case "cislo_down":
            $orderBy = "ORDER BY no Desc";
            break;
        
        case "cislo_up":
            $orderBy = "ORDER BY no Asc";
            break;

        case "telCislo_down":
            $orderBy = "ORDER BY phone Desc";
            break;
            
        case "telCislo_up":
            $orderBy = "ORDER BY phone Asc";
            break;
    }
    
    $stmt = $pdo->query("SELECT * FROM room $orderBy");

    echo "</tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td><a href='room.php?roomId={$row->room_id}'>{$row->name}</a></td><td>{$row->no}</td><td>{$row->phone}</td>";
            echo "</tr>";
        }
    echo "</table>";
    echo "<a href='index.html'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na prohlížeč databáze</a>";

    
}
unset($stmt);
?>
</body>
</html>