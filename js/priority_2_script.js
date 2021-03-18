//default settings
var choose_theme = 0;
var reject_theme = 0;
var reject_mode = false;
var is_priority = false;
var wanted_themes_color = new Array();

//theme section settings
//show theme details
$('.from_themes .im-fullscreen').click(function () {
  show_theme_details($(this).parent().prop('id'));
});
//close theme details
$('#close_theme_details').click(function () {
  show_theme_details(false);
});
$('.theme_show_details_shadow').click(function () {
  show_theme_details(false);
});

//theme type settings
//content 1 themes mode settings
$('.all_wish_theme_mode li').click(function () {
	if ($('#all_themes_conteiner').css('display') === 'block') {
	  $('#all_themes_conteiner').css('display', 'none');
	  $('#wanted_themes_conteiner').css('display', 'block');
	  $('#content_1 .content_title i.im-sync').css('display', 'none');
    $('.sort_theme_mode').css('display', 'none');
    $('.all_wish_theme_mode li:first-child').css('color', 'black');
	  $('.all_wish_theme_mode li:last-child').css('color', '#66E275');
	} else {
	  $('#wanted_themes_conteiner').css('display', 'none');
	  $('#all_themes_conteiner').css('display', 'block');
	  $('#content_1 .content_title i.im-sync').css('display', 'block');
	  $('.sort_theme_mode').css('display', 'flex');
	  $('.all_wish_theme_mode li:first-child').css('color', '#66E275');
	  $('.all_wish_theme_mode li:last-child').css('color', 'black');
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

//add/delete a fovorite theme
function add_a_favorite(id){
  id = parseInt(id.match(/\d+/));
  $.ajax({
    url: 'php/non_priority/wanted_theme.php', 
    type: 'POST',
    dataType: 'json', 
    data: {
      add_id: id
    },
    success: function(data){
      if (data instanceof Array) {
        if (data[0]) {
          w_modal('Уведомление!', data[1]);
          $.ajax({
            url: 'php/main/get_theme_details.php', 
            type: 'POST',
            dataType: 'json', 
            data: {
              theme_id: id
            },
            success: function(theme){
              $('#all_theme_index_' + id + ' i.im-star').css('color', '#6D89D5');
              $('#sort_theme_index_' + id + ' i.im-star').css('color', '#6D89D5');
              $('.wanted_themes_title span').text(parseInt($('.wanted_themes_title span').text()) + 1);
              var wishes_themes = new Array();
              var wishes_aprove_themes = new Array();
              for (i = 1; i <= $('.wanted_themes').children().length + 1; i++) {
                  if ($('.wanted_themes .from_themes:nth-child(' + i + ')').children().length) {
                    wishes_themes.push($('.wanted_themes .from_themes:nth-child(' + i + ')').clone(true));
                    wishes_aprove_themes.push($('.choose_theme .from_themes:nth-child(' + i + ')').clone(true));
                  }
              }
              $('.wanted_themes').empty();
              $('.choose_theme').empty();
              for (i = 0; i < wishes_themes.length; i++) {
                wishes_themes[i].clone(true).appendTo('.wanted_themes');
                wishes_aprove_themes[i].clone(true).appendTo('.choose_theme');
              }
              var file_color = '#66e275';
              if (theme['file']) file_color = '#d54b3e';
              $('.wanted_themes').append('<div class="from_themes" style="background: rgba(102, 226, 117, 0.5)"><p class="from_themes_user">преподаватель: <span> ' + theme['fio'] + ' </span></p> <p class="from_themes_title">тема: <span>' + theme['title'] + '</span></p> <div id="wishes_theme_index_' + theme['id'] + '" class="from_themes_intro"><i onclick="delete_a_fovorite($(this).parent().prop(\'id\'))" class="im im-x-mark-circle-o"> </i><i class="im im-paper-clip" style="color:' + file_color + '" ></i> <i onclick="show_theme_details($(this).parent().prop(\'id\'))" class="im im-fullscreen"></i></div> <div class="theme_info"><i class="im im-star"><span>' + theme['wished'] + '</span></i><i class="im im-bookmark"><span>' + theme['confirmed'] + '/8</span></i></div> </div>');
              $('.choose_theme').append('<div class="from_themes"><p class="from_themes_user">преподаватель: <span> ' + theme['fio'] + ' </span></p> <p class="from_themes_title">тема: <span>' + theme['title'] + '</span></p> <div id="choose_theme_index_' + theme['id'] + '" class="from_themes_intro"><i class="im im-x-mark-circle" onclick="my_theme_false_approval($(this).parent().prop(\'id\'));" ></i><i class="im im-check-mark-circle-o" onclick="my_theme_approval($(this));"></i><i class="im im-paper-clip" style="color:' + file_color + '" ></i> <i onclick="show_theme_details($(this).parent().prop(\'id\'))" class="im im-fullscreen"></i></div> <div class="theme_info"><i class="im im-star"><span>' + theme['wished'] + '</span></i><i class="im im-bookmark"><span>' + theme['confirmed'] + '/8</span></i></div> </div>');
              len = $('.wanted_themes').children().length;
              if ((5 - len) > 0) {
                for (i = 0; i < (5 - len); i++) {
                  $('.wanted_themes').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
                  $('.choose_theme').append('<div class="from_themes" style="width: 100%; background: rgba(255, 154, 64, 0.5)"></div>');
                }
              }
            },
            error:function(){
              w_modal('Уведомление!', 'Что-то пошло не так...');
            },
          });
        } else w_modal('Уведомление!', 'Что-то пошло не так...');
      } else w_modal('Уведомление!', data);
    },
    error:function(){
      w_modal('Уведомление!', 'Что-то пошло не так...');
    },
  });
}
$('.from_themes .from_themes_intro .im-star').click(function () {
  add_a_favorite($(this).parent().prop('id'));
});
function delete_a_fovorite(id){
  id = parseInt(id.match(/\d+/));
  $.ajax({
    url: 'php/non_priority/wanted_theme.php', 
    type: 'POST',
    dataType: 'json', 
    data: {
      delete_id: id
    },
    success: function(data){
      if (data instanceof Array) {
        $('.wanted_themes_title span').text(parseInt($('.wanted_themes_title span').text()) - 1);
        $('.all_themes #all_theme_index_' + id + ' i.im-star').css('color', 'dimgray');
        $('.wanted_themes #wishes_theme_index_' + id).parent().css('background' , 'rgba(213, 75, 62, 0.5)');
        $('.choose_theme #choose_theme_index_' + id).parent().css('background' , 'rgba(213, 75, 62, 0.5)');
        $('.wanted_themes #wishes_theme_index_' + id).remove();
        $('.choose_theme #choose_theme_index_' + id).remove();
        w_modal('Уведомление!', data[1]);
      } else w_modal('Уведомление!', 'Что-то пошло не так...');
    },
    error:function(){
      w_modal('Уведомление!', 'Что-то пошло не так...');
    },
  });
}
$('#content_1 .from_themes .from_themes_intro .im-x-mark-circle-o').click(function (){
  delete_a_fovorite($(this).parent().prop('id'));
});

function my_theme_approval(approval) {
  if (choose_theme === 0) {
    choose_theme = parseInt((approval.parent().prop('id')).match(/\d+/));
    choose_theme_mode(true, approval.parent().prop('id')); 
    w_modal('Уведомление!', 'Ваша тема выбрана. Для подтверждения выбора нажмите "ПОДТВЕРДИТЬ" ниже перечня избранных тем.');
  }
}
//choose theme section settings
$('#choose_theme_conteiner i.im-bookmark').click( function () {
  my_theme_approval($(this));
});
function my_theme_false_approval(id) {
  choose_theme_mode(false, id);
  choose_theme = 0;
}
$('#choose_theme_conteiner i.im-x-mark-circle').click(function () {
   my_theme_false_approval($(this).parent().prop('id'));
});
function choose_theme_mode(active, id){
  if (active) {
    $('#'+id + ' i.im-x-mark-circle').css('display' , 'block');
    $('#choose_theme_conteiner i.im-bookmark').not('.theme_info i.im-bookmark').css('display' , 'none');
    $('#'+id).parent().css('background' , 'rgba(102, 226, 117, 0.5)');
    $('.choose_theme_footer').css('background' , 'rgba(102, 226, 117, 0.5)');
    $('.choose_theme_footer .choose_theme_title').text('Подтвердить, выбранную тему');
    $('.choose_theme_footer').css('cursor' , 'pointer');
  } else {
    $('#'+id + ' i.im-x-mark-circle').css('display' , 'none');
    $('#choose_theme_conteiner i.im-bookmark').not('.theme_info i.im-bookmark').css('display' , 'block');
    $('#'+id).parent().css('background' , 'rgba(109, 137, 213, 0.5)');
    $('.choose_theme_footer').css('background' , 'rgba(255, 154, 64, 0.5)');
    $('.choose_theme_footer .choose_theme_title').text('Выберите тему(из избранного)');
    $('.choose_theme_footer').css('cursor' , 'auto');
  }
}
$('.choose_theme_footer').mouseover(function () {
  if (choose_theme != 0) $(this).css('background' , 'rgba(102, 226, 117, 0.8)');
})
$('.choose_theme_footer').mouseout(function () {
  if (choose_theme != 0) $(this).css('background' , 'rgba(102, 226, 117, 0.5)');
})
$('.choose_theme_footer').click(function () {
  if (choose_theme != 0) {
    $.ajax({
      url: 'php/non_priority/choose_theme.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        choose_id: choose_theme
      },
      success: function(data){
        choose_theme_mode(false, 'choose_theme_index_' + choose_theme);
        choose_theme = 0;
        var num = 3;
        w_modal('Уведомление!', data + ' Обновление данных через ' + num + '...');
        num--;
        setInterval(function(){
          $('.w_text p').text(data + ' Обновление данных через ' + num+ '...');
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
  }
})
function my_theme_reject(approval) {
  if (!reject_mode) {
      if (reject_theme === 0) {
      reject_theme = parseInt((approval.parent().prop('id')).match(/\d+/));
      reject_theme_mode(true, approval.parent().prop('id')); 
      w_modal('Уведомление!', 'Для подтверждения отказа нажмите "Отказаться от выбранной темы" ниже выбранной темы.'); 
      reject_mode = true;
    }
  } else {
    reject_theme_mode(false, approval.parent().prop('id')); 
    reject_theme = 0;
    reject_mode = false;
  }
}
//reject_theme_mode(true, 'choose_theme_index_37')
function reject_theme_mode(active, id){
  if (active) {
    $('#'+id + ' i.im-x-mark-circle-o').css('color' , '#d54b3e');
    $('#'+id).parent().css('background' , 'rgba(213, 75, 62, 0.5)');
    $('.choosed_theme_footer').css('background' , 'rgba(213, 75, 62, 0.5)');
    $('.choosed_theme_footer .choosed_theme_title').text('Отказаться от выбранной темы');
    $('.choosed_theme_footer').css('cursor' , 'pointer');
  } else {
    $('#'+id + ' i.im-x-mark-circle-o').css('color' , 'dimgray');
    if (confirmed_theme) $('#'+id).parent().css('background' , 'rgba(102, 226, 117, 0.5)');
    else $('#'+id).parent().css('background' , 'rgba(109, 137, 213, 0.5)');
    if (confirmed_theme) $('.choosed_theme_footer .choosed_theme_title').text('Тема подтверждена');
    else $('.choosed_theme_footer .choosed_theme_title').text('Тема выбрана и ожидает подтверждения преподавателя');
    $('.choosed_theme_footer').css('background' , 'rgba(255, 154, 64, 0.5)');
    $('.choosed_theme_footer').css('cursor' , 'auto');
  }
}
$('.choosed_theme_footer').mouseover(function () {
  if (reject_theme != 0) $(this).css('background' , 'rgba(213, 75, 62, 0.8)');
});
$('.choosed_theme_footer').mouseout(function () {
  if (reject_theme != 0) $(this).css('background' , 'rgba(213, 75, 62, 0.5)');
});
$('.choosed_theme_footer').click(function () {
  if (reject_theme != 0) {
    $.ajax({
      url: 'php/non_priority/choose_theme.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        restrict_theme: reject_theme
      },
      success: function(data){
        var num = 3;
        w_modal('Уведомление!', data + ' Обновление данных через ' + num + '...');
        num--;
        setInterval(function(){
          $('.w_text p').text(data + ' Обновление данных через ' + num+ '...');
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
  }
})
$('#choosed_theme_conteiner i.im-x-mark-circle-o').click(function () {
  my_theme_reject($(this));
});
$('#choosed_theme_conteiner i.im-question').click(function () {
  if (!confirmed_theme) w_modal('Уведомление!', 'Тема выбрана и ожидает подтверждения преподователя. Вы можете сменить тему, пока она не подтверждена.');
  else w_modal('Уведомление!', 'Ваша тема подтверждена!');
});