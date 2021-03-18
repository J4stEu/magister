<section id="r_a">
  <div class="form_conteiner">
    <form action="#" id="panel_1" class="form_panel">
      <?php
        $user = R::findOne('m4g4dmin', 'system_call=?', array('registration'));
        if ($user) {
          if ($user->value) {
      ?>
            <h2 class="panel_title"><i class="im im-paper-clip"></i></h2>
            <div class="hide_conteiner">
              <div><i class="im im-mail"></i><input type="email" id="r_email" class="email" name="r_email" placeholder="Введите ваш email"></div>
              <div><i class="im im-user-circle"></i><input type="text" id="r_fio" class="fio" name="r_fio" placeholder="Введите ваше ФИО"></div>
              <div><i class="im im-key"></i><input type="password" id="r_pass" class="password" name="r_pass" placeholder="Введите ваш пароль"></div>
              <div id="r_panel_button" class="panel_button"><span>отправить</span></div>
            </div>
      <?php
          } else {
      ?>
            <h2 class="panel_title"><i class="im im-paper-clip"></i></h2>   
            <div class="restricted_reg">
              <p>Регистрация недоступна! <span><u>Авторизируйтесь.</u></span></p>
            </div>
      <?php
          }
        }
      ?>
    </form>
    <form action="#" id="panel_2" class="form_panel">
      <?php
        $user = R::findOne('m4g4dmin', 'system_call=?', array('authorisation'));
        if ($user) {
          if ($user->value) {
      ?>
            <div class="panel_title"><i class="im im-link"></i></div>
            <div class="hide_conteiner">
              <div><i class="im im-mail"></i><input type="email" id="a_email" class="email" name="a_email" placeholder="Введите ваш email"></div>
              <div><i class="im im-key"></i><input type="password" id="a_pass" class="password" name="a_pass" placeholder="Введите ваш пароль"></div>
              <div id="a_panel_button" class="panel_button"><span>войти</span></div>
            </div>
      <?php
          } else {
      ?>
            <h2 class="panel_title"><i class="im im-link"></i></h2>   
            <div class="restricted_auth">
              <p>Авторизация недоступна!</p>
            </div>
      <?php
          }
        }
      ?>
    </form>
    <div id="r_a_change">
      <i class="im im-x-mark-circle circle_control"></i>
      <i class="im im-angle-right-circle"></i>
      <i class="im im-check-mark-circle circle_control"></i>
      <p class="reg_alert">Регистрация</p>
      <p class="auth_alert">Авторизация</p>
    </div>
    <div id="r_a_change_mobile">
      <p class="r_a_alert"><span>Регистрация</span> / <span>Авторизация</span></p>
    </div>
    <div id="creator">
      <div>
        <span><i class="im im-vk"></i></span>
        <span><i class="im im-mail"></i></span>
        <span>©J4stEu</span>
      </div>
    </div>
    <div id="q_widnow">
      <i class="im im-question"></i>
      <i class="im im-x-mark-circle"></i>
      <div class="q_window_text">
        <p><strong>Пример ввода email</strong>: <u>example@mail.ru</u></p>
        <p><strong>Пример ввода ФИО</strong>: <u>Фамилия Имя Отчество</u> (3 слова с загл. буквы)</p>
        <p><strong>Пример ввода пароля</strong>: <u>Example123</u> (Требование: латиница, наличие хотя бы одной заглавной буквы, цифры, спец. символа)</p>
      </div>
    </div>
  </div>
</section>