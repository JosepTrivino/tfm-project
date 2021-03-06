<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/variables.php');
include('includes/functions.php');

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];
$friend_found = 0;
$error = '';
$success = '';

if(isset($_GET['id']) && $_GET['id'] != ''){
  $userId = $_GET["id"];

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(update_user_friends($mysqli, $id, $userId, $_POST['option'])) {
        if($_POST['option'] == "add"){
          $success = "The user has been added to your friend list.";
        } else {
          $success = "The user has been deleted from your friend list.";
        }
    } else {
      $error = "Unexpected error. Try again later."; 
    }
  }
  if($userId != $id){
    $user_result = select_user_id($mysqli, $userId);
    if($user_result != 0){
      $date = date_format(date_create($user_result["userDBirth"]),"d/m/Y");
      $profile_result = select_user_id($mysqli, $id);
      if($profile_result['userFriends'] != ''){
          $friends_array = explode(',', $profile_result['userFriends']);
          foreach($friends_array as $friends){
              if($friends == $userId){
                  $friend_found = 1;
                  break;
              }
          }
      }
    } else {
      header('Location: error_page.php');
    }
  } else {
    header('Location: profile_information.php');
  }
} else {
  header('Location: error_page.php');
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile user</title>

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
        <a onClick="history.go(-1);" href="#"><img src="images/back.png" alt="Back button" style="max-width: 50px; margin-top:0px; margin-left: 20px;"/></a>
        <nav role="navigation" id="nav-main" class="okayNav">
            <ul>
                <li><a href="profile_information.php">Profile </a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="configuration.php">Configuration</a></li>
                <li><a href="help.php">Help</a></li>
                <li><a href="includes/logout.php">Close session</a></li>
            </ul>
        </nav>
    </header>
    <main style="margin-top: 5rem; background-color: white">
      <div style="display:flex;align-items:center; background-color: #3498DB">
          <img src="<?php echo $user_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image"> </img>
          <h1><?php echo $user_result["userName"]; echo " "; echo $user_result["userLastName"];?></h1>
          <br style="clear:both;" />
      </div>
      <div style="background-color: #3498DB">
      <div style="margin-top:0px; margin-left: 20px" class="success"><?php echo $success; ?></div>
      <div style="margin-top:0px; margin-left: 20px" class="error"><?php echo $error; ?></div>
        <form action="" method="post">
          <?php if($friend_found == 0){?>
            <button class="btn btn-user" name="option" value="add" type="Submit" align="right">Add friend</button>
          <?php } else{?>
            <button class="btn btn-user" name="option" value="delete" type="Submit" align="right">Delete friend</button>
          <?php }?>
          <a class="btn btn-user" align="right" href="message.php?id=<?php echo $user_result['userId'];?>" align="right">Send message</a>
        </form>
      </div>
      <ul class="nav nav-pills nav-justified" style="background-color: #3498DB">
        <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
        <li><a href="profile_user_opinions.php?id=<?php echo $userId;?>">Opinions</a></li>
      </ul>
      <div class="tab-content" style="background-color: white;  margin: 20px;">
        <div id="description" class="tab-pane fade in active">
          <section>
            <div class="item-profile">
              <label for="name">Name</label>
              <input readonly="readonly" class="blocked" type="text" value="<?php echo $user_result["userName"];?>"/>
            </div>
            <div class="item-profile">
              <label for="lastname">Lastname</label>
              <input readonly="readonly" class="blocked" type="text" value="<?php echo $user_result["userLastName"];?>"/>
            </div>
            <div class="item-profile">
              <label for="dateofbirth">Date of birth</label>
              <input readonly="readonly" class="blocked" type="text" value="<?php echo $date;?>"/>
            </div>
            <div class="item-profile-right">
              <label for="gender">Gender</label>
              <?php
                foreach($genders as $code => $gender){
                  if($code == $profile_result["userGender"]){
                    echo '<input readonly="readonly" class="blocked" type="text" value="'.$gender.'">';
                  }
                }
              ?>
            </div>
            <br style="clear:both;" />
          </section>
          <section>
            <div class="item-profile">
              <label for="country">Country</label>
              <?php
              if($profile_result["userCountry"] == ""){
                echo '<input style="max-width:420px; width:100%;" readonly="readonly" class="blocked" type="text" name="country" id="country" value="">';
              } else {
                foreach($countries as $code => $country){
                  if($code == $profile_result["userCountry"]){
                    echo '<input style="max-width:420px; width:100%;" readonly="readonly" class="blocked" type="text" name="country" id="country" value="'.$country.'">';
                  }
                }
              }
              ?>
            </div>
            <div class="item-profile">
              <label for="city">City</label>
              <input readonly="readonly" class="blocked" type="text" name="city" id="city" value="<?php echo $profile_result["userCity"];?>" />
            </div>
            <br style="clear:both;" />
          </section>
          <section>
            <div class="item-profile">
              <label for="information" >My information</label>
              <textarea readonly="readonly" class="blocked" rows="5" cols="50" name="information" id="information"><?php $profile_result["userDescription"];?></textarea>
            </div>
            <br style="clear:both;" />
          </section>
          <br style="clear:both;" />
        </div><hr>
      </div>
    </main>
    <script type="text/javascript">
      var navigation = $('#nav-main').okayNav();
    </script>
    <script>
    </script>
</body>
</html>