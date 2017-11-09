<?php



?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Esempio di validazione con JQuery</title>
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script type="text/javascript" src="includes/check.js"></script>
  <!-- Custom styles -->
  <style>
    #form label.error {
        color: red;
        font-weight: bold;
    }
     
    .main {
        width: 600px;
        margin: 0 auto;
    }
  </style>
</head>
<body>
  <!-- Form container -->
  <div class="main">
    <h1>Movie</h1>
 
    <form action="#" id="my_form">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control">
      </div>
 
      <div class="form-group">
        <label for="email">Year</label>
        <input type="text" name="year" class="form-control">
      </div>
 
       
      <div class="form-group">
        <input type="submit" value="Insert/Update" class="submit" class="form-control">
      </div>
    </form>
 
  </div>
</body>
</html>