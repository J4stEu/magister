<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && !$_SESSION['m4gister_user']['post'] && !$_SESSION["m4gister_user"]['allowed']){
    if (isset($_POST['st_id'])){
      $group_list = R::getAll('SELECT * FROM `m4gstgroup` WHERE `rank_2` = '.$_POST['st_id']);
      if ($group_list) echo json_encode($group_list);
      else echo json_encode('Для данной специальности нет групп!');
    }
  }
?>