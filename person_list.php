<?php
    require_once 'functions/functions.php';

    if ((string)filter_input(INPUT_GET, 'what') === 'dir'){
        $what = "dir";
        $title = "Directors";
    }else{
        $what = "act";
        $title = "Actors/Acteresses";
    }
    $rows = getAllPeople($what);
    $pagination = getPagination($rows);
    $rows = getAllPeople($what, $pagination['limit_from'], $pagination['res_per_page']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo strtoupper($title) ?> - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>
<?php include "includes/header.php"; ?>
<div class="main">   
    <H1><?php echo strtoupper($title) ?></H1>
    
<?php
    try {
        echo "<H3>Our DB contains " . $pagination['tot_res'] . " ". $title .".</H3>";
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
            echo "<td><A href='person_page.php?id=" 
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
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>