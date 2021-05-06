<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

?>

<div class="container my-5 katalog-materialov">

    <div class="row mb-4">
        <div class="col-12 col-md-7">
            <div class="d-block">
                <!--                <h4>--><?//=$arResult['ITEMS']["NAME"]?><!--</h4>-->
            </div>
        </div>
        <div class="col-12 col-md-5 text-center text-md-right mt-4 mt-md-0">
            <?if (count($_SESSION['USER_BASKET'])>0) { ?>
                В заказ добавлено <? echo count($_SESSION['USER_BASKET']);?> позиции
                <a href="/zayavka/add.php?type=add" class="btn-cart mt-2">Перейти к оформлению заказа</a>
            <?}?>
        </div>
    </div>

    <?
    if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $arParams['DISPLAY_TOP_PAGER']=='Y'):
        ?>
        <div class="row mb-5">
            <div class="col-12">
                <?
                echo $arResult["NAV_STRING"];
                ?>
            </div>
        </div>
    <?
    endif;
    ?>

    <div class="row">
        <?
        if (count($arResult['ITEMS'])>0) {
            foreach($arResult['ITEMS'] as $arItem):?>

                <?
                if($arItem["PREVIEW_PICTURE"]) {
                    $fileimg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 350, 'height' => 235), BX_RESIZE_IMAGE_EXACT, true);
                } else {
                    $fileimg["src"] = $componentPath."/images/nophoto.jpg";
                }
                ?>

                <div class="col-12 col-md-4 mb-4">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                        <h5><?=$arItem["NAME"]?></h5>
                        <div class="d-block position-relative overlay_container">
                            <img src="<?=$fileimg["src"]?>" class="img-fluid" alt="<?=$arItem["NAME"]?>" />
                            <div class="overlay">
                                <div class="overlay_text">
                                    <?=$arItem["PREVIEW_TEXT"]?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?
            endforeach;
        } else {
            ?>
            <div class="col-12 mb-4 text-center">
                <h3>Раздел наполняется.</h3>
            </div>
            <?
        }
        ?>


    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?
            if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $arParams['DISPLAY_BOTTOM_PAGER']=='Y'):
                echo $arResult["NAV_STRING"];
            endif;
            ?>
        </div>
    </div>
</div>

<?
$APPLICATION->SetTitle("Каталог материалов");
?>