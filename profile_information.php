<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$error=''; // error message
$errorUpload=''; // error message
$success=''; // success message
$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $name=validate_parameter($mysqli,$_POST['name']);
  $lastname=validate_parameter($mysqli,$_POST['lastname']);
  $dateofbirth=validate_parameter($mysqli,$_POST['dateofbirth']);
  $gender=validate_parameter($mysqli,$_POST['gender']);
  $country=validate_parameter($mysqli,$_POST['country']);
  $city=validate_parameter($mysqli,$_POST['city']);
  $information=validate_parameter($mysqli,$_POST['information']);
  $target_file="";

  if($_FILES["imageupload"]["name"] != ""){
    $target_dir = "upload_pictures/";
    $target_file = $target_dir . basename($_FILES["imageupload"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["imageupload"]["tmp_name"]);
    $uploadOk = 1;
    if($check !== false) {
      $uploadOk = 1;
    } else {
      $uploadOk = 0;
    }
    if ($_FILES["imageupload"]["size"] > 500000) {
      $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $uploadOk = 0;
    }
    if ($uploadOk == 0) {
      $errorUpload = "Sorry, your image was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["imageupload"]["tmp_name"], $target_file)) {
        $sql = "UPDATE users SET userImage='$target_file' WHERE userId='$id'";
        $result = mysqli_query ($mysqli, $sql);
      } else {
        $errorUpload = "Sorry, there was an error uploading your file.";
      }
    }
  }

  $sql = "UPDATE users SET userName='$name', userLastName='$lastname', userDBirth=STR_TO_DATE('$dateofbirth', '%d/%m/%Y'), userGender='$gender', userCountry='$country', userCity='$city', userDescription='$information' WHERE userId='$id'";
  $result = mysqli_query ($mysqli, $sql);
  if($result) {
    $success = "The information has been updated";
  }
  else{
    $error = "Unexpected error"; 
  }
}

$sql = "SELECT * FROM users WHERE userId = '$id'";
$result = mysqli_query($mysqli,$sql);
$profile_result = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Personal profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
    <script src="applications/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/profile.css" >
    <link rel="stylesheet" href="applications/okayNav/css/header.css" media="screen">
    <link rel="stylesheet" href="applications/okayNav/css/normalize.css" media="screen">
    <link rel="stylesheet" href="applications/okayNav/css/okayNav-base.css" media="screen">
    <link rel="stylesheet" href="applications/okayNav/css/okayNav-theme.css" media="screen">
    <script src="applications/okayNav/js/jquery.okayNav.js"></script>
</head>
<body>
    <header id="header" class="okayNav-header">
        <a class="okayNav-header__logo">
           <img src="images/logo.jpg" alt="Logo Icon"  height="50" width="50">
        </a>
        <nav role="navigation" id="nav-main" class="okayNav">
            <ul>
                <li><a href="profile_information.php" style="color:black;">Profile </a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="configuration.php">Configuration</a></li>
                <li><a href="help.php">Help</a></li>
                <li><a href="includes/logout.php">Close session</a></li>
            </ul>
        </nav>
    </header>
    <main style="margin-top: 5rem; background-color: white">
        <div style="display:flex;align-items:center; background-color: #3498DB">
          <?php
            echo '<img src="'.$profile_result["userImage"].'" alt="Profile Image"  height="70" width="70" class="profile-image">';
            echo '<h1>'.$profile_result["userName"]." ".$profile_result["userLastName"]. '</h1>'; 
          ?>
        </div>
        <ul class="nav nav-pills nav-justified" style="background-color: #3498DB">
          <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
          <li><a href="profile_messages.php">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li><a data-toggle="tab" href="#opinions">Opinions</a></li>
          <li><a data-toggle="tab" href="#visits">Visits</a></li>
        </ul>
        <div class="tab-content" style="background-color: white; margin: 20px;">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="control-group error"><?php echo $error; ?></div>
              <div class="success"><?php echo $success; ?></div>
              <section>
                <div class="item-profile">
                  <label for="name">Name</label>
                  <?php echo '<input type="text" required name="name" placeholder="Name" value="'.$profile_result["userName"].'">'; ?>
                </div>
                <div class="item-profile">
                  <label for="lastname">Lastname</label>
                  <?php echo '<input type="text" name="lastname" required placeholder="Lastname" value="'.$profile_result["userLastName"].'"/>'; ?>
                </div>
                <div class="item-profile">
                  <label for="dateofbirth">Date of birth</label>
                  <?php 
                    $date = date_format(date_create($profile_result["userDBirth"]),"d/m/Y");
                    echo '<input type="text" required name="dateofbirth" "Date of birth (DD/MM/YYYY)" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="DD/MM/YYYY" value="'.$date.'">'; ?>
                </div>
                <div class="item-profile-right">
                  <label for="email">Email</label>
                  <?php echo '<input readonly="readonly" class="blocked" type="email" name="email" value='.$profile_result["userEmail"].'>'; ?>
                </div>
                <br style="clear:both;" />
              </section>
              <section>
                <div class="item-profile">
                  <label for="country">Contry</label>
                  <select name="country" id="country">
                    <?php
                      foreach($countries as $code => $country){
                          if($code == $profile_result["userCountry"]){
                              echo '<option value="'.$code.'" selected>'.$country.'</option>';
                          }else{
                              echo '<option value="'.$code.'">'.$country.'</option>';
                          }
                      }
                    ?>
                  </select>
                </div>
                <div class="item-profile">
                  <label for="city">City</label>
                  <?php echo '<input type="text" name="city" id="city" value="'.$profile_result["userCity"].'">'; ?>
                </div>
                <div class="item-profile-right">
                  <label for="gender">Gender</label>
                  <select name="gender" id="gender">
                    <?php
                      foreach($genders as $code => $gender){
                          if($code == $profile_result["userGender"]){
                              echo '<option value="'.$code.'" selected>'.$gender.'</option>';
                          }else{
                              echo '<option value="'.$code.'">'.$gender.'</option>';
                          }
                      }
                    ?>
                  </select>
                </div>
                <br style="clear:both;" />
              </section>
              <section>
                <div class="item-profile">
                  <label for="imageupload" >Upload image</label>
                  <input type="file" name="imageupload" id="imageupload">
                  <div class="error"><?php echo $errorUpload; ?></div>
                </div>
                <br style="clear:both;" />
              </section>
              <section>
                <div class="item-profile">
                  <label for="information" >My information</label>
                  <?php echo '<textarea rows="5" cols="50" name="information" id="information">'.$profile_result["userDescription"].'</textarea>'; ?>
                </div>
                <br style="clear:both;" />
              </section>
              <button class="btn " type="Submit">Update information</button>
              <br style="clear:both;" />
            </form>
        </div>
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
    </script>
</body>
</html>