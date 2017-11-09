<?php

$is_submitted = false;
$errors = [];
$valid_data = [];
$redisplay_title = '';
$redisplay_year = '';

$button = filter_input(INPUT_POST, 'my_btn');
if (!is_null($button)) {
    $is_submitted = true;

    $title = filter_input(INPUT_POST, 'title');
    if (!is_null($title)) {
        $trimmed = trim($title);
        //controllare anche lunghezza if ((strlen($title) === 0 ) or (strlen($title) > 64)){
        
        if ($trimmed !== '') {
            $valid_data['title'] = $trimmed;
        } else {
            $errors['title'] = 'Please enter a title';
        }
        if (isset($valid_data['title'])) {
            $redisplay_title = htmlentities($valid_data['title'], ENT_QUOTES, 'utf-8');
        }
    } else {
        $errors['title'] = 'The title field is missing!';
    }

    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    if (!is_null($year)) {
        if ($year !== false) {
            $valid_data['year'] = $year;
        } else {
            $errors['year'] = 'Please enter the year';
        }
        if (isset($valid_data['year'])) {
            //controllare che sia un anno valido tra 1900 e anno corrente
            $redisplay_year = htmlentities($valid_data['year'], ENT_QUOTES, 'utf-8');
        }
    } else {
        $errors['year'] = 'The year field is missing!';
    }
    
    $error_msg = '';
    if (empty($errors)) {
        //$error_msg = 'ok';
        //controllare non ci siano altri con titolo e anno uguali
        //se id =0 INSERT
        //$sql = "INSERT INTO movies (title, year) VALUES ('".$title."',".$year.")";    
        $sql = "UPDATE movies SET title='".$title."',year=".$year." WHERE id_movie=7";
        $dsn = 'mysql:host=localhost;dbname=videoteca';
        $db = new PDO($dsn, 'root', '');
        $statement = $db->prepare($sql);
        
        if ($statement->execute()) {
        $error_msg = $sql;
            //$error_msg = 'ok';
        }else{
            $error_msg = "errore nell'upload";
        }
        
    } else {
        $error_msg = 'ERROR LIST:<br />';
        foreach($errors as $key => $value) {
            $error_msg .=  $key . ': ' . $value . '<br />';
        }
    }
    
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Forms</title>
        <link rel="stylesheet" href="" />
        <style>
        body {
            font-family: sans-serif;
        }
        label {
            display: block;
        }
        .error {
            color: red;
            border-color: red;
        }
        </style>
    </head>
    <body>
        <h1>Form example</h1>

<?php 
        if ($is_submitted === true) {
            echo '<p class="error">' . $error_msg . '</p>';
        }
?>
 
        <form action="prova.php" method="post">
            <p>
                <label>title:</label>
                <input type="text" name="title" id="title" autocomplete="false"  value="<?php echo $redisplay_title ?>" />
            </p>
            <p>
                <label>year:</label>
                <input type="text" name="year" id="year" autocomplete="false"  value="<?php echo $redisplay_year ?>" />
            </p>
            <input type="submit" name="my_btn" value="Send data" />

        </form>

    </body>
</html>
