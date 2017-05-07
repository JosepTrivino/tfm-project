<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$error=''; // error message
$success=''; // success message
$errorNoMessages='';
$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

$sql = "SELECT * FROM users WHERE userId = '$id'";
$result = mysqli_query($mysqli,$sql);
$profile_result = mysqli_fetch_assoc($result);

$userid = $profile_result["userId"];
$sql = "SELECT * FROM messages WHERE userRxId = '$userid' || userTxId = '$userid' ORDER BY messageDate DESC";
$result = mysqli_query($mysqli,$sql);
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
          <li><a href="profile_information.php">Description</a></li>
          <li class="active"><a data-toggle="tab" href="#messages">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li><a data-toggle="tab" href="#opinions">Opinions</a></li>
          <li><a data-toggle="tab" href="#visits">Visits</a></li>
        </ul>
        <div class="tab-content" style="background-color: white; margin: 20px">
            <?php
              if(mysqli_num_rows($result) > 0){
                while($messages_result = mysqli_fetch_assoc($result)){
                  $date = date_format(date_create($messages_result["messageDate"]),"d/m/Y");
                  echo '<div style="max-width:600px;">';
                  if($messages_result['userTxId'] == $_SESSION['login_id']){
                        echo '<p><strong>From:</strong> '.$messages_result["userTxName"].'</p>
                        <p><strong>To:</strong> <a class="link" href="profile_user.php?id='.$messages_result["userRxId"].'">'.$messages_result["userRxName"].'</a></p>';
                  }
                  else{
                        echo '<p><strong>From:</strong> <a class="link" href="profile_user.php?id='.$messages_result["userTxId"].'"> '.$messages_result["userTxName"].'</a></p>
                        <p><strong>To:</strong> '.$messages_result["userRxName"].'</p>';                  
                  }
                  echo'
                        <p><strong>Date:</strong> '.$date.'</p>
                        <p><strong>Title:</strong> '.$messages_result["messageTitle"].'</p>
                        <textarea readonly="readonly" class="blocked" rows="5" cols="50" style="width:600px;">'.$messages_result["messageText"].'</textarea>';
                  if($messages_result['userTxId'] != $_SESSION['login_id']){
                    echo '<a class="link link-answer" href="message.php?id='.$messages_result['userTxId'].'" align="right">Answer</a>';
                  }
                    echo'</div><hr>';
                }
              }
              else{
                echo'<div class="control-group error">You have not have any message</div>';
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