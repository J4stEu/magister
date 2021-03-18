<?php
  echo '<script type="text/javascript">';
  echo 'var allowed_filetypes = [];';
  for ($i = 0; $i <= count($_SESSION['m4gister_user']['allowed_filetypes']) - 1; $i++){
    echo 'allowed_filetypes.push("'.$_SESSION['m4gister_user']['allowed_filetypes'][$i].'");';
  }
  echo '</script>'
?>
<div id="content_1">
  <div class="info_holder">

    <div class="info_holder_title"><i class="im im-note-o"></i></div>
    
    <div class="about_themes">

      <div id="add_themes_conteiner">
        <form action="#" class="add_themes themes_holder">
          <div class="from_themes">
            <p class="from_themes_user">преподаватель: <span> <?php echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio']) ?></span></p>
            <p class="from_themes_title">тема: <span><input type="text" class="add_theme_title" placeholder="тема"></span></p>
            <div class="from_themes_intro">
              <input type="file" name="theme_info_file" id="theme_info_file">
              <label for="theme_info_file" class="file_for_theme"><i class="im im-paper-clip" title="Добавить файл"></i></label>
              <i class="im im-paperplane" title="Опубликовать тему"></i>
              <i class="im im-x-mark-circle-o" title="Сбросить"></i>
            </div>
          </div>
          <textarea class="add_theme_description" placeholder="Описание темы"></textarea>
        </form>
        <div class="example_themes themes_holder">
          <div class="from_themes"></div>
          <div class="from_themes"></div>
          <div class="from_themes"></div>
        </div>
        <div class="example_themes_title">Похожие темы</div>
      </div> 

      <div id="all_themes_conteiner">
        <div class="all_themes_type_conteiner">
          <div class="all_themes themes_holder">
            <?php
              $themes = R::getAll('SELECT * FROM `m4gthemes` ORDER by `id` DESC');
              if ($themes) {
                $themes_added = 0;
                for ($i = 0; $i < count($themes); $i++) {
                  if ($themes[$i]['allowed']) {
                    $themes_added++;
            ?>
                    <div class="from_themes">
                      <p class="from_themes_user">преподаватель: 
                        <span>
                          <?php 
                            $name = R::getAll('SELECT * FROM `m4gusers` where `id`='.$themes[$i]['user_id']);
                            $post = R::findOne('m4gpost', 'id=?', [$name[0]['post']]);
                            if (!is_null($name[0]['rank'])) {
                              $rank = R::findOne('m4grank1', 'id=?', [$name[0]['rank']]);
                              preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                              echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']).'('.$post['title'].'/'.implode('.', $rank[0]).')';
                            } else echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']).'('.$post['title'].')';
                          ?>
                        </span>
                      </p>
                      <p class="from_themes_title">тема: <span><?php echo $themes[$i]['title'] ?></span></p>
                      <div id="all_theme_index_<?php echo $themes[$i]['id'] ?>" class="from_themes_intro"><i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #6d89d5"'?> ></i><i class="im im-fullscreen"></i></div>
                      <div class="theme_info"><i class="im im-star"><span><?php echo $themes[$i]['wished'] ?></span></i><i class="im im-bookmark"><span><?php echo $themes[$i]['confirmed']?>/8</span></i></div>
                    </div>
            <?php 
                  }
                }
                if ((5 - $themes_added) > 0) {
                  for ($i = 0; $i < (5 - $themes_added); $i++) {
                    echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                  }
                }
              } else {
                for ($i = 0; $i < 5; $i++) {
                  echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                }
              }
            ?>
          </div>
          <div class="themes_result themes_holder"></div>
        </div>

        <div class="all_themes_footer">
          <div class="all_themes_search">
            <input class="search_themes_input" type="text" placeholder="поиск темы">
            <i class="im im-magnifier"></i> 
            <i class="im im-x-mark-circle-o"></i> 
          </div>
        </div> 
      
      </div>
    
    </div>
    
    <ul class="content_themes_settings">
      <ul class="add_all_theme_mode">
        Тип:
        <li>Добавить</li>
        /
        <li>Все</li>
      </ul>
      <ul class="sort_theme_mode">
        Сортировка:
        <li id="theme_sort_all">Все</li>
        /
        <li id="theme_sort_post">Без уч.звания</li>
        /
        <li id="theme_sort_rank">(к.*.н; д.*.н)</li>
        /
        <li id="theme_sort_wishes">Избранные</li>
        /
        <li id="theme_sort_confirmed">Принятые</li>
        <li id="sort_way">(<i class="im im-arrow-down"></i>)</li>
      </ul>
    </ul>
		<div class="content_title">Темы <i class="im im-sync"></i></div> 

  </div>  
</div>


<?php
  function load_themes_query_content($request) {
    $themes_request = R::getAll($request);
    if ($themes_request) {
      for ($i = 0; $i < count($themes_request); $i++) {
        $allowed_color = '';
        if (!$themes_request[$i]['allowed']) $allowed_color = 'rgba(255, 154, 64, 0.5)';
        echo  '<div class="from_themes" style="background:'.$allowed_color.'">';
        echo  '<p class="from_themes_title">тема: <span>'.$themes_request[$i]['title'].'</span></p>';
        if (is_null($themes_request[$i]['file'])) $paper_clip_color = '#d54b3e'; 
        else $paper_clip_color = '#6d89d5';
        if ($themes_request[$i]['allowed']) $allowed_theme_check = '<i class="im im-eye"></i>';
        else $allowed_theme_check = '<i class="im im-pencil"></i>';
        echo  '<div id="confirm_theme_index_'. $themes_request[$i]['id'].'" class="from_themes_intro">'.$allowed_theme_check.'<i class="im im-paper-clip" style="color:' . $paper_clip_color . '"></i><i class="im im-fullscreen"></i></div>';
        echo  '<div class="theme_info"><i class="im im-star"><span>'.$themes_request[$i]['wished'].'</span></i><i class="im im-bookmark"><span>'.$themes_request[$i]['confirmed'].'/8</span></i></div>';
        echo  '</div>';
      }
      if ((5 - count($themes_request)) > 0) {
        for ($i = 0; $i < (5 - count($themes_request)); $i++) {
          echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
        }
      }
    } else {
      for ($i = 0; $i < 5; $i++) {
        echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
      }
    }
  }
?>

<div id="content_2">
  <div class="info_holder">

    <div class="info_holder_title"><i class="im im-edit"></i></div>

    <div class="about_themes">
      <div id="your_themes_conteiner">
        <div class="your_themes themes_holder">
          <?php
            load_themes_query_content('SELECT * FROM `m4gthemes` where `user_id`='.$_SESSION["m4gister_user"]['id']);
          ?>
        </div>
        <div class="your_themes_students_query_button themes_query_button" title="Все запросы"><i class="im im-archive"></i></div>
      </div>
      <div id="your_confirmed_themes_conteiner">
        <div class="your_confirmed_themes themes_holder">
          <?php
            load_themes_query_content('SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 0)');
          ?>
        </div>
        <div class="your_themes_confirmed_students_query_button themes_query_button" title="Все принятые"><i class="im im-archive"></i></div>
      </div>
      <div id="your_restricted_themes_conteiner">
        <div class="your_restricted_themes themes_holder">
          <?php
            load_themes_query_content('SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 0 AND `restriction` = 1 AND `user_restriction` = 0)');
          ?>
        </div>
        <div class="your_themes_restricted_students_query_button themes_query_button" title="Общие отмененные"><i class="im im-archive"></i></div>
      </div>
      <div id="your_user_restricted_themes_conteiner">
        <div class="your_user_restricted_themes themes_holder">
          <?php
            load_themes_query_content('SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 1)');
          ?>
        </div>
        <div class="your_themes_user_restricted_students_query_button themes_query_button" title="Общие отказы"><i class="im im-archive"></i></div>
      </div>
    </div>  

    <?php if ($_SESSION['m4gister_user']['rank']) {
      preg_match_all('#(?<=\s|\b)\pL#u', $_SESSION['m4gister_user']['rank']['title'], $rank);
      $rank = implode('.', $rank[0]);
      $my_themes = R::getAll('SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION['m4gister_user']['id']);
    }?>
    <div class="content_title">Работа с вашими темами. Колличество тем:<span><?php echo count($my_themes)?>.</span><i class="im im-sync"></i></div> 

    <ul class="content_themes_settings">
      <ul class="all_theme_queries">
        Тип:
        <li class="theme_queries" title="Запросы от студентов">Запросы</li>
        /
        <li class="theme_confirmed_queries" title="Принятые вами запросы от студентов">Принятые</li>
        /
        <li class="theme_restricted_queries" title="Отмененные вами запросы от студентов">Отмененные</li>
        /
        <li class="theme_user_restricted_queries" title="Отказы студентов от тем">Отказы</li>
      </ul>
    </ul>
    
  </div>  
</div>

<div id="students_query">
	<div class="students_query_shadow"></div>
	<div class="students_theme_query"></div>
  <p class="content_2_theme_query_type">Запросы на тему: <span>Все</span></p>
	<div class="close_students_query"><i class="im im-x-mark-circle-o"></i></div>
</div>

<div id="content_3">
  <div class="info_holder">
    <div class="info_holder_title"><i class="im im-light-bulb"></i></div>
    <div class="about_notifications">
      <div class="notifications_container">
        <div class="all_notifications_type_container">
          <div class="all_notifications">
            <?php
              if ($notifications) {
                $notifications_added = 0;
                for ($i = 0; $i < count($notifications); $i++) {
                  $notifications_added++;
                  if (!$notifications[$i]['checked']) echo '<div id="my_notification_'.$notifications[$i]['id'].'" class="from_notifications" style="background: rgba(102, 226, 117, 0.5)">';
                  else echo '<div id="my_notification_'.$notifications[$i]['id'].'" class="from_notifications">';
            ?>
                    <p class="from_notifications_notification">Уведомление: <span><?php echo $notifications[$i]['notification'] ?></span></p>
                    <p class="from_notifications_date">Дата: <span><?php echo $notifications[$i]['date'] ?></span></p>
                    <div id="notification_index_<?php echo $notifications[$i]['id'] ?>" class="from_notification_intro">
                      <?php
                        if (!$notifications[$i]['checked']) echo '<i class="im im-check-mark-circle-o"></i>';
                      ?>      
                      <i class="im im-trash-can"></i>
                      <i class="im im-fullscreen"></i>
                    </div>
                  </div>
            <?php 
                }
                if ((6 - $notifications_added) > 0) {
                  for ($i = 0; $i < (6 - $notifications_added); $i++) {
                    echo '<div class="from_notifications" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                  }
                }
              } else {
                for ($i = 0; $i < 6; $i++) {
                  echo '<div class="from_notifications" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="content_title">Уведомления</div>
  </div>  
</div>

<div id="theme_details_edit">
  <div class="theme_details_edit_shadow"></div>
  <div class="theme_details_edit_conteiner">
    <div class="theme_holder_title"><i class="im im-pencil" title="Изменить тему"></i></div>
    <form action="#" class="themes_main_details">
      <p>преподаватель: <span></span></p>
      <p>email: <span></span></p>
      <p>тема: <input type="text" class="theme_edit"></p>
      <p>файл: <span></span> <i class="im im-x-mark" title="Удалить файл"></i></p>
      <p>
      Файл(новый): 
      <input type="file" name="update_theme_info_file" id="update_theme_info_file">
      <label for="update_theme_info_file" class="update_file_for_theme"><i class="im im-paper-clip" title="Обновить файл"></i></label>
      </p>
      <p>описание:</p>
      <textarea class="description_edit" placeholder="Описание темы"></textarea>
    </form>
    <div class="theme_footer">
      <div id="update_theme_id"></div>
      <p class="theme_date">дата создания: <span></span></p>
      <p class="theme_update_confirm"><i class="im im-paperplane" title="Обновить тему"></i></p>
      <p class="theme_delete"><i class="im im-trash-can" title="Удалить тему"></i></p>
    </div>
  </div>
  <div id="close_theme_edit"><i class="im im-x-mark-circle-o"></i></div>
</div>