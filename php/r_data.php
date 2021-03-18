<?php
  require_once '../db.php';
  
  if (isset($_POST['email']) && isset($_POST['fio']) && isset($_POST['pass'])) {
    date_default_timezone_set('Europe/Moscow');
    $data = $_POST;
    $errors = array();
    
    if ((trim($data['email']) === '') || (trim($data['fio']) === '') || (trim($data['pass']) === '')) {
      $errors[] = 'Данные некоректны!';
    }
    
    if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/u', trim($data['email']))){
      $errors[]='Данные некоректны!';
    }
    if (!preg_match("/^[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{0,}\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,}(\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,})?$/u", trim($data['fio']))){
      $errors[]='Данные некоректны!';
    }
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/u', trim($data['pass']))){
      $errors[]='Данные некоректны!';
    }
    
    if (R::count('m4gusers', 'email=?', array($data['email'])) > 0) {
      $errors[]='Такой пользователь уже существует!';
    }
    
    if (empty($errors)) {
      $verify_code = generate_password(5);
      $users = R::dispense('m4gusers');
      $users->email = $data['email'];
      $users->fio = $data['fio'];
      $users->pass = $data['pass'];
      $users->verify_code = $verify_code;
      $users->date = date('d.m.Y');
      $store_data = R::store($users);
      echo json_encode('Вы зарегестрированы! Авторизируйтесь!');
    } else {
      echo json_encode($errors[0]);
    }
  }

  function generate_password($number) {

    $arr = array('a','b','c','d','e','f',

    'g','h','i','j','k','l',

    'm','n','o','p','r','s',

    't','u','v','x','y','z',

    'A','B','C','D','E','F',

    'G','H','I','J','K','L',

    'M','N','O','P','R','S',

    'T','U','V','X','Y','Z',

    '1','2','3','4','5','6',

    '7','8','9','0');

    $pass = "";

    for($i = 0; $i < $number; $i++) {
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }

    return $pass;

  }
?>