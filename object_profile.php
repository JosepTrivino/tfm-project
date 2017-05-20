<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
include('includes/config.php');
include('includes/functions.php');

$email = $_SESSION['login_user'];
$id = $_SESSION['login_id'];
$error = '';
$total_score = '';
$total_opinions = '';
$error_total_opinions = '';
$error_travelers = '';
$error_dates = '';
$error_opinions = '';
$error_already_signon = '';
$error_post = '';
$success_post = '';
$people_result = '';
$people_found ='N';
$dates_found ='N';
$total_people = 0;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $object_id_post=validate_parameter($mysqli,$_GET['id']);
    $user_id_post=validate_parameter($mysqli,$id);
    $date_ini_post=validate_parameter($mysqli,$_GET['dateIni']);
    $date_end_post=validate_parameter($mysqli,$_GET['dateEnd']);
    $result = insert_visit($mysqli, $user_id_post, $object_id_post, $date_ini_post, $date_end_post);
    if($result) {
        $success_post = "You have successfully signed on";
    }
    else{
        $error_post = "Unexpected error. Try again later."; 
    }
}

if(isset($_GET['id']) && $_GET['id'] != ''){
    $object_id=validate_parameter($mysqli,$_GET['id']);
    $result = select_object_id($mysqli,$object_id);
    if($result && mysqli_num_rows($result) != 0){
        $object_result = mysqli_fetch_assoc($result);
        $total_opinions = count_total_opinions($mysqli, $object_id);
        if($total_opinions == 0){
            $total_score = "-";
            $error_total_opinions = 'No opinions found';
        } else {
            $total_score = round(count_mean_score($mysqli, $object_id) / $total_opinions, 1);
        }
        if(isset($_GET['dateIni']) && isset($_GET['dateEnd'])){
            $dates_found = 'S';
            $date_ini=validate_parameter($mysqli,$_GET['dateIni']);
            $date_end=validate_parameter($mysqli,$_GET['dateEnd']);
            if(select_user_signed($mysqli, $id, $object_id, $date_ini, $date_end) > 0){
                $error_already_signon = 'You have already signed on';
                $dates_found = 'N';
            }
            $people_result = select_visits($mysqli, $object_id, $date_ini, $date_end);
            $total_people = mysqli_num_rows($people_result);
            if($total_people > 0){
                $people_found = 'S';
            } else {
                $error_travelers = 'No one will be there during these days';
            }
        } else {
            $error_dates = 'To show this information select the dates';
        }
    } else {
        header("location: error_page.php");
    }
} else {
    header("location: error_page.php");
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Object profile</title>
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
        <div class="div-outer" style="max-width: 800px;">
            <div class="div-inner">
                <div class="error"><?php echo $error; ?></div>
                <div style="text-align: center" class="div-title">
                    <p class="float-right btn-score"><?php echo $total_score; ?></p>
                    <h1><?php echo $object_result["objectName"];?></h1>
                    <a class="btn" href="opinion.php?id=<?php echo $object_result["objectId"];?>">Give an opinion</a> 
                </div>
                <div style="margin-bottom: 40px; margin-top: 40px" class="control-group">
                    <div>
                        <input type="radio" value="0" checked="checked" name="optradio">Hostels</input>
                        <input type="radio" value="1" name="optradio">Activities </input>
                    </div>
                    <input style="max-width: 295px;" type="search" name="city" id="city" placeholder="Search city" required>
                    <label style="display:inline-block; margin-left: 10px" for="ini">From</label>
                    <input style="max-width: 120px;" placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="ini" id="ini" required>
                    <label style="display:inline-block; margin-left: 10px" for="end">To</label>
                    <input style="max-width: 120px;" placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="end" id="end" required>
                    <button class="btn" onclick="validate()" type="Submit" align="right">SEARCH</button>
                </div>
                <div id="valid-dates" class="error" hidden>Invalid dates</div>
                <div class="error"><?php echo $error_post; ?></div>
                <div class="success"><?php echo $success_post; ?></div>
                <hr/>
                <div class="vertical-line">
                    <a class="link margin-line" href="#description">Description</a>
                </div>
                <div class="vertical-line">
                    <a class="link margin-line" href="#opinions">Opinions</a>
                </div>
                <div class="vertical-line">
                    <a class="link margin-line" href="#travelers">Travelers</a>
                </div>
                <div class="vertical-line">
                    <a class="link margin-line" href="#price">Price</a>
                </div>
                <div style="display:inline-block;">
                    <a class="link margin-line" href="#signon">Sign on</a>
                </div>
                <hr/>
                <div>
                    <h3 id="description">Description</h3>
                    <p> <?php echo $object_result["objectText"];?></p>
                </div>
                <hr/>
                <div>
                    <h3 id="opinions">Opinions <?php echo " (".$total_opinions.")";?></h3>
                    <div class="error"><?php echo $error_total_opinions; ?></div>
                    <?php 
                        if($total_opinions > 0){
                            $opinions_result_show = select_opinions_limit($mysqli, $object_id);
                            while ($opinions_result = mysqli_fetch_assoc($opinions_result_show)){
                                $date = date_format(date_create($opinions_result["opinionDate"]),"d/m/Y");
                                $result_user = select_user_id($mysqli, $opinions_result['userId']);
                    ?>
                    <p class="btn-score-small"><?php echo $opinions_result["score"]; ?></p>
                    <p class="inline-element"><a class="link" href="profile_user_information.php?id=<?php echo $opinions_result["userId"];?>"><?php echo $result_user["userName"];?></a>, <?php echo $date;?></p>
                    <p><?php echo $opinions_result["opinionText"];?></p>
                    <?php } ?>
                            <a class="link link-login" align="right" href="opinions_list.php?id=<?php echo $object_id;?>">Show more</a>
                    <?php } ?>
                </div>
                <hr/>
                <div>
                    <h3 id="travelers" class="container-friends">Travelers <?php echo " (".$total_people.")";?></h3>
                    <div class="error"><?php echo $error_travelers; ?></div>
                    <div class="error"><?php echo $error_dates; ?></div>
                    <?php
                        if($people_found == 'S'){
                            while ($people_list = mysqli_fetch_assoc($people_result)){
                                $user_result = select_user_id($mysqli, $people_list['userId']);
                    ?>
                                <a href="profile_user_information.php?id=<?php echo $user_result["userId"];?>" class="link">
                                    <figure style="max-width: 100px">
                                        <img src="<?php echo $user_result["userImage"];?>" alt="Profile Image"  height="70" width="70" class="profile-image">
                                        <figcaption><?php echo $user_result["userName"];?></figcaption>
                                    </figure>
                                </a>                                
                            <?php } ?>
                        <?php } ?>
                </div>
                <hr/>
                <div>
                    <h3 id="price">Price</h3>
                    <p>From <?php echo $object_result["objectPrice"];?> €</p>
                </div>
                <hr/>
                <div style="margin-bottom: 40px;">
                    <h3 id="signon">Sign on</h3>
                    <div class="error"><?php echo $error_dates; ?></div>
                    <div class="error"><?php echo $error_already_signon; ?></div>
                    <?php if ($dates_found == 'S'){ ?>
                        <p class="inline-element"> From <strong><?php echo $date_ini;?></strong> to <strong><?php echo $date_end?></strong> </p>
                        <form class="float-right" style="display:inline-block" action="" method="post">
                          <button class="btn btn-user" onclick="validate()" type="Submit" align="right">Sign on</button>
                        </form>
                    <?php } ?>  
                </div>

            </div>
        </div> 
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
        function validate() {
            var date_ini = $('#ini').val();
            var date_end = $('#end').val();
            if ($.datepicker.parseDate('dd/mm/yy', date_ini) > $.datepicker.parseDate('dd/mm/yy', date_end)) {
                $('#valid-dates').show();
            }
            else{
                window.location = "object_list?city=" + $('#city').val() + "&dateIni=" + $('#ini').val() + "&dateEnd=" + $('#end').val() + "&type=" + $('input[name=optradio]:checked').val();
            }
        }
    </script>
</body>
</html>