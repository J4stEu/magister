<?php
  require_once '../db.php';
  if (isset($_POST["log_out"])) {
    if ($_POST["log_out"]) {
      session_unset();
      echo json_encode('Выход совершён!');
    }
  }
?>