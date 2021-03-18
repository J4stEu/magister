<?php
  require_once 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Magister</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="shortcut icon" href="icons/main_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/preloader.css"/>
  <?php
    if (!isset($_SESSION["m4gister_user"])){
      echo '<link rel="stylesheet" href="styles/r_a_style.css"/>';
      echo '<link rel="stylesheet" href="styles/r_a_media.css"/>';
    } else {
      if ($_SESSION["m4gister_user"]['allowed']) {
        echo '<link rel="stylesheet" href="styles/main_style.css"/>';
        echo '<link rel="stylesheet" href="styles/main_media.css"/>';
        if ($_SESSION['m4gister_user']['post']) echo '<link rel="stylesheet" href="styles/priority_1_styles.css"/>';
        else echo '<link rel="stylesheet" href="styles/priority_2_styles.css"/>';
      } else {
        echo '<link rel="stylesheet" href="styles/m4g_restricted.css"/>';
      }
    }
  ?>
  <link rel="stylesheet" href="fonts/fonts.css"/>
  <link rel="stylesheet" href="icons/iconmonstr-iconic-font-1.3.0/css/iconmonstr-iconic-font.css"/>
</head>
<body>
  <?php
    require_once 'pages/additions/modal_window.php';
    if (!isset($_SESSION["m4gister_user"])){
      require_once 'pages/r_a.php';
    } else {
      if ($_SESSION["m4gister_user"]['allowed']) require_once 'pages/main.php';
      else require_once 'pages/restricted_mode.php';
    }
  ?>
<script src="libs/jquery-3.2.1.js"></script>
<?php
  if (!isset($_SESSION["m4gister_user"])){
    echo '<script src="js/r_a_script.js"></script>';
  } else {
    if ($_SESSION["m4gister_user"]['allowed']) {
      echo '<script src="js/main_script.js"></script>';
      if ($_SESSION['m4gister_user']['post']) echo '<script src="js/priority_1_script.js"></script>';
      else echo '<script src="js/priority_2_script.js"></script>';
    } else echo '<script src="js/restricted_mode.js"></script>';
  }
?>
<script src="js/modal_window.js"></script>
</body>
</html>