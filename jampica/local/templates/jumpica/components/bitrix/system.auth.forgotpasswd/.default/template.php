<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?><?



?>
<div class="content-form login-form col-12">
    <div class="d-block text-center">
        <img src="<?= SITE_TEMPLATE_PATH; ?>/images/logo-login.png" alt="jumpica" class="login-jumpica"/>
    </div>

    <div class="form-login">

        <? ShowMessage($arParams["~AUTH_RESULT"]);?>

        <form name="bform" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">
            <?
            if (strlen($arResult["BACKURL"]) > 0) {
                ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <?
            }
            ?>
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="SEND_PWD">

<!--            <p>--><?// echo GetMessage("sys_forgot_pass_label") ?><!--</p>-->

            <div class="form-group">
<!--                <div><b>--><?//= GetMessage("sys_forgot_pass_login1") ?><!--</b></div>-->
                <div>
                    <input type="text" name="USER_LOGIN" value="<?= $arResult["LAST_LOGIN"] ?>" placeholder="<?= GetMessage("sys_forgot_pass_login1") ?>" class="input-field form-control form-control-lg"/>
                    <input type="hidden" name="USER_EMAIL"  class="input-field form-control form-control-lg"/>
                </div>
<!--                <div>--><?// echo GetMessage("sys_forgot_pass_note_email") ?><!--</div>-->
            </div>

            <? if ($arResult["PHONE_REGISTRATION"]): ?>

                <div class="form-group">
                    <div><b><?= GetMessage("sys_forgot_pass_phone") ?></b></div>
                    <div><input type="text" name="USER_PHONE_NUMBER" value=""  class="input-field form-control form-control-lg"/></div>
                    <div><? echo GetMessage("sys_forgot_pass_note_phone") ?></div>
                </div>
            <? endif; ?>

            <? if ($arResult["USE_CAPTCHA"]): ?>
                <div class="form-group">
                    <div>
                        <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/>
                    </div>
                    <div><? echo GetMessage("system_auth_captcha") ?></div>
                    <div><input type="text" name="captcha_word" maxlength="50" value=""  class="input-field form-control form-control-lg"/></div>
                </div>
            <? endif ?>
            <div class="d-block text-center text-md-left my-4">
                <input type="submit" name="send_account_info" value="<?= GetMessage("AUTH_SEND") ?>" class="orange btn-login"/>

                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="btn-reg float-none float-md-right"><?= GetMessage("AUTH_AUTH") ?></a>
            </div>
        </form>

        <!--
        <div class="d-block text-center">
            <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" class="orange"><?= GetMessage("AUTH_AUTH") ?></a>
        </div>
        -->
    </div>
</div>

<script type="text/javascript">
    document.bform.onsubmit = function () {
        document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;
    };
    document.bform.USER_LOGIN.focus();
</script>
