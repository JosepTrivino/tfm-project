<?php
  include("includes/config.php");
  include('includes/functions.php');

  $error=''; // error message

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = validate_parameter($mysqli,$_POST['email']);
    $password = validate_parameter($mysqli,$_POST['password']);
    $name = validate_parameter($mysqli,$_POST['name']);
    $lastname = validate_parameter($mysqli,$_POST['lastname']);
    $dateofbirth = validate_parameter($mysqli,$_POST['dateofbirth']);
    $gender = validate_parameter($mysqli,$_POST['gender']);

    $error = insert_user($mysqli, $email, $password, $name, $lastname, $dateofbirth, $gender);

  }
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Signin Form</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
  <script src="applications/bootstrap/js/bootstrap.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="css/default.css">
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <div class="login">
    <div class="login-screen">
      <div class="login-title">
        <h1>Create a new user</h1>
      </div>
      <form class="login-form" action="" method="post">
        <div class="control-group">
          <input type="text" name="name" placeholder="Name" required>
        </div>
        <div class="control-group">
          <input type="text" name="lastname" placeholder="Lastname" required>
        </div>
        <div class="control-group">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="control-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="control-group">
          <input class="datepicker readonly" placeholder="Date of birth (DD/MM/YYYY)" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="dateofbirth" id="date" required>
        </div>
        <div class="control-group">
          <select name="gender" required>
             <option value="" disabled selected>Gender</option>
             <option value="M">Male</option> 
             <option value="F">Female</option> 
             <option value="O">Others</option> 
          </select>
        </div>
        <div class="control-group error"><?php echo $error; ?></div>
        <button class="btn" type="Submit">CREATE</button>
      </form>
      <a class="link link-login" href="help.php" align="right">Help</a>
    </div>
  </div>
  <script type="text/javascript">
    $(".readonly").keydown(function(e){
      e.preventDefault();
    });
    $( function() {
      $( ".datepicker" ).datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: new Date(1900,1-1,1), maxDate: '-18Y',
        changeYear: true,
        changeMonth: true
      });
    } );
  </script>
</body>
</html>
