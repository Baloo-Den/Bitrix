<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>


<? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>

<?= $arResult["FORM_NOTE"] ?>

<? if ($arResult["isFormNote"] != "Y") {
    ?>
    <?= $arResult["FORM_HEADER"] ?>

    <div class="container">
        <div class="row">
            <?
            if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y") {
                ?>
                <div class="col-12 text-center">
                    <?
                    /***********************************************************************************
                     * form header
                     ***********************************************************************************/
                    if ($arResult["isFormTitle"]) {
                        ?>
                        <h3><?= $arResult["FORM_TITLE"] ?></h3>
                        <?
                    } //endif ;

                    if ($arResult["isFormImage"] == "Y") {
                        ?>
                        <a href="<?= $arResult["FORM_IMAGE"]["URL"] ?>" target="_blank" alt="<?= GetMessage("FORM_ENLARGE") ?>"><img src="<?= $arResult["FORM_IMAGE"]["URL"] ?>" <? if ($arResult["FORM_IMAGE"]["WIDTH"] > 300): ?>width="300" <? elseif ($arResult["FORM_IMAGE"]["HEIGHT"] > 200): ?>height="200"<? else:?><?= $arResult["FORM_IMAGE"]["ATTR"] ?><? endif;
                            ?> hspace="3" vscape="3" border="0"/></a>
                        <? //=$arResult["FORM_IMAGE"]["HTML_CODE"]
                        ?>
                        <?
                    } //endif
                    ?>

                    <p><?= $arResult["FORM_DESCRIPTION"] ?></p>

                </div>
                <?
            } // endif
            ?>
        </div>
    </div>

    <?
    /***********************************************************************************
     * form questions
     ***********************************************************************************/
    ?>
    <div class="container">
        <?
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
            if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                echo $arQuestion["HTML_CODE"];
            } else {
                ?>
                <div class="row my-2">
                    <div class="col-12 col-lg-3 font-weight-bold">

                        <?
                        if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                            <span class="error-fld" title="<?= htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID]) ?>"></span>
                        <?endif; ?>
                        <?= $arQuestion["CAPTION"] ?><?
                        if ($arQuestion["REQUIRED"] == "Y"):?><?= $arResult["REQUIRED_SIGN"]; ?><?endif; ?>
                        <?= $arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />" . $arQuestion["IMAGE"]["HTML_CODE"] : "" ?>
                    </div>
                    <div class="col-12 col-lg-9">
                        <?= $arQuestion["HTML_CODE"] ?>
                    </div>
                </div>
                <?
            }
        } //endwhile
        ?>
        <?
        if ($arResult["isUseCaptcha"] == "Y") {
            ?>
            <div class="row my-2">
                <div class="col-12 col-lg-3 font-weight-bold">
                    <?= GetMessage("FORM_CAPTCHA_TABLE_TITLE") ?>
                </div>

                <div class="col-12 col-lg-9">
                    <input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/><img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>" width="180" height="40"/>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12 col-lg-3 font-weight-bold">
                    <?= GetMessage("FORM_CAPTCHA_FIELD_TITLE") ?><?= $arResult["REQUIRED_SIGN"]; ?>
                </div>
                <div class="col-12 col-lg-9">
                    <input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext"/>
                </div>
            </div>
            <?
        } // isUseCaptcha
        ?>

        <div class="row my-2">
            <div class="col-12 text-right">

                <input class="btn-cart" <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="submit" name="web_form_submit" value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"/>

                <!--
                <? if ($arResult["F_RIGHT"] >= 15):?>
                    <input type="hidden" name="web_form_apply" value="Y"/>
                    <input class="btn-cart" type="submit" name="web_form_apply" value="<?= GetMessage("FORM_APPLY") ?>"/>
                <? endif; ?>
                -->
                <input class="btn-cart mt-2 mt-md-2" type="reset" value="<?= GetMessage("FORM_RESET"); ?>"/>
                <a href="/profil/" class="btn-cart mt-2 mt-md-2"><?= GetMessage("FORM_CANCEL"); ?></a>
            </div>
        </div>
    </div>
    <p>
        <?= $arResult["REQUIRED_SIGN"]; ?> - <?= GetMessage("FORM_REQUIRED_FIELDS") ?>
    </p>
    <?= $arResult["FORM_FOOTER"] ?>
    <?
} //endif (isFormNote)
?>