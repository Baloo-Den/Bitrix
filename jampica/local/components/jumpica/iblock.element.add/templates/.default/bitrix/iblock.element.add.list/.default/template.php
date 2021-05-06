<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(false);

$colspan = 2;
if ($arResult["CAN_EDIT"] == "Y") $colspan++;
if ($arResult["CAN_DELETE"] == "Y") $colspan++;
?>


    <div class="col-12 my-2 endfindinput">

        <div class="row">
            <div class="col-12">
                <?if (strlen($arResult["MESSAGE"]) > 0):?>
                    <?ShowNote($arResult["MESSAGE"])?>
                <?endif?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-3 font-weight-bold">
                <?=GetMessage('POINT_CODE')?>
            </div>

            <?if($arResult["NO_USER"] == "N"):?>




                <?if (count($arResult["ELEMENTS"]) > 0):?>
                    <div class="col-12 col-lg-9">

                            <?foreach ($arResult["ELEMENTS"] as $arElement):?>
                                <div class="row mb-2">
                                    <div class="col-12 col-lg-6 col-xl-8 font-weight-bold">
                                        <?=$arElement["NAME"]?>
                                    </div>
                                    <div class="col-12 col-lg-6 col-xl-4 text-right">
                                        <?if ($arResult["CAN_EDIT"] == "Y"):?>
                                            <?if ($arElement["CAN_EDIT"] == "Y"):?><a href="<?=$arParams["EDIT_URL"]?>?edit=Y&amp;CODE=<?=$arElement["ID"]?>" class="btn-cart"><?=GetMessage("IBLOCK_ADD_LIST_EDIT")?><?else:?>&nbsp;<?endif?></a>
                                        <?endif?>
                                        <?if ($arResult["CAN_DELETE"] == "Y"):?>
                                            <?if ($arElement["CAN_DELETE"] == "Y"):?><a href="?delete=Y&amp;CODE=<?=$arElement["ID"]?>&amp;<?=bitrix_sessid_get()?>" onClick="return confirm('<?echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM")))?>')" class="btn-cart"><?=GetMessage("IBLOCK_ADD_LIST_DELETE")?></a><?else:?>&nbsp;<?endif?>
                                        <?endif?>
                                    </div>
                                </div>
                            <?endforeach?>

                    </div>
                <?else:?>
                    <tr>
                        <td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_EMPTY")?></td>
                    </tr>
                <?endif?>

            <?endif?>

        </div>
    </div>


    <div class="col-12 text-right">
        <?if ($arParams["MAX_USER_ENTRIES"] > 0 && $arResult["ELEMENTS_COUNT"] < $arParams["MAX_USER_ENTRIES"]):?>
            <a href="<?=$arParams["EDIT_URL"]?>?edit=Y"><i class="fas fa-plus border-gyrey" title="<?=GetMessage("IBLOCK_ADD_LINK_TITLE")?>"></i></a><?else:?><?=GetMessage("IBLOCK_LIST_CANT_ADD_MORE")?>
        <?endif?>
    </div>

<?if (strlen($arResult["NAV_STRING"]) > 0):?><?=$arResult["NAV_STRING"]?><?endif?>