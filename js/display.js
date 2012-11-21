
jQuery(function($){

var stub_showing = false;

    function apos_bar_show() {
        if(stub_showing) {
          $('.apos_bar-stub').slideUp('fast', function() {
            $('.apos_bar').show('bounce', { times:3, distance:15 }, 300);
            $('body').animate({"marginTop": "32px"}, 300);
          });
        }
        else {
        $('.apos_bar').effect('bounce', { times: 3, distance: 15 }, 500);
          $('body').animate({"marginTop": "32px"}, 250);
        }
    }

  $("a.show-notify").click(function(e) {
    apos_bar_show();
  });

  $("a.close-notify").click(function(e) {
        $('.apos_bar').slideUp('fast', function() {
          $('.apos_bar-stub').show('bounce', { times:3, distance:15 }, 100);
          stub_showing = true;
        });

        if( $(window).width() > 1024 ) {
          $('body').animate({"marginTop": "0px"}, 250); // if width greater than 1024 pull up the body
        }
  });

     window.setTimeout(function() {
        apos_bar_show();
  });});