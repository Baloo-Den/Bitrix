<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
define("NEED_AUTH", true);
IncludeTemplateLangFile(__FILE__);

if ( CSite::InGroup( array(12) ) && basename($_SERVER['SCRIPT_NAME']) != 'noreg.php'):

    LocalRedirect('/noreg.php');

endif;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <title><?$APPLICATION->ShowTitle()?></title>
    <!-- js -->
    <script src="<?=SITE_TEMPLATE_PATH;?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH;?>/js/bootstrap.min.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH;?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH;?>/js/jquery.fancybox.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH;?>/js/script.js?ver=0.180"></script>
	<script src="<?=SITE_TEMPLATE_PATH;?>/js/scrollup.js?ver=0.5"></script>
    <!-- // js -->
    <?$APPLICATION->ShowHead();?>
    <?CJSCore::Init(array('popup', 'date'));?>
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH;?>/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=SITE_TEMPLATE_PATH;?>/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH;?>/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=SITE_TEMPLATE_PATH;?>/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE_TEMPLATE_PATH;?>/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?=SITE_TEMPLATE_PATH;?>/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=SITE_TEMPLATE_PATH;?>/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- css -->
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/webfonts.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/bootstrap.css">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/all.css">
	<!--<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/style.css">-->
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/jquery.fancybox.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/custom.css?ver=0.180">
    <!-- // css -->
</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>


<? if ($USER->IsAuthorized()) { ?>
    <header class="container-fluid">
        <div class="row">
            <div class="col-12 border-bottom-orange">

                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4 text-center text-md-left align-self-center">
                            <a href="<?=SITE_DIR?>" title="jumpica" >
                                <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" alt="jumpica" />
                            </a>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 text-center text-md-left my-2 my-md-0 a_header pl-0 pl-lg-5">
                            <div class="d-block">
                                <a href="tel:+79661611701" title="jumpica">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/phone.png" alt="jumpica" /> 8-(495)-771-18-31
                                </a>
                            </div>
                            <div class="d-block mt-2 mt-md-0 margin_bottom_2px">
                                <a href="mailto:reklama@jumpica.ru" title="jumpica">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/email.png" alt="jumpica" /> reklama@jumpica.ru
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 text-center text-md-right align-self-center">
<!--                            <img src="<?=SITE_TEMPLATE_PATH?>/images/MTS_Logo_rus_r.png" alt="jumpica" />-->
                            <a href="<?echo $APPLICATION->GetCurPageParam("logout=yes", array(
                                "login",
                                "logout",
                                "register",
                                "forgot_password",
                                "change_password"));?>" class="ml-0 ml-lg-4 logout">Выход</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 border-bottom-gray">

                <?$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
                    "ROOT_MENU_TYPE" => "top",
                    "MAX_LEVEL" => "2",
                    "CHILD_MENU_TYPE" => "top",
                    "USE_EXT" => "Y",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => ""
                ),
                    false,
                    array(
                        "ACTIVE_COMPONENT" => "Y"
                    )
                );?>

            </div>
        </div>
    </header>
<? } else { ?>


<? } ?>


<div id="page-wrapper" class="container">
    <div id="workarea" class="row py-4">