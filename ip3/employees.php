<!DOCTYPE html>

<html lang = "cs">
<head>
    <meta name = "viewport" content="width=device-width"  charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam zaměstnanců</title>
</head>
<body class="container">
    <h1>Seznam zaměstnanců</h1>
<?php
require_once "inc/database.php";


$stmt = $pdo->query('Select  e.employee_id as employId, Concat(e.name," ", e.surname) AS Jméno, r.name AS Místnost, r.phone AS Telefon, e.job AS Pozice FROM employee e JOIN room r ON e.room = r.room_id');

if ($stmt->rowCount() == 0) {
    echo "Záznam neobsahuje žádná data";
} else {
    echo "<table class='table table-striped'>";
    echo "<tr>";
    echo "<th>Jméno<a href='?poradi=name_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=name_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Místnost<a href='?poradi=mistnost_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=mistnost_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Telefon<a href='?poradi=telCislo_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=telCislo_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Pozice<a href='?poradi=pozice_down'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=pozice_up'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
    echo "</tr>";

    switch($_GET['poradi']){

        case "name_down":
          $orderBy = "ORDER BY Jméno Desc";
          break;
      
        case "name_up":
          $orderBy = "ORDER BY Jméno Asc";
          break;

        case "mistnost_down":
            $orderBy = "ORDER BY Místnost Desc";
            break;
        
        case "mistnost_up":
            $orderBy = "ORDER BY Místnost Asc";
            break;

        case "telCislo_down":
            $orderBy = "ORDER BY Telefon Desc";
            break;
            
        case "telCislo_up":
            $orderBy = "ORDER BY Telefon Asc";
            break;

        case "pozice_down":
                $orderBy = "ORDER BY Pozice Desc";
                break;
                
        case "pozice_up":
                $orderBy = "ORDER BY Pozice Asc";
                break;
    }
    
    $stmt = $pdo->query("Select  e.employee_id as employId, Concat(e.name,' ', e.surname) AS Jméno, r.name AS Místnost, r.phone AS Telefon, e.job AS Pozice FROM employee e JOIN room r ON e.room = r.room_id $orderBy");

    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td><a href='employee.php?employeeId={$row->employId}'>{$row->Jméno}</a></td><td>{$row->Místnost}</td><td>{$row->Telefon}</td><td>{$row->Pozice}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<a href='index.html'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na prohlížeč databáze</a>";

}
unset($stmt);
?>
</body>
</html>