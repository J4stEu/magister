<?php
  require_once '../db.php';

  if (isset($_POST['email']) && isset($_POST['pass'])) {
    $data = $_POST;
    $errors = array();
    
    if ((trim($data['email']) === '') || (trim($data['pass']) === '')) $errors[] = 'Данные некорректны...';
    
    if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/u', trim($data['email']))) $errors[] = 'Данные некорректны...';
    
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/u', trim($data['pass']))) $errors[] = 'Данные некорректны...';
    
    if (empty($errors)) {
      $user = R::findOne('m4gusers', 'email=? AND pass=?', [$data['email'], $data['pass']]);
      if (!$user) $user = R::findOne('m4gusers', 'email=? AND pass=?', [$data['email'], hash('sha256', 'J4stEu2:07'.$data['pass'].'03032020')]);
      if ($user) {
        deleteItem($user, 'verify_code');
        $user['pass'] = $data['pass'];
        if ($user['active']) {
          if (is_null($user['post'])) {
            if ($user['allowed']) {
              $mag_rank = R::findOne('m4grank2', 'id=?', [$user['rank']]);
              $st_group = R::findOne('m4gstgroup', 'id=? AND rank_2=?', [$user['st_group'] , $user['rank']]);
              if ($mag_rank && $st_group) {
                $user['post'] = false;
                $user['rank'] = $mag_rank;
                $user['st_group'] = $st_group;
                $user['page_position'] = 0;
                $_SESSION['m4gister_user'] = $user;
                echo json_encode(true);
              } else echo json_encode(false);
            } else {
              $user['post'] = false;
              $user['rank'] = false;
              $user['st_group'] = false;
              $user['page_position'] = 0;
              $_SESSION['m4gister_user'] = $user;
              echo json_encode(true);
            }
          } else {
            if (!$user['allowed']) {
              $userToAllow = R::load('m4gusers', $user['id']);
              $userToAllow->pass = hash('sha256', 'J4stEu2:07'.$user['pass'].'03032020');  
              if (R::store($userToAllow) && R::exec('UPDATE `m4gusers` SET `allowed` = 1 WHERE `id` = '. $user['id'])) $user['allowed'] = 1;
              else echo json_encode('Что-то пошло не так...');
            }
            $mag_post = R::findOne('m4gpost', 'id=?', [$user['post']]);
            if ($mag_post) {
              if (!is_null($user['rank'])) {
                $mag_rank = R::findOne('m4grank1', 'id=?', [$user['rank']]);
                if ($mag_rank) {
                  $user['post'] = $mag_post;
                  $user['rank'] = $mag_rank;
                  $user['allowed_filetypes'] = array('.doc', '.docx', 'pdf');
                  $user['page_position'] = 0; 
                  $_SESSION['m4gister_user'] = $user;
                  echo json_encode(true);
                } else echo json_encode(false);
              } else {
                $user['post'] = $mag_post;
                $user['rank'] = false;
                $user['page_position'] = 0; 
                $_SESSION['m4gister_user'] = $user;
                echo json_encode(true);
              }
            } else echo json_encode(false);
          }
        } else echo json_encode('Аккаунт заблокирован!');
      } else echo json_encode('Данные некорректны...');
    } else echo json_encode($errors[0]);
  }

  function deleteItem( &$array, $value ) {
    foreach( $array as $key => $val ){
        if ( is_array($val) ) {
            deleteItem($array[$key], $value);
        } elseif ( $val===$value ) {
            unset($array[$key]);
        }
    }
  }
?>