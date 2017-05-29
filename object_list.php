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
$which = '';

if(isset($_GET['city']) && isset($_GET['dateIni']) && isset($_GET['dateEnd']) && isset($_GET['type'])){
    $city = ucfirst(strtolower(validate_parameter($mysqli,$_GET['city'])));
    $date_ini=validate_parameter($mysqli,$_GET['dateIni']);
    $date_end=validate_parameter($mysqli,$_GET['dateEnd']);
    $type=validate_parameter($mysqli,$_GET['type']);
    $result = select_object_city($mysqli,$city, $type);
    if($type == 0){
        $which = 'Hostels in';
    } else {
        $which = 'Activities in';
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
    <title>List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="applications/bootstrap/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="applications/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/searcher.css" >
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="link" href="search.php">Search</a></li>
                  <li class="breadcrumb-item active">List</li>
                </ol>
                <div class="error"><?php echo $error; ?></div>
                <div style="text-align: center" class="div-title">
                    <h1 style="display: inline-block;"><?php echo $which; echo " "; echo $city;?></h1>
                    <p style="display: inline-block;"><?php echo "(".mysqli_num_rows($result). " found)";?> </p>
                </div>
                <div class="control-group">
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
            </div>
        </div> 
        <?php if(mysqli_num_rows($result) > 0){
            while($object_result = mysqli_fetch_assoc($result)){
                $object_id = $object_result["objectId"];
                $object_price = $object_result["objectPrice"];
                $num_persons = mysqli_num_rows(select_visits($mysqli, $object_id, $date_ini, $date_end));
                if ($num_persons == 0){  
                    $people_string = '(No one will be there during these days)';
                } elseif($num_persons == 1){
                    $people_string = '(1  traveler will be there during these days)';
                } else {
                    $people_string = '('.$num_persons.' travelers will be there during these days)';
                }

                $total_opinions = count_total_opinions($mysqli, $object_id);
                if($total_opinions == 0){
                    $total_score = "-";
                    $total_opinions = 'No opinions found';
                } else{
                    $total_score = round(count_mean_score($mysqli, $object_id) / $total_opinions, 1);
                    if($total_opinions == 1){
                        $total_opinions = "$total_opinions opinion";
                    } else{
                        $total_opinions = "$total_opinions opinions";
                    }
                }
                
        ?>
                <div class="div-outer">
                    <div id=<?php echo $object_id; ?> class="div-inner clickable-object">
                        <h3 class="inline-element" style="margin-bottom: 5px"><?php echo $object_result["objectName"]; ?></h3>
                        <p class="inline-element"><?php echo $people_string; ?></p>
                        <p><?php echo $object_result["objectAddress"]; ?> <a name="map" href="http://www.google.com/maps/place/<?php echo $object_result["latitude"]?>,<?php echo $object_result["longitude"]?>" class="material-icons icon link" style="margin-bottom: 20px; max-width: 160px; text-decoration: none">map</a></p>
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
            window.location = "object_profile.php?id=" + $(this).attr('id') + "&dateIni=" + "<?php echo $date_ini; ?>" + "&dateEnd=" + "<?php echo $date_end; ?>";
        });
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