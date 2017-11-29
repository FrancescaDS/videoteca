<?php
    require_once 'functions/functions.php';
    
    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (empty($che_id)){
        $che_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    }
    if (empty($che_id)){
        $che_id = 0;
    }
    $movie = new Movie($che_id);
    
    $msg = "";
    $done = "";
    $class = "msgok";
    
    if (!is_null(filter_input(INPUT_POST, 'btn_movie'))) {
        $title = filter_input(INPUT_POST, 'title');
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
        if ($movie->isUnique($title, $year)){
            $data = [
                'title' => $title,
                'year' => $year
            ];
            if ($che_id === 0){
                $done = $movie->insertUpdate($title, $year);
            } else {
                $done = $movie->insertUpdate($title, $year);
            }
            if ($done){
                $msg = "Record inserted/updated";
            } else {
                $msg = "Error insert/update";
                $class = "msgerror";
            }
        } else {
            $msg = "This movie (title+year) is already present in our db";
            $class = "msgerror";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_image'))){
        $result = uploadImage($che_id, 'movies');
        $msg = $result[1];
        if (!$result[0]){
            $class = "msgerror";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_newdirector'))) {
        $id_newdirector = filter_input(INPUT_POST, 'newdirector', FILTER_VALIDATE_INT);
        $result = $movie->insertDirector($id_newdirector);
        if (!$result){
            $msg = "Movie not updated: cannot insert new director";
            $class = "msgerror";
        }else{
            $msg = "Movie updated: director inserted";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_deldirector'))) {
        $id_deldirector = filter_input(INPUT_POST, 'deldirector', FILTER_VALIDATE_INT);
        $result = $movie->deleteDirector($id_deldirector);
        if (!$result){
            $msg = "Movie not updated: cannot delete director";
            $class = "msgerror";
        }else{
            $msg = "Movie updated: director deleted";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_newactor'))) {
        $id_newactor = filter_input(INPUT_POST, 'newactor', FILTER_VALIDATE_INT);
        $character_name = filter_input(INPUT_POST, 'character_name');
        $result = $movie->insertActor($id_newactor, $character_name);
        if (!$result){
            $msg = "Movie not updated: cannot insert new star";
            $class = "msgerror";
        }else{
            $msg = "Movie updated: star inserted";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_delactor'))) {
        $id_delactor = filter_input(INPUT_POST, 'delactor', FILTER_VALIDATE_INT);
        $result = $movie->deleteActor($id_delactor);
        if (!$result){
            $msg = "Movie not updated: cannot delete star";
            $class = "msgerror";
        }else{
            $msg = "Movie updated: star deleted";
        }
    }
    
    
    if (isset($movie->getData()['id_movie'])){
        $che_id = $movie->getData()['id_movie'];
        $title = $movie->getData()['title'];
        $year = $movie->getData()['year'];
    } else {
        $che_id = 0;
        $title = "";
        $year = "";
    }
    
    $title = htmlentities($title, ENT_QUOTES, 'utf-8');
    $year = htmlentities($year, ENT_QUOTES, 'utf-8');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMIN MOVIE PAGE - VIDEOTECA</title>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script type="text/javascript" src="includes/check_form.js"></script>
        <LINK rel="stylesheet" href='style.css'> 
    </head>
<body>

 <?php include "includes/header.php"; ?>   
 <?php include "includes/admin-top.php"; ?>   
    
  <div class="main">
    <H1>MOVIE</H1>
        
    <?php if ($msg<>""){ ; ?>
    <div class="<?php echo $class; ?>">
    <?php echo $msg; ?>
    </div>
    <?php } ?>
    
        <form action="admin-movie_page.php" id="form_movie" method="post">
            <input type='hidden' name='id' value="<?php echo $che_id; ?>">
          <div class="form-group">
            <label for="title">Title</label>
          <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
          </div>

          <div class="form-group">
            <label for="year">Year</label>
            <input type="text" name="year" class="form-control" value="<?php echo $year; ?>">
          </div>

          <div class="form-group">
            <input type="submit" name="btn_movie" value="Insert/Update movie" class="submit">
          </div>
        </form>
    
    <!-- DIRECTOR-CAST-POSTER ONLY ID THE MOVIE HAS A RECORD -->
    <?php
    if ($che_id<>0){ ?>
        
        <!-- DIRECTOR/S START -->
        <div class="container_border">
            <div class='sbox_sx'><b>Director/s</b></div>
            <div class='sbox_dx'>
                <!-- director/s already associated with the movie -->
                <?php $rows = $movie->getDirectors();
                if (count($rows) > 0){
                    echo '<div class="form-group">';
                    foreach ($rows as $row) {
                        $who = htmlentities($row['name'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['surname'], ENT_QUOTES, 'utf-8');
                        $who = '<a href="admin-person_page.php?id='.$row['id_person'].'">'.$who.'</a>'; ?>
                        <form action="admin-movie_page.php" id="form_deldirector" method="post">
                            <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                            <input type='hidden' name='deldirector' value="<?php echo $row['id_person']; ?>">
                            <input type="submit" name="btn_deldirector" value="Del" class="submit">&nbsp;
                            <?php echo $who;?>
                            <br>
                        </form>
                    <?php
                    }
                    echo '</div>';
                } ?>
                <!-- new director to insert -->
                <form action="admin-movie_page.php" id="form_newdirector" method="post">
                <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                <div class="form-group">
                    <label for="newdirector">New director</label><br>
                    <?php $rows = $movie->getDirectorsNot() ?>
                    <select id="newdirector" name="newdirector">
                        <option value="0">Select the new director</option>
                        <?php foreach($rows as $row){
                            $who = htmlentities($row['surname'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['name'], ENT_QUOTES, 'utf-8');
                            echo '<option value="'.$row['id_person'].'">'.$who.'</option>';
                            } ?>
                    </select>&nbsp;
                    <input type="submit" name="btn_newdirector" value="Insert new director">
                </div>
            </form>
            </div>
        </div>
        <!-- DIRECTOR/S END-->
        
        <!-- CAST START-->
            <div class="container_border">
            <div class='sbox_sx'><b>Cast</b></div>
            <div class='sbox_dx'>
                <!-- actors/actress already associated with the movie -->
                <?php $rows = $movie->getActors();
                if (count($rows) > 0){
                    echo '<div class="form-group">';
                    foreach ($rows as $row) {
                        $who = htmlentities($row['name'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['surname'], ENT_QUOTES, 'utf-8');
                        $who = '<a href="admin-person_page.php?id='.$row['id_person'].'">'.$who.'</a>';
                        $character = "<i>".htmlentities($row['character_name'], ENT_QUOTES, 'utf-8')."</i>" ?>
                        <form action="admin-movie_page.php" id="form_delactor" method="post">
                            <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                            <input type='hidden' name='delactor' value="<?php echo $row['id_person']; ?>">
                            <input type="submit" name="btn_delactor" value="Del" class="submit">&nbsp;
                            <?php echo $who . " as " . $character ;?>
                            <br>
                        </form>
                    <?php
                    }
                    echo '</div>';
                } ?>
                <!-- new actor/actress to insert -->
                <form action="admin-movie_page.php" id="form_newactor" method="post">
                <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                <div class="form-group">
                    <label for="newactor">New star</label><br>
                    <?php $rows = $movie->getActorsNot() ?>
                    <select id="newactor" name="newactor">
                        <option value="0">Select the new star</option>
                        <?php foreach($rows as $row){
                            $who = htmlentities($row['surname'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['name'], ENT_QUOTES, 'utf-8');
                            echo '<option value="'.$row['id_person'].'">'.$who.'</option>';
                            } ?>
                    </select> 
                    <br><label for="character_name">Character name</label>
                    <input type="text" name="character_name" class="form-control" value="">
                    <input type="submit" name="btn_newactor" value="Insert new star">
                    
                </div>
            </form>
            </div>
        </div>
        <!-- CAST END-->
        
        <!-- POSTER START-->
        <div class="container_border" >
        <?php
        $img = "archive/movies/".$che_id.".jpg";
        if (is_file($img)){ 
            echo "<div class='pic'><img src='".$img."'></div>";
        } ?>
            <div class='box_dx_top'>
                <form enctype="multipart/form-data" action="admin-movie_page.php" id="form_image" method="post">
                    <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                    <div class="form-group">
                        <label for="poster">New poster</label>
                        <input name="image" type="file" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="btn_image" value="Upload new poster" class="submit">
                    </div>
                </form>
            </div>
        </div>
        <!-- POSTER END -->
        
        <?php   
    } ?>
        
        
    
    <?php include "includes/admin-links.php"; ?>
    </div>
        
    <?php include "includes/footer.php"; ?>
</body>
</html>