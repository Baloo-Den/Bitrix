<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="content-form login-form col-12">
    <div class="fields">



        <div class="d-block text-center">
            <img src="<?= SITE_TEMPLATE_PATH; ?>/images/logo-login.png" alt="jumpica" class="login-jumpica"/>
            <?
            ShowMessage($arParams["~AUTH_RESULT"]);
            ShowMessage($arResult['ERROR_MESSAGE']);
            ?>
        </div>

        <div class="form-login">

            <form name="form_auth" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">

                <input type="hidden" name="AUTH_FORM" value="Y"/>
                <input type="hidden" name="TYPE" value="AUTH"/>
                <? if (strlen($arResult["BACKURL"]) > 0): ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? endif ?>
                <?
                foreach ($arResult["POST"] as $key => $value) {
                    ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <?
                }
                ?>

                <div class="form-group">
                    <!-- <label class="field-title">--><? //= GetMessage("AUTH_LOGIN") ?><!--</label>-->
                    <input type="text" name="USER_LOGIN" maxlength="50" value="<?= $arResult["LAST_LOGIN"] ?>" class="input-field form-control form-control-lg"/>
                </div>

                <div class="form-group">
                    <!-- <label class="field-title">--><? //= GetMessage("AUTH_PASSWORD") ?><!--</label>-->

                    <div class="form-input">
                        <input type="password" name="USER_PASSWORD" maxlength="50" class="input-field form-control form-control-lg"/>
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
                </div>


                <? if ($arResult["CAPTCHA_CODE"]): ?>
                    <div class="form-group">
                        <label class="field-title"><?= GetMessage("AUTH_CAPTCHA_PROMT") ?></label>
                        <div class="form-input"><input type="text" name="captcha_word" maxlength="50" class="input-field form-control form-control-lg"/></div>
                        <p style="clear: left;"><input type="hidden" name="captcha_sid" value="<? echo $arResult["CAPTCHA_CODE"] ?>"/><img src="/bitrix/tools/captcha.php?captcha_sid=<? echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/></p>
                    </div>
                <? endif; ?>

                <?
                if ($arResult["STORE_PASSWORD"] == "Y") {
                    ?>
                    <div class="d-block form-check form-check-inline">
                        <input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" class="form-check-input"/>
                        <label class="form-check-label" for="USER_REMEMBER">&nbsp;<?= GetMessage("AUTH_REMEMBER_ME") ?></label>
                    </div>
                    <?
                }
                ?>
                <div class="d-block text-center text-md-left my-4">
                    <input type="submit" name="Login" value="<?= GetMessage("AUTH_AUTHORIZE") ?>" class="orange btn-login"/>

                    <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>" rel="nofollow" class="btn-reg float-none float-md-right"><?= GetMessage("AUTH_REGISTER") ?></a>
                </div>
                <?
                if ($arParams["NOT_SHOW_LINKS"] != "Y") {
                    ?>
                    <noindex>
                    <?
                    if ($arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y") {
                        ?>
                        <div class="d-block text-center">
                            <!--
                            <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>" rel="nofollow" class="orange"><?= GetMessage("AUTH_REGISTER") ?></a>
                            <br/>
                            <?= GetMessage("AUTH_FIRST_ONE") ?>
                            -->
                            <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>" rel="nofollow"><?= GetMessage("AUTH_REG_FORM") ?></a>
                        </div>
                        <?
                    }
                    ?>

                    <div class="d-block text-center">
                        <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>" rel="nofollow" class="orange"><?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?></a>
                        <br/>
                        <?= GetMessage("AUTH_GO") ?> <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>" rel="nofollow"><?= GetMessage("AUTH_GO_AUTH_FORM") ?></a>
                        <br/>
                        <?= GetMessage("AUTH_MESS_1") ?> <a href="<?= $arResult["AUTH_CHANGE_PASSWORD_URL"] ?>" rel="nofollow"><?= GetMessage("AUTH_CHANGE_FORM") ?></a>
                    </div>
                    </noindex><?
                }
                ?>
            </form>

        </div>


        <script type="text/javascript">
            <?
            if (strlen($arResult["LAST_LOGIN"]) > 0) {
            ?>
            try {
                document.form_auth.USER_PASSWORD.focus();
            } catch (e) {
            }
            <?
            } else {
            ?>
            try {
                document.form_auth.USER_LOGIN.focus();
            } catch (e) {
            }
            <?
            }
            ?>
        </script>

    </div>
</div>