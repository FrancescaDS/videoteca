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
           $dod_string = $dod->format('jS F Y');
        } else {
       $today = new DateTime(date("Y-m-d"));
       $differ = $dob->diff($today);
       $age = $differ->y;
     }
     $born_string = "<b>Born</b> " . $dob->format('jS F Y');
     if (isset($age)){
        $born_string = $born_string . " (age ". $age . ")";
     }
     $born_string = $born_string . ",<br>in " . htmlentities($person->getData()['place'], ENT_QUOTES, 'utf-8');
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
    
    <div class="container_page">
        <?php
    $img = "archive/people/".$che_id.".jpg";
    if (is_file($img)){ 
        echo "<div class='pic'><img src='".$img."'></div>";
     } 
     
     ?>
        <div class="container_dx">
            <div class="bbox_dx">
                <div class="text_dx"><?php echo $born_string; ?></div>
                <div class="box_dx">
                    <div class="sbox_sx"><b>Filmography</b></div>
                    <div class="sbox_dx">
                    <?php
                        $list = $person->getDirectedMovies();
                        if (!empty($list)){ 
                            echo "<b>Director</b><br>";
                            foreach ($list as $row) {
                                echo "<a href='movie_page.php?id=" . $row['id_movie'] . "'>" . $row['title'] ."</A> (".$row['year'].")<br>";
                            }
                            echo "<br>";
                        }
                        $list = $person->getPlayedMovies();
                        if (!empty($list)){ 
                            echo "<b>Star</b><br>"; 
                            foreach ($list as $row) {
                                echo "<a href='movie_page.php?id=" 
                                . $row['id_movie'] . "'>" 
                                . htmlentities($row['title'], ENT_QUOTES, 'utf-8') ."</A>  (".$row['year'].") "
                                . "as <i>" . htmlentities($row['character_name'], ENT_QUOTES, 'utf-8') . "</i><br>";
                            }
                        } ?>
                    </div>
                </div>
            </div>        
        </div>
    
    </div>
    <div style="clear:both;"></div>
    
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>