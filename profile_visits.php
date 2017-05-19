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
  $visitId = $_POST['visitId'];
  $sql = "DELETE FROM visits WHERE visitId = $visitId";
  $result = mysqli_query($mysqli,$sql);
  if($result) {
    $success = "The visit has been deleted.";
  }
  else{
    $error = "Unexpected error. Try again later."; 
  }
}

$profile_result = select_user_id($mysqli, $id);

$sql = "SELECT * FROM visits WHERE userId = '$id' ORDER BY visitStart DESC";
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
          <img src="<?php echo $profile_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image"/>
          <h1> <?php echo $profile_result["userName"]; echo " "; echo $profile_result["userLastName"]; ?></h1>
        </div>

        <ul class="nav nav-pills nav-justified" style="background-color: #3498DB">
          <li><a href="profile_information.php">Description</a></li>
          <li><a href="profile_messages.php">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li><a href="profile_opinions.php">Opinions</a></li>
          <li class="active"><a data-toggle="tab" href="#visits">Visits</a></li>
        </ul>
        <div class="tab-content div-outer" style="background-color: white">
            <div class="success"><?php echo $success; ?></div>
            <div class="error"><?php echo $error; ?></div>
            <?php
              if(mysqli_num_rows($result) > 0){
                while($visits_result = mysqli_fetch_assoc($result)){
                  $date_ini = date_format(date_create($visits_result["visitStart"]),"d/m/Y");
                  $date_end = date_format(date_create($visits_result["visitEnd"]),"d/m/Y");
                  $object_id = $visits_result['objectId'];
                  $sql_object = "SELECT objectName FROM objects WHERE objectId = $object_id";
                  $result_object = mysqli_query($mysqli,$sql_object);
                  $result_object = mysqli_fetch_assoc($result_object);
            ?>
                   <div class="div-inner">
                    <form class="float-right" style="display:inline-block" action="" method="post">
                      <button class="btn btn-user" style="margin-left:0px; max-width:100px;" name="visitId" value="<?php echo $visits_result["visitId"];?>" type="Submit" align="right">Delete</button>
                    </form>
                    <p><strong>Name: </strong><a class="link" href="object_profile.php?id=<?php echo $visits_result["objectId"];?>"> <?php echo $result_object["objectName"];?></a></p>
                    <p style="display:inline-block"><strong>From </strong><?php echo $date_ini;?></p>
                    <p style="display:inline-block"><strong> to </strong><?php echo $date_end;?></p>
                    </div><hr>
            <?php }
              } else { ?>
                <div class="error">No visits found</div>
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