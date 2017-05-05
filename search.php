<?php
define( 'RESTRICTED', true );
require( 'includes/headers.php' );
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Searcher</title>
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
                <li><a href="search.php" style="color:black;">Search</a></li>
                <li><a href="#">Configuration</a></li>
                <li><a href="#">Help</a></li>
                <li><a href="includes/logout.php">Close session</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="searcher">
            <div class="searcher-screen">
                <form action="" method="post">
                    <div class="searcher-title">
                        <h1 style="text-align: center">What are you searching for?</h1>
                    </div>
                    <div class="control-group" center="left">
                        <input type="radio" value="h" checked="checked" name="optradio">Hostels</input>
                        <input type="radio" value="a" name="optradio">Activities </input>
                    </div>
                    <div class="control-group" center="left">
                        <input type="search" name="city" placeholder="Search city" required>
                    </div>
                    <div class="control-group" center="left">
                        <label for="ini">From</label>
                        <input placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="ini" id="ini" required>
                        <label for="fin">to</label>
                        <input placeholder="DD/MM/YYYY" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" name="fin" id="fin" required>
                    </div>
                    <div class="button-div">
                        <button class="btn" type="Submit" align="right">SEARCH</button>
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