
// simpl_preloadr
// $(window).load(function() { или 
(function ($) {
	"use strict";
	$(window).on('load', function () {
		$(".loader_inner").fadeOut();
		$(".loader").delay(400).fadeOut("slow");
		// simpl_animation_main

		// after_animatin
	});

})(jQuery);

$(document).ready(function () {



	$(window).scroll(function() {

		if ($(window).scrollTop() > 106) {
		  $('body').addClass('scroll_p');
		} else {
		  $('body').removeClass('scroll_p');
		}
	  
	  });

	// long menu 
	const container = document.querySelector('.tabs_cust');
	const primary = container.querySelector('.-primary');
	const primaryItems = container.querySelectorAll('.-primary > li:not(.-more)');
	container.classList.add('--jsfied');

	// insert "more" button and duplicate the list

	primary.insertAdjacentHTML('beforeend', `
  <li class="-more">
  <button type="button" class="toggle_menu" aria-haspopup="true" aria-expanded="false">
      Еще <span>&darr;</span>
    </button>
    <ul class="-secondary">
      ${primary.innerHTML}
    </ul>
  </li>
`);
	const secondary = container.querySelector('.-secondary');
	const secondaryItems = secondary.querySelectorAll('li');
	const allItems = container.querySelectorAll('li');
	const moreLi = primary.querySelector('.-more');
	const moreBtn = moreLi.querySelector('button');
	moreBtn.addEventListener('click', e => {
		e.preventDefault();
		container.classList.toggle('--show-secondary');
		moreBtn.setAttribute('aria-expanded', container.classList.contains('--show-secondary'));
	});

	// adapt tabs_cust
	function destr() {
		// reveal all items for the calculation
		allItems.forEach(item => {
			item.classList.remove('--hidden');
		});
	}
	const doAdapt = () => {
		destr();
		// hide items that won't fit in the Primary
		let stopWidth = moreBtn.offsetWidth;
		let hiddenItems = [];
		const primaryWidth = primary.offsetWidth;
		primaryItems.forEach((item, i) => {
			if (primaryWidth >= stopWidth + item.offsetWidth) {
				stopWidth += item.offsetWidth;
			} else {
				item.classList.add('--hidden');
				hiddenItems.push(i);
			}
		});

		// toggle the visibility of More button and items in Secondary
		if (!hiddenItems.length) {
			moreLi.classList.add('--hidden');
			container.classList.remove('--show-secondary');
			moreBtn.setAttribute('aria-expanded', false);
		} else {
			secondaryItems.forEach((item, i) => {
				if (!hiddenItems.includes(i)) {
					item.classList.add('--hidden');
				}
			});
		}

		if (window.innerWidth < 576) { // дестрой функции на определенном разрешении 
			setTimeout(() => {
				// reveal all items for the calculation
				destr();
			}, 10);

		}

	};

	setTimeout(doAdapt, 500); // adapt immediately on load
	window.addEventListener('resize', doAdapt); // adapt on window resize

	// hide Secondary on the outside click
    "use strict";
    "object"!=typeof window.CP&&(window.CP={}),window.CP.PenTimer={programNoLongerBeingMonitored:!1,timeOfFirstCallToShouldStopLoop:0,_loopExits:{},_loopTimers:{},START_MONITORING_AFTER:2e3,STOP_ALL_MONITORING_TIMEOUT:5e3,MAX_TIME_IN_LOOP_WO_EXIT:2200,exitedLoop:function(o){this._loopExits[o]=!0},shouldStopLoop:function(o){if(this.programKilledSoStopMonitoring)return!0;if(this.programNoLongerBeingMonitored)return!1;if(this._loopExits[o])return!1;var t=this._getTime();if(0===this.timeOfFirstCallToShouldStopLoop)return this.timeOfFirstCallToShouldStopLoop=t,!1;var i=t-this.timeOfFirstCallToShouldStopLoop;if(i<this.START_MONITORING_AFTER)return!1;if(i>this.STOP_ALL_MONITORING_TIMEOUT)return this.programNoLongerBeingMonitored=!0,!1;try{this._checkOnInfiniteLoop(o,t)}catch(o){return this._sendErrorMessageToEditor(),this.programKilledSoStopMonitoring=!0,!0}return!1},_sendErrorMessageToEditor:function(){try{if(this._shouldPostMessage()){var o={action:"infinite-loop",line:this._findAroundLineNumber()};parent.postMessage(JSON.stringify(o),"*")}else this._throwAnErrorToStopPen()}catch(o){this._throwAnErrorToStopPen()}},_shouldPostMessage:function(){return document.location.href.match(/boomerang/)},_throwAnErrorToStopPen:function(){throw"We found an infinite loop in your Pen. We've stopped the Pen from running. Please correct it or contact support@codepen.io."},_findAroundLineNumber:function(){var o=new Error,t=0;if(o.stack){var i=o.stack.match(/boomerang\S+:(\d+):\d+/);i&&(t=i[1])}return t},_checkOnInfiniteLoop:function(o,t){if(!this._loopTimers[o])return this._loopTimers[o]=t,!1;var i=t-this._loopTimers[o];if(i>this.MAX_TIME_IN_LOOP_WO_EXIT)throw"Infinite Loop found on loop: "+o},_getTime:function(){return+new Date}},window.CP.shouldStopExecution=function(o){var t=window.CP.PenTimer.shouldStopLoop(o);return t===!0&&console.warn("[CodePen]: An infinite loop (or a loop taking too long) was detected, so we stopped its execution. Sorry!"),t},window.CP.exitedLoop=function(o){window.CP.PenTimer.exitedLoop(o)};

	document.addEventListener('click', e => {
		let el = e.target;
		while (el.classList == "toggle_menu") {
			if (window.CP.shouldStopExecution(0)) break;
			if (el === secondary || el === moreBtn) {
				return;
			}
			el = el.parentNode;
		} window.CP.exitedLoop(0);
		container.classList.remove('--show-secondary');
		moreBtn.setAttribute('aria-expanded', false);
	});

	document.addEventListener('click', e => {
		document.querySelector(".menu_wrap").classList.toggle('active');
	});

	// long menu end

	if ($('.multiple-items').length > 0) {
		$('.multiple-items').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 3,
			speed: 300,
			arrows: true,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						infinite: true,

					}
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
				// You can unslick at a given breakpoint now by adding:
				// settings: "unslick"
				// instead of a settings object
			]
		});
	}



	//Кнопка "Наверх"
	$(window).scroll(function () {
		if ($(this).scrollTop() > 1500) {
			$('#topscroll').fadeIn();
		} else {
			$('#topscroll').fadeOut();
		}
	});
	$('#topscroll').click(function () {
		$('body,html').animate({ scrollTop: 0 }, 400); return false;
	});

});
