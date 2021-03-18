//default settings
var content_width = $('.form_panel').width();
var content_height = $('.form_panel').height();
var panel_position = 1;
var d_sh = '0px 13px 34px 0px rgba(0, 0, 0, 0.35)';//default content shadow
var r_err = [true , true, true];
var a_err = [true , true];

//document change settings
$(window).on('load' , function(){
  
});
$( document ).ready(function() {
  panel_resize();
  items_control();
});
$(window).resize(function(){
  panel_resize();
  items_control();
});

//justify main items settings
function items_control(){
  if ((($(window).width() >= 1300) && ($(window).height() <= 660)) || (($(window).width() <= 1300) && ($(window).height() <= 560))) {
    $('#r_a_change').css('bottom' , ($('#r_a').height() - $('.form_panel').height()) / 4 - $('#r_a_change').height() / 2);
    $('#r_a_change_mobile').css('bottom' , ($('#r_a').height() - $('.form_panel').height()) / 4 - $('#r_a_change_mobile').height() / 2);
  } else {
    $('#r_a_change').css('bottom' , ($(window).height() - content_height) / 4 - $('#r_a_change').height() / 2);
    $('#r_a_change_mobile').css('bottom' , ($(window).height() - content_height) / 4 - $('#r_a_change_mobile').height() / 2);
  }

  $('.restricted_reg').css({'height': content_height - ($('.panel_title').height() + parseInt($('.panel_title').css('padding-top')) * 2), 'margin-top': ($('.panel_title').height() + parseInt($('.panel_title').css('padding-top')) * 2)});
  $('.restricted_auth').css({'height': content_height - ($('.panel_title').height() + parseInt($('.panel_title').css('padding-top')) * 2), 'margin-top': ($('.panel_title').height() + parseInt($('.panel_title').css('padding-top')) * 2)});

  $('#r_panel_button').css('top' , 'calc(100% + ' + Math.round((content_height - parseInt($('#panel_1 .hide_conteiner').css('height'))) / 4 - $('#r_panel_button').height() / 2) + 'px)');
  $('#a_panel_button').css('top' , 'calc(100% + ' + Math.round( (content_height -parseInt($('#panel_2 .hide_conteiner').css('height'))) / 4 - $('#a_panel_button').height() / 2 ) + 'px)');

  $('.hide_conteiner div').not('.hide_conteiner div:last-child').css('width', $('.hide_conteiner div input').width() + parseInt($('.hide_conteiner div input').css('padding-left')) + 4);

  if (($(window).width() < 660) || ($(window).height() < 560)) {
    $('#r_a_change').css('display' , 'none');
    $('#r_a_change_mobile').css('display' , 'block');
  } else {
    $('#r_a_change').css('display' , 'block');
    $('#r_a_change_mobile').css('display' , 'none');
  }
}

//main content resize when window resize settings
function panel_resize(){
  var size = $(window).width();
  if (size >= 1300) {
    for (var i = 1; i <= 3; i++) {
      $('#panel_' + i).css('width' , '720px');
      $('#panel_' + i).css('height' , '540px');  
    }
  }
  if (size < 1300) {
    for (var i = 1; i <= 3; i++) {
      $('#panel_' + i).css('width' , '630px');
      $('#panel_' + i).css('height' , '450px');  
    }
  }
  if (size <= 660) {
    for(var i = 1; i <= 2; i++) {
      $('#panel_' + i).css('width' , '420px');
      $('#panel_' + i).css('height' , '450px');  
    }
  }
  if (size <= 460) {
    for(var i = 1; i <= 2; i++) {
      $('#panel_' + i).css('width' , '360px');
      $('#panel_' + i).css('height' , '450px');  
    }
  }
  if (size <= 400) {
    for(var i = 1; i <= 2; i++) {
      $('#panel_' + i).css('width' , '300px');
      $('#panel_' + i).css('height' , '390px');  
    }
  }
  content_width = $('.form_panel').width();
  content_height = $('.form_panel').height();
}

//r_s mouse effects
function over_control_r_a(){
  if (panel_position === 1) return 0;
  else return 180;
};
$('#r_a_change').mouseover(function(){
  $('#r_a_change .im-angle-right-circle').css('transform' , 'rotate('+over_control_r_a() + 'deg)');
});
$('#r_a_change').mouseout(function(){
  $('#r_a_change .im-angle-right-circle').css('transform' , 'rotate('+over_control_r_a() + 'deg)');
});

//change panel effects
function reset_panel(new_position){
  $('.hide_conteiner').css('display' , 'none');
  $('.hide_conteiner i').not('.panel_button i').css('display' , 'none');
  $('.panel_title span').css('opacity' , '0');
  $('.restricted_reg').css('display', 'none');
  $('.restricted_auth').css('display', 'none');
  $('.panel_button').css('opacity' , '0');
  $('#panel_' + panel_position).css({'box-shadow': '0px 0px 0px 0px rgba(0, 0, 0, 0)', '-webkit-box-shadow': '0px 0px 0px 0px rgba(0, 0, 0, 0)', 'width': Math.floor(content_width / 1.2), 'height': Math.floor(content_height / 1.2), 'opacity' : '0'});
  $('#panel_' + new_position).css({'display' : 'block', 'width': Math.floor(content_width / 1.2), 'height': Math.floor(content_height / 1.2)});
  setTimeout(function () {
    $('#panel_' + panel_position).css('display', 'none');
    $('#panel_' + new_position).css({'opacity' : '1', 'width': content_width, 'height': content_height, 'box-shadow': d_sh, '-webkit-box-shadow': d_sh});
    panel_position = new_position;
    setTimeout(function () {
      $('.hide_conteiner').fadeIn(400);
      $('.panel_title span').css('opacity' , '1');
      $('.hide_conteiner i').css('display' , 'block');
      $('.panel_button').css('opacity' , '1');
      $('.restricted_reg').fadeIn(400);
      $('.restricted_auth').fadeIn(400);
      items_control();
    }, 200);
  }, 500);
}
function r_a_change() {
  var new_position = 1;
  if (panel_position === new_position) {
    new_position+= 1;
    $('#r_a_change i').animate({
      left:'48%'
    }, 800, 'linear');
    setTimeout(function(){
      $('#r_a_change .im-x-mark-circle').css('display' , 'none');
      $('#r_a_change .im-check-mark-circle').css('display' ,'block'); 
      $('#r_a_change').css('background' , '#66E275');
    }, 800)
  } else {
    $('#r_a_change i').animate({
      left:'0'
    }, 800, 'linear');
    setTimeout(function(){
      $('#r_a_change .im-check-mark-circle').css('display' , 'none');
      $('#r_a_change .im-x-mark-circle').css('display' , 'block');
      $('#r_a_change').css('background' , '#FF9A40');
    }, 800)
  }
  reset_panel(new_position);
  setTimeout(function(){
    $('#r_a_change').css('pointer-events' , 'auto');
  }, 1000);
}
function r_a_change_mobile() {
  $('.r_a_alert span').css('color' , 'dimgray');
  if (panel_position === 1) $('.r_a_alert span:last-child').css('color' , '#FF9A40');
  else $('.r_a_alert span:first-child').css('color' , '#FF9A40');
}
$('#r_a_change').click(function(){
  r_a_change();
  r_a_change_mobile();
});
$('#r_a_change_mobile').click(function(){
  r_a_change();
  r_a_change_mobile();
});
$('.restricted_reg span').click(function(){
  r_a_change();
  r_a_change_mobile();
});

//form validation
function e_p_valid(id,c,i1,i2){
  var control = c;
  if (control.test($(id).val())) {
    if ((id === '#r_email')||(id === '#r_pass')) r_err[i1] = false;
    else a_err[i2] = false;
    $(id).css('color' , 'black');  
    $(id).prev().css('color' , '#66E275');  
  } else {
    if(id === '#r_pass') r_err[i1] = true;
    else a_err[i2] = true;
    $(id).css('color' , '#FF4A40');  
    $(id).prev().css('color' , '#FF4A40');  
  }
}
$('#r_email').change(function(){
  e_p_valid('#r_email', /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/, 0, 0);
});
$('#a_email').change(function(){
  e_p_valid('#a_email', /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/, 0, 0);
});
$('#r_pass').change(function(){
  e_p_valid('#r_pass', /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/, 2, 1);
});
$('#a_pass').change(function(){
  e_p_valid('#a_pass', /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/, 2, 1);
});
$('#r_fio').change(function(){
  var control = /^[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{0,}\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,}(\s[А-ЯA-Z][а-яa-zА-ЯA-Z\-]{1,})?$/;
  if (control.test($('.fio').val())) {
    r_err[1] = false;
    $('.fio').css('color' , 'black');  
    $('.im-user-circle').css('color' , '#66E275');  
  } else {
    r_err[1] = true;
    $('.fio').css('color' , '#FF4A40');  
    $('.im-user-circle').css('color' , '#FF4A40');  
  }
});

//registration query settings
$('#r_panel_button').click(function(){
  var access_point = false;
  for (var i = 0; i <= r_err.length; i++) {
    if (!r_err[i]) access_point = true;
    else {
      access_point = false;
      break;
    }
  }
  if (access_point) {
    $.ajax({
      url: 'php/r_data.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        email: $('#r_email').val(),
        fio: $('#r_fio').val(),
        pass: $('#r_pass').val()
      },
      success: function(data){
        w_modal('Уведомление!', data);
        $('#r_email').val('');
        $('#r_fio').val('');
        $('#r_pass').val('');
      },
      error:function(){
        w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });
  } else {
    w_modal('Уведомление!', 'Ошибка заполнения полей регистрации!');
  }
});

//authentication query settings
$('#a_panel_button').click(function(){
  var access_point = false;
  for (var i = 0; i <= a_err.length; i++) {
    if (!a_err[i]) access_point = true;
    else {
      access_point = false;
      break;
    }
  }
  if (access_point) {
    $.ajax({
      url: 'php/a_data.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        email: $('#a_email').val(),
        pass: $('#a_pass').val()
      },
      success: function(data){
        if (typeof data === 'boolean') {
          if (data) {
            var num = 3;
            w_modal('Уведомление!', 'Вы авторизованы! Перезагрузка через ' + num + '...');
            num--;
            setInterval(function(){
              $('.w_text p').text('Вы авторизованы! Перезагрузка через ' + num + '...');
              num--;
            }, 1000); 
            setTimeout(function(){
              location.reload();
            }, 3000);
          } else w_modal('Уведомление!', 'Данные некорректны...');
        } else w_modal('Уведомление!', data);
      },
      error:function(){
        w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });
  } else {
    w_modal('Уведомление!', 'Ошибка заполнения полей авторизации!');
  }
});

//question modal window settings
$('.im-question').mouseover(function(){
  $('#q_widnow').css('z-index' , '1');
  $('.q_window_text').css({'opacity' : '1', 'pointer-events': 'auto'});
  $('.im-question').css('display', 'none');
  $('#q_widnow .im-x-mark-circle').css('display', 'block');
});
function close_q_window() {
  $('#q_widnow').css('pointer-events' , 'none');
  $('.q_window_text').css({'opacity' : '0', 'pointer-events': 'none'});
  setTimeout(function(){
    $('#q_widnow').css({'z-index': 'auto', 'pointer-events': 'auto'});
  }, 500);
}
$('#q_widnow .im-x-mark-circle').click(function () {
  if ($('.q_window_text').css('opacity') == 1) close_q_window();
  $('.im-question').css('display', 'block');
  $('#q_widnow .im-x-mark-circle').css('display', 'none');
});
$('#q_widnow').mouseleave(function(){
  close_q_window();
  $('.im-question').css('display', 'block');
  $('#q_widnow .im-x-mark-circle').css('display', 'none');
});