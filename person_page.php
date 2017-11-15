<?php
    require_once 'functions/functions.php';
    
    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $person = new Person($che_id);
    $name = htmlentities($person->getData()['name'], ENT_QUOTES, 'utf-8');
    $surname = htmlentities($person->getData()['surname'], ENT_QUOTES, 'utf-8');
    $dob = new DateTime($person->getData()['dob']);
    $dod_string = "";
    $dod = new DateTime($person->getData()['dod']);
        if ($dod > $dob){
           $dod_string = $dod->format('j F Y');
        } else {
       $today = new DateTime(date("Y-m-d"));
       $differ = $dob->diff($today);
       $age = $differ->y;
     }
     $born_string = "<b>Born</b> " . $dob->format('j F Y');
     if (isset($age)){
        $born_string = $born_string . " (age ". $age . ")";
     }
     $born_string = $born_string . ", " . htmlentities($person->getData()['place'], ENT_QUOTES, 'utf-8');
     if ($dod_string <> ""){
        $born_string = $born_string . "<br><b>Die</b> ".$dod_string ;
     }
    
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $name; ?> <?php echo $surname; ?> - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>
<?php include "includes/header.php"; ?>
    <div class="main">   
    <H1><?php echo strtoupper($name); ?> <?php echo strtoupper($surname); ?></H1>
    
    <div style="clear:both;">
        <?php
    $img = "archive/people/".$che_id.".jpg";
    if (is_file($img)){ 
        echo "<div class='box'>"
        . "<figure class='figure'>"
        . "<img src='".$img."' class='figure-img img-fluid rounded' alt='A generic square placeholder image with rounded corners in a figure.'>"
        //. "<figcaption class='figure-caption'>A caption for the above image.</figcaption>"
        . "</figure>"
        . "</div>";
     } 
     
     ?>
            <div class="box"><?php echo $born_string; ?></div>        
    
    
    </div>
    <div style="clear:both;"></div>
    <TABLE>   
            
            
            
            
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
    
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>