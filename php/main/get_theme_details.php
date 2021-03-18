<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"])){
    if (isset($_POST['theme_id'])){
      $themes = R::findOne('m4gthemes', 'id=?', [$_POST['theme_id']]);
      if ($themes) {
        $name = R::findOne('m4gusers', 'id=?', [$themes['user_id']]);
        $post = R::findOne('m4gpost', 'id=?', [$name['post']]);
        if (!is_null($name['rank'])) {
          $rank = R::findOne('m4grank1', 'id=?', [$name['rank']]);
          if ($rank) {
            preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
            $rank = implode('.', $rank[0]);
          } else echo json_encode('Что-то пошло не так...'); 
        } else $rank = false;
        if ($name && $post) {
          $themes->fio = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']);
          $themes->email = $name['email'];
          $themes->post = $post['title'];
          $themes->rank = $rank;
          if (is_null($themes->file)) $themes->file = false;
          echo json_encode($themes);  
        } else echo json_encode('Что-то пошло не так...');
      } else echo json_encode('Что-то пошло не так...');
    }
  }
?>