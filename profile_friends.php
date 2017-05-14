<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

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
          <img src="<?php echo $profile_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image"/>
          <h1> <?php echo $profile_result["userName"]; echo " "; echo $profile_result["userLastName"]; ?></h1>
        </div>

      <ul class="nav nav-pills nav-justified" style="background-color: #3498DB">
        <li><a href="profile_information.php">Description</a></li>
        <li><a  href="profile_messages.php">Messages</a></li>
        <li class="active"><a data-toggle="tab" href="#friends">Friends</a></li>
        <li><a href="profile_opinions.php">Opinions</a></li>
        <li><a href="profile_visits.php">Visits</a></li>
      </ul>
      <div class="tab-content" style="background-color: white;">
        <?php
          if ($profile_result['userFriends'] != ','){
            $friends_array = explode(',', $profile_result['userFriends']);
        ?>
          <div id="container-friends">
          <?php
            foreach($friends_array as $friends){
              $sql_friend = "SELECT userId, userName, userLastName, userImage FROM users WHERE userId = '$friends'";
              $friends_result = mysqli_query($mysqli,$sql_friend);
              if(mysqli_num_rows($friends_result) > 0){
                $friends_result = mysqli_fetch_assoc($friends_result);
          ?>
                <a href="profile_user_information.php?id=<?php echo $friends_result["userId"];?>" class="link">
                    <figure>
                        <img src="<?php echo $friends_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image">
                        <figcaption><?php echo $friends_result["userName"]; echo " "; echo $friends_result["userLastName"];?></figcaption>
                    </figure>
                </a>
           <?php } ?>
          <?php } ?>
          </div>
          <?php }else{?>
            <div style="margin-left:20px" class="error">No friends found</div>
          <?php } ?>
      </div>
    </main>
    <script type="text/javascript">
      var navigation = $('#nav-main').okayNav();
    </script>
    <script>
    </script>
</body>
</html>