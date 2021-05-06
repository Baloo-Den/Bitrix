$(document).ready(function () {
    $(function () {

        $(window).scroll(function () {
            if ($(this).scrollTop() != 0) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });

        $('#toTop').click(function () {
            $('body,html').animate({scrollTop: 0}, 800);
        });

    });


    //store the element
    // var $cache = $('.block-info-material');
    //
    // //Небольшая правка, иначе ошибки в консоли на других страницах
    // if ($cache.length >= 1) {
    //
    //     //store the initial position of the element
    //     var vTop = $cache.offset().top - parseFloat($cache.css('margin-top').replace(/auto/, 0));
    //     $(window).scroll(function (event) {
    //         // what the y position of the scroll is
    //         var y = $(this).scrollTop();
    //
    //         // whether that's below the form
    //         if (y >= vTop) {
    //             // if so, ad the fixed class
    //             $cache.addClass('stuck');
    //         } else {
    //             // otherwise remove it
    //             $cache.removeClass('stuck');
    //         }
    //     });
    //
    // }

});


