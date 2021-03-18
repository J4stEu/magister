<div id="content_1">
  <div class="info_holder">
    <div class="info_holder_title"><i class="im im-note-o"></i></div>
    
    <div class="about_themes">

      <div id="all_themes_conteiner">
        <div class="all_themes_type_conteiner">
          <div class="all_themes themes_holder">
            <?php
              $wanted_themes = R::getAll('SELECT * FROM `m4gwishes` WHERE `user_id` = '.$_SESSION["m4gister_user"]['id']);
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
                      <div id="all_theme_index_<?php echo $themes[$i]['id'] ?>" class="from_themes_intro">
                      <i class="im im-star" 
                      <?php 
                          for ($j = 0; $j < count($wanted_themes); $j++){
                            $wanted_themes[$j]['theme_id'];
                            if ($themes[$i]['id'] === $wanted_themes[$j]['theme_id']){
                              echo 'style="color: #6D89D5"';
                              break;
                            }
                          }
                      ?> 
                      ></i>
                      <i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #66E275"'?> ></i><i class="im im-fullscreen"></i></div>
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

      <div id="wanted_themes_conteiner">
        <div class="wanted_themes themes_holder">
        <?php   
          if ($wanted_themes) {
            $k_wishes = 0;
            for ($i = 0; $i < count($wanted_themes); $i++) {
              if (!$wanted_themes[$i]['reservation']) {
                $themes = R::findOne('m4gthemes', 'id=?', [$wanted_themes[$i]['theme_id']]);
                $k_wishes++;
        ?>
                <div class="from_themes">
                  <p class="from_themes_user">преподаватель: 
                    <span>
                      <?php
                        $name = R::findOne('m4gusers', 'id=?', [$themes['user_id']]);
                        $post = R::findOne('m4gpost', 'id=?', [$name['post']]);
                        if (!is_null($name['rank'])) {
                          $rank = R::findOne('m4grank1', 'id=?', [$name['rank']]);
                          preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                          echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].'/'.implode('.', $rank[0]).')';
                        } else echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].')';
                      ?>
                    </span>
                  </p>
                  <p class="from_themes_title">тема: <span><?php echo $themes['title'] ?></span></p>
                  <div id="wishes_theme_index_<?php echo $themes['id'] ?>" class="from_themes_intro">
                    <i class="im im-x-mark-circle-o"></i>
                    <i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #66E275"'?> ></i>
                    <i class="im im-fullscreen"></i>
                  </div>
                  <div class="theme_info"><i class="im im-star"><span><?php echo $themes['wished'] ?></span></i><i class="im im-bookmark"><span><?php echo $themes['confirmed']?>/8</span></i></div>
                </div>
        <?php 
              }
            } 
            if ((5 - $k_wishes) > 0) {
              for ($i = 0; $i < (5 - $k_wishes); $i++) {
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

      <div class="wanted_themes_footer">
        <p class="wanted_themes_title">Ваши избранные темы: <span><?php echo count($wanted_themes)?></span></p>
      </div> 
      </div> 

      </div>
    </div>

    <ul class="content_themes_settings">
    <ul class="all_wish_theme_mode">
      Тип:
      <li>Все</li>
      /
      <li>Избранные</li>
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
  echo '<script type="text/javascript">';
  $choosed_theme = R::findOne('m4gwishes', 'user_id = ? AND reservation = ?', [$_SESSION['m4gister_user']['id'], '1']);
  if ($choosed_theme) echo 'var theme_reserved = true';
  else echo 'var theme_reserved = false';
  echo '</script>'
?>

<div id="content_2">
  <div class="info_holder">
    <div class="info_holder_title"><i class="im im-edit"></i></div>
    
    <div class="about_themes">
      <?php
        if (!$choosed_theme) {
      ?>
        <div id="choose_theme_conteiner">
          <div class="choose_theme themes_holder">
            <?php   
              if ($wanted_themes) {
                for ($i = 0; $i < count($wanted_themes); $i++) {
                  $themes = R::findOne('m4gthemes', 'id=?', [$wanted_themes[$i]['theme_id']]);
            ?>
                  <div class="from_themes">
                    <p class="from_themes_user">преподаватель: 
                      <span>
                        <?php 
                          $name = R::findOne('m4gusers', 'id=?', [$themes['user_id']]);
                          $post = R::findOne('m4gpost', 'id=?', [$name['post']]);
                          if (!is_null($name['rank'])) {
                            $rank = R::findOne('m4grank1', 'id=?', [$name['rank']]);
                            preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                            echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].'/'.implode('.', $rank[0]).')';
                          } else echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].')';
                        ?>
                      </span>
                    </p>
                    <p class="from_themes_title">тема: <span><?php echo $themes['title'] ?></span></p>
                    <div id="choose_theme_index_<?php echo $themes['id'] ?>" class="from_themes_intro">
                      <i class="im im-x-mark-circle"></i>
                      <i class="im im-bookmark"></i>
                      <i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #66E275"'?> ></i>
                      <i class="im im-fullscreen"></i>
                    </div>
                    <div class="theme_info"><i class="im im-star"><span><?php echo $themes['wished'] ?></span></i><i class="im im-bookmark"><span><?php echo $themes['confirmed']?>/8</span></i></div>
                  </div>
            <?php 
                } 
                if ((5 - count($wanted_themes)) > 0) {
                  for ($i = 0; $i < (5 - count($wanted_themes)); $i++) {
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
          <div class="choose_theme_footer">
            <p class="choose_theme_title">
              <?php  
                if ($wanted_themes) {
                  if (count($wanted_themes) === 0) echo 'Темы не выбраны	(из избранного)';
								  else echo 'Выберите тему(из избранного)';
                } else echo 'Выберите тему(из избранного)';
							?>
            </p>
          </div> 
        </div> 
      <?php
        } else {
          if ($choosed_theme['confirmation']) echo '<script> var confirmed_theme = true </script>';
          else echo '<script> var confirmed_theme = false </script>';
      ?>  
        <div id="choosed_theme_conteiner">
          <div class="choosed_theme themes_holder">
						<?php   
              $themes = R::findOne('m4gthemes', 'id=?', [$choosed_theme['theme_id']]);
						?>
						<div class="from_themes" style="<?php if ($choosed_theme['confirmation'] && !$choosed_theme['user_restriction']) echo 'background: rgba(102, 226, 117, 0.5)';  if ($choosed_theme['confirmation'] && $choosed_theme['user_restriction']) echo 'background: rgba(213, 75, 62, 0.5)'; if ($choosed_theme['restriction']) echo 'background: rgba(213, 75, 62, 0.5)';?>">
							<p class="from_themes_user">преподаватель: 
								<span>
                  <?php 
                    $name = R::findOne('m4gusers', 'id=?', [$themes['user_id']]);
                    $post = R::findOne('m4gpost', 'id=?', [$name['post']]);
                    if (!is_null($name[0]['rank'])) {
                      $rank = R::findOne('m4grank1', 'id=?', [$name['rank']]);
                      preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
                      echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].'/'.implode('.', $rank[0]).')';
                    } else echo preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']).'('.$post['title'].')';
									?>
								</span>
							</p>
							<p class="from_themes_title">тема: <span><?php echo $themes['title'] ?></span></p>
							<div id="choose_theme_index_<?php echo $themes['id'] ?>" class="from_themes_intro">
                <?php if (!$choosed_theme['user_restriction']) echo '<i class="im im-x-mark-circle-o"></i>'?>
                <?php if (!$choosed_theme['user_restriction']) echo '<i class="im im-question"></i>'?>
                <i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #66E275"'?> ></i>
                <i class="im im-fullscreen"></i>
              </div>
              <div class="theme_info"><i class="im im-star"><span><?php echo $themes['wished'] ?></span></i><i class="im im-bookmark"><span><?php echo $themes['confirmed']?>/8</span></i></div>
						</div>
        		<?php
							if (!$choosed_theme['confirmation']) {
                if (!$choosed_theme['restriction']) {
						?>
								<div class="from_themes from_themes_priority_1_download">
                  <p class="from_themes_title">файл: <span>Заяление</span></p>
                  <?php $m4g_file = R::findOne('m4gfiles', 'title=?', ['rejection']);?>
									<p class="from_themes_title"><span><a href="<?php echo $m4g_file['rejection']?>" download>Скачать файл</a></span></p>
								</div>
         		<?php
                }
							} else {
                if (!$choosed_theme['user_restriction']) {
						?>
                  <div class="from_themes from_themes_priority_1_download">
                    <p class="from_themes_title">файл: <span>Отказ</span></p>
                    <?php $m4g_file = R::findOne('m4gfiles', 'title=?', ['rejection']);?>
                    <p class="from_themes_title">ссылка: <span><a href="<?php echo $m4g_file['file']?>" download>Скачать файл</a></span></p>
                  </div>
                  <div class="from_themes from_themes_priority_1_download">
                    <p class="from_themes_title">файл: <span>Заяление</span></p>
                    <?php $m4g_file = R::findOne('m4gfiles', 'title=?', ['query']);?>
                    <p class="from_themes_title">ссылка: <span><a href="<?php echo $m4g_file['file']?>" download>Скачать файл</a></span></p>
                  </div>
             <?php
                } else {
              ?>
                  <div class="from_themes from_themes_priority_1_download">
                    <p class="from_themes_title">файл: <span>Отказ</span></p>
                    <?php $m4g_file = R::findOne('m4gfiles', 'title=?', ['rejection']);?>
                    <p class="from_themes_title">ссылка: <span><a href="<?php echo $m4g_file['file']?>" download>Скачать файл</a></span></p>
                  </div>
            <?php
                }
							}
						?>
          </div>
          <div class="choosed_theme_footer">
            <p class="choosed_theme_title">
            <?php
              if (!$choosed_theme['confirmation']) {
                if (!$choosed_theme['restriction']) echo 'Тема выбрана и ожидает подтверждения преподавателя';
                else echo 'Ваш запрос был отклонен. Откажитесь от данной темы, чтобы выбрать другую.';
              } else {
                if (!$choosed_theme['user_restriction']) echo 'Тема подтверждена';
                else echo 'Отказ от темы. Ожидание подтверждения преподавателя.';
              }
            ?></p>
          </div> 
        </div> 
      <?php
        }
      ?>
    </div>
  	<div class="content_title">Запросы</div>   
  </div>  
</div>

<div id="content_3">
  <div class="info_holder">
    <div class="info_holder_title"><i class="im im-light-bulb"></i></div>
    <div class="about_notifications">
      <div class="notifications_container">
        <div class="all_notifications_type_container">
          <div class="all_notifications">
            <?php
              $notifications = R::getAll('SELECT * FROM `m4gnotifications` WHERE `user_id` = '.$_SESSION['m4gister_user']['id'].' ORDER by `id` DESC');
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