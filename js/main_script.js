//default settings
var activeMenu = false;
var constSpace = 30;
var mainNavigation = {
  profile: 0,
  themes: 1,
  query: 2,
  notifications: 3
};

//document change settings
$(window).on('load' , function () {
  
});
$( document ).ready(function () {
  items_control();
  for (key in mainNavigation) {
    $('#' + key + ' i').css('color' , 'dimgray');
    if (!($('#content_' + mainNavigation[key]).css('display') === 'none')) {
      $('#' + key + ' i').css('color' , '#6D89D5');
    }
  }
});
$(window).resize(function(){
  items_control();
});

//main items on page position settings
function items_control(){
  $('#content_nav').css('left' , ($('#main_section').width() - $('#content_conteiner').width()) / 4 - $('#content_nav').width() + 15 + 'px');
  
  var serch_theme_justify = ($('.all_themes_conteiner').width() - $('.about_themes .all_themes_search_conteiner .search_themes_input').width()) / 4;
  $('.about_themes .all_themes_search_conteiner .im-x-mark-circle-o').css('left' , serch_theme_justify - $('.about_themes .all_themes_search_conteiner .im-magnifier').width() / 2);
  $('.about_themes .all_themes_search_conteiner .im-magnifier').css('right' , serch_theme_justify - $('.about_themes .all_themes_search_conteiner .im-magnifier').width() / 2);
}

//content control settings
function change_content(identify){
  if (identify != 'log_out') {
    for (key in mainNavigation) {
      $('#content_' + mainNavigation[key]).fadeOut();
      $('#' + key + ' i').css('color' , 'dimgray');
    }
    setTimeout(function () {
      $('#content_' + mainNavigation[identify]).fadeIn();
      $('#' + identify + ' i').css('color' , '#6D89D5');
    }, 400);

    $.ajax({
      url: 'php/main/page_position.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        page_position: mainNavigation[identify]
      },
      success: function(data){
       return data;
      },
      error:function(){
        w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });  
  }
}
$('#content_nav div div').click(function () {
  change_content($(this).prop('id'));
});

//password input settings
$('.data_edit .im-eye-off').click(function(){
	if ($('.user_pass').attr('type') === 'password'){
    $('.user_pass').attr('type', 'text');
    $('.data_edit .im-eye-off').css('color', '#FF9A40');
	} else {
    $('.user_pass').attr('type', 'password');
    $('.data_edit .im-eye-off').css('color', '#6d89d5');
	}
}); 

//themes section settings

//search theme settings
function search_theme(value, type, isalert){
  if (value != '') {
    if (!is_priority) {
      for (var i = 1; i <= $('.all_themes').children().length; i++) {
        if ($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').css('color') === 'rgb(109, 137, 213)') {
          wanted_themes_color.push(parseInt(($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').parent().prop('id')).match(/\d+/)));
        }
      }
    }
    $.ajax({
      url: 'php/main/search_theme.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        wanted: value
      },
      success: function(data){
        if (data instanceof Array) {
          var show_theme = 'show_theme_details($(this).parent().prop(\'id\'))';
          var who = '';
          var wanted = '';
          var file_color = '';
          for (var i = 0; i <= (data.length - 1); i++){
            if (data[i]['rank']) who = data[i]['fio'] + '(' + data[i]['post'] + '/' + data[i]['rank'] + ')';
            else who = data[i]['fio'] + '(' + data[i]['post'] + ')';
            file_color = '#d54b3e';
            if (data[i]['file']) file_color = '#6d89d5';
            wanted = '';
            if (!is_priority) {
              if (wanted_themes_color.indexOf(parseInt(data[i]['id'])) != -1) wanted = '<i class="im im-star" onclick="add_a_favorite($(this).parent().prop(\'id\'))" style="color:#6D89D5"></i>';
              else wanted = '<i class="im im-star" onclick="add_a_favorite($(this).parent().prop(\'id\'))"></i>';
            }
            $(type).append('<div class="from_themes example_exception"><p class="from_themes_user">преподаватель: <span> ' + who + ' </span></p> <p class="from_themes_title">тема: <span>' + data[i]['title'] + '</span></p><div id="all_theme_index_' + data[i]['id'] + '" class="from_themes_intro">' + wanted + '<i class="im im-paper-clip" style=" color:' + file_color + '"></i><i class="im im-fullscreen" onclick="' + show_theme + '"></i></div><div class="theme_info"><i class="im im-star"><span>' + data[i]['wished'] + '</span></i><i class="im im-bookmark"><span>' + data[i]['confirmed'] + '/8</span></i></div></div>');  
          }
        } else if (isalert) w_modal('Уведомление!', data);
      },
      error: function(){
        if (isalert) w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });
  } else if (isalert) w_modal('Уведомление!', 'Запрос введен некорректно!');
}

//sort themes settings
$('.sort_theme_mode li').click(function () {
  if ($(this).prop('id') === 'sort_way') return false;
	$('.sort_theme_mode li').css('color', 'black');
	$(this).css('color', '#66E275');
	$('.themes_result').empty();
	if ($(this).prop('id') === 'theme_sort_all') {
		$('.themes_result').css('display', 'none');
		$('.all_themes').css('display', 'block');
	} else {
		$('.all_themes').css('display', 'none');
    $('.themes_result').css('display', 'block');
    if (!is_priority) {
      for (var i = 1; i <= $('.all_themes').children().length; i++) {
        if ($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').css('color') === 'rgb(109, 137, 213)') {
          wanted_themes_color.push(parseInt(($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').parent().prop('id')).match(/\d+/)));
        }
      }
    }
		$.ajax({
			url: 'php/main/theme_sort.php',
			type: 'POST',
			dataType: 'json',
			data: {
				sort: $(this).prop('id')
			},
			success: function(data){
        if (data instanceof Array){
          var show_theme = 'show_theme_details($(this).parent().prop(\'id\'))';
          var file_color = '';
          var who = '';
          var wanted = '';
          for (var i = 0; i <= (data.length - 1); i++){
            if (data[i]['rank']) who = data[i]['fio'] + '(' + data[i]['post'] + '/' + data[i]['rank'] + ')';
            else who = data[i]['fio'] + '(' + data[i]['post'] + ')';
            file_color = '#d54b3e';
            if (data[i]['file']) file_color = '#6d89d5'; 
            if (!is_priority) {
              if (wanted_themes_color.indexOf(parseInt(data[i]['id'])) != -1) wanted = '<i class="im im-star" onclick="add_a_favorite($(this).parent().prop(\'id\'))" style="color:#6D89D5"></i>';
              else wanted = '<i class="im im-star" onclick="add_a_favorite($(this).parent().prop(\'id\'))"></i>';
            }
            $('.themes_result').append('<div class="from_themes example_exception"><p class="from_themes_user">преподаватель: <span> ' + who + ' </span></p> <p class="from_themes_title">тема: <span>' + data[i]['title'] + '</span></p> <div id="sort_theme_index_' + data[i]['id'] + '" class="from_themes_intro">' + wanted + '<i class="im im-paper-clip" style="color: ' + file_color + '" ></i>  <i class="im im-fullscreen" onclick="' + show_theme + '"></i></div><div class="theme_info"><i class="im im-star"><span>' + data[i]['wished'] + '</span></i><i class="im im-bookmark"><span>' + data[i]['confirmed'] + '/8</span></i></div></div>');  
          }
          if ((5 - data.length) > 0) {
            for (i = 0; i < (5 - data.length); i++) {
              $('.themes_result').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
            }
          }
        } else {
          $('.themes_result').empty();
          for (i = 0; i < 5; i++) {
            $('.themes_result').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
          }
        }
			},
			error: function(){
				w_modal('Уведомление!', 'Что-то пошло не так...');
			},
    });
	}
});

//themes refresh settings
$('.im-sync').click(function () {
  $(this).addClass('sync_animate');
});
function themesRefresh(request, where) {
	$.ajax({
		url: 'php/main/themes_refresh.php', 
		type: 'POST',
		dataType: 'json', 
		data: {
			requestRefresh: request
		},
		success: function (data) {
      setTimeout(function () {
        $('.im-sync').removeClass('sync_animate');
      }, 500);
      $('.sort_theme_mode li').css('color', 'black');
      $('.sort_theme_mode li:first-child').css('color', '#66E275');
      $('.themes_result').css('display', 'none');
		  $('.all_themes').css('display', 'block');
      if (data instanceof Array) {
        if (request === 'all') {
          $(where).empty();
          var show_theme = 'show_theme_details($(this).parent().prop(\'id\'))';
          var file_color = '';
          var wanted = '';
          for (var i = 0; i < data.length; i++) {
            file_color = '#d54b3e';
            if (data[i]['file']) file_color = '#6d89d5';
            if (!is_priority) var wanted = '<i class="im im-star" onclick="add_a_favorite($(this).parent().prop(\'id\'))"></i>';
            $(where).append('<div class="from_themes"><p class="from_themes_user">преподаватель: <span>' + data[i]['user'] + '</span> <p class="from_themes_title">тема: <span>' + data[i]['theme'] + '</span></p> <div id="all_theme_index_' + data[i]['id'] + '" class="from_themes_intro"> ' + wanted + ' <i class="im im-paper-clip" style="color: ' + file_color + '"></i><i class="im im-fullscreen" onclick=" ' + show_theme + ' " ></i></div>     <div class="theme_info"><i class="im im-star"><span>' + data[i]['wished'] + '</span></i><i class="im im-bookmark"><span>' + data[i]['confirmed'] + '/8</span></i></div></div>');	
          }
          if ((5 - data.length) > 0) {
            for (i = 0; i < (5 - data.length); i++) {
              $(where).append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
            }
          }
        } else {
          yourPriorityThemesRefresh(data, request, where);
        }	
      } else {
        $(where).empty();
        for (i = 0; i < 5; i++) {
          $(where).append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
        }
      }
      if (!is_priority) {
        for (i = 0; i < wanted_themes_color.length; i++) {
          $('#all_theme_index_' + wanted_themes_color[i] + ' i.im-star').css('color', '#6D89D5');
        }
      }
		},
		error:function () {
			w_modal('Уведомление!', 'Что-то пошло не так...');
		},
	});
}
$('#content_1 .im-sync').click(function () {
	$('#all_themes_conteiner .all_themes').animate({
		opacity: 0
	}, {
		duration: 400,
		easing: "linear"
		}
	);
	setTimeout(function () {
    for (var i = 1; i <= $('.all_themes').children().length; i++) {
        if ($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').css('color') === 'rgb(109, 137, 213)') {
          wanted_themes_color.push(parseInt(($('.all_themes .from_themes:nth-child(' + i + ')').find('.from_themes_intro i.im-star').parent().prop('id')).match(/\d+/)));
        }
    }
    themesRefresh(refresh_request, '#all_themes_conteiner .all_themes');
		$('#all_themes_conteiner .all_themes').animate({
			opacity: 1
		}, {
			duration: 400,
			easing: "linear"
			}
		);
	}, 500);
});

//get theme details settings
function show_theme_details(id) {
  if ($('#theme_show_details').css('display') === 'block') {
    $('.info_holder').fadeIn();
    $('#theme_show_details').fadeOut();  
    setTimeout(function () {
      $('.theme_details_conteiner p:nth-child(5) span').empty();
      $('.theme_details_conteiner p:nth-child(5) span').text('');
      $('.theme_details_conteiner .themes_main_details p:nth-child(4) span').empty();
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
          $('.theme_details_conteiner .themes_main_details p:nth-child(1) span').text(data['fio'] + '(' + name_addition + ')');
					$('.theme_details_conteiner .themes_main_details p:nth-child(2) span').text(data['email']);
          $('.theme_details_conteiner .themes_main_details p:nth-child(3) span').text(data['title']);
          if (data['file'] == 0) $('.theme_details_conteiner .themes_main_details p:nth-child(4) span').text('нет');
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

//notification settings
$('#content_3 i.im-fullscreen').click(function () {
  w_modal('Уведомление!', $('#my_notification_' + parseInt($(this).parent().prop('id').match(/\d+/)) + ' .from_notifications_notification span').text());
});
$('#content_3 i.im-check-mark-circle-o').click(function () {
 var id = parseInt($(this).parent().prop('id').match(/\d+/));
 $.ajax({
    url: 'php/main/notifications.php', 
    type: 'POST',
    dataType: 'json', 
    data: {
      notify_id: id
    },
    success: function(data){
      if (data) {
        $('#my_notification_' + id).css('background', 'rgba(109, 137, 213, 0.5)');
        $('#my_notification_' + id + ' .from_notification_intro .im-check-mark-circle-o').remove();
      }
      else {
        $('#my_notification_' + id).css('background', 'rgba(213, 75, 62, 0.5)');
        w_modal('Уведомление!', 'Что-то пошло не так...');
      }
    },
    error:function(){
      w_modal('Уведомление!', 'Что-то пошло не так...');
    },
  }); 
});
$('#content_3 i.im-trash-can').click(function () {
  var id = parseInt($(this).parent().prop('id').match(/\d+/));
  $.ajax({
     url: 'php/main/notifications.php', 
     type: 'POST',
     dataType: 'json', 
     data: {
       notify_delete_id: id
     },
     success: function(data){
       if (data) {
         $('#my_notification_' + id).css('background', 'rgba(213, 75, 62, 0.5)');
         $('#my_notification_' + id + ' .from_notification_intro').remove();
       } else {
         $('#my_notification_' + id).css('background', 'rgba(213, 75, 62, 0.5)');
         w_modal('Уведомление!', 'Что-то пошло не так...');
       }
     },
     error:function(){
       w_modal('Уведомление!', 'Что-то пошло не так...');
     },
   }); 
 });

//log out settings
$('#log_out').click(function () {
  $.ajax({
    url: 'php/log_out.php', 
    type: 'POST',
    dataType: 'json', 
    data: {
      log_out: true
    },
    success: function(data){
      var num = 3;
      w_modal('Уведомление!', data + ' Перезагрузка через ' + num + '...');
      num--;
      setInterval(function(){
        $('.w_text p').text(data + ' Перезагрузка через ' + num + '...');
        num--;
      }, 1000); 
      setTimeout(function(){
        location.reload();
      }, 3000);
    },
    error:function(){
      w_modal('Уведомление!', 'Что-то пошло не так...');
    },
  });
});