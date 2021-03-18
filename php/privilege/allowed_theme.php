<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION['m4gister_user']['post']){
    date_default_timezone_set('Europe/Moscow');
    if (isset($_POST['allow'])){
      if (R::exec('UPDATE `m4gthemes` SET `allowed` = 1 WHERE `id` = ' . $_POST['allow'])) {
        $theme = R::findOne('m4gthemes', 'id=?', [$_POST['allow']]);
        $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
        $newLog = R::dispense('m4guserslogs');
        $newLog->user_id = $_SESSION['m4gister_user']['id'];
        $newLog->additional_id = $theme['user_id'];
        $newLog->action = 'Принятие темы "'.$theme['title'].'". Автор темы: '.$themeCreator['fio'].'.';
        $newLog->date = date('d.m.Y');
        R::store($newLog);
        $notify = R::dispense('m4gnotifications');
        $notify->user_id = $theme['user_id'];
        $notify->notification = 'Тема "'.$theme['title'].'" подтверждена!';
        $notify->date = date('d.m.Y');
        R::store($notify);
        echo json_encode('Тема подтверждена!');
      } else echo json_encode('Что-то пошло не так...');
    }
    if (isset($_POST['dismiss'])){
      $theme = R::findOne('m4gthemes', 'id=?', [$_POST['dismiss']]);
      if (!is_null($theme['file'])){
        if (unlink('../'.'../'.$theme['file'])){
          R::trash($theme);
          R::exec('ALTER TABLE `m4gthemes` AUTO_INCREMENT = 0');
          $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
          $newLog = R::dispense('m4guserslogs');
          $newLog->user_id = $_SESSION['m4gister_user']['id'];
          $newLog->additional_id = $theme['user_id'];
          $newLog->action = 'Отклонение темы "'.$theme['title'].'". Автор темы: '.$themeCreator['fio'].'.';
          $newLog->date = date('d.m.Y');
          R::store($newLog);
          $notify = R::dispense('m4gnotifications');
          $notify->user_id = $theme['user_id'];
          $notify->notification = 'Тема "'.$theme['title'].'" отклонена!';
          $notify->date = date('d.m.Y');
          R::store($notify);
          if (!(R::findOne('m4gthemes', 'id=?', [$_POST['dismiss']]))) echo json_encode('Тема отклонена и удалена!');    
          else echo json_encode('Что-то пошло не так...');  
        } else echo json_encode('Что-то пошло не так...');  
      } else {
        R::trash($theme);
        R::exec('ALTER TABLE `m4gthemes` AUTO_INCREMENT = 0');
        $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
        $newLog = R::dispense('m4guserslogs');
        $newLog->user_id = $_SESSION['m4gister_user']['id'];
        $newLog->additional_id = $theme['user_id'];
        $newLog->action = 'Отклонение темы "'.$theme['title'].'". Автор темы: '.$themeCreator['fio'].'.';
        $newLog->date = date('d.m.Y');
        R::store($newLog);
        $notify = R::dispense('m4gnotifications');
        $notify->user_id = $theme['user_id'];
        $notify->notification = 'Тема "'.$theme['title'].'" отклонена!';
        $notify->date = date('d.m.Y');
        R::store($notify);
        if (!(R::findOne('m4gthemes', 'id=?', [$_POST['dismiss']]))) echo json_encode('Тема отклонена и удалена!');    
        else echo json_encode('Что-то пошло не так...');  
      }
    }
  }
?>