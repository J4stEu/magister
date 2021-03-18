<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION["m4gister_user"]['post']){
		if (isset($_POST['theme_id']) && isset($_POST['CertainTypeRequest'])){
			$theme_users = [];
			$themesTypeRequest = array(
				'reserved' => 'SELECT * FROM `m4gwishes` WHERE `theme_id` = '.$_POST['theme_id'].' AND `reservation` = 1 AND `confirmation` = 0 AND `restriction` = 0 AND `user_restriction` = 0', 
				'confirmed' => 'SELECT * FROM `m4gwishes` WHERE `theme_id` = '.$_POST['theme_id'].' AND `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 0', 
				'restricted' => 'SELECT * FROM `m4gwishes` WHERE `theme_id` = '.$_POST['theme_id'].' AND `reservation` = 1 AND `confirmation` = 0 AND `restriction` = 1 AND `user_restriction` = 0', 
				'user_restricted' => 'SELECT * FROM `m4gwishes` WHERE `theme_id` = '.$_POST['theme_id'].' AND `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 1'
			);
			$themes = R::getAll( $themesTypeRequest[$_POST['CertainTypeRequest']] );
			if ($themes) {
				for ($i = 0; $i < count($themes); $i++) {
					$theme_user = R::findOne('m4gusers', 'id = ?', [$themes[$i]['user_id']]);
					$rank = R::findOne('m4grank2', 'id=?', [$theme_user['rank']]);
					$group = R::findOne('m4gstgroup', 'id=?', [$theme_user['st_group']]);
					$file_query = 'query';
					if (in_array($_POST['CertainTypeRequest'], array('restricted', 'user_restricted'))) $file_query = 'rejection';
					$m4g_file = R::findOne('m4gfiles', 'title=?', [$file_query]);
					if ($theme_user && $rank && $group && $m4g_file) $theme_users[] = array('fio' => preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $theme_user['fio']), 'email' => $theme_user['email'], 'st_group' => $group['title'], 'id' => $themes[$i]['id'], 'rank' => $rank['title'], 'file' => $m4g_file);	
					else echo json_encode('Что-то пошло не так...');
				}
				echo json_encode($theme_users);
			} else echo json_encode('Нет запросов!');
		}
		if (isset($_POST['AllTypeRequest'])){
			$themes_users = [];
			$themesTypeRequest = array(
				'all' => 'SELECT * FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 0 AND `restriction` = 0 AND `user_restriction` = 0 AND `theme_id` IN (SELECT `id` FROM `m4gthemes` WHERE `user_id` = '. $_SESSION["m4gister_user"]['id'] .')', 
				'confirmed' => 'SELECT * FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 0 AND `theme_id` IN (SELECT `id` FROM `m4gthemes` WHERE `user_id` = '. $_SESSION["m4gister_user"]['id'] .')', 
				'restricted' => 'SELECT * FROM `m4gwishes` WHERE `reservation` = 1  AND `confirmation` = 0 AND `restriction` = 1 AND `user_restriction` = 0 AND `theme_id` IN (SELECT `id` FROM `m4gthemes` WHERE `user_id` = '. $_SESSION["m4gister_user"]['id'] .')', 
				'user_restricted' => 'SELECT * FROM `m4gwishes` WHERE `reservation` = 1 AND `confirmation` = 1 AND `restriction` = 0 AND `user_restriction` = 1 AND `theme_id` IN (SELECT `id` FROM `m4gthemes` WHERE `user_id` = '. $_SESSION["m4gister_user"]['id'] .')'
			);
			$themes = R::getAll( $themesTypeRequest[$_POST['AllTypeRequest']] );
			if ($themes) {
				for ($i = 0; $i < count($themes); $i++){
					$name = R::findOne('m4gusers', 'id=?', [$themes[$i]['user_id']]);
					$rank = R::findOne('m4grank2', 'id=?', [$name['rank']]);
					$group = R::findOne('m4gstgroup', 'id=?', [$name['st_group']]);
					$file_query = 'query';
					if (in_array($_POST['AllTypeRequest'], array('restricted', 'user_restricted'))) $file_query = 'rejection';
					$m4g_file = R::findOne('m4gfiles', 'title=?', [$file_query]);
					$theme_title = R::findOne('m4gthemes', 'id=?', [$themes[$i]['theme_id']]); 
					if ($name && $rank && $group && $m4g_file && $theme_title) {
						$themes_users[] = array('fio' => preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $name['fio']), 'email' => $name['email'], 'st_group' => $group['title'], 'id' => $themes[$i]['id'], 'rank' => $rank['title'], 'file' => $m4g_file, 'theme_title' => $theme_title['title']);
					}
				}
				echo json_encode($themes_users);
			} else echo json_encode('Нет запросов!');
		}
  }
?>