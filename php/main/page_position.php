<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"])){
    if (isset($_POST['page_position'])){
      $_SESSION["m4gister_user"]['page_position'] = $_POST['page_position'];
      echo json_encode(true);
    }
  }
?>