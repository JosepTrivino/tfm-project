<?php

  define( 'SEND_TO_HOME', true );
  require( 'includes/headers.php' );
  include("includes/config.php");
  include('includes/functions.php');

  $error=''; // error message

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email=validate_parameter($mysqli,$_POST['email']);
    $password=validate_parameter($mysqli,$_POST['password']); 

    $sql = "SELECT userId FROM users WHERE userEmail = '$email' and userPass = '".md5($password)."'";
    $result = mysqli_query($mysqli,$sql);
    $count =  mysqli_num_rows($result);
    $result_fetch = mysqli_fetch_assoc($result);

    if($count == 1) {
      session_start();
      $_SESSION['login_user'] = $email;
      $_SESSION['login_id'] = $result_fetch["userId"];
      header("location: search.php");
      }else {
        $error = "Incorrect email or password";
      }
   }
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Form</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
  <script src="applications/bootstrap/js/bootstrap.js"></script>
  <link rel="stylesheet" href="css/default.css">
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <div class="login">
    <div class="login-screen">
      <div class="login-title">
        <h1>Login</h1>
      </div>
      <form class="login-form" action="" method="post">
        <img src="images/logo.jpg" alt="Logo Icon"  height="150" width="150">
        <div class="control-group">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="control-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="control-group error"><?php echo $error; ?></div>
        <button class="btn" type="Submit">LOGIN</button><br/>
      </form>
      <a class="link link-login" href="signin.php" align="middle">Create a new user</a>
      <a class="link link-login" href="help.php" align="right">Help</a>
    </div>
  </div>
</body>
</html>
