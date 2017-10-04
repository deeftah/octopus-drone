(function($) {

  $('.hamburger').click(function() {
    if ($('#menu-mobile').hasClass('is-active')) {
      $('#site-navigation').removeClass('toggled');
      $('#menu-mobile').removeClass('is-active');
    } else {
      $('#site-navigation').addClass('toggled');
      $('#menu-mobile').addClass('is-active');
    }

  });

  $("body").swipe( {
    //Generic swipe handler for all directions
    swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
      if (direction == 'right') {
        $('#site-navigation').addClass('toggled');
        $('#menu-mobile').addClass('is-active');
      }
      if (direction == 'left') {
        $('#site-navigation').removeClass('toggled');
        $('#menu-mobile').removeClass('is-active');
      }
    }
  });
})( jQuery );
