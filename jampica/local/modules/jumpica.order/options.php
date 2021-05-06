<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use Jumpica\Order\RestFunction;

$MODULE_ID = 'jumpica.order';

//Только для администратора
if ($USER->IsAdmin() || Loader::includeModule($MODULE_ID)) {

    Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");
    Loc::loadMessages(__FILE__);

    if (Loader::includeModule('iblock')) {

        //Получаем список типов инфоблоков
        $arOrder = ['SORT' => 'ASC'];
        $arFilter = [
            'ACTIVE' => 'Y',
        ];

        $res = CIBlockType::GetList($arOrder, $arFilter);
        while ($arRes = $res->GetNext()) {

            //Настройки типа информационных блоков по ID
            if ($arResType = CIBlockType::GetByIDLang($arRes['ID'], LANG, true)) {

                $iblockTypeId = $arResType['ID'];
                $iblockTypeName = $arResType['NAME'];
                $arIblockType[$iblockTypeId] = $iblockTypeName;

            }

        }

        //Получаем список инфоблоков выбранного типа
        $arOrder = ['SORT' => 'ASC'];
        $arFilter = [
            'ACTIVE' => 'Y',
            'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
        ];

        $res = CIBlock::GetList($arOrder, $arFilter, true);
        while ($arRes = $res->Fetch()) {

            $iblockId = $arRes['ID'];
            $iblockName = $arRes['NAME'];
            $arIblock[$iblockId] = $iblockName;

        }

        //Свойства инфоблока
        if (!empty(Option::get($MODULE_ID, 'IBLOCK_ID_CATALOG'))) {

            //Получаем список пользовательских свойств выбранного инфоблока
            $arOrder = ['SORT' => 'ASC', 'NAME' => 'ASC'];
            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => Option::get($MODULE_ID, 'IBLOCK_ID_CATALOG'),
            ];

            $res = CIBlockProperty::GetList($arOrder, $arFilter, true);
            while ($arRes = $res->GetNext()) {

                $propertyId = $arRes['ID'];
                $propertyName = $arRes['NAME'];

                //Тип список
                if ($arRes['PROPERTY_TYPE'] == 'L') {

                    $arProperty[$propertyId] = $propertyName;

                }

            }

        }

        //Список сотрудников в Битрикс24
        if (!empty(Option::get($MODULE_ID, 'BX24_WEBHOOK_USER'))) {

            $arEmployeeList = RestFunction::userEmployeeList();

        }

    }

    //Вкладки
    $arTabs = [
        [
            'DIV' => 'OrderFunction',
            'TAB' => 'Управление заказами',
            'ICON' => 'ib_order_function',
            'TITLE' => 'Управление заказами',
        ],
        [
            'DIV' => 'RestFunction',
            'TAB' => 'Интеграция Битрикс24',
            'ICON' => 'ib_rest_function',
            'TITLE' => 'Интеграция Битрикс24',
        ],
    ];
    $tabControl = new CAdminTabControl("tabControl", $arTabs);

    //Настройки модуля
    $arOptions = [
        'OrderFunction' => [
            'IBLOCK_ID_CATALOG' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с заявками:',
                'HINT' => '',
                'HEADING' => 'Настройки инфоблоков',
            ],
            'IBLOCK_ID_CATALOG_CRM' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с заявками CRM:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_MATERIAL' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с каталогом материалов:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_PROCESSING' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с обработкой материалов:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_POINTCODE' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с кодами точек доставки:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_MATRIX' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с матрицей доступа:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_IMIDG' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с имейджами:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_IMIDG_PARTNER' => [
                'TYPE' => 'iblock_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите инфоблок с имейджами (партнерские):',
                'HINT' => '',
                'HEADING' => '',
            ],
            'IBLOCK_ID_CATALOG_PROP_STATUS' => [
                'TYPE' => 'iblock_property_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите свойство статус заявки:',
                'HINT' => '',
                'HEADING' => 'Настройки свойств',
            ],
            'PLAY_ORDER_PRICE_MIN' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Пользователь может отправлять заявки в производство не дороже (руб.):',
                'HINT' => '',
                'HEADING' => '',
            ],
            'EMAIL_ADMIN' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Почта главного администратора:',
                'HINT' => '',
                'HEADING' => '',
            ],
            'LAYOUT_PRICE' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Стоимость верстки материала (для клиента):',
                'HINT' => '',
                'HEADING' => '',
            ],
            'LAYOUT_PRICE_DESIGNER' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Стоимость верстки материала (для дизайнера):',
                'HINT' => '',
                'HEADING' => '',
            ],
            'EMAIL_MANAGER' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Email адреса пользователей подтверждающих заявки (через запятую):',
                'HINT' => '',
                'HEADING' => '',
            ],
        ],
        'RestFunction' => [
            'BX24_WEBHOOK_CRM' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Введите код авторизации вебхука для работы со сделками (CRM):',
                'HINT' => 'Например: https://bx24jm.up-co.ru/rest/3/mg3r6329ficbrfuv/',
                'HEADING' => 'Настройки вебхуков',
            ],
            'BX24_WEBHOOK_TASK' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Введите код авторизации вебхука для работы с задачами (TASK):',
                'HINT' => 'Например: https://bx24jm.up-co.ru/rest/3/tcr8lcj6bjas7brh/',
                'HEADING' => '',
            ],
            'BX24_WEBHOOK_USER' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Введите код авторизации вебхука для работы с сотрудниками (USER):',
                'HINT' => 'Например: https://bx24jm.up-co.ru/rest/3/a7kebcgrox53usp1/',
                'HEADING' => '',
            ],
            'BX24_WEBHOOK_BIZ' => [
                'TYPE' => 'text',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Введите код авторизации вебхука для работы с бизнесс процессами (BIZ):',
                'HINT' => 'Например: https://bx24jm.up-co.ru/rest/3/4v4mt3v2oue5xnba/',
                'HEADING' => '',
            ],
            'BX24_TASK_MANAGER' => [
                'TYPE' => 'bx24_employee_select',
                'DEFAULT' => '',
                'DESCRIPTION' => 'Выберите главного менеджера в Битрикс24:',
                'HINT' => '',
                'HEADING' => '',
            ],
        ],
    ];

    //Вкадки и настройки модуля
    $arAllOptions = [];
    foreach ($arTabs AS $arTab) {

        $optName = $arTab['DIV'];
        $arAllOptions = array_merge($arAllOptions, $arOptions[$optName]);

    }

    //Сохранение параметров
    $request = Application::getInstance()->getContext()->getRequest();

    if ($request->isPost()) {

        if (!empty($request->getPost('save')) && check_bitrix_sessid()) {

            foreach ($arAllOptions as $code => $v) {

                if (!empty($request->getPost($code))) {

                    Option::set($MODULE_ID, $code, $request->getPost($code));

                }

            }

            CAdminMessage::ShowMessage(['MESSAGE' => 'Значения сохранены', 'TYPE' => 'OK']);

        }

    }

    $tabControl->Begin();

}

?>

<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&lang=<?= LANGUAGE_ID ?>">

    <?php

    //Подсказки и сообщения
    Extension::load('ui.hint');

    foreach ($arTabs AS $arTab) {

        $optName = $arTab['DIV'];

        $tabControl->BeginNextTab();

        foreach ($arOptions[$optName] as $code => $v) {

            ?>

            <? if (!empty($v['HEADING'])): ?>

                <tr class="heading">
                    <td colspan="2"><?= $v['HEADING'] ?></td>
                </tr>

            <? endif; ?>

            <tr>
                <td width="40%">
                    <label for=""><?= $v['DESCRIPTION'] ?></label>
                </td>
                <td width="60%">

                    <? if ($v['TYPE'] == 'iblock_select'): ?>

                        <select name="<?= $code ?>" id="<?= $code ?>">
                            <!--<option value="">Выберите инфоблок</option>-->
                            <? foreach ($arIblock as $key => $value): ?>
                                <option value="<?= $key ?>"
                                        <? if (Option::get($MODULE_ID, $code) == $key): ?>selected<? endif; ?>><?= $value ?></option>
                            <? endforeach; ?>
                        </select>

                    <? elseif ($v['TYPE'] == 'iblock_property_select'): ?>

                        <select name="<?= $code ?>" id="<?= $code ?>">
                            <!--<option value="">Выберите свойство</option>-->
                            <? foreach ($arProperty as $key => $value): ?>
                                <option value="<?= $key ?>"
                                        <? if (Option::get($MODULE_ID, $code) == $key): ?>selected<? endif; ?>><?= $value ?></option>
                            <? endforeach; ?>
                        </select>

                    <? elseif ($v['TYPE'] == 'bx24_employee_select'): ?>

                        <select name="<?= $code ?>" id="<?= $code ?>">
                            <!--<option value="">Выберите главного менеджера</option>-->
                            <? foreach ($arEmployeeList['result'] as $key => $value): ?>
                                <option value="<?= $value['ID'] ?>"
                                        <? if (Option::get($MODULE_ID, $code) == $value['ID']): ?>selected<? endif; ?>><?= $value['LAST_NAME'] ?> <?= $value['NAME'] ?> <?= $value['SECOND_NAME'] ?> (<?= $value['EMAIL'] ?>)</option>
                            <? endforeach; ?>
                        </select>

                    <? else: ?>

                        <input type="text" size="35" name="<?= $code ?>" id="<?= $code ?>"
                               value="<?= Option::get($MODULE_ID, $code) ?>">

                    <? endif; ?>

                    <? if (!empty($v['HINT'])): ?>

                        <span data-hint="<?= $v['HINT'] ?>"></span>

                    <? endif; ?>

                </td>
            </tr>

            <?php

        }

    }

    ?>

    <? $tabControl->Buttons(); ?>
    <input type="submit" name="save" value="<?= GetMessage("MAIN_SAVE") ?>"
           title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>" class="adm-btn-save">
    <input type="button" name="cancel" value="<?= GetMessage("MAIN_OPT_CANCEL") ?>"
           title="<?= GetMessage("MAIN_OPT_CANCEL_TITLE") ?>" onclick="window.location=''">
    <?= bitrix_sessid_post(); ?>
    <? $tabControl->End(); ?>

</form>

<script>
    BX.ready(function () {
        BX.UI.Hint.init(BX('#RestFunction'));
    })
</script>