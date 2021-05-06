<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<pre>
<?
//print_r($_SERVER["REQUEST_URI"]);
//    \Bitrix\Main\Diag\Debug::dump($arParams);
//    \Bitrix\Main\Diag\Debug::dump($arResult);

//$ID = $_REQUEST["ID"];
//echo $ID;
?>
</pre>


<div class="container my-5 katalog-materialov">
<!--    <div class="row mb-5">-->
<!--        <div class="col-12 col-md-7 d-flex align-items-end">-->
<!--            Выберите товар для последующего оформления:-->
<!--        </div>-->
<!--        <div class="col-12 col-md-5 text-center text-md-right mt-4 mt-md-0">-->
<!--            В заказ добавлено 3 позиции-->
<!--            <a href="/zayavka/" class="btn-cart mt-2">Перейти к оформлению заказа</a>-->
<!--        </div>-->
<!--    </div>-->

    <div class="row mb-5">
        <div class="col-12">
            <?
            if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $arParams['DISPLAY_TOP_PAGER']=='Y'):
                echo $arResult["NAV_STRING"];
            endif;
            ?>
        </div>
    </div>

    <div class="row">
        <?foreach($arResult['ITEMS'] as $arItem):?>

            <?
            $fileimg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 350,'height' => 235), BX_RESIZE_IMAGE_EXACT, true);
            ?>

            <div class="col-12 col-md-4 mb-4">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                    <h5><?=$arItem["NAME"]?></h5>
                    <?=$arParams["PROPERTY_CODE"]["COLOR"]["VALUE"]?>
                    <div class="d-block position-relative overlay_container">
                        <img src="<?=$fileimg["src"]?>" class="img-fluid" alt="Баннер сплошной" />
                        <div class="overlay">
                            <div class="overlay_text">
                                <?=$arItem["PREVIEW_TEXT"]?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?endforeach;?>


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


<pre>
<?

//    \Bitrix\Main\Diag\Debug::dump($arParams);
//    \Bitrix\Main\Diag\Debug::dump($arResult);

//print_r($arParams["PROPERTY_CODE"]);
//print_r($arParams);
//print_r($arResult);
//print_r($arParams["ELEMENT_COUNT"]);
//print_r($arVariables);
//print_r($arElement);


//print_r($arResult['DISPLAY_PROPERTIES']);
?>
</pre>