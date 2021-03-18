<?php
  require_once '../../db.php';
  if (isset($_SESSION["m4gister_user"]) && $_SESSION["m4gister_user"]['post']){
	date_default_timezone_set('Europe/Moscow');
	if (isset($_POST['confirm'])) {
		$wish = R::findOne('m4gwishes', 'id=?', [$_POST['confirm']]);
		$theme = R::findOne('m4gthemes', 'id=?', [$wish['theme_id']]);
		if ($wish && $theme) {
			if ($theme['confirmed'] < 8) {
				if ((R::exec('UPDATE `m4gthemes` SET `confirmed` = "' . ($theme['confirmed'] + 1) . '" WHERE `id` = ' . $theme['id'])) && (R::exec('UPDATE `m4gwishes` SET `reservation` = 1, `confirmation` = 1, `restriction` = 0, `user_restriction` = 0 WHERE `id` = ' . $wish['id'])))
					$student = R::findOne('m4gusers', 'id=?', [$wish['user_id']]);
					$newLog = R::dispense('m4guserslogs');
					$newLog->user_id = $_SESSION['m4gister_user']['id'];
					$newLog->additional_id = $student['id'];
					$newLog->action = 'Принятие студента на тему "'.$theme['title'].'". Студент: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $student['fio'])).'.';
					$newLog->date = date('d.m.Y');
					R::store($newLog);
					$notify = R::dispense('m4gnotifications');
					$notify->user_id = $wish['user_id'];
					$notify->additional_id = $_SESSION['m4gister_user']['id'];
					$notify->notification = 'Запрос на выбранную вами тему "'.$theme['title'].'" был утвержден преподавателем. Преподаватель: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio']));
					$notify->date = date('d.m.Y');
					R::store($notify);
					echo json_encode('Запрос студента на тему принят!');
			} else echo json_encode('Лимит в 8 студентов на одну тему!');
		} else echo json_encode('Что-то пошло не так...');
	}
	if (isset($_POST['restrict'])) {
		$wish = R::findOne('m4gwishes', 'id=?', [$_POST['restrict']]);
		$theme = R::findOne('m4gthemes', 'id=?', [$wish['theme_id']]);
		if ($wish && $theme) {
			if (R::exec('UPDATE `m4gwishes` SET `reservation` = 1, `confirmation` = 0, `restriction` = 1, `user_restriction` = 0 WHERE `id` = ' . $wish['id'])){
				$student = R::findOne('m4gusers', 'id=?', [$wish['user_id']]);
				$newLog = R::dispense('m4guserslogs');
				$newLog->user_id = $_SESSION['m4gister_user']['id'];
				$newLog->additional_id = $student['id'];
				$newLog->action = 'Отмена запроса студента на тему "'.$theme['title'].'". Студент: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $student['fio'])).'.';
				$newLog->date = date('d.m.Y');
				R::store($newLog);
				$notify = R::dispense('m4gnotifications');
				$notify->user_id = $wish['user_id'];
				$notify->additional_id = $_SESSION['m4gister_user']['id'];
				$notify->notification = 'Запрос на выбранную вами тему "'.$theme['title'].'" был откланен преподавателем. Преподаватель: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio']));
				$notify->date = date('d.m.Y');
				R::store($notify);
				echo json_encode('Заспрос студента на тему отклонен!');
			} 
		} else echo json_encode('Что-то пошло не так...');
	}
	if (isset($_POST['restriction_confirm'])) {
		$wish = R::findOne('m4gwishes', 'id=?', [$_POST['restriction_confirm']]);
		$theme = R::findOne('m4gthemes', 'id=?', [$wish['theme_id']]);
		if ($wish && $theme) {
			if ((R::exec('UPDATE `m4gthemes` SET `confirmed` = "' . ($theme['confirmed'] - 1) . '" WHERE `id` = ' . $theme['id'])) && (R::exec('UPDATE `m4gwishes`  SET `reservation` = 0, `confirmation` = 0, `restriction` = 0, `user_restriction` = 0 WHERE id = ' . $_POST['restriction_confirm']))) {
				$student = R::findOne('m4gusers', 'id=?', [$wish['user_id']]);
				$newLog = R::dispense('m4guserslogs');
				$newLog->user_id = $_SESSION['m4gister_user']['id'];
				$newLog->additional_id = $student['id'];
				$newLog->action = 'Утверждение отказа студента на тему "'.$theme['title'].'". Студент: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $student['fio'])).'.';
				$newLog->date = date('d.m.Y');
				R::store($newLog);
				$notify = R::dispense('m4gnotifications');
				$notify->user_id = $wish['user_id'];
				$notify->additional_id = $_SESSION['m4gister_user']['id'];
				$notify->notification = 'Запрос на ваш отказ от темы "'.$theme['title'].'" был утвержден преподавателем. Преподаватель: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio']));
				$notify->date = date('d.m.Y');
				R::store($notify);
				echo json_encode('Отказ студента утверждён!');
			} else echo json_encode('Что-то пошло не так...');
		} else echo json_encode('Что-то пошло не так...');
	}
	if (isset($_POST['restriction_reject'])){
		$wish = R::findOne('m4gwishes', 'id=?', [$_POST['restriction_reject']]);
		$theme = R::findOne('m4gthemes', 'id=?', [$wish['theme_id']]);
		if ($wish && $theme) {
			if (R::exec('UPDATE `m4gwishes`  SET `reservation` = 1, `confirmation` = 1, `restriction` = 0, `user_restriction` = 0 WHERE id = ' . $_POST['restriction_reject'])) {
				$student = R::findOne('m4gusers', 'id=?', [$wish['user_id']]);
				$newLog = R::dispense('m4guserslogs');
				$newLog->user_id = $_SESSION['m4gister_user']['id'];
				$newLog->additional_id = $student['id'];
				$newLog->action = 'Отмена отказа студента на тему "'.$theme['title'].'". Студент: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $student['fio'])).'.';
				$newLog->date = date('d.m.Y');
				R::store($newLog);
				$notify = R::dispense('m4gnotifications');
				$notify->user_id = $wish['user_id'];
				$notify->additional_id = $_SESSION['m4gister_user']['id'];
				$notify->notification = 'Запрос на ваш отказ от темы "'.$theme['title'].'" был откланен преподавателем. Преподаватель: '.(preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['m4gister_user']['fio']));
				$notify->date = date('d.m.Y');
				R::store($notify);
				echo json_encode('Отказ студента отклонён!');
			} else echo json_encode('Что-то пошло не так...');
		} else echo json_encode('Что-то пошло не так...');
	}
  }
?>