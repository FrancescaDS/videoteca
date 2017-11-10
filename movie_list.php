<?php
header('Content-type: text/html;charset=utf-8');
require_once 'functions/functions.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>MOVIES - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>
<?php include "includes/header.php"; ?>
<div class="main">   
    <H1>MOVIES</H1> 
    
<?php
    try {
        $rows = getAllMovies();
        
        echo "<H3>Our DB conteins " . count($rows) . " movies.</H3>";
        echo "<table><tr><td><b>Title</b></td><td><b>Year</b></td></tr>";

        foreach($rows as $row){
            echo "<tr>";
            echo "<td><A href='movie_page.php?id=" . $row['id_movie'] . "'>" . htmlentities($row['title'], ENT_QUOTES, 'utf-8') . " </a></td><td>". $row['year'] . "</td>";
            echo "<tr>";
        }

        echo "</table>";
        
    } catch (Exception $ex) {
            echo $ex->getMessage();
    }

?>

    <?php include "includes/links.php"; ?>
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>