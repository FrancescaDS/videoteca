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
        $year = filter_input(INPUT_POST, 'year');
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
            $msg = "Movie not updated: problem with new director";
            $class = "msgerror";
        }else{
            $msg = "Movie updated with new director";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_deldirector'))) {
        $id_deldirector = filter_input(INPUT_POST, 'deldirector', FILTER_VALIDATE_INT);
        $result = $movie->deleteDirector($id_deldirector);
        if (!$result){
            $msg = "Movie not updated: problem with del director";
            $class = "msgerror";
        }else{
            $msg = "Movie updated deleted director";
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
            <label for="email">Year</label>
            <input type="text" name="year" class="form-control" value="<?php echo $year; ?>">
          </div>

          <div class="form-group">
            <input type="submit" name="btn_movie" value="Insert/Update movie" class="submit">
          </div>
        </form>
    
    <?php
    if ($che_id<>0){ ?>
    
    
        <div class="container_page" >
            <div class='sbox_sx'><b>Director/s</b></div>
            <div class='sbox_dx'>
                <?php 
                //director/s already linked to the movie
                $rows = $movie->getDirectors();
                if (count($rows) > 0){
                    echo '<div class="form-group">';
                    foreach ($rows as $row) {
                        $who = htmlentities($row['name'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['surname'], ENT_QUOTES, 'utf-8');
                        ?>
                        <form action="admin-movie_page.php" id="form_deldirector" method="post">
                            <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                            <input type='hidden' name='deldirector' value="<?php echo $row['id_person']; ?>">
                            <?php echo $who;?>
                            <input type="submit" name="btn_deldirector" value="Delete director" class="submit">
                            <br>
                        </form>
                        <?php
                    }
                    echo '</div>';
                }
                // new director to insert ?>
                <form action="admin-movie_page.php" id="form_newdirector" method="post">
                <input type='hidden' name='id' value="<?php echo $che_id; ?>">
                <div class="form-group">
                    <label for="director">New director</label>
                    <?php $rows = $movie->getDirectorsNot() ?>
                    <select class="form-control" id="newdirector" name="newdirector">
                        <option value="0">Select the new director</option>
                        <?php foreach($rows as $row){
                            $who = htmlentities($row['name'], ENT_QUOTES, 'utf-8') . " " . htmlentities($row['surname'], ENT_QUOTES, 'utf-8');
                            echo '<option value="'.$row['id_person'].'">'.$who.'</option>';
                            } ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="btn_newdirector" value="Insert new director" class="submit">
                </div>
            </form>
            </div>
            
        </div>
        
        
    <?php
        echo 'cast list + cancel<br>';
        echo 'cast add new<br>';
        
        //Poster
        echo '<div class="container_page" >';
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
        
    <?php   
    }
    ?>
        

        <?php include "includes/admin-links.php"; ?>
    </div>
        
    <?php include "includes/footer.php"; ?>
</body>
</html>