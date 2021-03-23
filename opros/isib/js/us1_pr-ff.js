document.addEventListener("DOMContentLoaded", function () {

    $(".rednblue-buttons-slider").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1280,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }

        ]
    });

    $(".crowd-slider-main").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,

        responsive: [{
            breakpoint: 1280,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }

        ]
    });

    $('.crowd-slider-main').on('setPosition', function () {

        $(this).find('.slick-slide').height('auto');
        var slickTrack = $(this).find('.slick-track');
        var slickTrackHeight = $(slickTrack).height();
        $(this).find('.slick-slide').css('height', slickTrackHeight + 'px');
    });

    $(".predlozenie-slider .owl-carousel").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,
        responsive: [{
            breakpoint: 1280,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }

        ]
    });

    $(".single-slider").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true

    });

    $(".compete-slider").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false

    });


    $(".slick_banners").slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1600,
            settings: {
                slidesToShow: 3
            }
        },
            {
                breakpoint: 1280,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1
                }
            }

        ]
    });

    if ($(".owl-carousel").length > 0) {
        $(".owl-carousel").slick({
            prevArrow: "<div class='slider-prev'></div>",
            nextArrow: "<div class='slider-next'></div>",
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 1600,
                settings: {
                    slidesToShow: 3
                }
            },
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1
                    }
                }

            ]
        });
    }
    $('.slick-photo-gallery .photo-photo-list').slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1
    });

    $('.slick-infogr-new').slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $('.slick-infogr').slick({
        prevArrow: "<div class='slider-prev'></div>",
        nextArrow: "<div class='slider-next'></div>",
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $(".fancybox").fancybox({
        padding: 0,
        helpers: {
            overlay: {
                locked: false
            }
        }
    });

});
 