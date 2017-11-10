<?php
    require_once 'functions/functions.php';
    
    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $person = new Person($che_id);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PERSON PAGE - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>
<?php include "includes/header.php"; ?>
<div class="main">   
    <H1>PERSON PAGE</H1>
    
        <TABLE>
            <?php
            $img = "archive/people/".$che_id.".jpg";
            if (is_file($img)){ 
                echo "<tr><td colspan='2'><img src='".$img."'></td></tr>";
            } ?>
            <TR><TD class='newp'>Name</TD>
                <TD><?php echo $person->getData()['name']; ?></TD></TR> 
            <TR><TD class='newp'>Surname</TD>
                <TD><?php echo $person->getData()['surname']; ?></TD</TR> 
            <tr><td colspan="2"><b>Filmography</b></td></tr>
            <?php
                $list = $person->getDirectedMovies();
                if (!empty($list)){ 
                    echo "<TR><TD class='newp'>Director</TD>"
                    . "<TD>";
                    foreach ($list as $row) {
                        echo "<a href='movie_page.php?id=" . $row['id_movie'] . "'>" . $row['title'] ."</A> (".$row['year'].")<br>";
                    }
                    echo "</TD></TR>";
                }
                $list = $person->getPlayedMovies();
                if (!empty($list)){ echo "<TR><TD class='newp'>Actor/Actress</TD>"
                    . "<TD>"; 
                foreach ($list as $row) {
                    echo "<a href='movie_page.php?id=" . $row['id_movie'] . "'>" . $row['title'] ."</A>  (".$row['year'].") as " . $row['character_name'] . "<br>";
                }
                echo "</TD></TR>";
                }
            ?>
        </TABLE>
    
<?php include "includes/links.php"; ?>
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>