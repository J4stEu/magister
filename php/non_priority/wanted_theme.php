<?php
  require_once '../../db.php';
  if (isset($_SESSION['m4gister_user']) && (!$_SESSION['m4gister_user']['is_priority'])){
    if (isset($_POST['add_id'])){
      $theme = R::findOne('m4gthemes', 'id=?', [$_POST['add_id']]);
      $user = R::findOne('m4gusers', 'id=?', [$theme['user_id']]);
      if ($theme && $user) {
        $theme_access = false;
        $dependence = R::getAll('SELECT * from `m4grankdependence` WHERE `rank_2` = ' . $_SESSION['m4gister_user']['rank']['id']);
        if ($dependence) {
          for ($i = 0; $i < count($dependence); $i++) {
            if ($dependence[$i]['rank_1'] === $user['rank']) {
              $theme_access = true;
              break;
            } else $theme_access = false;
          }
        } else $theme_access = true;
        if ($theme_access) {
          if (!R::exec('SELECT * FROM `m4gwishes` where (`theme_id` = '.$theme['id'].') AND (`user_id` = '.$_SESSION['m4gister_user']['id'].')')){
            $wanted_theme = R::dispense('m4gwishes');
            if ($wanted_theme) {
              $wanted_theme->theme_id = $theme['id'];
              $wanted_theme->user_id = $_SESSION['m4gister_user']['id'];
              if (R::store($wanted_theme) && R::exec('UPDATE `m4gthemes` SET `wished` = '. ($theme['wished'] + 1) .' WHERE `id` = '.$theme['id'])) echo json_encode([true, 'Тема добавлена в избранное!']);
              else echo json_encode('Что-то пошло не так...');  
            } else echo json_encode('Что-то пошло не так...');    
          } else echo json_encode('Тема уже в избранном!'); 
        } else echo json_encode('Вы не можете взаимодействовать с темами данного преподавателя!'); 
      } else echo json_encode('Что-то пошло не так...'); 
    }
    if (isset($_POST['delete_id'])){
      $dislike = R::findOne('m4gwishes', ' theme_id = ? AND user_id = ?', [$_POST['delete_id'], $_SESSION['m4gister_user']['id']]);
      $theme = R::findOne('m4gthemes', ' id = ?', [$_POST['delete_id']]);
      if ($dislike) {
        R::trash($dislike);
        R::exec('ALTER TABLE `m4gwishes` AUTO_INCREMENT = 0');
        if ((!R::findOne('m4gwishes', ' theme_id = ? AND user_id = ?', [$_POST['delete_id'], $_SESSION['m4gister_user']['id']])) && R::exec('UPDATE `m4gthemes` SET `wished` = '. ($theme['wished'] - 1) .' WHERE id = '.$theme['id'])) echo json_encode([false, 'Тема удалена из избранного!']);      
      } else echo json_encode('Что-то пошло не так...');
    }
  }
?>