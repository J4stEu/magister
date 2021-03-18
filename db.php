<?php
  require "libs/rb.php";
  define('DB_CHARSET', 'utf8');
  R::setup( 'mysql:host=localhost;dbname=magister','root', '' );
  session_start();
?>