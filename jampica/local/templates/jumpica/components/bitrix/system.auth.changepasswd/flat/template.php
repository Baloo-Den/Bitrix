<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult["PHONE_REGISTRATION"]) {
    CJSCore::Init('phone_auth');
}
?>
<div class="content-form login-form col-12">
    <div class="bx-auth">

        <?
        //    ShowMessage($arParams["~AUTH_RESULT"]);
        ?>

        <div class="d-block text-center">
            <img src="<?= SITE_TEMPLATE_PATH; ?>/images/logo-login.png" alt="jumpica" class="login-jumpica"/>
        </div>

        <div class="form-login">

            <? if ($arResult["SHOW_FORM"]): ?>

                <form method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <input type="hidden" name="AUTH_FORM" value="Y">
                    <input type="hidden" name="TYPE" value="CHANGE_PWD">

                    <b><?= GetMessage("AUTH_CHANGE_PASSWORD") ?></b>

                    <? if ($arResult["PHONE_REGISTRATION"]): ?>

                        <div class="form-group">
                            <input type="text" value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>" placeholder="<? echo GetMessage("sys_auth_chpass_phone_number") ?>" class="input-field form-control form-control-lg bx-auth-input" disabled="disabled"/>
                            <input type="hidden" name="USER_PHONE_NUMBER" value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>"/>
                        </div>

                        <div class="form-group">
                            <input type="text" name="USER_CHECKWORD" maxlength="50" value="<?= $arResult["USER_CHECKWORD"] ?>" placeholder="<? echo GetMessage("sys_auth_chpass_code") ?>" class="input-field form-control form-control-lg bx-auth-input" autocomplete="off"/>
                        </div>
                    <? else: ?>
                        <div class="form-group">
                            <input type="text" name="USER_LOGIN" maxlength="50" value="<?= $arResult["LAST_LOGIN"] ?>" placeholder="<?= GetMessage("AUTH_LOGIN") ?>" class="input-field form-control form-control-lg bx-auth-input"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="USER_CHECKWORD" maxlength="50" value="<?= $arResult["USER_CHECKWORD"] ?>" placeholder="<?= GetMessage("AUTH_CHECKWORD") ?>" class="input-field form-control form-control-lg bx-auth-input" autocomplete="off"/>
                        </div>
                    <? endif ?>
                    <div class="form-group">
                        <input type="password" name="USER_PASSWORD" maxlength="255" value="<?= $arResult["USER_PASSWORD"] ?>" placeholder="<?= GetMessage("AUTH_NEW_PASSWORD_REQ") ?>" class="input-field form-control form-control-lg bx-auth-input" autocomplete="off"/>

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
                        <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" placeholder="<?= GetMessage("AUTH_NEW_PASSWORD_CONFIRM") ?>" class="input-field form-control form-control-lg bx-auth-input" autocomplete="off"/>
                    </div>
                    <? if ($arResult["USE_CAPTCHA"]): ?>
                        <div class="form-group">

                            <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/>

                        </div>
                        <div class="form-group">
                            <input type="text" name="captcha_word" maxlength="50" value="" placeholder="<? echo GetMessage("system_auth_captcha") ?>" class="input-field form-control form-control-lg "/>
                        </div>
                    <? endif ?>
                    <div class="d-block text-center my-4">
                        <input type="submit" name="change_pwd" value="<?= GetMessage("AUTH_CHANGE") ?>" class="orange btn-login"/>
                    </div>
                </form>

                <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
                <p><span class="starrequired">*</span><?= GetMessage("AUTH_REQ") ?></p>

            <? if ($arResult["PHONE_REGISTRATION"]): ?>

                <script type="text/javascript">
                    new BX.PhoneAuth({
                        containerId: 'bx_chpass_resend',
                        errorContainerId: 'bx_chpass_error',
                        interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
                        data:
                        <?=CUtil::PhpToJSObject([
                            'signedData' => $arResult["SIGNED_DATA"]
                        ])?>,
                        onError:
                            function (response) {
                                var errorDiv = BX('bx_chpass_error');
                                var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                                errorNode.innerHTML = '';
                                for (var i = 0; i < response.errors.length; i++) {
                                    errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                                }
                                errorDiv.style.display = '';
                            }
                    });
                </script>

                <div id="bx_chpass_error" style="display:none"><? ShowError("error") ?></div>

                <div id="bx_chpass_resend"></div>

            <? endif ?>

            <? endif ?>

            <div class="d-block text-center">
                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="orange"><?= GetMessage("AUTH_AUTH") ?></a>
            </div>

        </div>

    </div>
</div>