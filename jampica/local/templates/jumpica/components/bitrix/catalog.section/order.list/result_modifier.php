<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
//dump($arResult['ITEMS']["UF_PRICE"]);
if (Bitrix\Main\Loader::includeModule('jumpica.order')) {

    $iblockIdCatalogPropStatus = Bitrix\Main\Config\Option::get('jumpica.order', 'IBLOCK_ID_CATALOG_PROP_STATUS');

    //Статусы заявок, тип список
    $arResult['STATUS_LIST'] = \Bitrix\Iblock\PropertyEnumerationTable::getList([
        'filter' => ['PROPERTY_ID' => $iblockIdCatalogPropStatus],
    ])->fetchAll();

    sort($arResult['STATUS_LIST']);

    //Пользователь может отправлять заявки в производство не дороже
    $arResult['PLAY_ORDER_PRICE_MIN'] = Bitrix\Main\Config\Option::get('jumpica.order', 'PLAY_ORDER_PRICE_MIN');

}

//Время отправки заявки на подтверждение
$todayTime = time();

$gru=get_gru();//Получаем название роли
foreach ($arResult['ITEMS'] as $key => $item) {
	//if($gru=='СВ' || $gru=='ТС')
			//$arResult['ITEMS'][$key]['PROPERTIES']['UF_PRICE']['VALUE']='0';//Если роль СВ или ТС, то цене присваиваем пустое значение
    //Блокировка отправки заявки на 24 часа
    $confirmationTime = $item['PROPERTIES']['UF_CONFIRMATION_SENT']['VALUE'] + 86400;

    //Если заявка в статусе Отклонена
    if ($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] == 83) {

        $arResult['ITEMS'][$key]['ORDER_PLAY'] = 'R';

    } else {

        if ($confirmationTime < $todayTime) {

            $arResult['ITEMS'][$key]['ORDER_PLAY'] = 'Y';

        } else {

            $arResult['ITEMS'][$key]['ORDER_PLAY'] = 'N';

        }

    }

}

?>
