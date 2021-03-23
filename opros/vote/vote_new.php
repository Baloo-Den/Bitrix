<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опрос");
?><!-- обертка контента -->
<div class="content container">
	 <!-- include нужных компонентов --> <!-- Хлебные крошки -->
	 <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"isib",
	Array(
		"PATH" => "",
		"SITE_ID" => "so",
		"START_FROM" => "0"
	)
);?>
	
	 <?$APPLICATION->IncludeComponent(
	"bitrix:voting.form",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"VOTE_ID" => $_REQUEST["VOTE_ID"],
		"VOTE_RESULT_TEMPLATE" => "vote_result.php?VOTE_ID=#VOTE_ID#"
	)
);?>
	<div class="container">
		<div class="row">
			<div class="mainpage__feedback col-12 mt-0 mb-5">
				<div class="mainpage__feedback-content py-3 py-sm-0 d-flex justify-content-center justify-content-sm-between align-items-center flex-sm-row flex-column">
					<div class="feedback-title pb-2 pb-sm-0">
						 У вас есть вопрос? Напишите нам!
					</div>
 <a href="#" class="btn-blue" tabindex="0"> </a>
				</div>
			</div>
		</div>
	</div>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>