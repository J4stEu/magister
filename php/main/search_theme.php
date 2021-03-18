<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"])){
    if (isset($_POST['wanted'])){
      $_POST['wanted'] = trim($_POST['wanted']);
      $name = R::getAll('SELECT * FROM `m4gusers` where (`post` != 0) AND (`fio` LIKE "%'.$_POST['wanted'].'%")');
      if ($name) {
        $search_result = array();
        for ($i = 0; $i <= (count($name) - 1); $i++) {
          $theme = R::getAll('SELECT * FROM `m4gthemes` where `user_id` = '.$name[$i]['id']);
          $post = R::findOne('m4gpost', 'id=?', [$name[$i]['post']]);
          if ($theme && $post) {
            if (!is_null($name[$i]['rank'])) {
              $rank = R::findOne('m4grank1', 'id=?', [$name[$i]['rank']]);
              if ($rank) {
                preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                $rank = implode('.', $rank[0]);
              } else echo json_encode('Что-то пошло не так...'); 
            } else $rank = false;
            for ($j = 0; $j <= (count($theme) - 1); $j++){
              if (is_null($theme[$j]['file'])) $theme[$j]['file'] = false;
              $theme[$j]['post'] = $post['title'];
              $theme[$j]['rank'] = $rank;
              $theme[$j]['fio'] = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[$i]['fio']);
              array_push($search_result, $theme[$j]);
            }
          } else echo json_encode('Что-то пошло не так...');  
        }
        if (empty($search_result)) echo json_encode('Тем не найдено!');
        else echo json_encode($search_result);
      } else {
        $theme = R::getAll('SELECT * FROM `m4gthemes` where `allowed` = 1 AND `title` LIKE "%'.$_POST['wanted'].'%"');
        if ($theme) {
          for ($i = 0; $i <= (count($theme) - 1); $i++){
            $name = R::getAll('SELECT * FROM `m4gusers` where `id` = '.$theme[$i]['user_id']);
            $post = R::findOne('m4gpost', 'id=?', [$name[0]['post']]);
            if ($name && $post) {
              if (!is_null($name[0]['rank'])) {
                $rank = R::findOne('m4grank1', 'id=?', [$name[0]['rank']]);
                if ($rank) {
                  preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                  $rank = implode('.', $rank[0]);
                } else echo json_encode('Что-то пошло не так...'); 
              } else $rank = false;
              if (is_null($theme[$i]['file'])) $theme[$i]['file'] = false;
              $theme[$i]['post'] = $post['title'];
              $theme[$i]['rank'] = $rank;
              $theme[$i]['fio'] = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']);     
            } else echo json_encode('Что-то пошло не так...');  
          }
          echo json_encode($theme);
        } else echo json_encode('Тем не найдено!');  
      }
    }
  }
?>