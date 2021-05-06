<?php

namespace Jumpica\Order;

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\UserTable;
use CIBlockElement;

Loader::includeModule('iblock');

class OrderFunction
{

    //Варианты статусов заявки
    const STATUS_PROCESSING = 46;
    const STATUS_PRODUCTION = 47;
    const STATUS_COMPLETED = 48;
    const STATUS_DELETED = 50;
    const STATUS_UNFINISHED = 51;

    //Описание вариантов статусов заявки
    public static $statusDesc = [
        self::STATUS_UNFINISHED => 'Незавершенные',
        self::STATUS_PROCESSING => 'В обработке',
        self::STATUS_PRODUCTION => 'В производстве',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_DELETED => 'Удалена',
    ];

    /**
     * Возвращает колличество незавершенных заявок пользователя
     * @param $userId
     * @return \CIBlockResult|int
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function unfinishedOrder($userId)
    {

        $iblockIdCatalog = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG');

        $count = 0;

        if (!empty($userId) && !empty($iblockIdCatalog)) {

            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
                'CREATED_BY' => $userId,
                'PROPERTY_UF_STATUS' => self::STATUS_UNFINISHED, //Незавершенные
            ];

            $count = \CIBlockElement::GetList([], $arFilter, [], false, []);

        }

        return $count;

    }

    /**
     * Возвращает свойства пользователя по ID
     * @param $userId
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getUserFields($userId)
    {

        $arFields = [];

        if (!empty($userId)) {

            $arFields = UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                    'PERSONAL_PHONE',
                    'PERSONAL_STATE',
                    'PERSONAL_CITY',
                    'PERSONAL_STREET',
                    'UF_NOTIFICATION',
                    'UF_DOP_FIO',
                    'UF_DOP_TEL',
                    'UF_CRM_COMPANY_ID',
                    'UF_CRM_CONTACT_ID',
                    'UF_DIVISION',
                    'UF_VERIFIED_ADDRESS',
                    'UF_DELIVERY_ADDRESS_NEXT',
                    'UF_DELIVERY_ADDRESS_PREV',
					'UF_GRU',//Добавил Шпотин
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $userId,
                ],
                'limit' => 1,
            ])->fetch();

            if (!empty($arFields['PERSONAL_PHONE'])) {

                $arFields['PERSONAL_PHONE'] = '7' . substr(preg_replace('/[^\d]/', '', $arFields['PERSONAL_PHONE']), -10);

            }

        }

        return $arFields;

    }

    /**
     * Возвращает свойства заявки по ID
     * @param $orderId
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getOrderFields($orderId)
    {

        $iblockIdCatalog = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG');

        $arResult = [];

        if (!empty($orderId) && !empty($iblockIdCatalog)) {

            $arOrder = ['NAME' => 'ASC'];
            $arSelect = [
                '*',
            ];
            $arFilter = [
                'ACTIVE' => 'Y',
                'ID' => $orderId,
                'IBLOCK_ID' => $iblockIdCatalog,
            ];

            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect);
            while ($arRes = $res->GetNextElement()) {

                $arFields = $arRes->GetFields();
                $arProperties = $arRes->GetProperties();

                $arResult = array_merge($arFields, $arProperties);

            }

        }

        return $arResult;

    }

    /**
     * Возвращает свойства кода точки по ID
     * @param $pointId
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getPointFields($pointId)
    {

        $iblockIdPointcode = Option::get('jumpica.order', 'IBLOCK_ID_POINTCODE');

        $arResult = [];

        if (!empty($pointId) && !empty($iblockIdPointcode)) {

            $arOrder = ['NAME' => 'ASC'];
            $arSelect = [
                '*',
            ];
            $arFilter = [
                'ACTIVE' => 'Y',
                'ID' => $pointId,
                'IBLOCK_ID' => $iblockIdPointcode,
            ];

            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect);
            while ($arRes = $res->GetNextElement()) {

                $arFields = $arRes->GetFields();
                $arProperties = $arRes->GetProperties();

                $arResult = array_merge($arFields, $arProperties);

            }

        }

        return $arResult;

    }

    /**
     * Возвращает свойства кода точки по номеру (названию)
     * @param $pointName
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getPointFieldsInfo($pointName)
    {

        $iblockIdPointcode = Option::get('jumpica.order', 'IBLOCK_ID_POINTCODE');

        $arResult = [];

        if (!empty($pointName) && !empty($iblockIdPointcode)) {

            $arOrder = ['NAME' => 'ASC'];
            $arSelect = [
                '*',
            ];
            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdPointcode,
                'NAME' => $pointName,
            ];

            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect);
            while ($arRes = $res->GetNextElement()) {

                $arFields = $arRes->GetFields();
                $arProperties = $arRes->GetProperties();

                $arResult = array_merge($arFields, $arProperties);

            }

        }

        return $arResult;

    }

    /**
     * Возвращает идентификатор ответственного менеджера за регион
     * @param $personalState
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getCrmAssigned($personalState)
    {

        $bx24TaskManager = Option::get('jumpica.order', 'BX24_TASK_MANAGER');

        if (!empty($personalState)) {

            $regionId = \CUserFieldEnum::GetList([], ['USER_FIELD_NAME' => 'UF_REGION', 'VALUE' => $personalState])->GetNext()['ID'];

            if (!empty($regionId)) {

                $arFields = UserTable::getList([
                    'order' => ['ID' => 'ASC'],
                    'select' => [
                        'ID',
                        'UF_CRM_ASSIGNED_ID',
                        'UF_REGION',
                    ],
                    'filter' => [
                        'ACTIVE' => 'Y',
                        '!UF_CRM_ASSIGNED_ID' => false,
                        'UF_REGION' => $regionId,
                    ],
                    'limit' => 1,
                ])->fetch();

                if (!empty($arFields['UF_CRM_ASSIGNED_ID'])) {

                    $bx24TaskManager = $arFields['UF_CRM_ASSIGNED_ID'];

                }

            }

        }

        return $bx24TaskManager;

    }


    /**
     * Возвращает актуальный номер заявки по ID элемента, если ID не передан возвращает предыдущий актуальный номер заявки
     * @param bool $orderId
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function getOrderNumber($orderId = false)
    {

        $iblockIdCatalog = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG');

        $orderNumber = '';

        if (!empty($iblockIdCatalog)) {

            $arOrder = ['ID' => 'DESC'];
            $arSelect = [
                'PROPERTY_UF_ORDER_NUMBER',
            ];
            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
                [
                    'LOGIC' => 'AND',
                    '>PROPERTY_UF_ORDER_NUMBER' => 0,
                    '!PROPERTY_UF_ORDER_NUMBER' => 'ERROR',
                ],
            ];

            if (!empty($orderId)) {

                $arFilter['ID'] = $orderId;

            }

            $orderNumber = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect)->Fetch()['PROPERTY_UF_ORDER_NUMBER_VALUE'];

        }

        //Если для заявки не установлен номер
        if (!empty($orderId) && empty($orderNumber)) {

            $orderNumber = $orderId;

        }

        return $orderNumber;

    }

}

?>