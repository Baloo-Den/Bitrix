<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
IncludeTemplateLangFile(__FILE__);
?>
<!-- Подключение head-->
<!DOCTYPE html>
<html lang="ru-RU">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
	<?$APPLICATION->ShowMeta("robots")?>
	<?$APPLICATION->ShowCSS()?>
	<?$APPLICATION->ShowHeadStrings()?>
	<?$APPLICATION->ShowHeadScripts()?> 	
  <link type="image/x-icon" rel="shortcut icon" href="http://isib.myopenugra.ru/img/favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <link href="<?=SITE_TEMPLATE_PATH?>/css/opensans/ui.font.opensans.css" type="text/css" rel="stylesheet">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/animate/animate.min.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/fa/font-awesome.min.css">
  <link href="<?=SITE_TEMPLATE_PATH?>/plugins/slick/slick.css" type="text/css" data-template-style="true" rel="stylesheet">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/datepicker/css/bootstrap-material-datetimepicker.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/swal/sweetalert2.min.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/selectize/css/selectize.default.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/plugins/fancybox/jquery.fancybox.min.css">

  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/jquery.min.js"></script>
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/bootstrap/popper.min.js"></script>
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/bootstrap/bootstrap.min.js"></script>

	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/slick/slick.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/moment/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/maskedinput/jquery.maskedinput.min.js"></script>
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/swal/sweetalert2.all.min.js"></script>

  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/selectize/js/standalone/selectize.js"></script>
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/fancybox/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/plugins/datepicker/js/bootstrap-material-datetimepicker.js"></script>

  <!-- общие скрипты -->
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/main.js"></script>
  <!-- общие скрипты -->

  <!-- custom scripts -->
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/us1_pr-ff.js"></script>
  <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/us2_pr-ff.js"></script>
  <!-- custom scripts -->

  <script src="https://yastatic.net/share2/share.js"></script>

<!-- общие стили -->
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/styles.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/media.css">
<!-- общие стили -->

  <!-- custom style -->
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/us1_pr-ff.css">
  <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/us2_pr-ff.css">
  <!-- custom style -->

  <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body class="d-flex flex-column h-100">
<main class="flex-shrink-0">
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
    <!-- Подключение header-->
    <header>
    <nav class="tabs_cust header-resources">
        <ul class="-primary">
            <li> <a target="_blank" href="https://myopenugra.ru/about/">Центр Открытый Регион</a> </li>
            <li> <a target="_blank" href="https://isib.myopenugra.ru/">Инициативное бюджетирование</a> </li>
            <li> <a target="_blank" href="https://myopenugra.ru/services/sotsiologicheskaya-sluzhba/">Онлайн-опросы</a> </li>
            <li> <a target="_blank" href="https://myopenugra.ru/crowd/">Краудсорсинг</a> </li>
            <li> <a target="_blank" href="https://ng.myopenugra.ru/komf_yugra/">Комфортная Югра</a> </li>
            <li> <a target="_blank" href="https://myopenugra.ru/services/kp/">Книга предложений</a> </li>
        </ul>
    </nav>

    <div class="header-main">
        <div class="container">
            <div class="header-main-logo">
                <a href="/">
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/main_logo.svg" alt="Инициативное бюджетирование Югры"></a>
            </div>
            <div class="header-main-menu d-none d-lg-flex">
                <ul>

                    <li>
                        <a class="" href="#">О нас</a>
                        <div>
                            <ul>
                                <li>
                                    <a class="" href="#">Миссия</a>
                                </li>
                                <li>
                                    <a class="" href="#">Наша команда</a>
                                </li>
                                <li>
                                    <a class="" href="#">Вакансии</a>
                                </li>
                                <li>
                                    <a class="" href="#">Награды</a>
                                </li>
                                <li>
                                    <a class="" href="#">Фирменный стиль</a>
                                </li>
                                <!--close--> </ul>
                        </div>
                    </li>

                    <li>
                        <a href="#" class="">Проекты</a>

                    </li>

                    <li>
                        <a class="" href="#">Услуги</a>
                    </li>

                    <li>
                        <a class="" href="#">Новости</a>
                    </li>
                    <li>
                        <a class="" href="#">Документы</a>
                        <div>
                            <ul>
                                <li>
                                    <a class="" href="#">АНТИКОРРУПЦИОННАЯ
                                        ДЕЯТЕЛЬНОСТЬ</a>
                                </li>
                                <li>
                                    <a class="" href="#">РЕЗУЛЬТАТЫ ОЦЕНКИ УСЛОВИЙ ТРУДА</a>
                                </li>
                                <li>
                                    <a class="" href="#">РЕЗУЛЬТАТЫ ОЦЕНКИ УСЛОВИЙ ТРУДА</a>
                                </li>
                                <li>
                                    <a class="" href="#">ПОЛИТИКА ОБРАБОТКИ ПЕРСОНАЛЬНЫХ ДАННЫХ</a>
                                </li>
                                <li>
                                    <a class="" href="#">Сведения об образовательной организации</a>
                                </li>
                                <!--close--> </ul>
                        </div>
                    </li>
                    <li>
                        <a class="" href="#">Наши ресурсы</a>
                    </li>

                </ul>
                <a href="/search/" title="Поиск" class="header-search-main d-md-inline-block d-none">
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/icon-search.svg"></a>
            </div>
            <div class="header-main-actions d-md-none">
                <div class="d-inline-flex mobile-menu-toggle  avatar avatar-50">
                    <div><i class="fa fa-navicon"></i>
                    </div>
                </div>

                <div class="mobile-main-menu">
                    <div class="mobile-top-menu">
                        <div class="mobile-menu-toggle mobile-main-menu-close"><i class="fa fa-times"></i>
                        </div>
                    </div>

                    <ul>


                        <li>
                            <a class="" href="#">О нас</a>
                            <div>
                                <ul>
                                    <li>
                                        <a class="" href="#">Миссия</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">Наша команда</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">Вакансии</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">Награды</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">Фирменный стиль</a>
                                    </li>
                                    <!--close--> </ul>
                            </div>
                        </li>

                        <li>
                            <a href="#" class="">Проекты</a>

                        </li>

                        <li>
                            <a class="" href="#">Услуги</a>
                        </li>

                        <li>
                            <a class="" href="#">Новости</a>
                        </li>
                        <li>
                            <a class="" href="#">Документы</a>
                            <div>
                                <ul>
                                    <li>
                                        <a class="" href="#">АНТИКОРРУПЦИОННАЯ
                                            ДЕЯТЕЛЬНОСТЬ</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">РЕЗУЛЬТАТЫ ОЦЕНКИ УСЛОВИЙ ТРУДА</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">РЕЗУЛЬТАТЫ ОЦЕНКИ УСЛОВИЙ ТРУДА</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">ПОЛИТИКА ОБРАБОТКИ ПЕРСОНАЛЬНЫХ ДАННЫХ</a>
                                    </li>
                                    <li>
                                        <a class="" href="#">Сведения об образовательной организации</a>
                                    </li>
                                    <!--close--> </ul>
                            </div>
                        </li>
                        <li>
                            <a class="" href="#">Наши ресурсы</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</header>
