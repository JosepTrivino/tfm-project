<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$error=''; // error message
$success=''; // success message
$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    if($password == $password2) {
        $password = md5($_POST['password']);
        $sql = "UPDATE users SET userPass='$password' WHERE userId='$id'";
        $result = mysqli_query ($mysqli, $sql);
        if($result) {
            $success = "The password has been updated";
        }
        else{
            $error = "Unexpected error"; 
        }
    }
    else{
        $error="Passwords don't match.";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuration</title>
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
                <li><a href="configuration.php" style="color:black;">Configuration</a></li>
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
                    <h1 style="text-align: center">Configuration</h1>
                </div>
                <div class="control-group" center="left">
                    <p><strong>Change password</strong></p>
                    <input placeholder="New password" type="password" name="password" required>
                    <input placeholder="Repeat new password" type="password" name="password2" required>
                    <button class="btn" type="Submit" align="right">CHANGE</button>
                </div>
                <div class="error"><?php echo $error; ?></div>
                <div class="success"><?php echo $success; ?></div>
            </form>
        </div>
      </div>
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
    </script>
</body>
</html>