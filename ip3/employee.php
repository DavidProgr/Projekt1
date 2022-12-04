<?php
   $id = filter_input(INPUT_GET,
       'employeeId',
       FILTER_VALIDATE_INT,
       ["options" => ["min_range"=> 1]]
   );
   
   if($id === null || $id === false){
    http_response_code(400);
    $status = "bad_request";
   }else{

    require_once "inc/database.php";
    
    $key = $pdo->query("Select r.room_id ,r.name AS kliceJmena FROM room r JOIN `key` k ON r.room_id = k.room Where k.employee=$id");
    $KartaZamestnance = $pdo->query("Select e.employee_id AS Emp_id, e.name AS Jmeno, e.surname AS Prijmeni, e.job AS Pozice, e.wage AS Mzda, r.name AS Mistnost, r.room_id FROM employee e JOIN room r ON e.room = r.room_id Where e.employee_id=$id");
    
  
    if($KartaZamestnance->rowCount()===0&&$key->rowCount()===0){
        http_response_code(404);
        $status = "not_found";
    }
    else{
        
        $klice = $key;
        $kartaZ = $KartaZamestnance;
        $status = "OK";
    }
   }

   function detail($dats, $dats2){
    $row = $dats -> fetch();
    $jmeno = $row -> Jmeno;
    $jmeno = substr($jmeno,0,1);
    echo "<h1>Karta osoby: <em>{$row -> Prijmeni} {$jmeno}.</em></h1>";
    echo "<dl class='dl-horizontal'>";
   
    if ($row) {
        echo "<dd>";
        echo "<dt>Jméno</dt>";
        echo "</dd>";
        
        echo "<dt>";
        echo "<dd>{$row->Jmeno}</dd>";
        echo "</dt>";

        echo "<dd>";
        echo "<dt>Příjmení</dt>";
        echo "</dd>";

        echo "<dt>";
        echo "<dd>{$row->Prijmeni}</dd>";
        echo "</dt>";

        echo "<dd>";
        echo "<dt>Pozice</dt>";
        echo "</dd>";

        echo "<dt>";
        echo "<dd>{$row->Pozice}</dd>";
        echo "</dt>";

        echo "<dd>";
        echo "<dt>Mzda</dt>";
        echo "</dd>";

        echo "<dt>";
        echo "<dd>{$row->Mzda}</dd>";
        echo "</dt>";

        echo "<dd>";
        echo "<dt>Mistnost</dd>";
        echo "</dd>";

        echo "<dt>";
        echo "<dd><a href='room.php?roomId={$row->room_id}'>{$row->Mistnost}</a></dd>";
        echo "</dt>";
    }
    
    echo "<dd>";
    echo "<dt>Klíče</dt>";
    echo "</dd>";

    while($row = $dats2->fetch()){
        echo "<dt>";
        echo "<dd><a href='room.php?roomId={$row->room_id}'><li class = klice>{$row->kliceJmena}</li></a></dd>";
        echo "</dt>";
    }
    
    echo "</dl>";

    echo "<a href='employees.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam zaměstnanců</a>";
   }

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleemployee.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php
        if($id === false || $id === null){
            echo "<title>Karta osoby</title>";
        }else{
            $ZamestnanecQuery = $pdo->query("Select name AS Jmeno, surname AS Prijmeni FROM employee Where employee_id=$id");
            if($jmenoAprijmeni = $ZamestnanecQuery -> fetch()){
                $Prijmeni = $jmenoAprijmeni -> Prijmeni;
                $Jmeno = $jmenoAprijmeni -> Jmeno;
                $Jmeno = substr($Jmeno,0,1);
                echo "<title>Karta osoby: {$jmenoAprijmeni -> Prijmeni} {$Jmeno}.</title>";
            }
        }
?>
    
</head>
<body class = "container">
<?php
        switch($status){
            case "bad_request";
                echo "<h1> Error 400: Bad request</h1><br>";
                echo "<a href='employees.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam zaměstnanců</a>";
                break;
             case "not_found";
                echo "<h1> Error 404: Not found</h1><br>";
                echo "<a href='employees.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam zaměstnanců</a>";
                break;
            default:
                detail($kartaZ, $klice);
                break;
    }
?>
    
</body>
</html>