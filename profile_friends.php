<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$email = $_SESSION['login_user'];

$sql = "SELECT * FROM users WHERE userEmail = '$email'";
$result = mysqli_query($mysqli,$sql);
$profile_result = mysqli_fetch_assoc($result);
$profile_result['userFriends'] = "2,3";
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
                <li><a href="#">Configuration</a></li>
                <li><a href="#">Help</a></li>
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
        <li><a href="profile_information.php">Description</a></li>
        <li><a  href="profile_messages.php">Messages</a></li>
        <li class="active"><a data-toggle="tab" href="#friends">Friends</a></li>
        <li><a data-toggle="tab" href="#opinions">Opinions</a></li>
        <li><a data-toggle="tab" href="#visits">Visits</a></li>
      </ul>
      <div class="tab-content" style="background-color: white; margin-left: 20px; margin-right: 20px;">
        <?php
          if ($profile_result['userFriends'] != ''){
            $friends_array = explode(',', $profile_result['userFriends']);
            echo '<div id="container-friends">';
            foreach($friends_array as $friends){
              $sql_friend = "SELECT userName, userLastName, userImage FROM users WHERE userId = '$friends'";
              $result_friend = mysqli_query($mysqli,$sql_friend);
              if(mysqli_num_rows($result_friend) > 0){
                $result_friend = mysqli_fetch_assoc($result_friend);
                echo'
                <a href="#">
                    <figure>
                        <img src="'.$result_friend["userImage"].'" alt="Profile Image"  height="70" width="70" class="profile-image">
                        <figcaption class="link">'.$result_friend["userName"].' '.$result_friend["userLastName"].'</figcaption>
                    </figure>
                </a>';
              }
            }
            echo '</div';
          }
          else{
            echo'<div class="control-group error">You have not have any friend</div>';
          }
        ?>
      </div>
    </main>
    <script type="text/javascript">
      var navigation = $('#nav-main').okayNav();
    </script>
    <script>
    </script>
</body>
</html>