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
            } else{
                $error_travelers = 'No one will be there during these days';
            }
        } else{
            $error_dates = 'To show this information select the dates';

        }
    } else {
        header("location: error_page.php");
    }
} else{
    header("location: error_page.php");
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Opinions list</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
    <script src="applications/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/searcher.css" >
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
        <div class="div-outer" style="max-width: 800px;">
            <div class="div-inner">
                <div style="text-align: center" class="div-title">
                    <p class="float-right btn-score"><?php echo $total_score; ?></p>
                    <h1>Opinions of <?php echo $object_result["objectName"];?></h1>
                    <a class="btn" href="opinion.php?id=<?php echo $object_result["objectId"];?>">Give an opinion</a> 
                </div>
                <div style="margin-bottom: 40px; margin-top: 40px" class="control-group">
                <form onSubmit="event.preventDefault(); validate();">
                    <div>
                        <input type="radio" value="0" checked="checked" name="optradio">Hostels</input>
                        <input type="radio" value="1" name="optradio">Activities </input>
                    </div>
                    <input style="max-width: 295px;" type="search" name="city" id="city" placeholder="Search city" required>
                    <label style="display:inline-block; margin-left: 10px" for="ini">From</label>
                    <input class="datepicker readonly" style="max-width: 120px;" placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="ini" id="ini" required>
                    <label style="display:inline-block; margin-left: 10px" for="end">To</label>
                    <input class="datepicker readonly" style="max-width: 120px;" placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="end" id="end" required>
                    <button class="btn" type="Submit" align="right">SEARCH</button>
                </form>
                </div>
                <div id="valid-dates" class="error" hidden>Invalid dates</div>
                <hr/>
                <div>
                    <?php 
                        $opinions_result_show = select_opinions_object($mysqli, $object_id);
                        while ($opinions_result = mysqli_fetch_assoc($opinions_result_show)){
                            $date = date_format(date_create($opinions_result["opinionDate"]),"d/m/Y");
                            $result_user = select_user_id($mysqli, $opinions_result['userId']);
                    ?>
                    <p class="btn-score-small"><?php echo $opinions_result["score"]; ?></p>
                    <p class="inline-element"><a class="link" href="profile_user_information.php?id=<?php echo $opinions_result["userId"];?>"><?php echo $result_user["userName"];?></a>, <?php echo $date;?></p>
                    <p><?php echo $opinions_result["opinionText"];?></p>
                    <hr/>
                    <?php } ?>
                </div>
            </div>
        </div> 
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
        $( function() {
          $( ".datepicker" ).datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: 'today'
          });
        });
        $(".readonly").keydown(function(e){
            e.preventDefault();
        });
        function validate() {
            var date_ini = $('#ini').val();
            var date_end = $('#end').val();
            if ($.datepicker.parseDate('dd/mm/yy', date_ini) > $.datepicker.parseDate('dd/mm/yy', date_end)) {
                $('#valid-dates').show();
            }
            else{
                window.location = "object_list.php?city=" + $('#city').val() + "&dateIni=" + $('#ini').val() + "&dateEnd=" + $('#end').val() + "&type=" + $('input[name=optradio]:checked').val();
            }
        }
    </script>
</body>
</html>