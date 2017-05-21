<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$error=''; // error message

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

if(isset($_GET['id']) && $_GET['id'] != ''){
    $result = select_object_id($mysqli,$_GET["id"]);
    if($result && mysqli_num_rows($result) != 0){
        $object_result = mysqli_fetch_assoc($result);
    } else {
        header("location: error_page.php");
    }
} else {
    header("location: error_page.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $rate=validate_parameter($mysqli,$_POST['rate']);
    $opinion=validate_parameter($mysqli,$_POST['opinion']);
    $error=insert_opinion($mysqli, $id, $_GET['id'], $rate, $opinion);
} else {
    $_SESSION['history'] = $_SERVER['HTTP_REFERER'];
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New opinion</title>
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
        <div class="div-outer">
            <div class="div-inner">
                <form action="" method="post">
                    <div class="div-title">
                        <h1 style="text-align: center">Opinion <?php echo $object_result["objectName"];?></h1>
                    </div>
                    <div class="control-group">
                        <p class="inline-element"><strong>Rating from 1-10:</strong></p>
                        <input class="inline-element" type="text" name="rate" id="rate" min="1" max="10" required style="max-width: 40px"></input>
                    </div>
                    <div class="control-group">
                        <p><strong>Opinion:</strong></p>
                        <textarea rows=5 style="width:100%" name="opinion" id="opinion" required></textarea>
                    </div>
                    <div class="button-div">
                        <div class="control-group error"><?php echo $error; ?></div>
                        <button class="btn" type="Submit" align="right">SUBMIT</button>
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