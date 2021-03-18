<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && !$_SESSION['m4gister_user']['post'] && !$_SESSION["m4gister_user"]['allowed']){
    if (isset($_POST['email_code']) && isset($_POST['rank']) && isset($_POST['group'])){
      $user = R::findOne('m4gusers', 'email=? AND pass=?', [$_SESSION['m4gister_user']['email'], $_SESSION['m4gister_user']['pass']]);
      if ($user) {
        if ($_POST['email_code'] === $user['verify_code']) {
          if (R::findOne('m4grank2', 'id=?', [$_POST['rank']]) && R::findOne('m4gstgroup', 'id=? AND rank_2=?', [$_POST['group'], $_POST['rank']])) {
            $userToAllow = R::load('m4gusers', $user['id']);
            $userToAllow->pass = hash('sha256', 'J4stEu2:07'.$user['pass'].'03032020');
            if (R::store($userToAllow) && R::exec('UPDATE `m4gusers` SET `rank` = '. $_POST['rank'] .', `st_group` = '. $_POST['group'] .', `verify_code` = NULL, verified = 1, allowed = 1 WHERE `id` = ' . $user['id'])){
              $mag_rank = R::findOne('m4grank2', 'id=?', [$_POST['rank']]);
              $st_group = R::findOne('m4gstgroup', 'id=? AND rank_2=?', [$_POST['group'], $_POST['rank']]);
              if ($mag_rank && $st_group) {
                $_SESSION["m4gister_user"]['rank'] = $mag_rank;
                $_SESSION["m4gister_user"]['st_group'] = $st_group;
                $_SESSION["m4gister_user"]['page_position'] = 0;
                $_SESSION["m4gister_user"]['allowed'] = 1;
                echo json_encode('Ваши данные подтверждены!');
              } else echo json_encode('Что-то пошло не так...');
            } else echo json_encode('Данные некорректны!');
          } else echo json_encode('Данные некорректны!');
        } else echo json_encode('Данные некорректны!');
      } else echo json_encode('Что-то пошло не так...');
    }
  }
?>