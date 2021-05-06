<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();



//Адрес доставки
if (\Bitrix\Main\Loader::includeModule('iblock')) {
//    if (!empty($arParams['IBLOCK_ID_DELIVERY'])) {
        $arOrder = ['NAME' => 'ASC', 'PROPERTY_CITY' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_DIVISION',
            'PROPERTY_REGION',
            'PROPERTY_CITY',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            //'IBLOCK_ID' => $arParams['IBLOCK_ID_DELIVERY']
            'IBLOCK_CODE' => 'delivery'
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {
            $arResult['DELIVERY_ADDRESS_LIST'][] = $arRes;
        }

//    }
    echo '<pre style="display:none"';
    print_r($arResult);
    echo '</pre>';
}else {

    $arResult['ERROR_MESSAGE'] = 'Модуль iblock не подключен';

}
//Адрес доставки

if ($arResult["SHOW_SMS_FIELD"] == true) {
    CJSCore::Init('phone_auth');
}
?>


<script>
    //массив по регионам и городам
    jQuery(document).ready(function ($) {

        AddressListArrFull = $.makeArray(<?php echo json_encode($arResult["DELIVERY_ADDRESS_LIST"]); ?>);

        UF_DIVISION = '<?=$arResult["UF_DIVISION"]?>';
        PERSONAL_STATE = '<?=$arResult["PERSONAL_STATE"]?>';
        PERSONAL_CITY = '<?=$arResult["PERSONAL_CITY"]?>';


        //выборка по региона из дивизиона
        function UpdateDivision(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#UF_DIVISION').find('option').remove();

            if ($('#UF_DIVISION').find('option').length < 1 ) {
                $('#UF_DIVISION').append("<option value=''><?=GetMessage('selected_division')?></option>");
            }

            $.each(AddressListArrFull, function (i, val) {
                var selected = '';

                if ($('#UF_DIVISION').find('option[value="' + val.PROPERTY_DIVISION_VALUE + '"]').length == 0) {
                    if (val.PROPERTY_DIVISION_VALUE == UF_DIVISION) {
                        selected = 'selected';
                    }
                    $('#UF_DIVISION').append("<option value='" + val.PROPERTY_DIVISION_VALUE + "' " + selected + ">" + val.PROPERTY_DIVISION_VALUE + "</option>");
                }

            });

            SelectRegion(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);
        }

        //выборка городов по региону
        function SelectRegion(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#PERSONAL_STATE').find('option').remove();

            if ($('#PERSONAL_STATE').find('option').length < 1 ) {
                $('#PERSONAL_STATE').append("<option value=''><?=GetMessage('selected_region')?></option>");
            }

            $.each(AddressListArrFull, function (i, val) {
                if (val.PROPERTY_DIVISION_VALUE == UF_DIVISION) {
                    var selected = '';

                    if ($('#PERSONAL_STATE').find('option[value="' + val.PROPERTY_REGION_VALUE + '"]').length == 0) {
                        if (val.PROPERTY_REGION_VALUE == PERSONAL_STATE) {
                            selected = 'selected';
                        }
                        $('#PERSONAL_STATE').append("<option value='" + val.PROPERTY_REGION_VALUE + "' " + selected + ">" + val.PROPERTY_REGION_VALUE + "</option>");
                    }
                }
            });
            if(UF_DIVISION == '') {
                PERSONAL_STATE = '';
            }
            SelectRegionCity(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);
        }

        //выборка городов по городу
        function SelectRegionCity(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#PERSONAL_CITY').find('option').remove();

            if ($('#PERSONAL_CITY').find('option').length < 1 ) {
                $('#PERSONAL_CITY').append("<option value=''><?=GetMessage('selected_city')?></option>");
            }
            if (PERSONAL_STATE=='') {

            } else {
                $.each(AddressListArrFull, function (i, val) {
                    if (val.PROPERTY_REGION_VALUE == PERSONAL_STATE) {
                        var selected = '';
                        if (val.PROPERTY_CITY_VALUE == PERSONAL_CITY && PERSONAL_STATE != 'non') {
                            selected = 'selected';
                        }
                        $('#PERSONAL_CITY').append("<option value='" + val.PROPERTY_CITY_VALUE + "' " + selected + ">" + val.PROPERTY_CITY_VALUE + "</option>");
                    }
                });
            }
        }

        $('#UF_DIVISION').on('change', function () {
            UpdateDivision(AddressListArrFull, $('#UF_DIVISION').val(), PERSONAL_STATE, PERSONAL_CITY);
        });
        $('#PERSONAL_STATE').on('change', function () {
            SelectRegion(AddressListArrFull, $('#UF_DIVISION').val(), $('#PERSONAL_STATE').val(), PERSONAL_CITY);
        });

        UpdateDivision(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);

    });
</script>


<div class="content-form login-form col-12">
    <div class="bx-auth">

        <div class="d-block text-center">
            <img src="<?= SITE_TEMPLATE_PATH; ?>/images/logo-login.png" alt="jumpica" class="login-jumpica"/>
        </div>

        <div class="form-login">
            <?
            ShowMessage($arParams["~AUTH_RESULT"]);
            ?>
            <? if ($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]): ?>
                <p><? echo GetMessage("AUTH_EMAIL_SENT") ?></p>
            <? endif; ?>

            <? if (!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
                <p><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></p>
            <? endif ?>
            <noindex>

                <? if ($arResult["SHOW_SMS_FIELD"] == true): ?>

                    <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="regform">
                        <input type="hidden" name="SIGNED_DATA" value="<?= htmlspecialcharsbx($arResult["SIGNED_DATA"]) ?>"/>

                        <div class="form-group">
                            <!--                                <span class="starrequired">*</span>--><? // echo GetMessage("main_register_sms_code") ?>
                            <input size="30" type="text" name="SMS_CODE" value="<?= htmlspecialcharsbx($arResult["SMS_CODE"]) ?>" placeholder="<? echo GetMessage("main_register_sms_code") ?>" class="input-field form-control form-control-lg" autocomplete="off"/>
                        </div>

                        <div class="d-block text-center text-md-left my-4">
                            <input type="submit" name="code_submit_button" value="<? echo GetMessage("main_register_sms_send") ?>" class="orange btn-login"/>
                        </div>

                    </form>

                    <script>
                        new BX.PhoneAuth({
                            containerId: 'bx_register_resend',
                            errorContainerId: 'bx_register_error',
                            interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
                            data:
                            <?=CUtil::PhpToJSObject([
                                'signedData' => $arResult["SIGNED_DATA"],
                            ])?>,
                            onError:
                                function (response) {
                                    var errorDiv = BX('bx_register_error');
                                    var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                                    errorNode.innerHTML = '';
                                    for (var i = 0; i < response.errors.length; i++) {
                                        errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                                    }
                                    errorDiv.style.display = '';
                                }
                        });
                    </script>

                    <div id="bx_register_error" style="display:none"><? ShowError("error") ?></div>

                    <div id="bx_register_resend"></div>

                <? elseif (!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]): ?>

                    <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" enctype="multipart/form-data">
                        <input type="hidden" name="AUTH_FORM" value="Y"/>
                        <input type="hidden" name="TYPE" value="REGISTRATION"/>

                        <div class="d-block text-center my-4 orange">
                            <b><?= GetMessage("AUTH_REGISTER") ?></b>
                        </div>

                        <div class="form-group">
                            <input type="text" required name="USER_NAME" maxlength="50" value="<?= $arResult["USER_NAME"] ?>" placeholder="<?= GetMessage("AUTH_NAME") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                        </div>
                        <div class="form-group">
                            <input type="text" required name="USER_LAST_NAME" maxlength="50" value="<?= $arResult["USER_LAST_NAME"] ?>" placeholder="<?= GetMessage("AUTH_LAST_NAME") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                        </div>
                        <div class="form-group">
                            <input type="text" required name="USER_SECOND_NAME" maxlength="50" value="<?= $arResult["USER_SECOND_NAME"] ?>" placeholder="<?= GetMessage("AUTH_SECOND_NAME") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                        </div>
                        <div class="form-group d-none">
                            <input type="text" id="USER_LOGIN" name="USER_LOGIN" maxlength="50" value="<?= $arResult["USER_LOGIN"] ?>" placeholder="<?= GetMessage("AUTH_LOGIN_MIN") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                        </div>


                        <div class="form-group">
                            <select id="UF_DIVISION" required name="UF_DIVISION" class="custom-select mr-sm-2" >
                                <option value=''><?=GetMessage('selected_division')?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select id="PERSONAL_STATE" required name="PERSONAL_STATE" class="custom-select mr-sm-2">
                                <option value=''><?=GetMessage('selected_region')?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select id="PERSONAL_CITY" required name="PERSONAL_CITY" class="custom-select mr-sm-2">
                                <option value=''><?=GetMessage('selected_city')?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" required name="PERSONAL_STREET" maxlength="255" value="<?=$arResult["PERSONAL_STREET"]?>" placeholder="<?=GetMessage("USER_STREET")?>" class="bx-auth-input input-field form-control form-control-lg" />
                        </div>

                        <? if ($arResult["EMAIL_REGISTRATION"]): ?>
                            <div class="form-group">
                                <input type="text" id="USER_EMAIL" name="USER_EMAIL" maxlength="255" value="<?= $arResult["USER_EMAIL"] ?>" placeholder="<? if ($arResult["EMAIL_REQUIRED"]): ?>*<? endif ?><?= GetMessage("AUTH_EMAIL") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                            </div>
                        <? endif ?>


                        <div class="form-group">
                            <input type="password" name="USER_PASSWORD" maxlength="255" value="<?= $arResult["USER_PASSWORD"] ?>" placeholder="<?= GetMessage("AUTH_PASSWORD_REQ") ?>" class="bx-auth-input input-field form-control form-control-lg" autocomplete="off"/>
                            <? if ($arResult["SECURE_AUTH"]): ?>
                                <span class="bx-auth-secure" id="bx_auth_secure" title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
                                            <div class="bx-auth-secure-icon"></div>
                                        </span>
                                <noscript>
                                        <span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
                                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                        </span>
                                </noscript>
                                <script type="text/javascript">
                                    document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                </script>
                            <? endif ?>
                        </div>
                        <div class="form-group">
                            <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" placeholder="<?= GetMessage("AUTH_CONFIRM") ?>" class="bx-auth-input input-field form-control form-control-lg" autocomplete="off"/>
                        </div>

                        <? if ($arResult["PHONE_REGISTRATION"]): ?>
                            <div class="form-group">
                                <input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="<?= $arResult["USER_PHONE_NUMBER"] ?>" placeholder="<? if ($arResult["PHONE_REQUIRED"]): ?>*<? endif ?><? echo GetMessage("main_register_phone_number") ?>" class="bx-auth-input input-field form-control form-control-lg"/>
                            </div>
                        <? endif ?>

                        <? // ********************* User properties ***************************************************?>
                        <? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
                            <div class="form-group orange">
                                <?= strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB") ?>
                            </div>
                            <? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>
                                <div class="form-group">
                                        <span>
                                            <? if ($arUserField["MANDATORY"] == "Y"): ?>*<? endif; ?><?= $arUserField["EDIT_FORM_LABEL"] ?>
                                        </span>

                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:system.field.edit",
                                        $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                        array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS" => "Y"));
                                    ?>
                                </div>
                            <? endforeach; ?>
                        <? endif; ?>
                        <? // ******************** /User properties ***************************************************

                        /* CAPTCHA */
                        if ($arResult["USE_CAPTCHA"] == "Y") {
                            ?>
                            <div class="form-group">
                                <b><?= GetMessage("CAPTCHA_REGF_TITLE") ?></b>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off" placeholder="<?= GetMessage("CAPTCHA_REGF_PROMT") ?>" class="input-field form-control form-control-lg"/>
                            </div>
                            <?
                        }
                        /* CAPTCHA */
                        ?>
                        <div class="form-group">
                            <? $APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
                                array(
                                    "ID" => COption::getOptionString("main", "new_user_agreement", ""),
                                    "IS_CHECKED" => "Y",
                                    "AUTO_SAVE" => "N",
                                    "IS_LOADED" => "Y",
                                    "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
                                    "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
                                    "INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
                                    "REPLACE" => array(
                                        "button_caption" => GetMessage("AUTH_REGISTER"),
                                        "fields" => array(
                                            rtrim(GetMessage("AUTH_NAME"), ":"),
                                            rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
                                            rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
                                            rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
                                            rtrim(GetMessage("AUTH_EMAIL"), ":"),
                                        )
                                    ),
                                )
                            ); ?>
                        </div>

                        <div class="d-block text-center text-md-left my-4">
                            <input type="submit" name="Register" value="<?= GetMessage("AUTH_REGISTER") ?>" class="orange btn-login"/>

                            <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="btn-reg float-none float-md-right"><?= GetMessage("AUTH_AUTH") ?></a>
                        </div>

                    </form>


                    <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
                    <p><span class="starrequired">*</span><?= GetMessage("AUTH_REQ") ?></p>

                    <!--                    <p><a href="--><? //= $arResult["AUTH_AUTH_URL"] ?><!--" rel="nofollow"><b>--><? //= GetMessage("AUTH_AUTH") ?><!--</b></a></p>-->

                    <script type="text/javascript">
                        document.bform.USER_NAME.focus();

                        jQuery(document).ready(function ($) {
                            $('#USER_EMAIL').on('change, input, keyup, keypress, keydown, focusout', function () {
                                $('#USER_LOGIN').val($(this).val());
                            });
                        });

                    </script>

                <? endif ?>

            </noindex>
        </div>
    </div>
</div>
