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

?>
<div class="col-12">
<?
    if (!empty($arResult["ERRORS"])):?>
        <?ShowError(implode("<br />", $arResult["ERRORS"]))?>
    <?endif;
    if (strlen($arResult["MESSAGE"]) > 0):?>
        <?ShowNote($arResult["MESSAGE"])?>
    <?endif?>


    <form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
        <?=bitrix_sessid_post()?>
        <?if ($arParams["MAX_FILE_SIZE"] > 0):?><input type="hidden" name="MAX_FILE_SIZE" value="<?=$arParams["MAX_FILE_SIZE"]?>" /><?endif?>

            <?if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])):?>

                <?foreach ($arResult["PROPERTY_LIST"] as $propertyID):?>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <?if (intval($propertyID) > 0):?><?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"]?><?else:?><?=!empty($arParams["CUSTOM_TITLE_".$propertyID]) ? $arParams["CUSTOM_TITLE_".$propertyID] : GetMessage("IBLOCK_FIELD_".$propertyID)?><?endif?><?if(in_array($propertyID, $arResult["PROPERTY_REQUIRED"])):?><span class="starrequired">*</span><?endif?>
                        </div>
                        <div  class="col-12 col-lg-5 col-xl-6">
                            <?
                            if (intval($propertyID) > 0)
                            {
                                if (
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "T"
                                    &&
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] == "1"
                                )
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "S";
                                elseif (
                                    (
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "S"
                                        ||
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "N"
                                    )
                                    &&
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] > "1"
                                )
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "T";
                            }
                            elseif (($propertyID == "TAGS") && CModule::IncludeModule('search'))
                                $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "TAGS";

                            if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y")
                            {
                                $inputNum = ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) ? count($arResult["ELEMENT_PROPERTIES"][$propertyID]) : 0;
                                $inputNum += $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE_CNT"];
                            }
                            else
                            {
                                $inputNum = 1;
                            }

                            if($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"])
                                $INPUT_TYPE = "USER_TYPE";
                            else
                                $INPUT_TYPE = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];

                            switch ($INPUT_TYPE):
                                case "USER_TYPE":
                                    for ($i = 0; $i<$inputNum; $i++)
                                    {
                                        if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
                                        {
                                            $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"] : $arResult["ELEMENT"][$propertyID];
                                            $description = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["DESCRIPTION"] : "";
                                        }
                                        elseif ($i == 0)
                                        {
                                            $value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                            $description = "";
                                        }
                                        else
                                        {
                                            $value = "";
                                            $description = "";
                                        }
                                        echo call_user_func_array($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"],
                                            array(
                                                $arResult["PROPERTY_LIST_FULL"][$propertyID],
                                                array(
                                                    "VALUE" => $value,
                                                    "DESCRIPTION" => $description,
                                                ),
                                                array(
                                                    "VALUE" => "PROPERTY[".$propertyID."][".$i."][VALUE]",
                                                    "DESCRIPTION" => "PROPERTY[".$propertyID."][".$i."][DESCRIPTION]",
                                                    "FORM_NAME"=>"iblock_add",
                                                ),
                                            ));
                                    ?><br /><?
                                    }
                                break;

                                case "S":
                                case "N":
                                    for ($i = 0; $i<$inputNum; $i++)
                                    {
                                        if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
                                        {
                                            $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
                                        }
                                        elseif ($i == 0)
                                        {
                                            $value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];

                                        }
                                        else
                                        {
                                            $value = "";
                                        }
                                    ?>
                                    <span class="cool-line activeborder">
                                            <input type="text" name="PROPERTY[<?=$propertyID?>][<?=$i?>]" size="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]; ?>" value="<?=$value?>" class="w-100" />
                                    </span>
                                        <?
                                    }
                                break;

                            endswitch;?>
                        </div>
                    </div>
                <?endforeach;?>

            <?endif?>


        <div class="row my-2">
            <div class="col-12 text-right">
                <input type="submit" name="iblock_submit" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" class="btn-cart" />

                <?if (strlen($arParams["LIST_URL"]) > 0):?>
                    <!-- <input type="submit" name="iblock_apply" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" class="btn-cart" /> -->
                    <input type="button" name="iblock_cancel" value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>" onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"])?>';" class="btn-cart mt-2 mt-md-2" />
                <?endif?>
            </div>
        </div>

    </form>

</div>