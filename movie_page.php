<?php
    require_once 'functions/functions.php';
    
    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $movie = new Movie($che_id);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>MOVIE - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>

    <?php include "includes/header.php"; ?>
   
    <H1>MOVIE</H1>
        <TABLE>
            <?php
            $img = "archive/poster/".$che_id.".jpg";
            if (is_file($img)){ 
                echo "<tr><td colspan='2'><img src='".$img."'></td></tr>";
            } ?>
            <TR><TD class='newp'>Title</TD>
                <TD><?php echo $movie->getData()['title']; ?></TD></TR> 
            <TR><TD class='newp'>Year</TD>
                <TD><?php echo $movie->getData()['year']; ?></TD</TR> 
            
            <?php
                $list = $movie->getDirectors();
                if (!empty($list)){ ?>
            <TR><TD class='newp'>Director/s</TD>
                <TD>
                <?php 
                foreach ($list as $row) {
                    echo "<a href='person_page.php?id=" . $row['id_person'] . "'>" . $row['name'] ." ".$row['surname']."</A><br>";
                }
                ?>    
               </TD></TR>
            
            <?php
                }
                $list = $movie->getActors();
                if (!empty($list)){ ?>
            <TR><TD class='newp'>Cast</TD>
                <TD>
                <?php 
                foreach ($list as $row) {
                    echo "<a href='person_page.php?id=" . $row['id_person'] . "'>" . $row['name'] ." ".$row['surname']."</A> as " . $row['character_name'] . "<br>";
                }
                ?>    
               </TD></TR>
            
            <?php
                }
            ?>
       
            
        </TABLE>
    
 <?php include "includes/links.php"; ?>

<?php include "includes/footer.php"; ?>
  </BODY>
</HTML>


