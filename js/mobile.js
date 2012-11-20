<!-- END WOAHbar FOR DESKTOP - START WOAHbar FOR MOBILE  -->

var mstub_showing = false;

    function mwoahbar_show() {
        if(mstub_showing) {
          $('.mwoahbar-stub').slideUp('fast', function() {
            $('.mwoahbar').show('bounce', { times:3, distance:15 }, 300);
            $('body').animate({"marginTop": "32px"}, 300);
          });
        }
        else {
          $('.mwoahbar').show('bounce', { times:3, distance:15 }, 500);
          $('body').animate({"marginTop": "32px"}, 250);
        }
    }

    function mwoahbar_hide() {
        $('.mwoahbar').slideUp('fast', function() {
          $('.mwoahbar-stub').show('bounce', { times:3, distance:15 }, 100);
          mstub_showing = true;
        });

        if( $(window).width() > 1024 ) {
          $('body').animate({"marginTop": "0px"}, 250); // if width greater than 1024 pull up the body
        }
    }

    $().ready(function() {
        window.setTimeout(function() {
        mwoahbar_show();
     }, 0);
    });