<?php
    require_once 'functions/functions.php';

    $rows = getAllPeopleAll();
    $pagination = getPagination($rows);
    $rows = getAllPeopleAll($pagination['limit_from'], $pagination['res_per_page']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMIN PEOPLE - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>

 <?php include "includes/header.php"; ?>   
 <?php include "includes/admin-top.php"; ?>   
    
  <div class="main">  
    <H1>PEOPLE</H1>
    
<?php
    try {
        echo "<H3>Our DB contains " . $pagination['tot_res'] . " people.</H3>";
        echo "<table class='table table-hover'>
            <thead>
              <tr>
                <th>Surname and name</th>
                <th>Date of birth</th>
              </tr>
            </thead>
            <tbody>";

        foreach($rows as $row){
            echo "<tr>";
            echo "<td><A href='admin-person_page.php?id=" 
                . $row['id_person'] . "'>" 
                . htmlentities($row['surname'], ENT_QUOTES, 'utf-8')  
                ." ". htmlentities($row['name'], ENT_QUOTES, 'utf-8')  
                . "</a></td>";
            $dob = new DateTime($row['dob']);
            echo "<td>" . $dob->format('j F Y') ."</td>";
            echo "<tr>";
        }

        echo "<tbody></table>";
        
    } catch (Exception $ex) {
            echo $ex->getMessage();
    }

?>

    <?php include "includes/pagination.php"; ?>
<?php include "includes/admin-links.php"; ?>
    </div>
        
    <?php include "includes/footer.php"; ?>
</body>
</html>