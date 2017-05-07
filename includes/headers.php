<?php
session_start();

if ( defined( 'RESTRICTED' ) ) {
    if ( !isset( $_SESSION['login_id'] ) ) {
      header( 'Location: login.php' );
      exit();
    }
}
else {
    if ( defined( 'SEND_TO_HOME' ) && isset( $_SESSION['login_id'] ) ) {
      header( 'Location: search.php' );
      exit();
    }     
}
?>