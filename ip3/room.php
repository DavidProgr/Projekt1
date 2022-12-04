<?php
   $id = filter_input(INPUT_GET,
       'roomId',
       FILTER_VALIDATE_INT,
       ["options" => ["min_range"=> 1]]
   );
   
   if($id === null || $id === false){
    http_response_code(400);
    $status = "bad_request";
   }else{

    require_once "inc/database.php";

    $KartaMistnosti = $pdo->query("Select r.room_id, r.no, r.name, r.phone, AVG(e.wage) AS Pmzda FROM room r JOIN employee e ON r.room_id = e.room Where r.room_id=$id");
   
   
    $b = $KartaMistnosti->fetch();
    if($KartaMistnosti->rowCount()===0||$b -> room_id !== $id){
        http_response_code(404);
        $status = "not_found";
       
    }
    else{
        $Lidi = $pdo ->query("Select e.employee_id AS empId, e.name, e.surname FROM room r JOIN employee e ON r.room_id = e.room WHERE r.room_id = $id");
        $keyLide = $pdo ->query("Select e.employee_id AS empId, e.name ,e.surname FROM employee e JOIN `key` k ON e.employee_id = k.employee Where k.room=$id");
        $roomDetails = $b;
        $lide = $Lidi;
        $klice = $keyLide;
        $status = "OK";
    }

    function detail($dats, $dats2,$dats3){
        $row = $dats;

        echo "<h1>Místnost č. {$row -> no}</h1>";
        echo "<dl class='dl-horizontal'>";
       
        if ($row) {
            echo "<dd>";
            echo "<dt>Číslo</dt>";
            echo "</dd>";
            
            echo "<dt>";
            echo "<dd>$row->no</dd>";
            echo "</dt>";
            
            echo "<dd>";
            echo "<dt>Název</dt>";
            echo "</dd>";
            
            echo "<dt>";
            echo "<dd>$row->name</dd>";
            echo "</dt>";

            echo "<dd>";
            echo "<dt>Telefon</dt>";
            echo "</dd>";

            echo "<dt>";
            echo "<dd>$row->phone</dd>";
            echo "</dt>";

            echo "<dd>";
            echo "<dt>Průměrná mzda</dt>";
            echo "</dd>";
            
            echo "<dt>";
            echo "<dd>$row->Pmzda</dd>";
            echo "</dt>";
        }
        
        echo "<dd>";
        echo "<dt>Lidé</dt>";
        echo "</dd>";
        
        
        while($row = $dats2->fetch()){
         
            $jmeno = $row->name;
            $jmeno = substr($jmeno,0,1);
            $prijmeni = $row->surname;
            $fullname = implode(' ', array($prijmeni,$jmeno));
          
            echo "<dt>";
            echo "<dd><a href='employee.php?employeeId={$row->empId}'><li class = klice>{$fullname}.</li></a></dd>";
            echo "</dt>";
        }

        echo "<dd>";
        echo "<dt>Klíče</dt>";
        echo "</dd>";

        while($row = $dats3->fetch()){

            $jmeno = $row->name;
            $jmeno = substr($jmeno,0,1);
            $prijmeni = $row->surname;
            $fullname = implode(' ', array($prijmeni, $jmeno));
            echo "<dt>";
            echo "<dd><a href='employee.php?employeeId={$row->empId}'><li class = klice>{$fullname}.</li></a></dd>";
            echo "</dt>";
        }

        echo "</dl>";
        
        echo "<a href='rooms.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam místností</a>";
       }
    }
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleroom.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php
        if($id === false || $id === null){
            echo "<title>Karta místnosti</title>";
        }else{
            $Mistnost = $pdo->query("Select no as Cislo FROM room WHERE room_id=$id");
            if($cisloM = $Mistnost ->fetch()){
                echo "<title>Karta místnosti č. {$cisloM -> Cislo}</title>";
            }
        }
    ?>
    
</head>
<body class = "container">
<?php
    switch($status){
        case "bad_request";
            echo "<h1> Error 400: Bad request</h1><br>";
            echo "<a href='rooms.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam místností</a>";
            break;
        case "not_found";
            echo "<h1> Error 404: Not found</h1><br>";
            echo "<a href='rooms.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam místností</a>";
            break;
        default:
            detail($roomDetails, $lide, $keyLide);
            break;
    }
?>
</body>
</html>