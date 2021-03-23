<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опрос");
?>
	<!-- обертка контента -->
	<div class="content container">
		<!-- include нужных компонентов -->

		<!-- Хлебные крошки -->
        <div class="breadcrumbs" itemprop="http://schema.org/breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
	<div id="bx_breadcrumb_0" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
		<a href="/" title="Главная" itemprop="item">
			<span itemprop="name">Главная</span>
		</a>
		<meta itemprop="position" content="1"></div>
	<div id="bx_breadcrumb_1" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
		<span class="sep">/</span>
		<a href="/best/" title="Категория 1" itemprop="item">
			<span itemprop="name">Категория</span>
		</a>
		<meta itemprop="position" content="2"></div>
	<div class="bx-breadcrumb-item">
		<span class="sep">/</span>
		<span>Название</span>
	</div>
	<div style="clear:both"></div>
</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:voting.form", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"VOTE_ID" => $_REQUEST["VOTE_ID"],
		"VOTE_RESULT_TEMPLATE" => "vote_result.php?VOTE_ID=#VOTE_ID#",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	),
	false
);?>
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>