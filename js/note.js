//search theme settings
function search_theme(value, type){
    if (value != '') {
        $.ajax({
        url: 'php/main/search_theme.php', 
        type: 'POST',
        dataType: 'json', 
        data: {
            wanted: value
        },
        success: function(data){
            if (data instanceof Object) {
            var show_theme = '';
            if (typeof (show_theme_details_priority) === "function") show_theme = 'show_theme_details_priority($(this).parent().prop(\'class\'), $(this).find(\'.from_themes_intro\').prop(\'id\'))';
            else show_theme = 'show_theme_details($(this).find(\'.from_themes_intro\').prop(\'id\'))';
            for (var i = 0; i < (data.length - 1); i++){
                $(type).append('<div class="from_themes" onclick="' + show_theme + '"><p class="from_themes_user">преподаватель: <span> ' + data[data.length - 1] + ' </span></p> <p class="from_themes_title">тема: <span>' + data[i]['theme'] + '</span></p> <div id="all_theme_index_' + data[i]['id'] + '" class="from_themes_intro"><i class="im im-fullscreen"></i></div> </div>');  
            }  
            } else w_modal('Уведомление!', data);
        },
        error: function(){
            w_modal('Уведомление!', 'Что-то пошло не так...');
        },
        });
    } else w_modal('Уведомление!', 'Запрос введен некорректно!');
}

//show theme details/delete theme
function show_theme_details_priority(type,id){
    if (!type) {
        $('.theme_details_conteiner .theme_delete').css('pointer-events' , 'none');
        $('.theme_details_conteiner .theme_delete .im-trash-can').css('display' , 'none');
        show_theme_details(id);
    } else {
        $('.theme_details_conteiner .theme_delete').css('pointer-events' , 'auto');
        $('.theme_details_conteiner .theme_delete .im-trash-can').css('display' , 'block');
        show_theme_details(id);
    }
}
//show theme details (with/with out protection)
$('#content_1 .all_themes_conteiner .from_themes').click(function () {
    show_theme_details_priority(false, $(this).find('.from_themes_intro').prop('id'));
});
$('#content_2 .your_themes_conteiner i.im-fullscreen').click(function () {
    show_theme_details_priority(false, $(this).parent().prop('id'));
});
$('#students_query .students_theme_query i.im-fullscreen').click(function () {
    show_theme_details_priority(false, $(this).parent().prop('id'));
});
$('#content_1 .your_themes_conteiner i.im-trash-can').click(function () {
    show_theme_details($(this).parent().prop('id'));
    setTimeout(function () {
    delete_confirm();
    }, 500)
});

/*
//search your theme settings
$('.all_themes_search_conteiner .im-magnifier').click(function () {
  $('.all_themes_search_conteiner .search_result').empty();
  search_theme($('.all_themes_search_conteiner .search_themes_input').val(),'.search_result');
});

//post your theme settings
$('.add_theme_user_limit').change(function(){
  var control = /^[0-9]+$/;
  if (control.test($('.add_theme_user_limit').val())) $('.add_theme_user_limit').css('color' , 'black'); 
  else $('.add_theme_user_limit').css('color' , '#FF4A40');  
});
function post_theme(){
  var control = /^[0-9]+$/;
  if (control.test($('.add_theme_user_limit').val()) && (($('.add_theme_user_limit').val()).length != 0) && (($('.add_theme_title').val()).length != 0) && (($('.add_theme_description').val()).length != 0)){
    $.ajax({
      url: 'php/priority/post_theme.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        title: $('.add_theme_title').val(),
        description: $('.add_theme_description').val(),
        limit: $('.add_theme_user_limit').val()
      },
      success: function(data){
        $('.add_theme_title').val('');
        $('.add_theme_description').val('');
        $('.add_theme_user_limit').val('');
        if (data instanceof Object) {
          w_modal('Уведомление!', data[0]);
          $('.all_themes').append('<div class="from_themes" onclick="show_theme_details_priority(false, $(this).find(\'.from_themes_intro\').prop(\'id\'))"><p class="from_themes_user">преподаватель: <span> ' + data[1] + ' </span></p> <p class="from_themes_title">тема: <span>' + data[2] + '</span></p> <div id="all_theme_index_' + data[3] + '" class="from_themes_intro"><i class="im im-fullscreen"></i></div> </div>');
          $('.your_themes').append('<div class="from_themes" onclick="show_theme_details_priority(true, $(this).find(\'.from_themes_intro\').prop(\'id\'))"><p class="from_themes_user">преподаватель: <span> ' + data[1] + ' </span></p> <p class="from_themes_title">тема: <span>' + data[2] + '</span></p> <div id="your_theme_index_' + data[3] + '" class="from_themes_intro"><i class="im im-fullscreen"></i></div> </div>');
          $('.all_themes .from_themes:last-child').css('background' , 'rgba(102, 226, 117, 0.5)');
          $('.your_themes .from_themes:last-child').css('background' , 'rgba(102, 226, 117, 0.5)');  
        } else {
          w_modal('Уведомление!', data);
        }
      },
      error: function(){
        w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });
  } else {
    w_modal('Уведомление!', 'Данные введены некорректно!');
  }
}
$('.your_themes_post_button .im-paperplane').click(function () {
  post_theme();
});

//delete your theme settings
function delete_addition() {
  $('.theme_delete').css('height' , '30px');
  $('.theme_delete p').fadeOut();
  $('.theme_delete .im-check-mark-circle-o').fadeOut();
  $('.theme_delete .im-x-mark-circle-o').fadeOut();
  setTimeout(function () {
    $('.theme_delete .im-trash-can').css('display' , 'block');
  }, 400);
}
function delete_confirm(){
  if ($('.theme_delete .im-check-mark-circle-o').css('display') === 'block') {
    delete_addition();
  } else {
    $('.theme_delete').css('height' , '50px');
    $('.theme_delete .im-trash-can').fadeOut();
    setTimeout(function () {
      $('.theme_delete p').css('display' , 'block');
      $('.theme_delete .im-check-mark-circle-o').css('display' , 'block');
      $('.theme_delete .im-x-mark-circle-o').css('display' , 'block');
    }, 400);  
  }
}
$('.theme_delete .im-trash-can').click(function () {
  delete_confirm();
});
$('.theme_delete .im-x-mark-circle-o').click(function () {
  delete_confirm();
});
function delete_your_theme(id){
  var control = /^[0-9]+$/;
  if (control.test(id)) {
    $.ajax({
      url: 'php/priority/delete_theme.php', 
      type: 'POST',
      dataType: 'json', 
      data: {
        id: id
      },
      success: function(data){
        w_modal('Уведомление!', data);
        $('#all_theme_index_' + id).parent().css({'background': '#FF4A40', 'pointer-events': 'none'});
        $('#your_theme_index_' + id).parent().css({'background': '#FF4A40', 'pointer-events': 'none'}); 
        delete_confirm();
        show_theme_details(false);
      },
      error:function(){
        w_modal('Уведомление!', 'Что-то пошло не так...');
      },
    });  
  } else {
    w_modal('Уведомление!', 'Что-то пошло не так...');
  }
}
$('.theme_details_conteiner .im-check-mark-circle-o').click(function () {
  delete_your_theme($(this).parent().parent().children('.details_id').text());
});
*/

/*
//themes mobile settings
function themes_reset_panel(type_1, type_2){
  if ($(type_1).css('display') === 'block'){
    $(type_1).fadeOut();
    setTimeout(function () {
      $(type_2).fadeIn();
    }, 400);
  } else {
    $(type_2).fadeOut();
    setTimeout(function () {
      $(type_1).fadeIn();
    }, 400);
  }
}
$('.all_themes_search_button .im-magnifier').click(function () {
  themes_reset_panel('.all_themes_show_conteiner', '.all_themes_search_conteiner');
  setTimeout(function () {
    items_control();
  }, 400);
});
$('.search_themes_conteiner .im-x-mark-circle-o').click(function () {
  themes_reset_panel('.all_themes_show_conteiner', '.all_themes_search_conteiner');
  $('.all_themes_search_conteiner .search_themes_input').val('');
  $('.all_themes_search_conteiner .search_result').empty();
  setTimeout(function () {
    items_control();
  }, 400);
});
$('#content_1 .your_themes_add_button .im-plus-circle').click(function () {
  themes_reset_panel('.your_themes_show_conteiner', '.your_themes_add_conteiner');
});
$('.your_themes_post_button .im-x-mark-circle-o').click(function () {
  themes_reset_panel('.your_themes_show_conteiner', '.your_themes_add_conteiner');
  setTimeout(function () {
    $('.add_theme_title').val('');
    $('.add_theme_description').val('');
    $('.add_theme_user_limit').val('');
  }, 400)
});
*/

//close theme details
$('#close_theme_details').click(function () {
    show_theme_details(false);
    delete_addition();
    setTimeout(function () {
      $('.theme_details_conteiner .theme_delete').css('pointer-events' , 'auto');
      $('.theme_details_conteiner .theme_delete .im-trash-can').css('display' , 'block');
    }, 500)
  });
  $('.theme_show_details_shadow').click(function () {
    show_theme_details(false);
    delete_addition();
    setTimeout(function () {
      $('.theme_details_conteiner .theme_delete').css('pointer-events' , 'auto');
      $('.theme_details_conteiner .theme_delete .im-trash-can').css('display' , 'block');
    }, 500)
  });