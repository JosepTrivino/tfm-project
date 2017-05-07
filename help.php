<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help</title>
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
    <?php
    session_start();
    if (isset( $_SESSION['login_id'] ) ) {
    echo '
          <header id="header" class="okayNav-header">
        <a class="okayNav-header__logo">
           <img src="images/logo.jpg" alt="Logo Icon"  height="50" width="50">
        </a>
        <nav role="navigation" id="nav-main" class="okayNav">
            <ul>
                <li><a href="profile_information.php">Profile</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="configuration.php">Configuration</a></li>
                <li><a href="help.php" style="color:black;">Help</a></li>
                <li><a href="includes/logout.php">Close session</a></li>
            </ul>
        </nav>
    </header>';
    }
    ?>
    <main>
      <div class="searcher">
        <div class="searcher-screen">
          <div class="searcher-title">
            <h1 style="text-align: center">Help</h1>
          </div>
          <div class="control-group" center="left">
            <p><strong>What is this webpage?</strong></p>
            <p>This page has been created as a final master project for the Universitat Oberta de Catalunya.</p>
          </div>
          <div class="control-group" center="left">
            <p ><strong>Is it necessary to be registered?</strong></p>
            <p>Yes. It is necessary to register in order to access this content.</p>
          </div>
          <div class="control-group" center="left">
            <p ><strong>Contact</strong></p>
            <p>Josep Triviño valls (peptrivi@gmail.com)</p>
          </div>
        </div>
      </div>
    </main>
    <script type="text/javascript">
        var navigation = $('#nav-main').okayNav();
    </script>
</body>
</html>