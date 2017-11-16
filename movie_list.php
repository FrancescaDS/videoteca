<?php
    header('Content-type: text/html;charset=utf-8');
    require_once 'functions/functions.php';
    
    $rows = getAllMovies();
    $pagination = getPagination($rows);
    $rows = getAllMovies($pagination['limit_from'], $pagination['res_per_page']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>MOVIES - VIDEOTECA</title>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'>  
        
    </head>
<body>
    <?php include "includes/header.php"; ?>
<div class="main">   
    <H1>MOVIES</H1> 
    
<?php
    try {
        
        echo "<H3>Our DB contains " . $pagination['tot_res'] . " movies.</H3>"
            ."<table class='table table-hover'>
            <thead>
              <tr>
                <th>Title</th>
                <th>Year</th>
              </tr>
            </thead>
            <tbody>";

        foreach($rows as $row){
            echo "<tr>"
            . "<td><A href='movie_page.php?id=" . $row['id_movie'] . "'>"
            . htmlentities($row['title'], ENT_QUOTES, 'utf-8') . " </a></td>
            <td>". $row['year'] . "</td>
            <tr>";
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