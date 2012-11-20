(function ($) {
	"use strict";
	$(function () {

    console.log("haha");

                var stub_showing = false;

                    function woahbar_hide() {
                        $('.woahbar').slideUp('fast', function() {
                          $('.woahbar-stub').show('bounce', { times:3, distance:15 }, 100);
                          stub_showing = true;
                        });

                        if( $(window).width() > 1024 ) {
                          $('body').animate({"marginTop": "0px"}, 250); // if width greater than 1024 pull up the body
                        }
                    }

                    function woahbar_show() {
                        if(stub_showing) {
                          $('.woahbar-stub').slideUp('fast', function() {
                            $('.woahbar').show('bounce', { times:3, distance:15 }, 300);
                            $('body').animate({"marginTop": "32px"}, 300);
                          });
                        }
                        else {
                          // $('.woahbar').show('bounce', { times:3, distance:15 }, 500);
                          // $('.woahbar').effect('bounce', { times:3, distance:15 }, 500);
                          $('.woahbar').show();
                          // $('.woahbar').show('bounce', {}, 2000);
                          $('body').animate({"marginTop": "32px"}, 250);
                        }
                    }


                        window.setTimeout(function() {
                        woahbar_show();
                        });
	});
}(jQuery));

