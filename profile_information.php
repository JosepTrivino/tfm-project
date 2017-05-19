<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$error=''; // error message
$error_upload=''; // error message
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
    $error_upload = update_profile_image($mysqli,$id,$_FILES["imageupload"]);
  }

  $result = update_user($mysqli, $id, $name, $lastname, $dateofbirth, $gender, $country, $city, $information);
  if($result) {
    $success = "The information has been updated";
  } else {
    $error = "Unexpected error"; 
  }
}

$profile_result = select_user_id($mysqli, $id);
$date = date_format(date_create($profile_result["userDBirth"]),"d/m/Y");

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
          <img src="<?php echo $profile_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image"/>
          <h1> <?php echo $profile_result["userName"]; echo " "; echo $profile_result["userLastName"]; ?></h1>
        </div>
        <ul class="nav nav-pills nav-justified" style="background-color: #3498DB">
          <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
          <li><a href="profile_messages.php">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li><a href="profile_opinions.php">Opinions</a></li>
          <li><a href="profile_visits.php">Visits</a></li>
        </ul>
        <div class="tab-content" style="background-color: white; margin: 20px;">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="error"><?php echo $error; ?></div>
              <div class="success"><?php echo $success; ?></div>
              <section>
                <div class="item-profile">
                  <label for="name">Name</label>
                  <input type="text" required name="name" placeholder="Name" value="<?php echo $profile_result["userName"];?>" />
                </div>
                <div class="item-profile">
                  <label for="lastname">Lastname</label>
                  <input type="text" name="lastname" required placeholder="Lastname" value="<?php echo $profile_result["userLastName"];?>" />
                </div>
                <div class="item-profile">
                  <label for="dateofbirth">Date of birth</label>
                  <input type="text" required name="dateofbirth" pattern="\d{1,2}/\d{1,2}/\d{4}" placeholder="DD/MM/YYYY" value="<?php echo $date;?>" />
                </div>
                <div class="item-profile-right">
                  <label for="email">Email</label>
                  <input readonly="readonly" class="blocked" type="email" name="email" value="<?php echo $profile_result["userEmail"];?>" />
                </div>
                <br style="clear:both;"/>
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
                  <input type="text" name="city" id="city" value="<?php echo $profile_result["userCity"];?>" />
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
                  <div class="error"><?php echo $error_upload; ?></div>
                </div>
                <br style="clear:both;" />
              </section>
              <section>
                <div class="item-profile">
                  <label for="information" >My information</label>
                   <textarea rows="5" cols="50" name="information" id="information"><?php echo $profile_result["userDescription"]?></textarea>
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