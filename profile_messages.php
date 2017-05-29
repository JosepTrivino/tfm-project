<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$error=''; // error message
$success=''; // success message
$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

$profile_result = select_user_id($mysqli, $id);
$result = select_messages_by_id($mysqli,$id);
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
          <li class="active"><a data-toggle="tab" href="#messages">Messages</a></li>
          <li><a href="profile_friends.php">Friends</a></li>
          <li><a href="profile_opinions.php">Opinions</a></li>
          <li><a href="profile_visits.php">Visits</a></li>
        </ul>
        <div class="tab-content div-outer" style="background-color: white">
            <?php
              if(mysqli_num_rows($result) > 0){
                while($messages_result = mysqli_fetch_assoc($result)){
                  $date = date_format(date_create($messages_result["messageDate"]),"d/m/Y");
            ?>
                  <div class="div-inner">
                  <?php if($messages_result['userTxId'] != $_SESSION['login_id']){ ?>
                    <a class="link float-right" href="message.php?id=<?php echo $messages_result['userTxId'];?>">Answer</a>
                  <?php } ?>
                  <?php if($messages_result['userTxId'] == $_SESSION['login_id']){ 
                    $result_user = select_user_id($mysqli,$messages_result['userRxId']);
                    ?>
                        <p><strong>From:</strong> <?php echo $profile_result["userName"];?></p>
                        <p><strong>To:</strong> <a class="link" href="profile_user_information.php?id=<?php echo $messages_result["userRxId"];?>"> <?php echo $result_user["userName"];?></a></p>
                  <?php } else { 
                    $result_user = select_user_id($mysqli,$messages_result['userTxId']);
                    ?>
                        <p><strong>From:</strong> <a class="link" href="profile_user_information.php?id=<?php echo $messages_result["userTxId"];?>"> <?php echo $result_user["userName"];?></a></p>
                        <p><strong>To:</strong> <?php echo $profile_result["userName"];?></p>               
                  <?php } ?>
                        <p><strong>Date:</strong> <?php echo $date;?></p>
                        <p><strong>Title:</strong> <?php echo $messages_result["messageTitle"];?></p>
                        <textarea readonly="readonly" class="blocked" rows="5" cols="50" style="width:600px;"><?php echo $messages_result["messageText"];?></textarea>
                    </div><hr>
                <?php } ?>
              <?php } else { ?>
                <div style="margin-left:20px" class="error">No messages found</div>
              <?php } ?>
        </div><hr>
    </main>
    <script type="text/javascript">
      var navigation = $('#nav-main').okayNav();
    </script>
    <script>
    </script>
</body>
</html>