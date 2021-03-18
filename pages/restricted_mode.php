<?php
  if (!$_SESSION['m4gister_user']['post']) {
    echo '<script type="text/javascript">';
      echo 'var rank_options = new Array();';
      $rank_list = R::getAll('SELECT * FROM `m4grank2`');
      for ($i = 0; $i < count($rank_list); $i++) {
        echo 'rank_options.push("'.$rank_list[$i]['title'].'");';
      }
    echo '</script>'
?>  
    <section id="not_allowed_section">
      <section id="content_conteiner">
        <div id="content_0">
          <div class="info_holder">
            <div class="info_holder_title"><i class="im im-cloud"></i></div>
            <form action="#" class="user_info">
              <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>email</p><input type="text" class="user_email" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['email'].'"'; ?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
              <div class="u_info_control_conteiner"><p>код подтверждения</p><input type="text" class="user_email_code"><div class="data_edit"><i class="im im-x-mark-circle-o"></i></div></div>
              <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>пароль</p><input type="password" class="user_pass" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['pass'].'"'; ?> ><div class="data_edit"><i class="im im-eye-off"></i><i class="im im-check-mark-circle-o"></i></div></div>
              <div class="u_info_control_conteiner"><i class="im im-cloud"></i><p>ФИО</p><input type="text" class="user_fio" disabled <?php echo 'value="'.$_SESSION["m4gister_user"]['fio'].'"'; ?> ><div class="data_edit"><i class="im im-check-mark-circle-o"></i></div></div>
              <div class="u_info_control_conteiner"><p>специальность</p>
                <select class="user_rank_list">
                  <option value="" hidden disabled selected>Выбрать...</option>
                  <?php
                    $rank_list = R::getAll('SELECT * FROM `m4grank2`');
                    for ($i = 0; $i < count($rank_list); $i++) {
                      echo '<option id="rank_'. $rank_list[$i]['id'] .'">'. $rank_list[$i]['title'] .'</option>';
                    }
                  ?>
                </select> 
              <div class="data_edit"><i class="im im-check-mark-circle-o" style='display: none'></i><i class="im im-x-mark-circle-o"></i></div></div>
              <div class="u_info_control_conteiner"><p>группа</p>
                <select class="user_group_list">
                  <option value="" hidden disabled selected>Выберите специальность!</option>
                </select> 
              <div class="data_edit"><i class="im im-check-mark-circle-o" style='display: none'></i><i class="im im-x-mark-circle-o"></i></div></div>
              <div id="user_info_button"><span>подтвердить</span></div>
            </form>  
            <p class="u_info_profile_type">Профиль: студент. Тип: подтверждение данных.</p>
          </div>
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
<?php 
  } else echo 'Что-то пошло не так...';
  echo $_SESSION['m4gister_user']['allowed'];
?>