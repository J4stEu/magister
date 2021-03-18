<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION['m4gister_user']['post']){
    date_default_timezone_set('Europe/Moscow');
    if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description'])) {
      date_default_timezone_set('Europe/Moscow');
      $data = $_POST;
      $data['title'] = trim($data['title']);
      $data['description'] = trim($data['description']);
      $checkTheme = R::findOne("m4gthemes", "id=?", array($data["id"]));
      if ($checkTheme) {
        if (!$checkTheme['allowed']){
          $themeUpdate = R::load('m4gthemes', $data["id"]);
          if ($themeUpdate) {
            $themeUpdate->title = $data['title'];
            $themeUpdate->description = $data['description'];
            if (R::store($themeUpdate)) {
              $new_theme = R::findOne('m4gthemes', 'title=?', [$data['title']]);
              $new_theme_name = R::findOne('m4gusers', 'id=?', [$new_theme['user_id']]);
              if ($new_theme && $new_theme_name) {
                $access_data = new ArrayObject(array('Тема успешно обновлена!', preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $new_theme_name['fio']), $new_theme['theme'], $new_theme['id']));
                $newLog = R::dispense('m4guserslogs');
                $newLog->user_id = $_SESSION['m4gister_user']['id'];
                $newLog->action = 'Обновление темы: "'.$data['title'].'".';
                $newLog->date = date('d.m.Y');
                R::store($newLog);
                $notify = R::dispense('m4gnotifications');
                $notify->user_id = $_SESSION["m4gister_user"]['id'];
                $notify->notification = 'Вы обновили тему "'.$data['title'].'".';
                $notify->date = date('d.m.Y');
                R::store($notify);
                echo json_encode($access_data);
              } else echo json_encode('Что-то пошло не так...'); 
            } else echo json_encode('Что-то пошло не так...'); 
          }
        } else echo json_encode('Тема уже принята и не может быть изменена!');
      } else echo json_encode('Что-то пошло не так...Такой темы не существует!');
    }
    if (isset($_POST['file_id']) && isset($_FILES['theme_file'])) {
      if ($_FILES['theme_file']['size'] > 10*1024*1024) echo json_encode('Файл не может превышать больше 10 мб!'); 
      else {  
        for ($i = 0; $i <= count($_SESSION['m4gister_user']['allowed_filetypes']) - 1; $i++){
          if (stristr($_FILES['theme_file']['name'], $_SESSION['m4gister_user']['allowed_filetypes'][$i])) {
              $_FILES['theme_file']['name'] = 't_file'.'('.($_POST['file_id']).')'.$_SESSION['m4gister_user']['allowed_filetypes'][$i];  
              $ext = $_SESSION['m4gister_user']['allowed_filetypes'][$i];
              break;
          } else $ext = false;
        }
        if (!$ext) echo json_encode('Данный тип файла не поддерживается...');
        else {
          $uploaddir = '../../files/t_files/';
          $uploadfile = $uploaddir . basename($_FILES['theme_file']['name']);
          if (file_exists($uploadfile)) {
            unlink($uploadfile);
          }
          if (move_uploaded_file($_FILES['theme_file']['tmp_name'], $uploadfile)) {
            $uploaddir = 'files/t_files/';
            $uploadfile = $uploaddir . basename($_FILES['theme_file']['name']);
            $themeUpdate = R::load('m4gthemes', $_POST['file_id']);
            $themeUpdate->file = $uploadfile;
            R::store($themeUpdate);
            echo json_encode(array('Обновлен файл: '.basename($_FILES['theme_file']['name']), $uploadfile));
          } else echo json_encode('Файл обновить не удалось...Попробуйте отредактировать тему и обновить файл заново...');
        }
      }
    }
    if (isset($_POST['file_delete_id'])) {
      $theme = R::findOne('m4gthemes', 'id=?', [$_POST['file_delete_id']]);
      if ($theme) {
        if (!$theme['allowed']){
          if (!is_null($theme['file'])){
            if (file_exists('../'.'../'.$theme['file'])) {
              if (unlink('../'.'../'.$theme['file'])) {
                $themeUpdate = R::load('m4gthemes', $_POST['file_delete_id']);
                $themeUpdate->file = NULL;
                R::store($themeUpdate);
                echo json_encode('Тема обновлена! Файл удален.');
              }
            } else echo json_encode('Файл удалить не удалось...Попробуйте отредактировать тему и удалить файл заново...');
          } else echo json_encode('К данной теме не прикреплен файл!');
        } else echo json_encode('Тема уже принята и не может быть изменена!');
      } else echo json_encode('Что-то пошло не так...');
    }
    if (isset($_POST['theme_delete_id'])) {
      date_default_timezone_set('Europe/Moscow');
      $theme = R::findOne('m4gthemes', 'id=?', [$_POST['theme_delete_id']]);
      if ($theme) {
        if (!$theme['allowed']){
          if (!is_null($theme['file'])){
            unlink('../'.'../'.$theme['file']);
          } 
          R::trash($theme);
          R::exec('ALTER TABLE `m4gthemes` AUTO_INCREMENT = 0');
          $themeCreator = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
          $newLog = R::dispense('m4guserslogs');
          $newLog->user_id = $_SESSION['m4gister_user']['id'];
          $newLog->action = 'Удаление темы "'.$theme['title'].'". Автор темы: '.$themeCreator['fio'].'.';
          $newLog->date = date('d.m.Y');
          R::store($newLog);
          $notify = R::dispense('m4gnotifications');
          $notify->user_id = $theme['user_id'];
          $notify->notification = 'Вы удалили тему "'.$theme['title'].'".';
          $notify->date = date('d.m.Y');
          R::store($notify);
          if (!(R::findOne('m4gthemes', 'id=?', [$_POST['theme_delete_id']]))) echo json_encode('Тема удалена!');    
          else echo json_encode('Что-то пошло не так...');  
        } else echo json_encode('Тема уже принята и не может быть удалена!');  
      } else echo json_encode('Что-то пошло не так...');  
    }
  }
?>