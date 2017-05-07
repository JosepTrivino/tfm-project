<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$error=''; // error message

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];
$userId = $_GET["id"];

$sql = "SELECT * FROM users WHERE userId = '$userId'";
$result = mysqli_query($mysqli,$sql);
$user_result = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM users WHERE userId = '$id'";
$result = mysqli_query($mysqli,$sql);
$profile_result = mysqli_fetch_assoc($result); 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $rxId=validate_parameter($mysqli,$_POST['userRxId']);
    $txId=validate_parameter($mysqli,$_POST['userTxId']);
    $rxName=validate_parameter($mysqli,$_POST['userRxName']);
    $txName=validate_parameter($mysqli,$_POST['userTxName']);
    $message=validate_parameter($mysqli,$_POST['message']);
    $title=validate_parameter($mysqli,$_POST['title']);
    if($_SESSION['login_id'] == $txId){
        $sql = "INSERT INTO messages (userTxId,userRxId,userTxName,userRxName,messageTitle,messageText) VALUES ('$txId','$rxId','$txName','$rxName', '$title','$message')";
        $result = mysqli_query ($mysqli, $sql);
        if($result) {
            header("location: {$_SESSION['history']}");
        }
        else{
            $error="Message couldn't be send. Try later.";
        }
    }
}
else{
    $_SESSION['history'] = $_SERVER['HTTP_REFERER'];
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New message</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
    <script src="applications/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/searcher.css" >
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
                <li><a href="profile_information.php">Profile</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="configuration.php">Configuration</a></li>
                <li><a href="help.php">Help</a></li>
                <li><a href="includes/logout.php">Close session</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="searcher">
            <div class="searcher-screen">
                <form action="" method="post">
                    <div class="searcher-title">
                        <h1 style="text-align: center">New message</h1>
                    </div>
                    <div class="control-group">
                        <?php
                            echo '<p ><strong>From:</strong> '.$profile_result["userName"].' '.$profile_result["userLastName"].'</p>';
                            echo '<p><strong>To:</strong> <a href="profile_user.php?id='.$user_result["userId"].'" class="link"> '.$user_result["userName"].' '.$user_result["userLastName"].'</a></p>';
                            echo '<input type="hidden" name="userRxId" value="'.$user_result["userId"].'"/>';
                            echo '<input type="hidden" name="userTxId" id="userTxId" value="'.$profile_result["userId"].'"/>';
                            echo '<input type="hidden" name="userRxName" id="userRxName" value="'.$user_result["userName"].' '.$user_result["userLastName"].'"/>';
                            echo '<input type="hidden" name="userTxName" id="userTxName" value="'.$profile_result["userName"].' '.$profile_result["userLastName"].'"/>';
                        ?>
                    </div>
                    <div class="control-group">
                        <p><strong>Title:</strong></p>
                        <input type="text" name="title" id="title" required style="width:50%"></input>
                    </div>
                    <div class="control-group">
                        <p><strong>Message:</strong></p>
                        <textarea rows=5 style="width:100%" name="message" id="message" required></textarea>
                    </div>
                    <div class="button-div">
                        <div class="control-group error"><?php echo $error; ?></div>
                        <button class="btn" type="Submit" align="right">SEND</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
    </script>
</body>
</html>