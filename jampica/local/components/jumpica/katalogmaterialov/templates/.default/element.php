<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$arItem = $arResult["ITEMS"][0];

?>

<div class="container my-5 katalog-materialov">

    <div class="row mb-4">
        <div class="col-12 col-md-7">
            <div class="d-block">
                <!--<h4>--><?//=$arResult['ITEMS']["NAME"]?><!--</h4>-->
            </div>
        </div>
        <div class="col-12 col-md-5 text-center text-md-right mt-4 mt-md-0">

            <div class="small-basket <?=(count($_SESSION['USER_BASKET']) > 0) ? 'active' : ''?>">
                В заказ добавлено <span class="small-basket-count"><?=count($_SESSION['USER_BASKET'])?> позиции</span>
                <a href="/zayavka/add.php?type=add" class="btn-cart mt-2">Перейти к оформлению заказа</a>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="col-12 col-md-6">

            <div class="row">
                <?
                if (count($arItem["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DISPLAY_VALUE"])>0) {
                    foreach ($arItem["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DISPLAY_VALUE"] as $arItemFile):


                        if($arItemFile) {
                            $fileimgfull = CFile::GetPath($arItemFile);
                            $fileimg = CFile::ResizeImageGet($arItemFile, array('width' => 445,'height' => 335), BX_RESIZE_IMAGE_EXACT, true);
                        } else {
                            $fileimgfull = $componentPath."/images/nophoto.jpg";
                            $fileimg["src"] = $componentPath."/images/nophoto.jpg";
                        }

                        ?>
                        <div class="col-12 col-md-6 mb-4">
                            <a href="<?=$fileimgfull?>" data-fancybox="gallery">
                                <img src="<?=$fileimg["src"]?>" class="w-100" alt="<?= $arItem["NAME"] ?>"/>
                            </a>
                        </div>
                    <?
                    endforeach;
                } else {
                    ?>
                    <div class="col-12 col-md-6 mb-4 text-center">
                       <h3>Товар на фотосъёмке</h3>
                    </div>
                    <?
                }
                ?>
            </div>

        </div>


        <div class="col-12 col-md-5 mt-4 mt-md-0 offset-md-1">
            <div class="block-info-material">
                <h3><?=$arItem["NAME"]?></h3>

                <?
                if($arItem["PREVIEW_PICTURE"]) {
                    $fileimg = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 350, 'height' => 235), BX_RESIZE_IMAGE_EXACT, true);
                } else {
                    $fileimg["src"] = $componentPath."/images/nophoto.jpg";
                }
                ?>

                <img src="<?=$fileimg["src"]?>" class="w-100 my-4" alt="<?=$arItem["NAME"]?>" >

                <div class="d-block py-4 px-2 katalog-materialov-spisok-rabot">
                    <?=$arItem["DETAIL_TEXT"]?>
                </div>

                <div class="d-block text-center text-md-left mt-4">
                    <span class="katalog-materialov-sales mt-1 d-inline-block1 d-none">Цена: <?= CCurrencyLang::CurrencyFormat($arItem["DISPLAY_PROPERTIES"]["SALE"]["DISPLAY_VALUE"], "RUB");?></span>
                    <p class="d-none">Цена указана за 1м<sup>2</sup>.</p>

                    <? if(in_array($arItem['ID'], $_SESSION['USER_BASKET'])): ?>
                        <a href="#" id="<?= $arItem['ID'] ?>" class="btn-cart float-right basket-add active">Добавлена в заявку</a>
                    <? else: ?>
                        <a href="#" id="<?= $arItem['ID'] ?>" class="btn-cart float-right basket-add">Добавить в заявку</a>
                    <? endif; ?>

                </div>
            </div>
        </div>
    </div>

</div>

<?
$APPLICATION->SetTitle($arItem["NAME"]);
$APPLICATION->AddChainItem($arItem["NAME"]);
?>