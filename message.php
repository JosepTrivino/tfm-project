<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$error=''; // error message

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

if(isset($_GET['id']) && $_GET['id'] != ''){
    $user_result = select_user_id($mysqli,$_GET["id"]);
    if($user_result == 0){
        header("location: error_page.php");
    }
} else {
    header("location: error_page.php");
}

$profile_result = select_user_id($mysqli,$id); 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $rxId=validate_parameter($mysqli,$_POST['userRxId']);
    $txId=validate_parameter($mysqli,$_POST['userTxId']);
    $message=validate_parameter($mysqli,$_POST['message']);
    $title=validate_parameter($mysqli,$_POST['title']);
    if($_SESSION['login_id'] == $txId){
        $error = insert_message($mysqli, $txId, $rxId, $title, $message);
    }
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
        <a onClick="history.go(-1);" href="#"><img src="images/back.png" alt="Back button" style="max-width: 50px; margin-top:0px; margin-left: 20px;"/></a>
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
        <div class="div-outer">
            <div class="div-inner">
                <form action="" method="post">
                    <div class="div-title">
                        <h1 style="text-align: center">New message</h1>
                    </div>
                    <div class="control-group">
                        <p ><strong>From:</strong> <?php echo $profile_result["userName"]; echo " "; echo $profile_result["userLastName"];?></p>
                        <p><strong>To:</strong> <a href="profile_user_information.php?id=<?php echo $user_result["userId"];?>" class="link"> <?php echo $user_result["userName"]; echo " "; echo $user_result["userLastName"];?></a></p>
                        <input type="hidden" name="userRxId" value="<?php echo $user_result["userId"]?>"/>
                        <input type="hidden" name="userTxId" id="userTxId" value="<?php echo $profile_result["userId"];?>"/>
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