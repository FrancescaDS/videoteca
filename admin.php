<?php
require_once 'functions/functions.php';

$email = "";
$password = "";
$logged_in = FALSE;
$msg ="";
$class = "msgok";
$the_user = new UserService();

if (isset($_SESSION['logged_in'])){
    if (!is_null(filter_input(INPUT_GET, 'logout')))  {
        $the_user->logout();
    } else {
        $logged_in = TRUE;
    }
} else {
    if (!is_null(filter_input(INPUT_POST, 'btn_login'))) {
        $email = trim(filter_input(INPUT_POST, 'email'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        
        if ($the_user->login($email, $password)){
            $msg = 'Welcome back '. $the_user->getData()['name'];
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $the_user->getData();
            $logged_in = TRUE;
            $name = $the_user->getData()['name'];
            $msg = 'Hello '. htmlentities($name, ENT_QUOTES, 'utf-8');
        } else {
            $msg = 'Sorry, your credentials are invalid';
            $class = "msgerror";
        }
    }
}

$email = htmlentities($email, ENT_QUOTES, 'utf-8');
$password = htmlentities($password, ENT_QUOTES, 'utf-8');

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMIN LOGIN - VIDEOTECA</title>
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
    
    <?php if(!$logged_in) {?>    
        <form action="admin.php" id="form_login" method="post">
            <div class="form-group">
                <label for="title">Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            
            <div class="form-group">
                <input type="submit" name="btn_login" value="Login" class="submit" class="form-control">
            </div>
        </form>

    <p><div>New user? <a href="admin-register.php">Register</a></div></p>
        
    <?php } else { ?>
        <?php include "includes/admin-links.php"; ?>
    <?php }  ?>    
    
</div>
        
<?php include "includes/footer.php"; ?>

</BODY>
</HTML>