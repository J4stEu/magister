<?php
  require_once '../../db.php';
  if (isset($_SESSION['m4gister_user']) && !($_SESSION['m4gister_user']['is_priority'])){
    date_default_timezone_set('Europe/Moscow');
    if (isset($_POST['choose_id'])){
      if (!R::findOne('m4gwishes', 'user_id = ? AND reservation = ?', [$_SESSION['m4gister_user']['id'], '1'])) {
        $theme = R::findOne('m4gthemes', 'id=?', [$_POST['choose_id']]);
        if ($theme) {
          if ($theme['confirmed'] < 8) {
            if (R::exec('UPDATE `m4gwishes` SET `reservation` = 1 WHERE `theme_id` = '.$_POST['choose_id'].' AND `user_id` = '.$_SESSION['m4gister_user']['id'])) {
              $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
              $newLog = R::dispense('m4guserslogs');
              $newLog->user_id = $_SESSION['m4gister_user']['id'];
              $newLog->additional_id = $themeCreator['id'];
              $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
              $newLog->action = 'Выбрана тема: "'.$theme['title'].'". Автор темы: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $themeCreator['fio'])).'.';
              $newLog->date = date('d.m.Y');
              R::store($newLog);
              $notify = R::dispense('m4gnotifications');
              $notify->user_id = $theme['user_id'];
              $notify->additional_id = $_SESSION['m4gister_user']['id'];
              $notify->notification = 'Студент '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio'])).' выбрал вашу тему и ожидает подтверждения. Тема: "'.$theme['title'].'".';
              $notify->date = date('d.m.Y');
              R::store($notify);
              echo json_encode('Тема подтверждена!');
            }
            else echo json_encode('Что-то пошло не так...'); 
          } else echo json_encode('Привышен лимит в 8 студентов на тему, тема для выбора недоступна!');
        } else echo json_encode('Что-то пошло не так...');
      } else echo json_encode('У вас уже есть выбранная тема!');  
    }
    if (isset($_POST['restrict_theme'])){
      $my_theme_restriction = R::findOne('m4gwishes', 'user_id = ? AND reservation = ?', [$_SESSION['m4gister_user']['id'], '1']);
      if ($my_theme_restriction){
        if ($my_theme_restriction['confirmation']){
          if (R::exec('UPDATE `m4gwishes` SET `user_restriction` = 1 WHERE `theme_id` = '.$_POST['restrict_theme'].' AND `user_id` = '.$_SESSION['m4gister_user']['id'])) {
            $theme = R::findOne('m4gthemes', 'id=?', [$_POST['restrict_theme']]);
            $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
            $newLog = R::dispense('m4guserslogs');
            $newLog->user_id = $_SESSION['m4gister_user']['id'];
            $newLog->additional_id = $themeCreator['id'];
            $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
            $newLog->action = 'Отказ от подтвержденной темы: "'.$theme['title'].'". Автор темы: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $themeCreator['fio'])).'.';
            $newLog->date = date('d.m.Y');
            R::store($newLog);
            $notify = R::dispense('m4gnotifications');
            $notify->user_id = $theme['user_id'];
            $notify->additional_id = $_SESSION['m4gister_user']['id'];
            $notify->notification = 'Студент '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio'])).' хочет отказаться от вашей темы и ожидает подтверждения. Тема: "'.$theme['title'].'".';
            $notify->date = date('d.m.Y');
            R::store($notify);
            echo json_encode('Вы отказались от темы. Ожидайте подтверждения преподавателя.');   
          }
        } else {
          if (R::exec('UPDATE `m4gwishes` SET `reservation` = 0, `restriction` = 0 WHERE `theme_id` = '.$_POST['restrict_theme'].' AND `user_id` = '.$_SESSION['m4gister_user']['id'])) {
            $theme = R::findOne('m4gthemes', 'id=?', [$_POST['restrict_theme']]);
            $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
            $newLog = R::dispense('m4guserslogs');
            $newLog->user_id = $_SESSION['m4gister_user']['id'];
            $newLog->additional_id = $themeCreator['id'];
            $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
            $newLog->action = 'Отказ от неподтвержденной темы: "'.$theme['title'].'". Автор темы: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $themeCreator['fio'])).'.';
            $newLog->date = date('d.m.Y');
            R::store($newLog);
            $notify = R::dispense('m4gnotifications');
            $notify->user_id = $theme['user_id'];
            $notify->additional_id = $_SESSION['m4gister_user']['id'];
            $notify->notification = 'Студент '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio'])).' отказался от вашей темы. Тема: "'.$theme['title'].'".';
            $notify->date = date('d.m.Y');
            R::store($notify);
            echo json_encode('Вы отказались от темы.');
          }
        }
      } else echo json_encode('У вас нет темы!');  
    }
  }
?>