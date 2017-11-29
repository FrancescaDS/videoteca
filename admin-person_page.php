<?php
    require_once 'functions/functions.php';
    //$data_input = "31/05/2012";
    //$query = "INSERT INTO table VALUES('" . STR_TO_DATE($data_input, '%d/%m/%Y' )."')";

    $che_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (empty($che_id)){
        $che_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    }
    if (empty($che_id)){
        $che_id = 0;
    }
    $person = new Person($che_id);
    
    $msg = "";
    $done = "";
    $class = "msgok";
    
    if (!is_null(filter_input(INPUT_POST, 'btn_person'))) {
        $name = filter_input(INPUT_POST, 'name');
        $surtitle = filter_input(INPUT_POST, 'surname');
        $place = filter_input(INPUT_POST, 'place');
        $dob = filter_input(INPUT_POST, 'dob');
        $dod = filter_input(INPUT_POST, 'dod');
        if ($person->isUnique($name, $surname,  $dob)){
            $data = [
                'name' => $name,
                'surname' => $surname,
                'dob' => $dob,
                'place' => $place,
                'dod' => $dod
            ];
            if ($che_id === 0){
                $done = $person->insertUpdate($name, $surname, $dob, $place, $dod);
            } else {
                $done = $person->insertUpdate($name, $surname, $dob, $place, $dod);
            }
            if ($done){
                $msg = "Record inserted/updated";
            } else {
                $msg = "Error insert/update";
                $class = "msgerror";
            }
        } else {
            $msg = "This person (name+surname+dob) is already present in our db";
            $class = "msgerror";
        }
    }elseif (!is_null(filter_input(INPUT_POST, 'btn_image'))){
        $result = uploadImage($che_id, 'people');
        $msg = $result[1];
        if (!$result[0]){
            $class = "msgerror";
        }
    }
        
    if (isset($person->getData()['id_person'])){
        $che_id = $person->getData()['id_person'];
        $name = $person->getData()['name'];
        $surname = $person->getData()['surname'];
        $dob = $person->getData()['dob'];
        $place = $person->getData()['place'];
        $dod = $person->getData()['dod'];
    } else {
        $che_id = 0;
        $name = "";
        $surname = "";
        $dob = "";
        $place = "";
        $dod = "";
    }
    
    $name = htmlentities($name, ENT_QUOTES, 'utf-8');
    $surname = htmlentities($surname, ENT_QUOTES, 'utf-8');
    $place = htmlentities($place, ENT_QUOTES, 'utf-8');
    if ($dob <> ""){
        $dob_date = new DateTime($dob);
        $dob = $dob_date->format('d/m/Y');

        $dod_date = new DateTime($dod);
        if ($dod_date < $dob_date){
            $dod = "";
        }else{
            $dod = $dod_date->format('d/m/Y');
        }
    }
    
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMIN PERSON PAGE - VIDEOTECA</title>
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
    <H1>PERSON</H1>
        
    <?php if ($msg<>""){ ; ?>
    <div class="<?php echo $class; ?>">
    <?php echo $msg; ?>
    </div>
    <?php } ?>
    
        <form action="admin-person_page.php" id="form_person" method="post">
          <input type='hidden' name='id' value="<?php echo $che_id; ?>">
          <div class="form-group">
            <label for="name">Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
          </div>

          <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
          </div>
          
          <div class="form-group">
            <label for="dob">Date of birth</label>
            <input type="text" name="dob" class="form-control" value="<?php echo $dob; ?>">
          </div>
          
          <div class="form-group">
            <label for="place">Place of birth</label>
            <input type="text" name="place" class="form-control" value="<?php echo $place; ?>">
          </div>
          
          <div class="form-group">
            <label for="dod">Date of death</label>
            <input type="text" name="dod" class="form-control" value="<?php echo $dod; ?>">
          </div>

          <div class="form-group">
            <input type="submit" name="btn_person" value="Insert/Update person" class="submit">
          </div>
        </form>
    
    
    <!-- DIRECTOR-CAST-POSTER ONLY ID THE PERSON HAS A RECORD -->
    <?php
    if ($che_id<>0){ ?>
        
        <!-- POSTER START-->
        <div class="container_border" >
        <?php
        $img = "archive/people/".$che_id.".jpg";
        if (is_file($img)){ 
            echo "<div class='pic'><img src='".$img."'></div>";
        } ?>
            <div class='box_dx_top'>
                <form enctype="multipart/form-data" action="admin-person_page.php" id="form_image" method="post">
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