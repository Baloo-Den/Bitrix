<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
foreach($arResult["ITEMS"] as &$arItem) {
    $arItem["PICTURE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 400, "height" => 250), BX_RESIZE_IMAGE_EXACT)["src"];
}
?>