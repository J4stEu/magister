<?php
  echo '<style>';
  $tRef = '';
  for ($i=0; $i<=4; $i++) {
    if ($i != $_SESSION['m4gister_user']['page_position']) {
      echo '#content_'.$i.'{display: none}';
    } else {
      $tRef = $i;
      echo '#content_'.$i.'{display: block}';
    }
  }
  echo '</style>';

  echo '<script type="text/javascript">';
    if ($_SESSION['m4gister_user']['post']){
      if (($tRef === 1) || ($tRef === 2)) {
        if ($tRef === 1)  echo 'var refresh_request = "all";';
        else echo 'var refresh_request = "theme_requests";';
      } else echo 'var refresh_request = "";';
    } else echo 'var refresh_request = "all";';
  echo '</script>';
  $notifications = R::getAll('SELECT * FROM `m4gnotifications` WHERE `user_id` = '.$_SESSION['m4gister_user']['id'].' ORDER by `id` DESC');
  $is_notify = false;
  if ($notifications) {
    for ($i = 0; $i < count($notifications); $i++) {
      if (!$notifications[$i]['checked']) {
        $is_notify = true;
        break;
      }
    }
  }
?>
<section id="main_section">

  <nav id="content_nav">
    <div class="content_nav_conteiner">
      <div id="profile" title="Профиль"><i class="im im-user-male"></i></div>
      <div id="themes" title="Темы"><i class="im im-note-o"></i></div>
      <?php 
        if ($_SESSION['m4gister_user']['post']){
      ?>
          <div id="query" title="Работа с вашими темами"><i class="im im-edit"></i></div>
      <?php 
        } else {
      ?>
          <div id="query" title="Запросы"><i class="im im-edit"></i></div>
      <?php 
        } 
      ?>
      <div id="notifications" title="Уведомления" <?php if ($is_notify) echo 'style="background: rgba(102, 226, 117, 0.5)"'?>><i class="im im-light-bulb" ></i></div>
      <div id="log_out" title="Выход"><i class="im im-unlink"></i></div>
    </div>
  </nav>
  
  <section id="content_conteiner">
   
    <div id="content_0">
      <div class="info_holder">
        <div class="info_holder_title"><i class="im im-user-settings"></i></div>
        <form action="#" class="user_info">
          <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>email</p><input type="text" class="user_email" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['email'].'"'; ?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
          <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>пароль</p><input type="password" class="user_pass" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['pass'].'"'; ?> ><div class="data_edit"><i class="im im-eye-off"></i><i class="im im-check-mark-circle-o"></i></div></div>
          <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>ФИО</p><input type="text" class="user_fio" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['fio'].'"'; ?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
          <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p><?php if ($_SESSION['m4gister_user']['post']) echo 'ученая степень'; else echo'специальность'?></p><input type="text" class="user_fio" disabled <?php if ($_SESSION['m4gister_user']['rank']) echo 'value="'.$_SESSION["m4gister_user"]['rank']['title'].'" style="text-transform: capitalize"'; else echo 'value="нет"'?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
          <?php if (!$_SESSION['m4gister_user']['post']) { ?>
            <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>Группа</p><input type="text" class="user_group" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['st_group']['title'].'"'; ?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
          <?php } ?>
          <?php 
            if ($_SESSION['m4gister_user']['post']) {
              $user = R::findOne('m4gprivilegeusers', 'user_id=?', [$_SESSION["m4gister_user"]['id']]);
              if ($user) {
          ?>
                <div class="u_info_control_conteiner"><i class="im im-coffee"></i><a href="pages/privilege_mode.php" target="_blank">Привилегированный режим</a></div>
          <?php 
              }
            }
          ?>
        </form>  
        <?php if ($_SESSION['m4gister_user']['post']) { ?>
          <p class="u_info_profile_type">Профиль: <?php echo $_SESSION['m4gister_user']['post']['title']; ?>
          </p>
        <?php } else { ?>
          <p class="u_info_profile_type">Профиль: <?php echo 'студент'; ?></p>
        <?php } ?>
      </div>
    </div>
    
    <?php
      if ($_SESSION['m4gister_user']['post']) require_once 'add_pages/priority_1_content.php';
      else require_once 'add_pages/priority_2_content.php';
    ?>
    
    <div id="theme_show_details">
      <div class="theme_show_details_shadow"></div>
      <div class="theme_details_conteiner">
        <div class="theme_holder_title"><i class="im im-file" title="Детали темы"></i></div>
        <div class="themes_main_details">
          <p>преподаватель: <span></span></p>
          <p>email: <span></span></p>
          <p>тема: <span></span></p>
          <p>файл: <span></span></p>
          <p>описание:</p>
          <p class="description"></p>
        </div>
        <div class="theme_footer">
          <p class="theme_date">дата создания: <span></span></p>
        </div>
      </div>
      <div id="close_theme_details"><i class="im im-x-mark-circle-o"></i></div>
    </div>
    
  </section>

  <div id="creator">
    <div>
      <span><i class="im im-vk"></i></span>
      <span><i class="im im-mail"></i></span>
      <span>©J4stEu</span>
    </div>
  </div>
  
</section>