<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Jumpica\Order\RestFunction;
use Jumpica\Order\OrderFunction;

Loader::includeModule('jumpica.order');

//После авторизации пользователя
AddEventHandler("main", "OnAfterUserAuthorize", ["OnAfterUserAuthorize", "OnAfterUserAuthorizeHandler"]);

class OnAfterUserAuthorize
{

    function OnAfterUserAuthorizeHandler($arUser)
    {

        //Матрица доступа
        accessMatrix();

        //Незавершенные заявки пользователя
        $_SESSION['UNFINISHED_ORDER'] = OrderFunction::unfinishedOrder($arUser['user_fields']['ID']);

        LocalRedirect('/profil/');

    }

}

//При регистрации нового пользователя
AddEventHandler("main", "OnBeforeUserRegister", ["OnBeforeUserRegister", "addSecondNameOnReg"]);

class OnBeforeUserRegister
{

    function addSecondNameOnReg(&$arParams)
    {

        if (strlen($_REQUEST["USER_SECOND_NAME"]) > 0) {

            $arParams['SECOND_NAME'] = htmlspecialcharsbx($_REQUEST["USER_SECOND_NAME"]);

        }

        if (strlen($_REQUEST["USER_PASSWORD"]) > 0) {

            $arParams['UF_PASS'] = htmlspecialcharsbx($_REQUEST["USER_PASSWORD"]);

        }

        if (strlen($_REQUEST["UF_DIVISION"]) > 0) {

            $arParams['UF_DIVISION'] = htmlspecialcharsbx($_REQUEST["UF_DIVISION"]);

        }

        if (strlen($_REQUEST["PERSONAL_STATE"]) > 0) {

            $arParams['PERSONAL_STATE'] = htmlspecialcharsbx($_REQUEST["PERSONAL_STATE"]);

        }

        if (strlen($_REQUEST["PERSONAL_CITY"]) > 0) {

            $arParams['PERSONAL_CITY'] = htmlspecialcharsbx($_REQUEST["PERSONAL_CITY"]);

        }

        if (strlen($_REQUEST["PERSONAL_STREET"]) > 0) {

            $arParams['PERSONAL_STREET'] = htmlspecialcharsbx($_REQUEST["PERSONAL_STREET"]);

        }

        $arParams['UF_DELIVERY_ADDRESS_NEXT'] = $arParams['UF_DIVISION'] . ', ' . $arParams['PERSONAL_STATE'] . ', ' . $arParams['PERSONAL_CITY'] . ', ' . $arParams['PERSONAL_STREET'];
        $arParams['UF_DELIVERY_ADDRESS_PREV'] = $arParams['UF_DIVISION'] . ', ' . $arParams['PERSONAL_STATE'] . ', ' . $arParams['PERSONAL_CITY'] . ', ' . $arParams['PERSONAL_STREET'];

    }

}

//После добавления нового пользователя
AddEventHandler("main", "OnAfterUserAdd", ["OnAfterUserAdd", "OnAfterUserAddHandler"]);

class OnAfterUserAdd
{

    function OnAfterUserAddHandler(&$arFields)
    {

        if ($arFields['ID'] > 0) {

            //Добавляем новый контакт в CRM
            //$arContact = RestFunction::crmContactAdd(OrderFunction::getUserFields($arFields['ID']));

            if (!empty($arContact['result'])) {

                $userUpdate = new CUser;
                $userUpdate->Update($arFields['ID'], [
                    'UF_CRM_CONTACT_ID' => $arContact['result'],
                ]);

            } else {

                //TODO Если не удалось добавить новый контакт в CRM

            }

            //Отправляем письмо пользователю
            \CEvent::SendImmediate('JUMPICA_NEW_USER', $arFields['LID'], $arFields, 'Y', 90);

            //Отправляем письмо администраторам
            $emailManager = getEmailManagerActivationUser();

            $arEventFieldsSend = [
                'EMAIL_MANAGER' => (!empty($emailManager)) ? $emailManager : getEmailAdministratorJumpica(),
                'CONFIRM_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/ajax/user_activation_email.php?type=confirm&id=' . $arFields['ID'] . '&code=' . md5(sha1(crc32($arFields['ID']))),
                'DELIVERY_ADDRESS_NEXT' => $arFields['UF_DELIVERY_ADDRESS_NEXT'],
            ];

            $arEventFieldsSendMerge = array_merge($arFields, $arEventFieldsSend);

            \CEvent::SendImmediate('JUMPICA_NEW_USER_ACTIVATION', 's1', $arEventFieldsSendMerge, 'Y', 95);

        }

    }

}

//До изменения параметров пользователя
AddEventHandler("main", "OnBeforeUserUpdate", ["OnBeforeUserUpdate", "OnBeforeUserUpdateHandler"]);

class OnBeforeUserUpdate
{

    function OnBeforeUserUpdateHandler(&$arFields)
    {

        global $USER;
		unset ($arFields["LOGIN"]);
			//dump($arFields); die;
        $arUserFields = OrderFunction::getUserFields($arFields['ID']);

        //Смена адреса доставки пользователя
        if (!empty($arFields['UF_DIVISION']) && !empty($arFields['PERSONAL_STATE']) && !empty($arFields['PERSONAL_CITY']) && !empty($arFields['PERSONAL_STREET']) && !in_array(1, $USER->GetUserGroupArray())) {

            //Новый адрес доставки
            $deliveryAddressNext = $arFields['UF_DIVISION'] . ', ' . $arFields['PERSONAL_STATE'] . ', ' . $arFields['PERSONAL_CITY'] . ', ' . $arFields['PERSONAL_STREET'];
            $arFields['UF_DELIVERY_ADDRESS_NEXT'] = $deliveryAddressNext;

            //Старый адрес доставки
            $deliveryAddressPrev = $arUserFields['UF_DELIVERY_ADDRESS_PREV'];
            $arFields['UF_DELIVERY_ADDRESS_PREV'] = $deliveryAddressPrev;

            //Проверяем адрес доставки
            if (md5($arFields['UF_DELIVERY_ADDRESS_NEXT']) !== md5($arFields['UF_DELIVERY_ADDRESS_PREV'])) {

                $arFields['UF_VERIFIED_ADDRESS'] = false;

                $emailManager = getEmailManagerActivationAddress();
                $userName = $arUserFields['LAST_NAME'] . ' ' . $arUserFields['NAME'] . ' ' . $arUserFields['SECOND_NAME'];

                //Отправляем письмо
                $arEventFieldsSend = [
                    'EMAIL_MANAGER' => (!empty($emailManager)) ? $emailManager : getEmailAdministratorJumpica(),
                    'CONFIRM_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/ajax/user_update_email.php?type=confirm&id=' . $arFields['ID'] . '&code=' . md5(sha1(crc32($arFields['ID']))),
                    'EDIT_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/ajax/user_update_email.php?type=edit&id=' . $arFields['ID'] . '&code=' . md5(sha1(crc32($arFields['ID']))),
                    'USER_NAME' => $userName,
                    'MANAGER_NAME' => '',
                    'DELIVERY_ADDRESS_NEXT' => $arFields['UF_DELIVERY_ADDRESS_NEXT'],
                    'DELIVERY_ADDRESS_PREV' => $arFields['UF_DELIVERY_ADDRESS_PREV'],
                ];

                \CEvent::SendImmediate('JUMPICA_CHECK_ADDRESS', 's1', $arEventFieldsSend, 'Y');

            }

        }

        //Подтверждение адреса доставки
        if (!empty($arFields['UF_VERIFIED_ADDRESS']) && !empty($arFields['UF_VERIFIED_ADDRESS_EVENT'])) {

            //Отправляем письмо
            $arEventFieldsSend = [
                'EMAIL_USER' => $arUserFields['EMAIL'],
                'DELIVERY_ADDRESS_NEXT' => $arFields['UF_DELIVERY_ADDRESS_NEXT'],
                'DELIVERY_ADDRESS_PREV' => $arFields['UF_DELIVERY_ADDRESS_PREV'],
            ];

            \CEvent::SendImmediate('JUMPICA_ADDRESS_EVENT', 's1', $arEventFieldsSend, 'Y');

        }

        $arFields['UF_VERIFIED_ADDRESS_EVENT'] = false;

    }

}

//После попытки изменения свойств пользователя
AddEventHandler("main", "OnAfterUserUpdate", ["OnAfterUserUpdate", "OnAfterUserUpdateHandler"]);

class OnAfterUserUpdate
{

    function OnAfterUserUpdateHandler(&$arFields)
    {

        if ($arFields['ID'] > 0 && empty($arFields['RESULT_MESSAGE'])) {

            //Обновляем контакт в CRM
            $arContactUpdate = RestFunction::crmContactUpdate(OrderFunction::getUserFields($arFields['ID']));

            if (empty($arContactUpdate['result'])) {

                //Добавляем новый контакт в CRM
                $arContact = RestFunction::crmContactAdd(OrderFunction::getUserFields($arFields['ID']));

                if (!empty($arContact['result'])) {

                    $userUpdate = new CUser;
                    $userUpdate->Update($arFields['ID'], [
                        'UF_CRM_CONTACT_ID' => $arContact['result'],
                    ]);

                } else {

                    //TODO Если не удалось добавить новый контакт в CRM

                }

            }

        }

    }

}

AddEventHandler("main", "OnSendUserInfo", "MyOnSendUserInfoHandler");

function MyOnSendUserInfoHandler(&$arParams)
{

    $rsUser = CUser::GetByID($arParams["USER_FIELDS"]["ID"]);
    $arUser = $rsUser->Fetch();

    if (strlen($arParams["FIELDS"]["UF_PASS"]) <= 0) {

        $arParams["FIELDS"]["UF_PASS"] = $arUser["UF_PASS"];

    } else {

        $arParams["FIELDS"]["UF_PASS"] = "пароль скрыт";

    }

}

//После добавления элемента
AddEventHandler("iblock", "OnAfterIBlockElementAdd", ["OnAfterIBlockElementAdd", "OnAfterIBlockElementAddHandler"]);

class OnAfterIBlockElementAdd
{

    function OnAfterIBlockElementAddHandler(&$arFields)
    {

        if ($arFields['IBLOCK_ID'] == 5) {

            $arValue = [];
            $userFieldsID = '';

            $resPropFields = CUserFieldEnum::GetList([], ['USER_FIELD_NAME' => 'UF_REGION']);

            while ($arPropFields = $resPropFields->GetNext()) {

                $arValue[] = $arPropFields['VALUE'];
                $userFieldsID = $arPropFields['USER_FIELD_ID'];

            }

            if (!empty($arValue)) {

                if (!in_array($arFields['PROPERTY_VALUES'][112], $arValue)) {

                    //Добавляем новое значение
                    $newEnum = new CUserFieldEnum();

                    $arAddEnum['n' . time()] = [
                        'VALUE' => $arFields['PROPERTY_VALUES'][112],
                    ];

                    $newEnum->SetEnumValues($userFieldsID, $arAddEnum);

                }

            }

        }
		/*if ($arFields['IBLOCK_ID'] == 11)//ИД Блока «Заявки на сайте»
		{
            //Отправляем письмо администраторам
            //$emailManager = getEmailManagerActivationUser();//baloo-beer@yandex.ru
			$emailManager = 'baloo-beer@yandex.ru';//

            $arEventFieldsSend = [
                'EMAIL_MANAGER' => (!empty($emailManager)) ? $emailManager : getEmailAdministratorJumpica(),
                //'CONFIRM_LINK' => 'http://' . $_SERVER['SERVER_NAME'] . '/ajax/user_activation_email.php?type=confirm&id=' . $arFields['ID'] . '&code=' . md5(sha1(crc32($arFields['ID']))),
                //'DELIVERY_ADDRESS_NEXT' => $arFields['UF_DELIVERY_ADDRESS_NEXT'],
            ];

            $arEventFieldsSendMerge = array_merge($arFields, $arEventFieldsSend);
			
			//$arFields=array('Name'=>["PROPERTY"][86]["VALUE"]);

            		
			//dump($emailManager);
			
			$last_name=$arFields["PROPERTY_VALUES"][86]["n0"]["VALUE"];
			$first_name=$arFields["PROPERTY_VALUES"][87]["n0"]["VALUE"];
			$father_name=$arFields["PROPERTY_VALUES"][88]["n0"]["VALUE"];
			$MyarFields['FULL_NAME']=$last_name.' '.$first_name.' '.$father_name;
			$MyarFields['TELE']=$arFields["PROPERTY_VALUES"][89]["n0"]["VALUE"];
			$MyarFields['MAIL']=$arFields["PROPERTY_VALUES"][90]["n0"]["VALUE"];
			$MyarFields['CITY']=$arFields["PROPERTY_VALUES"][92]["n0"]["VALUE"];
			$MyarFields['STREET']=$arFields["PROPERTY_VALUES"][93]["n0"]["VALUE"];
			$MyarFields['MATERIAL']=$arFields["PROPERTY_VALUES"][95]["n0"]["VALUE"];
			$MyarFields['WIDTH']=$arFields["PROPERTY_VALUES"][96]["n0"]["VALUE"];
			$MyarFields['HIGH']=$arFields["PROPERTY_VALUES"][97]["n0"]["VALUE"];
			$MyarFields['COUNT']=$arFields["PROPERTY_VALUES"][98]["n0"]["VALUE"];
			$MyarFields['COMMENT']=$arFields["PROPERTY_VALUES"][99]["n0"]["VALUE"];
			$all='';
			
			dump($arFields);
			\CEvent::SendImmediate('JUMPICA_NEW_USER_ACTIVATION', 's1', $MyarFields, 'Y', 98);	
			//die;
		}
*/
    }

}

//До изменения элемента
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler"]);

class OnBeforeIBlockElementUpdate
{

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {

        $iblockIdCatalog = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG');
        $iblockIdCatalogPropStatus = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG_PROP_STATUS');

        if (!empty($arFields['NAME']) && $arFields['IBLOCK_ID'] == $iblockIdCatalog) {

            $newStatus = $arFields['PROPERTY_VALUES'][$iblockIdCatalogPropStatus][0]['VALUE'];

            $arEventFields = OrderFunction::getOrderFields($arFields['ID']);

            //Отправляем письмо
            $arEventFieldsSend = [
                'LID' => $arEventFields['LID'],
                'EMAIL_MANAGER' => getEmailAdministratorJumpica(),
                'EMAIL_USER' => $arEventFields['UF_EMAIL']['VALUE'],
                'ORDER_ID' => $arEventFields['UF_ORDER_NAME']['VALUE'],
                'ORDER_STATUS' => OrderFunction::$statusDesc[$newStatus],
            ];

            //Сравниваем статусы
            if ($arEventFields['UF_STATUS']['VALUE_ENUM_ID'] != $newStatus) {

                \CEvent::SendImmediate('JUMPICA_CHANGE_STATUS', $arEventFields['LID'], $arEventFieldsSend, 'Y');

            }

        }

    }

}

//Вызывается в момент добавления почтового события
AddEventHandler("main", "OnBeforeEventAdd", ["OnBeforeEventAdd", "OnBeforeEventAddHandler"]);

class OnBeforeEventAdd
{

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {

        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/log/event_add_log.txt", print_r($event, true) . print_r($lid, true) . print_r($arFields, true) . "\n", FILE_APPEND);

    }

}

//Вызывается перед отправкой сообщения
AddEventHandler('main', 'OnBeforeEventSend', ["OnBeforeEventSend", "OnBeforeEventSendHandler"]);

class OnBeforeEventSend
{

    function OnBeforeEventSendHandler($arFields, $arTemplate)
    {

        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/log/event_send_log.txt", print_r($arFields, true) . print_r($arTemplate, true) . "\n", FILE_APPEND);

    }

}

?>
