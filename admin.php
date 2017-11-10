<?php
require_once 'functions/functions.php';

$email = "";
$password = "";
$logged_in = FALSE;
$msg ="";

if (isset($_SESSION['logged_in'])){
    if (!is_null(filter_input(INPUT_POST, 'btn_logout')))  {
        UserLogOut();
    } else {
        $logged_in = TRUE;
    }
} else {
    if (!is_null(filter_input(INPUT_POST, 'btn_login'))) {
        $email = trim(filter_input(INPUT_POST, 'email'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        
        $the_user = new UserService();
        if ($the_user->login($email, $password)){
            $msg = 'Welcome back '. $the_user->getData()['name'];
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $the_user->getData();
            $logged_in = TRUE;
            $name = $the_user->getData()['name'];
            $msg = 'Welcome back '. htmlentities($name, ENT_QUOTES, 'utf-8');
        } else {
            $msg = 'Sorry, your credentials are invalid';
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

<div class="main">
    <?php echo $msg; ?>
    <H1>ADMIN</H1>
    
    <?php if(!$logged_in) {?>    
        <form action="admin.php" id="form_login" method="post">
            <div class="form-group">
                <label for="title">Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label for="email">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>

            <div class="form-group">
                <input type="submit" name="btn_login" value="Login" class="submit" class="form-control">
            </div>
        </form>

        <div>New user?</div>
        <form action="admin-register.php" id="form_go_register" method="post">
            <div class="form-group">
                <input type="submit" name="btn_go_register" value="Register" class="submit" class="form-control">
            </div>
        </form>
        
    <?php } else { ?>
        
        <form action="admin.php" id="form_logout" method="post">
            <div class="form-group">
                <input type="submit" name="btn_logout" value="Logout" class="submit" class="form-control">
            </div>
        </form>
        <?php include "includes/admin-links.php"; ?>
    <?php }  ?>    
    
</div>
        
<?php include "includes/footer.php"; ?>

</BODY>
</HTML>