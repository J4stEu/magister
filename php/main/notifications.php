<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"])){
    if (isset($_POST['notify_id'])){
      if (R::exec('UPDATE `m4gnotifications` SET `checked` = 1 WHERE `id` = '.$_POST['notify_id'])) echo json_encode(true);
      else echo json_encode(false);
    }
    if (isset($_POST['notify_delete_id'])){
      $notification = R::findOne('m4gnotifications', 'id=?', [$_POST['notify_delete_id']]);
      R::trash($notification);
      R::exec('ALTER TABLE `m4gnotifications` AUTO_INCREMENT = 0');
      if (!(R::findOne('m4gnotifications', 'id=?', [$_POST['notify_delete_id']]))){
        echo json_encode(true);
      } else echo json_encode(false);
    }
  }
?>