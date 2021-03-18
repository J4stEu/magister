<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION['m4gister_user']['post']){
    if (isset($_POST['sort'])){
      $sortRequest = array(
        'theme_sort_post' => 'SELECT * FROM `m4gthemes` WHERE `user_id` IN (SELECT `id` FROM `m4gusers` WHERE (`rank` IS NULL)) AND `allowed` = 1', 
        'theme_sort_rank' => 'SELECT * FROM `m4gthemes` WHERE `user_id` IN (SELECT `id` FROM `m4gusers` WHERE (`rank` IS NOT NULL)) AND `allowed` = 1', 
        'theme_sort_wishes' => 'SELECT * FROM `m4gthemes` WHERE `allowed` = 1 ORDER BY wished DESC', 
        'theme_sort_confirmed' => 'SELECT * FROM `m4gthemes` WHERE `allowed` = 1 ORDER BY `confirmed` DESC'
      );
      $sReq = R::getAll( $sortRequest[$_POST['sort']] );
      if ($sReq) {
        for ($i = 0; $i <= count($sReq) - 1; $i++){
          $name = R::getAll('SELECT * FROM `m4gusers` where `id` = '.$sReq[$i]['user_id']);
          $post = R::findOne('m4gpost', 'id=?', [$name[0]['post']]);
          if (!is_null($name[0]['rank'])) {
            $rank = R::findOne('m4grank1', 'id=?', [$name[0]['rank']]);
            if ($rank) {
              preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
              $rank = implode('.', $rank[0]);
            } else echo json_encode('Что-то пошло не так...'); 
          } else $rank = false;
          if ($name && $post) {
            if (is_null($sReq[$i]['wished'])) $sReq[$i]['wished'] = 0;
            if (is_null($sReq[$i]['confirmed'])) $sReq[$i]['confirmed'] = 0;
            if (is_null($sReq[$i]['file'])) $sReq[$i]['file'] = false;
            $sReq[$i]['post'] = $post['title'];
            $sReq[$i]['rank'] = $rank;
            $sReq[$i]['fio'] = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']); 
          } else echo json_encode('Что-то пошло не так...');  
        }
        echo json_encode($sReq);
      } else echo json_encode('Что-то пошло не так...');
    }
  }
?>