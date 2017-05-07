<?php
  include("includes/config.php");
  include('includes/functions.php');

  $error=''; // error message

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email=validate_parameter($mysqli,$_POST['email']);
    $password=validate_parameter($mysqli,$_POST['password']);
    $name=validate_parameter($mysqli,$_POST['name']);
    $lastname=validate_parameter($mysqli,$_POST['lastname']);
    $dateofbirth=validate_parameter($mysqli,$_POST['dateofbirth']);
    $gender=validate_parameter($mysqli,$_POST['gender']);

    //Check if the user exists
    $sql = "SELECT userId FROM users WHERE userEmail = '$email'";
    $result = mysqli_query($mysqli,$sql);
    $count = mysqli_num_rows($result);
    $result_fetch = mysqli_fetch_assoc($result);
    if($count == 0) { 
      $sql = "INSERT INTO users (userEmail,userPass,userName,userLastName,userDBirth,userGender,userImage,userFriends) VALUES ('$email','".md5($password)."','$name','$lastname', STR_TO_DATE('$dateofbirth', '%d/%m/%Y'),'$gender','images/profile',',')";
      $result = mysqli_query ($mysqli, $sql);
      if($result) {
        session_start();
        $sql = "SELECT userId FROM users WHERE userEmail = '$email'";
        $result = mysqli_query($mysqli,$sql);
        $result_fetch = mysqli_fetch_assoc($result);
        $_SESSION['login_user'] = $email;
        $_SESSION['login_id'] = $result_fetch["userId"];
        header("location: search.php");
      }
      else{
        $error = "Unexpected error"; 
      }
    }
    else {
     $error = "User already exists in the database"; 
    }
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
          <input placeholder="Date of birth (DD/MM/YYYY)" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="dateofbirth" id="date" required>
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
</body>
</html>
