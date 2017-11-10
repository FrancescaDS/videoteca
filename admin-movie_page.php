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
            }
        } else {
            $msg = "These name + year are in another record";
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
        
        <div class="main">
        <?php echo $msg; ?>
        <H1>MOVIE</H1>
        <form action="admin-movie_page.php" id="form_movie" method="post">
        <INPUT type='text' name='id' hidden="true" value="<?php echo $che_id; ?>">
            <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
        </div>

        <div class="form-group">
          <label for="email">Year</label>
          <input type="text" name="year" class="form-control" value="<?php echo $year; ?>">
        </div>

        <div class="form-group">
          <input type="submit" name="btn_movie" value="Insert/Update" class="submit" class="form-control">
        </div>
      </form>
        
          <?php include "includes/links.php"; ?>
        </div>
        
    <?php include "includes/footer.php"; ?>
  </BODY>
</HTML>