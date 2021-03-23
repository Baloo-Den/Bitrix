document.addEventListener("DOMContentLoaded", function () {
	$('.slider-mainpage').slick({
		arrows: false,
		dots: true,
		dotsClass: "my-dots",
	})

	$('.vacancies__item .arrow-img').on('click', function () {
		$(this).closest('.vacancies__block').toggleClass('active')
		$(this).parent().next().slideToggle()
	})

	$('.mou-slider').slick({
		arrows: false,
		dots: true,
		dotsClass: "my-dots",
	})

	$('.archive-fade').on('click', function (e) {
		e.preventDefault()
		$('.mou-grey__filter-inputs').slideToggle()
		$('.archive-fade .fade-arrow').toggleClass('arrow-rotate')
	}
)


});