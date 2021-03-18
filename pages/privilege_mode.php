<?php
  require_once '../db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Magister-Privilege</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="shortcut icon" href="../icons/main_icon.png" type="image/png">
  <link rel="stylesheet" href="../styles/m4g_priviledge.css"/>
  <link rel="stylesheet" href="../styles/preloader.css"/>
  <link rel="stylesheet" href="../fonts/fonts.css"/>
  <link rel="stylesheet" href="../icons/iconmonstr-iconic-font-1.3.0/css/iconmonstr-iconic-font.css"/>
</head>
<body style="overflow: visible;">
<?php
  if (isset($_SESSION["m4gister_user"])) {
    if ($_SESSION['m4gister_user']['post']) {
      $user = R::findOne('m4gprivilegeusers', 'user_id=?', [$_SESSION["m4gister_user"]['id']]);
      if ($user) {
        require_once '../pages/additions/modal_window.php';
?>
        <section id="main_priviledge">
          <span style="position: absolute; left: 30px; top: 30px">Привилегированный режим!</span>
          <section id="priviledge_conteiner">

            <div id="content_priviledge">

              <div class="info_holder">

                <div class="info_holder_title"><i class="im im-coffee"></i></div>
                
                <div class="about_themes">

                  <div id="not_allowed_themes_conteiner">
                    <div class="all_themes_type_conteiner">
                      <div class="all_themes themes_holder">
                        <?php
                          $themes = R::getAll('SELECT * FROM `m4gthemes` WHERE `allowed` = 0 ORDER by `id` DESC');
                          if ($themes) {
                            $themes_added = 0;
                            for ($i = 0; $i < count($themes); $i++) {
                              $themes_added++;
                        ?>
                              <div class="from_themes" style="background: rgba(255, 154, 64, 0.5)">
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
                                <div id="all_theme_index_<?php echo $themes[$i]['id'] ?>" class="from_themes_intro"><i class="im im-check-mark-circle-o"></i><i class="im im-x-mark-circle-o"></i><i class="im im-paper-clip" <?php if (is_null($themes[$i]['file'])) echo 'style="color: #d54b3e"'; else echo 'style="color: #6d89d5"'?> ></i><i class="im im-fullscreen"></i></div>
                                <div class="theme_info"><i class="im im-star"><span><?php echo $themes[$i]['wished'] ?></span></i><i class="im im-bookmark"><span><?php echo $themes[$i]['confirmed']?>/8</span></i></div>
                              </div>
                        <?php 
                            }
                            if ((6 - $themes_added) > 0) {
                              for ($i = 0; $i < (6 - $themes_added); $i++) {
                                echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                              }
                            }
                          } else {
                            for ($i = 0; $i < 6; $i++) {
                              echo '<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>';
                            }
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                  
                  <div id="allowed_themes_conteiner">
                    <div class="all_themes_type_conteiner">
                      <div class="allowed_themes themes_holder">
                        <?php
                          $themes = R::getAll('SELECT * FROM `m4gthemes` WHERE `allowed` = 1 ORDER by `id` ASC');
                          if ($themes) {
                            $themes_added = 0;
                            for ($i = 0; $i < count($themes); $i++) {
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
                      <div class="allowed_themes_result themes_holder"></div>
                    </div>

                      <div class="allowed_themes_footer">
                        <div class="allowed_themes_search">
                          <input class="search_themes_input" type="text" placeholder="поиск темы">
                          <i class="im im-magnifier"></i> 
                          <i class="im im-x-mark-circle-o"></i> 
                        </div>
                      </div> 

                    </div>
                  </div>

                </div>            
        
                <ul class="content_themes_settings">
                  <ul class="add_all_theme_mode">
                    Тип:
                    <li>Непринятые</li>
                    /
                    <li>Принятые</li>
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
                <div class="content_title">Темы</div> 

              </div>  
            </div>
            
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

        <script src="../libs/jquery-3.2.1.js"></script>
        <script src="../js/privilege_mode.js"></script>
        <script src="../js/modal_window.js"></script>
<?php
      }
      else echo '<span style="position: absolute; left: 30px; top: 30px">У вас нет доступа!</span>';
    } else echo '<span style="position: absolute; left: 30px; top: 30px">У вас нет доступа!</span>';
  }
  else echo '<span style="position: absolute; left: 30px; top: 30px">У вас нет доступа!</span>';
?>
</body>
</html> 