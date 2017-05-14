<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$error = '';
$people_string = '';
$total_score = '';
$total_opinions = '';
$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];

if(isset($_GET['city']) && isset($_GET['dateIni']) && isset($_GET['dateEnd']) && isset($_GET['type'])){
    $city=validate_parameter($mysqli,$_GET['city']);
    $date_ini=validate_parameter($mysqli,$_GET['dateIni']);
    $date_end=validate_parameter($mysqli,$_GET['dateEnd']);
    $type=validate_parameter($mysqli,$_GET['type']);
    $sql = "SELECT * FROM objects WHERE objectCity = '$city' and objectType = '$type'";
    $result = mysqli_query($mysqli,$sql);
}
else{
    $error = 'Values are not correct';
}

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
<!--     <header id="header" class="okayNav-header">
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
    </header> -->
    <main>
        <div class="div-outer">
            <div class="div-inner">
                <div class="error"><?php echo $error; ?></div>
                <div class="div-title">
                    <h1><?php echo $city; ?></h1>
                </div>
            </div>
        </div> 
        <?php if(mysqli_num_rows($result) > 0){
            while($object_result = mysqli_fetch_assoc($result)){
                $object_id = $object_result["objectId"];
                $object_price = $object_result["objectPrice"];
                $sql_people = "SELECT * FROM visits WHERE objectId = '$object_id' and userId != '$id' and  ((visitStart between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y')) or (visitEnd between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y')) or (STR_TO_DATE('$date_ini', '%d/%m/%Y') between visitStart and VisitEnd) or (STR_TO_DATE('$date_end', '%d/%m/%Y') between visitStart and visitEnd))";
                $people_result = mysqli_query($mysqli,$sql_people);
                $num_persons = mysqli_num_rows($people_result);
                if ($num_persons == 0){  
                    $people_string = "(No one will be there during these days)";
                }
                elseif($num_persons == 1){
                    $people_string = "(1  person will be there during these days)";
                }
                else {
                    $people_string = "($num_persons persons will be there during these days)";
                }

                $sql_opinions = "SELECT COALESCE(sum(score), 0) AS total_score, COUNT(*) AS total_opinions FROM opinions WHERE objectId = $object_id";
                $opinions_result = mysqli_query($mysqli,$sql_opinions);
                $opinions_result = mysqli_fetch_assoc($opinions_result); 
                $total_opinions = $opinions_result["total_opinions"];
                if($total_opinions == 0){
                    $total_score = "-";
                    $total_opinions = "No opinions found";
                }
                else{
                    $total_score = round($opinions_result["total_score"] / $total_opinions, 1);
                    $total_opinions = "$total_opinions opinions";
                }
                
        ?>
                <div class="div-outer">
                    <div id=<?php echo $object_id; ?> class="div-inner clickable-object">
                        <h3 class="inline-element" style="margin-bottom: 5px"><?php echo $object_result["objectName"]; ?></h3>
                        <p class="inline-element"><?php echo $people_string; ?></p>
                        <p><?php echo $object_result["objectAddress"]; ?></p>
                        <p class="btn-score"><?php echo $total_score; ?></p>
                        <p class="inline-element"><?php echo $total_opinions; ?></p>
                        <p class="inline-element" style="line-height: 3; float: right" align="right"><?php echo "From $object_price €"; ?></p>
                    </div>
                </div>
        <?php   } ?>
        <?php } else {?>  
            <div class="div-outer">
                <div class="div-inner">
                    <h3 class="error">No results found</h3>
                </div>
            </div>           
        <?php } ?>
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
        $(".clickable-object").click(function (evt) {
            window.location = "object_profile?id=" + evt.target.id + "&dateIni=" + "<?php echo $date_ini; ?>" + "&dateEnd=" + "<?php echo $date_end; ?>";
        });
    </script>
</body>
</html>