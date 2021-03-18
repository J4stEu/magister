//priority 1 settings
//default settings
var inputFile = false;
var newInputFile = false;
var updateChange = false;
var theme_queries_mode = 1;
var is_priority = true;

$('#themes').click(function () {
	refresh_request = 'all';
});
$('#query').click(function () {
	refresh_request = 'theme_requests';
	yourThemesRefresh();
});

//theme add settings

//theme file settings
function themeFile(){
	inputFile = $('#theme_info_file');
	if (inputFile.val() != '') {
		var ext = '';
		for (var i = 0; i <= (allowed_filetypes.length - 1); i++){
			if (inputFile.val().indexOf(allowed_filetypes[i]) != -1) { 
				ext = allowed_filetypes[i];
				break;
			} else ext = false;
		}
		if (!ext){
			inputFile = false;
			w_modal('Уведомление!', 'Данный тип файла не поддерживается...');
		} else {
			$('.file_for_theme i').css('color' , '#66E275');
			w_modal('Уведомление!', 'Выбран файл ' + inputFile[0].files[0].name);
		}
	} else {
		$('.file_for_theme i').css('color' , '#d54b3e');
		w_modal('Уведомление!', 'Файл не выбран...');
	}
}
$('#theme_info_file').change(function () {
	themeFile();
});
//find theme example settings
$('.add_theme_title').keypress(function () {
	$('.example_themes').empty();
	search_theme($('.add_theme_title').val(), '.example_themes', false);
});
//clear theme inputs settings
function clearTheme() {
	$('#add_themes_conteiner .add_theme_title').val('');
	$('#add_themes_conteiner .add_theme_description').val('');
	$('.example_themes').empty();
	for (var i = 0; i < 3; i++){
		$('.example_themes').append('<div class="from_themes"></div>');
	}
	$('#theme_info_file').val('');
	inputFile = false;
}
$('#add_themes_conteiner i.im-x-mark-circle-o').click(function () {
	clearTheme();
	themeFile();
});
//post your theme
function postTheme(newTheme){
	$.ajax({
		url: 'php/priority/post_theme.php',
		type: 'POST',
		dataType: 'json',
		data: {
			title: $('.add_theme_title').val(),
			description: $('.add_theme_description').val(),
		},
		success: function(data_1){
			if (data_1 instanceof Object) {
				if (newTheme instanceof Object && newTheme instanceof FormData){
					newTheme.append('file_id', data_1[3]);
					$.ajax({
						url: 'php/priority/post_theme.php',
						type: 'POST',
						dataType: 'json',
						data: newTheme,
						processData: false,
						contentType: false,
						success: function(data_2){
							w_modal('Уведомление!', data_1[0] + data_2);
							$('.file_for_theme i').css('color' , '#d54b3e');
						},
						error: function(){
							w_modal('Уведомление!', 'Что-то пошло не так...');
						},
					});	
				} else {
					w_modal('Уведомление!', data_1[0] + 'Файл не был загружен.');
					clearTheme();
					$('.file_for_theme i').css('color' , '#d54b3e');
				}
			} else w_modal('Уведомление!', data_1);
		},
		error: function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}
function themeValidate(){
	if (($('.add_theme_title').val() != '') && ($('.add_theme_description').val() != '')){
		if (inputFile) {
			if (inputFile.val() != '') {
				var newTheme = new FormData();
				newTheme.append('theme_file', inputFile.prop('files')[0]);
			} else var newTheme = false;
		} else var newTheme = false;
		postTheme(newTheme);
	} else w_modal('Уведомление!', 'Данные введены некорректно! Ошибка заполнения полей: тема, описание.');
}
$('#add_themes_conteiner .im-paperplane').click(function () {
	themeValidate();
});

//content 1 themes mode settings
$('.add_all_theme_mode li').click(function () {
	if ($('#all_themes_conteiner').css('display') === 'block') {
	  $('#all_themes_conteiner').css('display', 'none');
	  $('#add_themes_conteiner').css('display', 'block');
	  $('#content_1 .content_title i.im-sync').css('display', 'none');
	  $('.sort_theme_mode').css('display', 'none');
	  $('.add_all_theme_mode li:first-child').css('color', '#66E275');
	  $('.add_all_theme_mode li:last-child').css('color', 'black');
	} else {
	  $('#add_themes_conteiner').css('display', 'none');
	  $('#all_themes_conteiner').css('display', 'block');
	  $('#content_1 .content_title i.im-sync').css('display', 'block');
	  $('.sort_theme_mode').css('display', 'flex');
	  $('.add_all_theme_mode li:first-child').css('color', 'black');
	  $('.add_all_theme_mode li:last-child').css('color', '#66E275');
	}
});

//search your theme settings
$('.all_themes_footer .im-magnifier').click(function () {
  $('#all_themes_conteiner .themes_result').empty();
  search_theme($('.all_themes_footer .search_themes_input').val(),'#all_themes_conteiner .themes_result', true);
  $('.all_themes').css('display' , 'none');
  $('.themes_result').css('display' , 'block');
  $('.all_themes_search i.im-x-mark-circle-o').css('display', 'block');
});
$('.all_themes_footer .im-x-mark-circle-o').click(function () {
  $('.all_themes_footer .search_themes_input').val('');
  $('#all_themes_conteiner .themes_result').empty();
  $('.themes_result').css('display' , 'none');
  $('.all_themes').css('display' , 'block');
  $('.all_themes_footer .im-x-mark-circle-o').css('display', 'none');
});

//show/close theme details
$('.from_themes i.im-fullscreen').click(function () {
  show_theme_details($(this).parent().prop('id'));
});
$('#close_theme_details').click(function () {
  show_theme_details(false);
});
$('.theme_show_details_shadow').click(function () {
  show_theme_details(false);
});

//edit theme details
function edit_theme(id) {
	if ($('#theme_details_edit').css('display') === 'block') {
	  $('.info_holder').fadeIn();
	  $('#theme_details_edit').fadeOut(); 
	  $('#update_theme_id').removeClass(); 
	  setTimeout(function () {
		$('.theme_details_edit_conteiner .themes_main_details p:nth-child(3) input').val('');
		$('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').text('');
		$('.theme_details_edit_conteiner .description_edit').val('');
	  }, 400);
	} else {
	  if (id) {
	   id = parseInt(id.match(/\d+/));
	  }
	  $.ajax({
		url: 'php/main/get_theme_details.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
		  theme_id: id
		},
		success: function(data){
		  if (data instanceof Object) {
			var name_addition = data['post'];
			if (data['rank']) name_addition = data['post'] + '/' + data['rank'];
			$('.theme_details_edit_conteiner .themes_main_details p:nth-child(1) span').text(data['fio'] + '(' + name_addition + ')');
			$('.theme_details_edit_conteiner .themes_main_details p:nth-child(2) span').text(data['email']);
			$('.theme_details_edit_conteiner .themes_main_details p:nth-child(3) input').val(data['title']);
			if (data['file'] == 0) $('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').text('нет');
			else $('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').append('<a href="' + data['file'] + '" download>' + 'Скачать' + '</a>');
			$('.theme_details_edit_conteiner .description_edit').val(data['description']);
			$('.theme_details_edit_conteiner .theme_date span').text(data['date']);
			$('#update_theme_id').addClass('updateTheme' + id);
			$('.info_holder').fadeOut();  
			$('#theme_details_edit').fadeIn();  
		  } else w_modal('Уведомление!', data); 
		},
		error:function(){
		  w_modal('Уведомление!', 'Не удаётся открыть тему...Что-то пошло не так...');
		},
	  });  
	}
}
$('.from_themes .im-pencil').click(function () {
	edit_theme($(this).parent().prop('id'));
});
$('#close_theme_edit').click(function () {
	edit_theme(false);
});
//update theme file settings
function editThemeFile(){
	newInputFile = $('#update_theme_info_file');
	if (newInputFile.val() != '') {
		var ext = '';
		for (var i = 0; i <= (allowed_filetypes.length - 1); i++){
			if (newInputFile.val().indexOf(allowed_filetypes[i]) != -1) { 
				ext = allowed_filetypes[i];
				break;
			} else ext = false;
		}
		if (!ext){
			newInputFile = false;
			w_modal('Уведомление!', 'Данный тип файла не поддерживается...');
		} else {
			$('.update_file_for_theme i').css('color' , '#66E275');
			w_modal('Уведомление!', 'Выбран файл ' + newInputFile[0].files[0].name);
			updateChange = true;
		}
	} else {
		$('.update_file_for_theme i').css('color' , '#d54b3e');
		w_modal('Уведомление!', 'Файл не выбран...');
	}
}
$('.theme_edit').change(function () {
	updateChange = true;
});
$('.description_edit').change(function () {
	updateChange = true;
});
function editThemeValidate(id){
	id = parseInt(id.match(/\d+/));
	if (($('.theme_edit').val() != '') && ($('.description_edit').val() != '') && updateChange){
		if (newInputFile) {
			if (newInputFile.val() != '') {
				var newTheme = new FormData();
				newTheme.append('theme_file', newInputFile.prop('files')[0]);
			} else var newTheme = false;
		} else var newTheme = false;
		updateTheme(id, newTheme);
	} else w_modal('Уведомление!', 'Данные введены некорректно! Ошибка заполнения полей: тема, описание.');
}
$('#theme_details_edit .im-paperplane').click(function () {
	editThemeValidate($('#update_theme_id').prop('class'));
});
$('#update_theme_info_file').change(function () {
	editThemeFile();
});
//update your theme
function updateTheme(id, newTheme){
	$.ajax({
		url: 'php/priority/update_theme.php',
		type: 'POST',
		dataType: 'json',
		data: {
			id: id,
			title: $('.theme_edit').val(),
			description: $('.description_edit').val(),
		},
		success: function(data_1){
			if (data_1 instanceof Object) {
				if (newTheme instanceof Object && newTheme instanceof FormData){
					newTheme.append('file_id', data_1[3]);
					$.ajax({
						url: 'php/priority/update_theme.php',
						type: 'POST',
						dataType: 'json',
						data: newTheme,
						processData: false,
						contentType: false,
						success: function(data_2){
							$('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').empty();
							$('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').append('<a href="' + data_2[1] + '" download>' + 'Скачать' + '</a>');
							w_modal('Уведомление!', data_1[0] + data_2[0]);
							$('#update_theme_info_file').val('');
							newInputFile = false;
							$('.update_file_for_theme i').css('color' , '#d54b3e');
							updateChange = false;
							yourThemesRefresh();
						},
						error: function(){
							w_modal('Уведомление!', 'Что-то пошло не так...');
						},
					});	
				} else {
					w_modal('Уведомление!', data_1[0] + 'Файл не был обновлен.');
					$('.file_for_theme i').css('color' , '#d54b3e');
				}
			} else w_modal('Уведомление!', data_1);
		},
		error: function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}
function deleteThemeFile(id) {
	id = parseInt(id.match(/\d+/));
	$.ajax({
		url: 'php/priority/update_theme.php',
		type: 'POST',
		dataType: 'json',
		data: {
			file_delete_id: id,
		},
		success: function(data){
			$('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').empty();
			$('.theme_details_edit_conteiner .themes_main_details p:nth-child(4) span').append('<span>нет</span>');
			w_modal('Уведомление!', data);
		},
		error: function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}
$('#theme_details_edit .im-x-mark').click(function () {
	deleteThemeFile($('#update_theme_id').prop('class'));
});
function themeDelete(id) {
	id = parseInt(id.match(/\d+/));
	$('.theme_update_confirm').css('display', 'none');
	$('.theme_delete').css('display', 'none');
	var interval_num = 3;
	w_modal('Уведомление!', 'Удаление темы через ' + interval_num + '...');
	interval_num--;
	var interval = setInterval(function () {
		$('.w_text p').text('Удаление темы через ' + interval_num + '...');
		interval_num--;
	}, 1000); 
	setTimeout(function () {
		clearInterval(interval);
		if ($('.modal_window').css('display') === 'block') {
			$('.modal_window').fadeOut();
			$.ajax({
				url: 'php/priority/update_theme.php',
				type: 'POST',
				dataType: 'json',
				data: {
					theme_delete_id: id,
				},
				success: function(data){
					edit_theme(false);
					yourThemesRefresh();
					$('.theme_update_confirm').css('display', 'block');
					$('.theme_delete').css('display', 'block');
					setTimeout(function () {
						w_modal('Уведомление!', data);
					}, 400);
				},
				error: function(){
					setTimeout(function () {
						w_modal('Уведомление!', 'Что-то пошло не так...');
					}, 400);
				},
			});
		} else {
			$('.theme_update_confirm').css('display', 'block');
			$('.theme_delete').css('display', 'block');
			$('.modal_window').fadeOut();
			setTimeout(function () {
			  w_modal('Уведомление!', 'Удаление темы отменено.');
			}, 400);
		}
	}, 3000);
}
$('#theme_details_edit .im-trash-can').click(function () {
	themeDelete($('#update_theme_id').prop('class'));
});

//content 2 themes mode settings
$('#content_2 .content_themes_settings li').click(function () {
	$('#content_2 .content_themes_settings li').css('color', 'black');
	$(this).css('color', '#66E275');
	switch ($(this).prop('class')) {
		case 'theme_queries':
			$('#content_2 .about_themes').children().css('display', 'none');
			$('#your_themes_conteiner').css('display', 'block');
			theme_queries_mode = 1;
			refresh_request = 'theme_requests';
			break;
		case 'theme_confirmed_queries':
			$('#content_2 .about_themes').children().css('display', 'none');
			$('#your_confirmed_themes_conteiner').css('display', 'block');
			theme_queries_mode = 2;
			refresh_request = 'theme_confirmed';
		 	break;
		case 'theme_restricted_queries':
			$('#content_2 .about_themes').children().css('display', 'none');
			$('#your_restricted_themes_conteiner').css('display', 'block');
			theme_queries_mode = 3;
			refresh_request = 'theme_restricted';
		 	break;
		case 'theme_user_restricted_queries':
			$('#content_2 .about_themes').children().css('display', 'none');
			$('#your_user_restricted_themes_conteiner').css('display', 'block');	
			theme_queries_mode = 4;
			refresh_request = 'theme_user_restricted';
		 	break;
	}
	yourThemesRefresh();
});
$('#content_1 .add_all_theme_mode li:last-child').click(function () {
	refresh_request = 'all';
});

//students queries

//query section settings
function show_students_query() {
	if ($('#students_query').css('display') === 'none') {
		$('#students_query').fadeIn();
		$('#content_2 .info_holder').fadeOut();
	} else {
		$('#students_query').fadeOut();
		$('#content_2 .info_holder').fadeIn();
	}
}
//general theme queries
function generalThemesRequest(request) {
	$.ajax({
		url: 'php/priority/themes_queries.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
			AllTypeRequest: request
		},
		success: function(data){
			if (data instanceof Array) {
				$('#students_query .students_theme_query').empty();
				var query_color = '';
				var query_settings = '';
				var from_themes_title = '';
				for (i = 0; i < data.length; i++) {
					switch (theme_queries_mode) {
						case 1:
							query_settings = '<span>' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a><i class="im im-check-mark-circle" onclick="themeConfData($(this).parent().prop(\'id\'), true);"></i><i class="im im-x-mark-circle" onclick="themeConfData($(this).parent().prop(\'id\'), false)"></i>';
							from_themes_title = 'Нет запросов!';
							break;
						case 2:
							query_settings = '<span>' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a>';
							query_color = 'rgba(102, 226, 117, 0.5)';
							from_themes_title = 'Нет принятых!';
							break;
						case 3:
							query_color = '#d54b3e';
							from_themes_title = 'Нет отмененных!';
							break;
						case 4:
							query_settings = '<span style="color: black">' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a><i class="im im-check-mark-circle" style="color:black" onclick="themeRejextionConf($(this).parent().prop(\'id\'))"></i>';
							query_color = '#d54b3e';
							from_themes_title = 'Нет отказов!';
							break;
					}
					$('#students_query .students_theme_query').append('<div class="from_themes" style="background: ' + query_color + '"><p class="from_themes_user">студент: <span>' + data[i]['fio'] + '(' + data[i]['rank'] + '/' + data[i]['st_group'] + ')' +  '</span></p><p class="from_themes_title">Email: <span>' + data[i]['email'] + '</span></p><p class="from_themes_title">Тема: <span>' + data[i]['theme_title'] + '</span> </p><div id="confirm_theme_index_' + data[i]['id'] + '" class="from_themes_intro">' + query_settings + ' </div></div	>');
				}	
				$('.content_2_theme_query_type span').text('Все');
				show_students_query();
			} else {
				switch (theme_queries_mode) {
					case 1:
						from_themes_title = 'Нет запросов!';
						break;
					case 2:
						from_themes_title = 'Нет принятых!';
						break;
					case 3:
						from_themes_title = 'Нет отмененных!';
						break;
					case 4:
						from_themes_title = 'Нет отказов!';
						break;
				}
				$('#students_query .students_theme_query').empty();
				$('#students_query .students_theme_query').append('<div class="from_themes" style="background: #d54b3e"><p class="from_themes_title"><span style="text-decoration: none">' + from_themes_title + '</span></p></div	>');
				$('.content_2_theme_query_type span').text('Все');
				show_students_query();
			}
			yourThemesRefresh();
		},
		error:function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	}); 
}
$('.your_themes_students_query_button').click(function () {
	generalThemesRequest('all');
});
$('.your_themes_confirmed_students_query_button').click(function () {
	generalThemesRequest('confirmed');
});
$('.your_themes_restricted_students_query_button').click(function () {
	generalThemesRequest('restricted');
});
$('.your_themes_user_restricted_students_query_button').click(function () {
	generalThemesRequest('user_restricted');
});
//close/show query modal settings
$('#students_query .close_students_query .im-x-mark-circle-o').click(function () {
	show_students_query();
});
$('#students_query .students_query_shadow').click(function () {
	show_students_query();
});
//certain theme queries
function certain_theme_queries(id, request, theme_title){
	id = parseInt(id.match(/\d+/));
	request = request.trim();
	$.ajax({
		url: 'php/priority/themes_queries.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
			theme_id: id,
			CertainTypeRequest: request
		},
		success: function(data){
			if (data instanceof Array) {
				$('#students_query .students_theme_query').empty();
				var query_color = '';
				var query_settings = '';
				$('#students_query .students_theme_query').empty();
				for (i = 0; i < data.length; i++) {
					switch (theme_queries_mode) {
						case 1:
							query_settings = '<span>' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a><i class="im im-check-mark-circle" onclick="themeConfData($(this).parent().prop(\'id\'), true);"></i><i class="im im-x-mark-circle" onclick="themeConfData($(this).parent().prop(\'id\'), false)"></i>';
							break;
						case 2:
							query_settings = '<span>' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a>';
							query_color = 'rgba(102, 226, 117, 0.5)';
							break;
						case 3:
							query_color = '#d54b3e';
							break;
						case 4:
							query_settings = '<span style="color: black">' + data[i]['file']['title'] + '</span><a href="' + data[i]['file']['file'] + '" download><i class="im im-paper-clip"></i></a><i class="im im-check-mark-circle" style="color: black" onclick="themeRejextionConf($(this).parent().prop(\'id\'))"></i><i class="im im-x-mark-circle" style="color: black" onclick="themeRejextionReject($(this).parent().prop(\'id\'))"></i>';
							query_color = '#d54b3e';
							break;
					}
					$('#students_query .students_theme_query').append('<div class="from_themes" style="background: ' + query_color + '"><p class="from_themes_user">студент: <span>' + data[i]['fio'] + '(' + data[i]['rank'] + '/' + data[i]['st_group'] + ')' +  '</span></p><p class="from_themes_email">Email: <span>' + data[i]['email'] + '</span></p><div id="confirm_theme_index_' + data[i]['id'] + '" class="from_themes_intro">' + query_settings + '</div></div	>');
				}
				$('.content_2_theme_query_type span').text(theme_title);
				show_students_query();
			} else {
				switch (theme_queries_mode) {
					case 1:
						from_themes_title = 'Нет запросов!';
						break;
					case 2:
						from_themes_title = 'Нет принятых!';
						break;
					case 3:
						from_themes_title = 'Нет отмененных!';
						break;
					case 4:
						from_themes_title = 'Нет отказов!';
						break;
				}
				$('#students_query .students_theme_query').empty();
				$('#students_query .students_theme_query').append('<div class="from_themes" style="background: #d54b3e"><p class="from_themes_title"><span style="text-decoration: none">' + from_themes_title + '</span></p></div	>');
				$('.content_2_theme_query_type span').text(theme_title);
				show_students_query();
			}
			yourThemesRefresh();
		},
		error:function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});  
}
$('#content_2 #your_themes_conteiner i.im-eye').click(function () {
	certain_theme_queries($(this).parent().prop('id'), 'reserved', $(this).parent().parent().find('.from_themes_title span').text());
});
$('#content_2 #your_confirmed_themes_conteiner i.im-eye').click(function () {
	certain_theme_queries($(this).parent().prop('id'), 'confirmed', $(this).parent().parent().find('.from_themes_title span').text());
});
$('#content_2 #your_restricted_themes_conteiner i.im-eye').click(function () {
	certain_theme_queries($(this).parent().prop('id'), 'restricted', $(this).parent().parent().find('.from_themes_title span').text());
});
$('#content_2 #your_user_restricted_themes_conteiner i.im-eye').click(function () {
	certain_theme_queries($(this).parent().prop('id'), 'user_restricted', $(this).parent().parent().find('.from_themes_title span').text());
});

//confirm/restrict theme
function themeConfirmation(confirm){
	$.ajax({
		url: 'php/priority/theme_confirmation.php', 
		type: 'POST',
		dataType: 'json', 
		data: confirm,
		processData: false,
		contentType: false,
		success: function(data){
			w_modal('Уведомление!', data);
			show_students_query();
			yourThemesRefresh();

		},
		error:function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
			show_students_query();
		},
	});
}
//confirm/restrict query settings
function themeConfData(id, conf) {
	id = parseInt(id.match(/\d+/));
	if (conf) {
		var confirm_query = new FormData();
		confirm_query.append('confirm', id);
		themeConfirmation(confirm_query);
	} else {
		var restrict_query = new FormData();
		restrict_query.append('restrict', id);
		themeConfirmation(restrict_query);
	}
}
$('#students_query .im-check-mark-circle').click(function () {
	themeConfData($(this).parent().prop('id'),true);
});
$('#students_query .im-x-mark-circle').click(function () {
	themeConfData($(this).parent().prop('id'),false);
});

//confirm user rejection settings
function themeRejextionConf(id){
	id = parseInt(id.match(/\d+/));
	$.ajax({
		url: 'php/priority/theme_confirmation.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
			restriction_confirm: id
		},
		success: function(data){
			w_modal('Уведомление!', data);
			$('#students_query .students_theme_query').empty();
		},
		error:function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}
//reject user rejection settings
function themeRejextionReject(id){
	id = parseInt(id.match(/\d+/));
	$.ajax({
		url: 'php/priority/theme_confirmation.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
			restriction_reject: id
		},
		success: function(data){
			w_modal('Уведомление!', data);
			$('#students_query .students_theme_query').empty();
		},
		error:function(){
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}

//refresh your themes
function yourThemesRefresh() {
	var whereRefresh = '';
	switch (refresh_request) {
		case 'theme_requests':
			whereRefresh = '#your_themes_conteiner .your_themes';
			break;
		case 'theme_confirmed':
			whereRefresh = '#your_confirmed_themes_conteiner .your_confirmed_themes';
			break;
		case 'theme_restricted':
			whereRefresh = '#your_restricted_themes_conteiner .your_restricted_themes';
			break;
		case 'theme_user_restricted':
			whereRefresh = '#your_user_restricted_themes_conteiner .your_user_restricted_themes';
			break;
	}
	themesRefresh(refresh_request, whereRefresh);
}
function yourPriorityThemesRefresh(data, request, where) {
	var open = '';
	switch (request) {
	case 'theme_requests':
		open = '\'reserved\'';
		break;
	case 'theme_confirmed':
		open = '\'confirmed\'';
		break;
	case 'theme_restricted':
		open = '\'restricted\'';
		break;
	case 'theme_user_restricted':
		open = '\'user_restricted\'';
		break;
	}
	$(where).empty();
	var show_theme = 'show_theme_details($(this).parent().prop(\'id\'))';
	var file_color ='';
	var allowed_color = '';
	var allowed_theme_check = '';
	for (i = 0; i < data.length; i++) {
	if (!data[i]['allowed']) {
		allowed_color = 'style="background:rgba(255, 154, 64, 0.5)"';
		allowed_theme_check = '<i class="im im-pencil" onclick="edit_theme($(this).parent().prop(\'id\'))"></i>';
	} else {
		allowed_color = 'style="background:rgba(109, 137, 213, 0.5)"';
		allowed_theme_check = '<i class="im im-eye" onclick="certain_theme_queries($(this).parent().prop(\'id\'), ' + open + ', $(this).parent().parent().find(\'.from_themes_title span\').text())"></i>';
	}
	file_color = '#d54b3e';
	if (data[i]['file']) file_color = '#6d89d5' ;
	$(where).append('<div class="from_themes" ' + allowed_color + '><p class="from_themes_title">тема: <span>'+ data[i]['theme'] + '</span></p><div id="confirm_theme_index_' + data[i]['id'] + '" class="from_themes_intro">' + allowed_theme_check + '<i class="im im-paper-clip" style="color: ' + file_color + '"></i><i class="im im-fullscreen" onclick=" ' + show_theme + ' "></i></div><div class="theme_info"><i class="im im-star"><span>' + data[i]['wished'] + '</span></i><i class="im im-bookmark"><span>' + data[i]['confirmed'] + '/8</span></i></div></div>');	
	}
	if ((5 - data.length) > 0) {
		for (i = 0; i < (5 - data.length); i++) {
			$(where).append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
		}
	}
}
$('#content_2 .im-sync').click(function () {
	whereRefresh = $('#content_2 .about_themes div div').not('.themes_query_button');
	$(whereRefresh).animate({
		opacity: 0
	}, {
			duration: 400,
			easing: "linear"
		}
	);
	setTimeout(function () {
		yourThemesRefresh();
		$(whereRefresh).animate({
			opacity: 1
		}, {
			duration: 400,
			easing: "linear"
			}
		);
	}, 500);
});