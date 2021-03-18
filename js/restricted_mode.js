//default settings
var group_options = new Array();

//document change settings
$(window).on('load' , function () {
  
});
$( document ).ready(function () {
  panel_button_control();
  w_modal('Уведомление!', 'Подтвердите и дополните данные своего профиля для дальнейшей работы!');
});
$(window).resize(function(){
  
});

//panel button position settings
function panel_button_control() {
  $('#user_info_button').css('top' , 'calc(100% + ' + Math.round(($('#content_0').height() - $('.user_info').height()) / 4 - ($('#user_info_button').height() / 2) - 30) + 'px)');
}

//user info settings
$('.data_edit .im-eye-off').click(function(){
	if ($('.user_pass').attr('type') === 'password'){
    $('.user_pass').attr('type', 'text');
    $('.data_edit .im-eye-off').css('color', '#FF9A40');
	} else {
    $('.user_pass').attr('type', 'password');
    $('.data_edit .im-eye-off').css('color', '#6d89d5');
	}
}); 
$('.user_rank_list').change(function () {
  if (rank_options.indexOf($('.user_rank_list').val()) != -1) {
    $(this).css('border', '2px solid #66e275');
    var id = parseInt($(this).find('option:selected').attr('id').match(/\d+/)); 
    $.ajax({
			url: 'php/restricted_mode/st_group.php',
			type: 'POST',
			dataType: 'json',
			data: {
				st_id: id
			},
			success: function(data){
        if (data instanceof Array) {
          $('.user_group_list').empty();
          $('.user_group_list').append('<option value="" hidden disabled selected>Выбрать...</option>');
          for (i = 0; i < data.length; i++) {
            $('.user_group_list').append('<option id="group_' + data[i]['id'] + '">' + data[i]['title'] + '</option>');
            group_options.push(data[i]['title']);
          }
        } else {
          $('.user_group_list').empty();
          $('.user_group_list').append('<option value="" hidden disabled selected>' + data + '</option>');
        }
			},
			error: function(){
				w_modal('Уведомление!', 'Что-то пошло не так...');
			},
    });
  } else $(this).css('border', '2px solid #FF9A40');
});
$('.user_group_list').click(function () {
  if (group_options.indexOf($('.user_group_list').val()) != -1) $(this).css('border', '2px solid #66e275');
  else $(this).css('border', '2px solid #FF9A40');
});
$('.user_email_code').change(function () {
  if ($('.user_email_code').val()) $(this).css('border', '2px solid #66e275');
  else $(this).css('border', '2px solid #FF9A40');
});

//get access settings
function get_access() {
  if ($('.user_email_code').val() && (rank_options.indexOf($('.user_rank_list').val()) != -1) && (group_options.indexOf($('.user_group_list').val()) != -1)){
    var rank = parseInt($('.user_rank_list').find('option:selected').attr('id').match(/\d+/));
    var group = parseInt($('.user_group_list').find('option:selected').attr('id').match(/\d+/));
    $.ajax({
			url: 'php/restricted_mode/allowance.php',
			type: 'POST',
			dataType: 'json',
			data: {
        email_code: $('.user_email_code').val(),
        rank: rank,
        group: group
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
			error: function(){
				w_modal('Уведомление!', 'Что-то пошло не так...');
			},
    });
  } else w_modal('Уведомление!', 'Ошибка заполнения полей подтверждения: код подтверждения, специальность, группа');
}
$('#user_info_button').click(function () {
  get_access();
});