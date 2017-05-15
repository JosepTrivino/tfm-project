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

if(isset($_GET['id'])){
    $object_id=validate_parameter($mysqli,$_GET['id']);
    $date_ini=validate_parameter($mysqli,$_GET['dateIni']);
    $date_end=validate_parameter($mysqli,$_GET['dateEnd']);
    $sql = "SELECT * FROM objects WHERE objectId = '$object_id'";
    $result = mysqli_query($mysqli,$sql);
    $object_result = mysqli_fetch_assoc($result);
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
        <div class="div-outer" style="max-width: 800px;">
            <div class="div-inner">
                <div class="error"><?php echo $error; ?></div>
                <div style="text-align: center" class="div-title">
                    <h1 style="display: inline-block;"><?php echo $object_result["objectName"];?></h1>
                </div>
                <div style="margin-bottom: 40px;" class="control-group">
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
                    <h3 id="opinions">Opinions</h3>
                </div>
                <hr/>
                <div>
                    <h3 id="travelers">Travelers</h3>
                </div>
                <hr/>
                <div>
                    <h3 id="price">Price</h3>
                </div>
                <hr/>
                <div>
                    <h3 id="signon">Sign on</h3>
                </div>
            </div>
        </div> 
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
        $(".clickable-object").click(function (evt) {
            window.location = "object_profile?id=" + evt.target.id + "&dateIni=" + "<?php echo $date_ini; ?>" + "&dateEnd=" + "<?php echo $date_end; ?>";
        });
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