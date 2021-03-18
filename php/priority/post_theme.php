<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION['m4gister_user']['post']){
    date_default_timezone_set('Europe/Moscow');
    if (isset($_POST['title']) && isset($_POST['description'])) {
      date_default_timezone_set('Europe/Moscow');
      $data = $_POST;
      $data['title'] = trim($data['title']);
      $data['description'] = trim($data['description']);
      if (!(R::count("m4gthemes", "title=?", array($data["title"])) > 0)) {
        $themes = R::dispense('m4gthemes');
        $themes->user_id = (int)$_SESSION["m4gister_user"]['id'];
        $themes->title = $data['title'];
        $themes->description = $data['description'];
        $themes->date = date('d.m.Y');
        if (R::store($themes)) {
          $new_theme = R::findOne('m4gthemes', 'title=?', [$data['title']]);
          $new_theme_name = R::findOne('m4gusers', 'id=?', [$new_theme['user_id']]);
          if ($new_theme && $new_theme_name) {
            $access_data = new ArrayObject(array('Тема успешно добавлена! Ожидайте её подтверждения.', preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $new_theme_name['fio']), $new_theme['theme'], $new_theme['id']));
            $newLog = R::dispense('m4guserslogs');
            $newLog->user_id = $_SESSION['m4gister_user']['id'];
            $newLog->action = 'Добавление темы: "'.$data['title'].'".';
            $newLog->date = date('d.m.Y');
            R::store($newLog);
            $notify = R::dispense('m4gnotifications');
            $notify->user_id = $_SESSION["m4gister_user"]['id'];
            $notify->notification = 'Вы опубликовали тему "'.$data['title'].'". Ожидайте её подтверждения.';
            $notify->date = date('d.m.Y');
            R::store($notify);
            echo json_encode($access_data);
          } else echo json_encode('Что-то пошло не так...');
        } else echo json_encode('Что-то пошло не так...');
      } else echo json_encode('Такая тема уже существует!');
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
          if (move_uploaded_file($_FILES['theme_file']['tmp_name'], $uploadfile)) {
            $uploaddir = 'files/t_files/';
            $uploadfile = $uploaddir . basename($_FILES['theme_file']['name']);
            if (R::exec('UPDATE `m4gthemes` SET file = "' . $uploadfile . '" WHERE `id` = '.$_POST['file_id'])){
              echo json_encode('Загружен файл: '.basename($_FILES['theme_file']['name']));
            }
          } else echo json_encode('Файл загрузить не удалось...Попробуйте отредактировать тему и добавить файл заново...');
        }
      }
    }
  }
?>