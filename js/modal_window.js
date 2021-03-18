//main modal window settings
function w_modal(title, text){
  $('.widnow h1').css({'background': 'rgba(109, 137, 213, 0.5)', 'border-bottom': '2px solid #6D89D5'});
  $('.modal_window .w_text p').css('display' , 'block');
  $('.modal_window .window_confirm').css('display' , 'none');
  $('.widnow h1').text(title);
  $('.modal_window .w_text p').text(text);
  if ($('.modal_window').css('display') === 'none') {
    $('.modal_window').fadeIn();
  } else {
    $('.modal_window').fadeOut();
  }
}
$('.w_close').click(function(){
  w_modal();
});
$('.w_shadow').click(function(){
  w_modal();
});