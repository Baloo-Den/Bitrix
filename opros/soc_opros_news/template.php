<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?php if(!empty($arResult["ITEMS"])): ?>
<div class="mb-5 our_team">
	<section class="pb-5 pt-4">
		<div class="container">
			<div class="d-sides align-items-start border-bottom">
				<h4>Новости</h4>
				<a href="/news/" class="aux-link grey">Все новости <img src="<?=SITE_TEMPLATE_PATH?>/img/arrow-white.svg"></a>
			</div>
			<div class="crowd-slider crowd-slider-main pt-4 ">
                <?php foreach($arResult["ITEMS"] as $arItem): ?>
                <?
            	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            	?>
				<a  href="<?php echo $arItem["DETAIL_PAGE_URL"]; ?>" class="card-news h-100 d-flex flex-column justify-content-between">
					<div>
						<img src="<?php echo $arItem["PICTURE"]; ?>" class="card-news-image mb-4">
						<div class="card-news-icons d-flex mb-4">
							<img class="mr-2" src="<?=SITE_TEMPLATE_PATH?>/img/human-icon.svg" alt="">
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news-icon2-blue.svg" alt="">
						</div>
						<h5 class="mb-4 text-dark"><?php echo $arItem["NAME"]; ?></h5>
						<h3 class="text-news"><?php echo $arItem['~PREVIEW_TEXT']; ?></h3>
						<h6 class="small-text-news"><?php echo $arItem["DISPLAY_ACTIVE_FROM"]; ?></h6>
					</div>

				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</div>
<?php endif; ?>