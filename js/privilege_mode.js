//content 1 themes mode settings
$('.add_all_theme_mode li').click(function () {
	if ($('#not_allowed_themes_conteiner').css('display') === 'block') {
	  $('#not_allowed_themes_conteiner').css('display', 'none');
    $('#allowed_themes_conteiner').css('display', 'block');
    $('.sort_theme_mode').css('display', 'flex');
	  $('.add_all_theme_mode li:first-child').css('color', 'black');
	  $('.add_all_theme_mode li:last-child').css('color', '#66E275');
	} else {
    $('#not_allowed_themes_conteiner').css('display', 'block');
	  $('#allowed_themes_conteiner').css('display', 'none');
	  $('.sort_theme_mode').css('display', 'none');
	  $('.add_all_theme_mode li:first-child').css('color', '#66E275');
	  $('.add_all_theme_mode li:last-child').css('color', 'black');
	}
});

//get theme details settings
function show_theme_details(id) {
  if ($('#theme_show_details').css('display') === 'block') {
    $('.info_holder').fadeIn();
    $('#theme_show_details').fadeOut();  
    setTimeout(function () {
      $('.theme_details_conteiner p:nth-child(5) span').empty();
      $('.theme_details_conteiner p:nth-child(5) span').text('');
    }, 400);
  } else {
    if (id) {
     id = parseInt(id.match(/\d+/));
    }
    $.ajax({
      url: '../php/main/get_theme_details.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        theme_id: id
      },
      success: function(data){
        if (data instanceof Object) {
          var name_addition = data['post'];
          if (data['rank']) name_addition = data['post'] + '/' + data['rank'];
          $('.theme_details_conteiner .themes_main_details p:nth-child(1) span').text(data['fio'] + '(' + name_addition + ')');
					$('.theme_details_conteiner .themes_main_details p:nth-child(2) span').text(data['email']);
          $('.theme_details_conteiner .themes_main_details p:nth-child(3) span').text(data['title']);
          $('.theme_details_conteiner .themes_main_details p:nth-child(4) span').empty();
          if (data['file'] == 0) $('.theme_details_conteiner .themes_main_details p:nth-child(4) span').text('none');
          else $('.theme_details_conteiner .themes_main_details p:nth-child(4) span').append('<a href="' + data['file'] + '" download>' + 'Скачать' + '</a>');
          $('.theme_details_conteiner .description').text(data['description']);
          $('.theme_details_conteiner .theme_date span').text(data['date']);
          $('.info_holder').fadeOut();  
          $('#theme_show_details').fadeIn();   
        } else w_modal('Уведомление!', data); 
      },
      error:function(){
        w_modal('Уведомление!', 'Не удаётся открыть тему...Что-то пошло не так...');
      },
    });  
  }
}
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

//sort themes settings
$('.sort_theme_mode li').click(function () {
  if ($(this).prop('id') === 'sort_way') return false;
	$('.sort_theme_mode li').css('color', 'black');
	$(this).css('color', '#66E275');
	$('.allowed_themes_result').empty();
	if ($(this).prop('id') === 'theme_sort_all') {
		$('.allowed_themes_result').css('display', 'none');
		$('.allowed_themes').css('display', 'block');
	} else {
		$('.allowed_themes').css('display', 'none');
    $('.allowed_themes_result').css('display', 'block');
		$.ajax({
			url: '../php/privilege/theme_sort.php',
			type: 'POST',
			dataType: 'json',
			data: {
				sort: $(this).prop('id')
			},
			success: function(data){
        if (data instanceof Array){
          var show_theme = 'show_theme_details($(this).parent().prop(\'id\'))';
          var who = '';
          var file_color = '';
          for (var i = 0; i <= (data.length - 1); i++){
            var who = '';
            if (data[i]['rank']) who = data[i]['fio'] + '(' + data[i]['post'] + '/' + data[i]['rank'] + ')';
            else who = data[i]['fio'] + '(' + data[i]['post'] + ')';
            file_color = '#d54b3e';
            if (data[i]['file']) file_color = '#6d89d5'; 
            $('.allowed_themes_result').append('<div class="from_themes example_exception"><p class="from_themes_user">преподаватель: <span> ' + who + ' </span></p> <p class="from_themes_title">тема: <span>' + data[i]['title'] + '</span></p> <div id="sort_theme_index_' + data[i]['id'] + '" class="from_themes_intro"><i class="im im-paper-clip" style="color: ' + file_color + '" ></i>  <i class="im im-fullscreen" onclick="' + show_theme + '"></i></div><div class="theme_info"><i class="im im-star"><span>' + data[i]['wished'] + '</span></i><i class="im im-bookmark"><span>' + data[i]['confirmed'] + '/8</span></i></div></div>');  
          }
          if ((5 - data.length) > 0) {
            for (i = 0; i < (5 - data.length); i++) {
              $('.allowed_themes_result').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
            }
          }
        } else {
          $('.allowed_themes_result').empty();
          for (i = 0; i < 5; i++) {
            $('.allowed_themes_result').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
          }
        }
			},
			error: function(){
				w_modal('Уведомление!', 'Что-то пошло не так...');
			},
    });
	}
});

//allow/dismiss theme
function allowTheme(allow_theme, text) {
  $('.theme_delete').css('display', 'none');
  $('.from_themes_intro').css('display', 'none');
  var interval_num = 3;
  w_modal('Уведомление!', text + interval_num + '...');
  interval_num--;
  var interval = setInterval(function () {
    $('.w_text p').text(text + interval_num + '...');
    interval_num--;
  }, 1000); 
  setTimeout(function(){
    clearInterval(interval);
    if ($('.modal_window').css('display') === 'block') {
      $('.modal_window').fadeOut();
      $.ajax({
        url: '../php/privilege/allowed_theme.php',
        type: 'POST',
        dataType: 'json',
        data: allow_theme,
        processData: false,
        contentType: false,
        success: function(data){
          setTimeout(function () {
            $('.from_themes_intro').css('display', 'block');
            interval_num = 3;
            w_modal('Уведомление!', data + ' Обновление данных через ' + interval_num + '...');
            interval_num--;
            setInterval(function(){
              $('.w_text p').text(data + ' Обновление данных через ' + interval_num + '...');
              interval_num--;
            }, 1000); 
            setTimeout(function(){
              location.reload();
            }, 3000);
          }, 400);
        },
        error: function(){
          w_modal('Уведомление!', 'Что-то пошло не так...');
        },
      });
    } else {
      $('.theme_delete').css('display', 'block');
      $('.from_themes_intro').css('display', 'block');
      $('.modal_window').fadeOut();
      setTimeout(function () {
        w_modal('Уведомление!', 'Взаимодействие с темой отменено.');
      }, 400);
    }
  }, 3000);
}
$('.all_themes .im-check-mark-circle-o').click(function () {
  var id = $(this).parent().prop('id');
  id = parseInt(id.match(/\d+/));
  var allow_theme = new FormData();
  allow_theme.append('allow', id);
  allowTheme(allow_theme, 'Утверждение темы через ');
});
$('.all_themes .im-x-mark-circle-o').click(function () {
  var id = $(this).parent().prop('id');
  id = parseInt(id.match(/\d+/));
  var allow_theme = new FormData();
  allow_theme.append('dismiss', id);
  allowTheme(allow_theme, 'Отмена и удаление темы через ');
});