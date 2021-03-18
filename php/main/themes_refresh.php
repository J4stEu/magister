<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"])){
		if (isset($_POST['requestRefresh'])){
			$requestRefresh = array(
				'all' => 'SELECT * FROM `m4gthemes` WHERE `allowed` = 1 ORDER by `id` DESC',
				'theme_requests' => 'SELECT * FROM `m4gthemes` where `user_id`='.$_SESSION["m4gister_user"]['id'], 
				'theme_confirmed' => 'SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 0)', 
				'theme_restricted' => 'SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 0 AND `restriction` = 1 AND `user_restriction` = 0)', 
				'theme_user_restricted' => 'SELECT * FROM `m4gthemes` WHERE `user_id` = ' . $_SESSION["m4gister_user"]['id'] . ' AND `id` IN (SELECT `theme_id` FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 1)'
			);
			if ($_POST['requestRefresh'] === 'all') {
				$themes = [];
				$refresh = R::getAll($requestRefresh[$_POST['requestRefresh']]);
				if ($refresh) {
					for ($i = 0; $i < count($refresh); $i++) {
						$name = R::getAll('SELECT * FROM `m4gusers` where `id`='.$refresh[$i]['user_id']);
						$post = R::findOne('m4gpost', 'id=?', [$name[0]['post']]);
						if ($name && $post) {
							if (!is_null($name[0]['rank'])) {
								$rank = R::findOne('m4grank1', 'id=?', [$name[0]['rank']]);
								preg_match_all('#(?<=\s|\b)\pL#u', $rank['title'], $rank);
								$user = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']).'('.$post['title'].'/'.implode('.', $rank[0]).')';
							} else $user = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name[0]['fio']).'('.$post['title'].')';
							if (!is_null($refresh[$i]['file'])) $m4g_file = false;
							else $m4g_file = true;
							$themes[] = array('user' => $user, 'theme' => $refresh[$i]['title'],'id' => $refresh[$i]['id'], 'file' => $m4g_file, 'wished' => $refresh[$i]['wished'], 'confirmed' => $refresh[$i]['confirmed']);
						} else echo json_encode('Что-то пошло не так...');
					}
					echo json_encode($themes);
				} else echo json_encode(false);
			} else {
				if ($_SESSION["m4gister_user"]['post']) {
					$refresh = R::getAll($requestRefresh[$_POST['requestRefresh']]);
					if ($refresh) {
						$themes = [];
						for ($i = 0; $i < count($refresh); $i++) {
							if (is_null($refresh[$i]['file'])) $m4g_file = false;
							else $m4g_file = true;
							if ($refresh[$i]['allowed']) $refresh[$i]['allowed'] = true;
							else $refresh[$i]['allowed'] = false;
							$themes[] = array('theme' => $refresh[$i]['title'],'id' => $refresh[$i]['id'], 'allowed' => $refresh[$i]['allowed'], 'file' => $m4g_file, 'wished' => $refresh[$i]['wished'], 'confirmed' => $refresh[$i]['confirmed']);
						}
						echo json_encode($themes);
					} else echo json_encode(false);
				} else echo json_encode('Доступ запрещён!');
			}
		}
  }
?>