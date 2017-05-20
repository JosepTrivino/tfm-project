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

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = delete_opinion($mysqli, $_POST['opinionId']);
  if($result) {
    $success = "The opinion has been deleted.";
  }
  else{
    $error = "Unexpected error. Try again later."; 
  }
}

$profile_result = select_user_id($mysqli, $id);
$result = select_opinion_id($mysqli,$id);

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
          <li><a href="profile_messages.php">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li class="active"><a data-toggle="tab" href="#opinions">Opinions</a></li>
          <li><a href="profile_visits.php">Visits</a></li>
        </ul>
        <div class="tab-content div-outer" style="background-color: white">
            <div class="success"><?php echo $success; ?></div>
            <div class="error"><?php echo $error; ?></div>
            <?php
              if(mysqli_num_rows($result) > 0){
                while($opinions_result = mysqli_fetch_assoc($result)){
                  $date = date_format(date_create($opinions_result["opinionDate"]),"d/m/Y");
                  $result_object = select_object_id($mysqli, $opinions_result['objectId']);
                  $result_object = mysqli_fetch_assoc($result_object);
            ?>
                    <div class="div-inner">
                    <form class="float-right" style ="margin-right:20px" action="" method="post">
                    <button class="btn btn-user" name="opinionId" value="<?php echo $opinions_result["opinionId"];?>" type="Submit" >Delete</button>
                    </form>
                    <p><strong>Name:</strong> <a class="link" href="object_profile.php?id=<?php echo $opinions_result["objectId"];?>"><?php echo $result_object["objectName"];?> </a></p>
                    <p><strong>Rating:</strong> <?php echo $opinions_result["score"];?></p>
                    <p><strong>Date:</strong> <?php echo $date;?></p>
                    <textarea readonly="readonly" class="blocked" rows="5" cols="50" style="width:600px;"><?php echo $opinions_result["opinionText"];?></textarea>
                    </div><hr>
              <?php } 
                } else { ?>
                <div class="error">No opinions found</div>
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