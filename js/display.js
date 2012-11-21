
jQuery(function($){

var stub_showing = false;

    function apos_bar_show() {
        $.removeCookie('aposbar', { path: '/' });
        if(stub_showing) {
            $('.apos_bar-stub').hide().slideUp('slow', function() {
              $('.apos_bar.apos_bar_bottom').show('bounce', { direction:'down', times:3, distance:15 }, 300);
              $('.apos_bar.apos_bar_top').show('bounce', { times:3, distance:15 }, 300);
              $('body').animate({"marginTop": "32px"}, 300);
            });
        }
        else {
          if($.cookie("aposbar")=="hidebar") {
            show_stub();
          } else {
            $('.apos_bar.apos_bar_bottom').show('bounce', { direction:'down', times:3, distance:15 }, 300);
            $('.apos_bar.apos_bar_top').show('bounce', { times:3, distance:15 }, 300);
            $('body').animate({"marginTop": "32px"}, 250);
          }
        }
    }

    function show_stub(){
          $('.apos_bar-stub').show('bounce', { times:3, distance:15 }, 100);
          stub_showing = true;
    }

    function apos_bar_hide(){
         $.cookie("aposbar", "hidebar", { expires: 7, path: '/' });
         $('.apos_bar').slideUp('fast', function() {
          show_stub();
        });

        if( $(window).width() > 1024 ) {
          $('body').animate({"marginTop": "0px"}, 250); // if width greater than 1024 pull up the body
        }
    }

  $("a.show-notify").click(function(e) {
    apos_bar_show();
  });

  $("a.close-notify").click(function(e) {
      apos_bar_hide();
  });

     window.setTimeout(function() {
        if($.cookie("aposbar")=="hidebar") {
          show_stub();
        } else {
          apos_bar_show();
        }
  });});