<?php   
    require_once 'functions/functions.php';
    
    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $movie = new Movie($che_id);
    $title = htmlentities($movie->getData()['title'], ENT_QUOTES, 'utf-8');    
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title; ?> - VIDEOTECA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>
<?php include "includes/header.php"; ?>
    <div class="main">   
    <H1><?php echo strtoupper($title); ?></H1>
    
    <div class="container_page">
        <?php
    $img = "archive/movies/".$che_id.".jpg";
    if (is_file($img)){ 
        echo "<div class='pic'><img src='".$img."'></div>";
     } 
     
     ?>
        <div class="container_dx">
            <div class="bbox_dx">
                <div class="text_dx"><?php echo $title; ?>, (<?php echo $movie->getData()['year']; ?>)</div>
                
                <?php
                $list = $movie->getDirectors();
                if (!empty($list)){ ?>
                <div class="box_dx">
                    <div class="sbox_sx"><b>Director/s</b></div>
                    <div class="sbox_dx">
                    <?php
                    foreach ($list as $row) {
                        echo "<a href='person_page.php?id=" . $row['id_person'] . "'>"
                            . htmlentities($row['name'], ENT_QUOTES, 'utf-8') ." "
                            . htmlentities($row['surname'], ENT_QUOTES, 'utf-8') ."</A><br>";
                    } ?>
                    </div>
                </div>
                <?php } ?>
                <br>
                <?php
                $list = $movie->getActors();
                if (!empty($list)){ ?>
                <div class="box_dx">
                    <div class="sbox_sx"><b>Stars</b></div>
                    <div class="sbox_dx">
                    <?php
                    foreach ($list as $row) {
                        echo "<a href='person_page.php?id=" . $row['id_person'] . "'>"
                            . htmlentities($row['name'], ENT_QUOTES, 'utf-8') ." "
                            . htmlentities($row['surname'], ENT_QUOTES, 'utf-8') ."</A>"
                            . " as " . htmlentities($row['character_name'], ENT_QUOTES, 'utf-8') . "<br>";
                    } ?>
                    </div>
                </div>
                <?php } ?>
            
            </div>        
        </div>
    
    </div>
    <div style="clear:both;"></div>
    
</div>
    
<?php include "includes/footer.php"; ?>
</body>
</html>