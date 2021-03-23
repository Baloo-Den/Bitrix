<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ИСИБ");
?>
	<div class="slider-mainpage bot_blue d-lg-block d-none">
		<!-- slide -->
		<div class="slider-mainpage-slide  position-relative"
			 style="background-image: url(.<?=SITE_TEMPLATE_PATH?>/img/Mask-Group.jpg)">
			<div class="container slide-content">
				<div class="slide-info">
					<div class="title">
						Опросы <br>
						Общественного <br>
						Мнения
					</div>
					<div class="slide-content-link d-flex align-items-center">
						<a href="#">Перейти</a>
						<img src=".<?=SITE_TEMPLATE_PATH?>/img/arrow.svg" alt="">
					</div>
				</div>
				<div class="slide-content-info ml-auto">
					<div class="rednblue-stats justify-content-around pb-3 pt-5">

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon1.svg">
							<div>
								<div class="numbers">&gt;3000</div>
								<div class="name">Проектов</div>
								<div class="description">реализовано</div>
							</div>
						</div>

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon2.svg">
							<div>
								<div class="numbers"> &gt;10</div>
								<div class="name">Жителей округа</div>
								<div class="description">приняли участие в проектах</div>
							</div>
						</div>

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon3.svg">
							<div>
								<div class="numbers">&gt;1350</div>
								<div class="name">Идей</div>
								<div class="description">предложено</div>
							</div>
						</div>

					</div>
				</div>
				<div>
				</div>
			</div>
		</div>

		<!-- slide -->
		<div class="slider-mainpage-slide  position-relative"
			 style="background-image: url(.<?=SITE_TEMPLATE_PATH?>/img/Mask-Group.jpg)">
			<div class="container slide-content">
				<div class="slide-info">
					<div class="title">
						Слайд 2 <br>
					</div>
					<div class="slide-content-link d-flex align-items-center">
						<a href="#">Перейти</a>
						<img src=".<?=SITE_TEMPLATE_PATH?>/img/arrow.svg" alt="">
					</div>
				</div>
				<div class="slide-content-info ml-auto">
					<div class="rednblue-stats justify-content-around pb-3 pt-5">

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon1.svg">
							<div>
								<div class="numbers">&gt;11000</div>
								<div class="name">Text</div>
								<div class="description">Text</div>
							</div>
						</div>

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon2.svg">
							<div>
								<div class="numbers"> &gt;10</div>
								<div class="name">Жителей округа</div>
								<div class="description">приняли участие в проектах</div>
							</div>
						</div>

						<div class="mb-4 pb-2">
							<img class="mr-3" src="<?=SITE_TEMPLATE_PATH?>/img/blue_soc_icon3.svg">
							<div>
								<div class="numbers">&gt;1350</div>
								<div class="name">Идей</div>
								<div class="description">предложено</div>
							</div>
						</div>

					</div>
				</div>
				<div>
				</div>
			</div>
		</div>

		<!-- slide -->
		<div class="slider-mainpage-slide position-relative"
			 style="background-image: url(.<?=SITE_TEMPLATE_PATH?>/img/Mask-Group.jpg)">
			<div class="container slide-content">
				<div class="slide-info">
					<div class="title">
						Центр<br>
						«Открытый<br> регион-Югра»
					</div>
					<div class="slide-content-link d-flex align-items-center">
						<a href="#">Видео о центре</a>
						<img src=".<?=SITE_TEMPLATE_PATH?>/img/arrow.svg" alt="">
					</div>
				</div>
				<div class="slide-content-info position-absolute">

				</div>
				<div>
				</div>
			</div>
		</div>


	</div>


	<section class="py-0 mt-minus-25">
		<div class="container">
			<div class="row">
				<div class="mainpage__feedback mt-0 custom_cont w-100 position-relative">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/custom_cont_user.svg" class="position-absolute t-0 l-0">
					<div class="mainpage__feedback-content d-flex justify-content-between row mx-0">
						<div class="custom_cont__title col-12 col-md-5 col-lg-4">
							<div class="pl-md-5 pt-4">

								<p class="font-weight-boldy text-dark text-20">
									Благодарим каждого
									за участие в опросах!
								</p>

							</div>
						</div>
						<div class="col-12 col-md-7  col-lg-8 custom_cont__descr">
							<div class="pt-4 text-dark ">
								<p><strong>Проект «Неравнодушный гражданин Югры» разработан по инициативе Губернатора
										Югры
										Натальи Комаровой в 2017 году в качестве площадки для взаимодействия общества и
										органов власти.</strong></p>
								<p class="pb-4">Высказывая свое мнение на одной из площадок портала, вы влияете на
									принимаемые властями
									решения. Именно от неравнодушных граждан зависит, какой станет Югра завтра!</p>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>
<!-- Сами опросы -->

<?$APPLICATION->IncludeComponent(
	"bitrix:voting.list", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CHANNEL_SID" => array(
		),
		"VOTE_FORM_TEMPLATE" => "vote_new.php?VOTE_ID=#VOTE_ID#",
		"VOTE_RESULT_TEMPLATE" => "vote_result.php?VOTE_ID=#VOTE_ID#"
	),
	false
);?>
<!--Конец компонента опросов-->
	<div class="container">
		<div class="row">
			<div class="mainpage__feedback col-12 mt-0 mb-5">
				<div class="mainpage__feedback-content py-3 py-sm-0 d-flex justify-content-center justify-content-sm-between align-items-center flex-sm-row flex-column">
					<div class="feedback-title pb-2 pb-sm-0">
						У вас есть вопрос? Напишите нам!
					</div>
					<a href="#" class="btn-blue" tabindex="0">

					</a>
				</div>
			</div>
		</div>
	</div>
<!--Блок новостей-->
	<div class="mb-5 our_team">
		<section class="pb-5 pt-4">
			<div class="container">
				<div class="d-sides align-items-start border-bottom">
					<h4>Новости</h4>
					<a href="polls.php" class="aux-link grey">Все опросы <img src="<?=SITE_TEMPLATE_PATH?>/img/arrow-white.svg"></a>
				</div>
				<div class="crowd-slider crowd-slider-main pt-4 ">
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="./img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
					<a href="#" class="card-news h-100 d-flex flex-column justify-content-between">
						<div>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news/news1.jpg" class="card-news-image mb-4">
							<div class="card-news-icons d-flex mb-4">
								<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
								<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
							</div>
							<h5 class="mb-4 text-dark">Специалисты ЦУР за месяц отработали более трех тысяч
								обращений
								югорчан</h5>
							<h3 class="text-news">Большинство вопросов касаются коммунальной сферы, лечения и
								профилактики COVID-19.</h3>
							<h6 class="small-text-news">12 Января 08:30</h6>
						</div>

					</a>
				</div>
			</div>
		</section>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>