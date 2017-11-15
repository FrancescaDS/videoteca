<?php
require_once 'functions/functions.php';

$name = "";
$surname = "";
$email = "";
$password = "";

$logged_in = FALSE;
$msg ="";
$class = "msgok";
$the_user = new UserService();

if (!is_null(filter_input(INPUT_POST, 'btn_register')) || !is_null(filter_input(INPUT_POST, 'btn_update'))) {
    $name = trim(filter_input(INPUT_POST, 'name'));
    $surname = trim(filter_input(INPUT_POST, 'surname'));
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
}


if (isset($_SESSION['logged_in'])){
    $logged_in = TRUE;
    if (!is_null(filter_input(INPUT_POST, 'btn_update'))) {
        $id = $_SESSION['user']['id_user'];
        if (!$the_user->existsEmail($email, $id)){
            if ($the_user->update($id, $email, $name, $surname, $password)){
                $_SESSION['user'] = $the_user->getData();
                $password = "";
                $msg = 'Dear '. htmlentities($name, ENT_QUOTES, 'utf-8')." your data has been updated";
            }else{
                $msg = 'Error during the update';
                $class = "msgerror";
            }
        } else {
            $msg = 'Sorry, this email is already in our DB';
            $class = "msgerror";
        }
    } else {
        $name = $_SESSION['user']['name'];
        $surname = $_SESSION['user']['surname'];
        $email = $_SESSION['user']['email'];
        $msg = 'Welcomeback '. htmlentities($name, ENT_QUOTES, 'utf-8');
    }   
} else {
    if (!is_null(filter_input(INPUT_POST, 'btn_register'))) {
        if (!$the_user->existsEmail($email)){
            if ($the_user->register($email, $password, $name, $surname)){
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $the_user->getData();
                header( 'Location: admin.php' ) ;
                //$msg = 'Welcome '. htmlentities($name, ENT_QUOTES, 'utf-8');
                //$logged_in = TRUE;
            } else {
                $msg = 'Error during the registration';
                $class = "msgerror";
            }
        } else {
            $msg = 'Sorry, your email is already in our DB';
            $class = "msgerror";
        }
    }
}

$name = htmlentities($name, ENT_QUOTES, 'utf-8');
$surname = htmlentities($surname, ENT_QUOTES, 'utf-8');
$email = htmlentities($email, ENT_QUOTES, 'utf-8');
$password = htmlentities($password, ENT_QUOTES, 'utf-8');

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMIN REGISTER - VIDEOTECA</title>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script type="text/javascript" src="includes/check_form.js"></script>
        <LINK rel="stylesheet" href='style.css'> 
    </head>
    
<body>
<?php include "includes/header.php"; ?>   
<?php if($logged_in) {
        include "includes/admin-top.php";
    }?>
<div class="main">
    
    <H1>ADMIN</H1>
    
    <?php if ($msg<>""){ ; ?>
    <div class="<?php echo $class; ?>">
    <?php echo $msg; ?>
    </div>
    <?php } ?>
    
    <?php if (!$logged_in) {?>    
        <form action="admin-register.php" id="form_register" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            </div>

            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
            </div>
        
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            
            <div class="form-group">
                <label for="password2">Confirm password</label>
                <input type="password" name="password2" class="form-control" value="">
            </div>

            <div class="form-group">
                <input type="submit" name="btn_register" value="Register" class="submit" class="form-control">
            </div>
        </form>
    
        <p><div>Have you already been registered? <a href="admin.php">Login</a></div></p>
    
    
    <?php } else { ?>
        
        <form action="admin-register.php" id="form_update" method="post">
            <div class="form-group">
                <label for="title">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            </div>

            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
            </div>
        
            <div class="form-group">
                <label for="title">Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label for="email">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            
            <div class="form-group">
                <label for="password2">Confirm password</label>
                <input type="password" name="password2" class="form-control" value="">
            </div>

            <div class="form-group">
                <input type="submit" name="btn_update" value="Update" class="submit" class="form-control">
            </div>
        </form>
    
        <?php include "includes/admin-links.php"; ?>
    <?php }  ?>    
    
</div>
        
<?php include "includes/footer.php"; ?>

</BODY>
</HTML>