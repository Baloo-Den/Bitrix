<?php

namespace Jumpica\Order;

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

class RestFunction
{

    /**
     * Добавляет новый контакт в CRM
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmContactAdd($arFields)
    {

        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');

        if (!empty($arFields) && !empty($bx24webhookUrl)) {

            $arData = http_build_query([
                'fields' => [
                    'NAME' => (!empty($arFields['NAME'])) ? $arFields['NAME'] : 'Не заполнено',
                    'SECOND_NAME' => (!empty($arFields['SECOND_NAME'])) ? $arFields['SECOND_NAME'] : 'Не заполнено',
                    'LAST_NAME' => (!empty($arFields['LAST_NAME'])) ? $arFields['LAST_NAME'] : 'Не заполнено',
                    'PHONE' => [
                        [
                            'VALUE' => $arFields['PERSONAL_PHONE'],
                            'VALUE_TYPE' => 'WORK',
                        ],
                    ],
                    'EMAIL' => [
                        [
                            'VALUE' => $arFields['EMAIL'],
                            'VALUE_TYPE' => 'WORK',
                        ],
                    ],
                    'COMPANY_ID' => '',
                ],
                'params' => [
                    'REGISTER_SONET_EVENT' => 'Y',
                ],
            ]);

            return self::crmSend($arData, $bx24webhookUrl . 'crm.contact.add');

        }

        return false;

    }


    /**
     * Обновляет контакт в CRM
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmContactUpdate($arFields)
    {

        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');

        if (!empty($arFields) && !empty($bx24webhookUrl) && !empty($arFields['UF_CRM_CONTACT_ID'])) {

            $arData = http_build_query([
                'id' => $arFields['UF_CRM_CONTACT_ID'],
                'fields' => [
                    'NAME' => (!empty($arFields['NAME'])) ? $arFields['NAME'] : 'Не заполнено',
                    'SECOND_NAME' => (!empty($arFields['SECOND_NAME'])) ? $arFields['SECOND_NAME'] : 'Не заполнено',
                    'LAST_NAME' => (!empty($arFields['LAST_NAME'])) ? $arFields['LAST_NAME'] : 'Не заполнено',
                    'PHONE' => [
                        [
                            'VALUE' => $arFields['PERSONAL_PHONE'],
                            'VALUE_TYPE' => 'WORK',
                        ],
                    ],
                    'EMAIL' => [
                        [
                            'VALUE' => $arFields['EMAIL'],
                            'VALUE_TYPE' => 'WORK',
                        ],
                    ],
                    'COMPANY_ID' => '',
                ],
                'params' => [
                    'REGISTER_SONET_EVENT' => 'Y',
                ],
            ]);

            return self::crmSend($arData, $bx24webhookUrl . 'crm.contact.update');

        }

        return false;

    }

    /**
     * Добавляет новую сделку в CRM
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmLeadAdd($arFields)
    {

        $urlAddressSite = Option::get('main', 'server_name');
        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');
        $bx24TaskManager = Option::get('jumpica.order', 'BX24_TASK_MANAGER');
        $layoutPrice = Option::get('jumpica.order', 'LAYOUT_PRICE');

        if (!empty($arFields) && !empty($bx24webhookUrl)) {

            //Идентификатор ответственного за сделку менеджера в CRM
            if ($arFields['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'product' && !empty($arFields['UF_CRM_ASSIGNED_ID']['VALUE'])) {

                $bx24TaskManager = $arFields['UF_CRM_ASSIGNED_ID']['VALUE'];

            }

            //Контакт по сделке
            if (!empty($arFields['UF_SUPER_CRM_ID']['VALUE'])) {

                //Идентификатор супервайзера
                $contactId = $arFields['UF_SUPER_CRM_ID']['VALUE'];

            } else {

                //Идентификатор торг.представителя
                $contactId = $arFields['UF_CRM_CONTACT_ID']['VALUE'];

            }

            $arLead = [
                'FIELDS' => [
                    'TITLE' => $arFields['NAME'],
                    'CONTACT_ID' => $contactId,
                    'ASSIGNED_BY_ID' => $bx24TaskManager,
                ],
            ];

            $fileData = [];

            foreach ($arFields['UF_FILES']['VALUE'] as $key => $value) {

                $arFileInfo = \CFile::GetFileArray($value);
                $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                $data = file_get_contents($url);
                $base64 = base64_encode($data);
                $name = $arFileInfo['ORIGINAL_NAME'];

                $fileData[]['fileData'] = [$name, $base64];

            }

            $totalProcessingPrice = 0;
            $totalLayoutPrice = 0;

            foreach ($arFields['UF_MATERIAL']['VALUE'] as $key => $value) {

                //Стоимость обработки материалов
                $arProcessingPrice = explode('###', $arFields['UF_PRICE_PROCESSING']['VALUE'][$key]); //Стоимость обработки
                $arProcessingElement = explode('###', $arFields['UF_PROCESSING']['VALUE'][$key]); //Варианты обработки

                foreach ($arProcessingElement as $proKey => $proValue) {

                    $totalProcessingPrice += $arProcessingPrice[$proKey];

                }

				//Колличество макетов
				if (!empty($arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key])) {
					
					$countLayouts = $arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key];
					
				} else {

                    //По умолчанию 1 макет, чтобы корректно рассчитывались уже существующие заявки
					$countLayouts = 1;
					
				}
				
				//Стоимость верстки материалов
                $totalLayoutPrice += ($layoutPrice * $countLayouts);

            }

            //Блок о сделке
            $arLead['FIELDS']['TYPE_ID'] = 'SALE'; //Тип сделки, продажа
            $arLead['FIELDS']['UF_CRM_5EA04CB3248CF'] = $fileData; //Прикрепленные файлы к заявке
            $arLead['FIELDS']['COMMENTS'] = $arFields['UF_COMMENT']['VALUE']; //Комментарий к заявке

            //Блок контактное лицо
            $arLead['FIELDS']['UF_CRM_1583443708'] = $arFields['UF_PERSONAL_PHONE']['VALUE']; //Телефон
            $arLead['FIELDS']['UF_CRM_1583443727'] = $arFields['UF_EMAIL']['VALUE']; //Email
            $arLead['FIELDS']['UF_CRM_5E5D0156AC714'] = $arFields['UF_LAST_NAME']['VALUE']; //Фамилия
            $arLead['FIELDS']['UF_CRM_5E5D0156C4045'] = $arFields['UF_NAME']['VALUE']; //Имя
            $arLead['FIELDS']['UF_CRM_5E5D0156CF4B9'] = $arFields['UF_SECOND_NAME']['VALUE']; //Отчество
            $arLead['FIELDS']['UF_CRM_1583194621'] = $arFields['UF_DIVISION']['VALUE']; //Дивизион
            $arLead['FIELDS']['UF_CRM_5E5D0156E3DC9'] = $arFields['UF_PERSONAL_STATE']['VALUE']; //Область
            $arLead['FIELDS']['UF_CRM_1583194642'] = $arFields['UF_PERSONAL_CITY']['VALUE']; //Город
            $arLead['FIELDS']['UF_CRM_5E5D0156EF070'] = $arFields['UF_PERSONAL_STREET']['VALUE']; //Улица

            //Блок доп.контактное лицо
            $arLead['FIELDS']['UF_CRM_5E57A0066F98F'] = $arFields['UF_DOP_PHONE']['VALUE']; //Телефон (доп.)
            $arLead['FIELDS']['UF_CRM_5E57A00666F92'] = $arFields['UF_DOP_USER']['VALUE']; //Контактное лицо (доп.)

            //Блок доставка
            $arLead['FIELDS']['UF_CRM_5E57A00674ADB'] = $arFields['UF_DELIVERY_PLACE']['VALUE']; //Количество грузомест

            //Способ отправки
            if ($arFields['UF_DELIVERY_TYPE']['VALUE'] == 'Экспресс') {

                $arLead['FIELDS']['UF_CRM_1592308505789'] = 28; //Экспресс доставка

            } else {

                $arLead['FIELDS']['UF_CRM_1592308505789'] = 27; //Сборный груз

            }

            //Блок производство
            $arLead['FIELDS']['UF_CRM_1592462131999'] = 31; //Гуднайт принт

            $arLead['FIELDS']['UF_CRM_1583451279'] = $arFields['UF_MARKIROVKA_JUMPICA']['VALUE']; //Маркировка джампика
            $arLead['FIELDS']['UF_CRM_1583366097'] = $arFields['UF_MARKIROVKA_TRANSPORT']['VALUE'][0]; //Маркировка транспортная заявка
            $arLead['FIELDS']['UF_CRM_1585315311266'] = $arFields['UF_MATERIAL_WEIGHT']['VALUE']; //Фактический вес

            //Блок доп.стоимость
            $arLead['FIELDS']['UF_CRM_1590274396838'] = $arFields['UF_PRICE_DELIVERY']['VALUE']; //Стоимость доставки
            $arLead['FIELDS']['UF_CRM_1590274980711'] = $totalLayoutPrice; //Стоимость верстки
            $arLead['FIELDS']['UF_CRM_1590274932219'] = $totalProcessingPrice; //Стоимость обработки материалов

            //Служебные поля
            $arLead['FIELDS']['UF_CRM_1583196733'] = $arFields['UF_ORDER_ID']['VALUE']; //ID заявки на сайте
            $arLead['FIELDS']['UF_CRM_1583198091'] = md5(sha1(crc32($arFields['UF_ORDER_ID']['VALUE']))); //Проверочный код
            $arLead['FIELDS']['UF_CRM_1583788975'] = $arFields['UF_ORDER_TYPE']['VALUE_XML_ID']; //Тип заявки
            $arLead['FIELDS']['UF_CRM_1593513193045'] = $arFields['UF_ORDER_NUMBER']['VALUE']; //Номер заявки на сайте

            //Блок супервайзера
            $arLead['FIELDS']['UF_CRM_1593513006701'] = $arFields['UF_SUPER_NAME']['VALUE']; //Имя супервайзера
            $arLead['FIELDS']['UF_CRM_1593513023588'] = $arFields['UF_SUPER_SURNAME']['VALUE']; //Фамилия супервайзера
            $arLead['FIELDS']['UF_CRM_1593513036975'] = $arFields['UF_SUPER_LASTNAME']['VALUE']; //Отчество супервайзера
            $arLead['FIELDS']['UF_CRM_1593513107309'] = $arFields['UF_SUPER_EMAIL']['VALUE']; //Email супервайзера
            $arLead['FIELDS']['UF_CRM_1593513074132'] = $arFields['UF_SUPER_PHONE']['VALUE']; //Телефон супервайзера
			
			//Направление
			$arLead['FIELDS']['CATEGORY_ID'] = 1; //Направление сделки

            $arData = http_build_query($arLead);

            return self::crmSend($arData, $bx24webhookUrl . 'crm.deal.add');

        }

        return false;

    }

    /**
     * Добавляет новую рекламацию в CRM
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmLeadAddReclamation($arFields)
    {

        $urlAddressSite = Option::get('main', 'server_name');
        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');
        $bx24TaskManager = Option::get('jumpica.order', 'BX24_TASK_MANAGER');

        if (!empty($arFields) && !empty($bx24webhookUrl)) {

            $dealName = $arFields['UF_ORDER_NAME']['VALUE'] . '_' . $arFields['UF_PRICE']['VALUE'];

            $arLead = [
                'FIELDS' => [
                    'TITLE' => $arFields['UF_ORDER_NAME']['VALUE'] . '_' . $arFields['UF_PRICE']['VALUE'] . '_Рекламация',
                    'CONTACT_ID' => $arFields['UF_CRM_CONTACT_ID']['VALUE'],
                    'ASSIGNED_BY_ID' => $bx24TaskManager,
                ],
            ];

            $fileData = [];

            foreach ($arFields['UF_DEFECT_FILES']['VALUE'] as $key => $value) {

                $arFileInfo = \CFile::GetFileArray($value);
                $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                $data = file_get_contents($url);
                $base64 = base64_encode($data);
                $name = $arFileInfo['ORIGINAL_NAME'];

                $fileData[]['fileData'] = [$name, $base64];

            }

            //Блок о сделке
            $arLead['FIELDS']['TYPE_ID'] = 'SERVICE'; //Тип сделки, сервисное обслуживание
            $arLead['FIELDS']['UF_CRM_5EA04CB3248CF'] = $fileData; //Прикрепленные файлы
            $arLead['FIELDS']['COMMENTS'] = "Рекламация по сделке <a href='/crm/deal/list/?with_preset=Y&FIND=$dealName'>$dealName</a><br /><br />" . $arFields['UF_DEFECT_INFO']['VALUE']; //Комментарий к рекламации

            //Блок контактное лицо
            $arLead['FIELDS']['UF_CRM_1583443708'] = $arFields['UF_PERSONAL_PHONE']['VALUE']; //Телефон
            $arLead['FIELDS']['UF_CRM_1583443727'] = $arFields['UF_EMAIL']['VALUE']; //Email
            $arLead['FIELDS']['UF_CRM_5E5D0156AC714'] = $arFields['UF_LAST_NAME']['VALUE']; //Фамилия
            $arLead['FIELDS']['UF_CRM_5E5D0156C4045'] = $arFields['UF_NAME']['VALUE']; //Имя
            $arLead['FIELDS']['UF_CRM_5E5D0156CF4B9'] = $arFields['UF_SECOND_NAME']['VALUE']; //Отчество
            $arLead['FIELDS']['UF_CRM_1583194621'] = $arFields['UF_DIVISION']['VALUE']; //Дивизион
            $arLead['FIELDS']['UF_CRM_5E5D0156E3DC9'] = $arFields['UF_PERSONAL_STATE']['VALUE']; //Область
            $arLead['FIELDS']['UF_CRM_1583194642'] = $arFields['UF_PERSONAL_CITY']['VALUE']; //Город
            $arLead['FIELDS']['UF_CRM_5E5D0156EF070'] = $arFields['UF_PERSONAL_STREET']['VALUE']; //Улица

            //Блок доп.контактное лицо
            $arLead['FIELDS']['UF_CRM_5E57A0066F98F'] = $arFields['UF_DOP_PHONE']['VALUE']; //Телефон (доп.)
            $arLead['FIELDS']['UF_CRM_5E57A00666F92'] = $arFields['UF_DOP_USER']['VALUE']; //Контактное лицо (доп.)

            //Служебные поля
            $arLead['FIELDS']['UF_CRM_1583788975'] = 'reclamation'; //Тип заявки

            $arData = http_build_query($arLead);

            return self::crmSend($arData, $bx24webhookUrl . 'crm.deal.add');

        }

        return false;

    }

    /**
     * Добавляет товары в каталог
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmProductAdd($arFields)
    {

        $urlAddressSite = Option::get('main', 'server_name');
        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');
        $iblockImidg = Option::get('jumpica.order', 'IBLOCK_ID_IMIDG');
        $iblockImidgPartner = Option::get('jumpica.order', 'IBLOCK_ID_IMIDG_PARTNER');

        if (!empty($arFields) && !empty($bx24webhookUrl)) {

            $cnt = 1;
            $proCnt = 1;

            foreach ($arFields['UF_MATERIAL']['VALUE'] as $key => $value) {

                $cnt++;

                //Заявка
                if ($arFields['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'product') {

                    //Файлы к материалам
                    $fileData = [];

                    foreach ($arFields['UF_MATERIAL_FILES']['VALUE'] as $mKey => $mValue) {

                        $arFileInfo = \CFile::GetFileArray($mValue);
                        $fileDescription = explode('###', $arFileInfo['DESCRIPTION']);

                        //Уникальный префикс файла
                        //$uniqueFilePrefix = md5($arFields['UF_MATERIAL']['VALUE'][$key] . '_' . $arFields['UF_PROCESSING']['VALUE'][$key] . '_' . $arFields['UF_WIDTH']['VALUE'][$key] . '_' . $arFields['UF_HEIGHT']['VALUE'][$key] . '_' . $arFields['UF_AMOUNT']['VALUE'][$key] . '_' . $arFields['UF_PRICE_DETAIL']['VALUE'][$key]);
                        $uniqueFilePrefix = md5($arFields['UF_MATERIAL']['VALUE'][$key]);

                        if ($key > 0) {
                            $uniqueFilePrefix = $uniqueFilePrefix.$key;
                        }

                        if ($fileDescription[0] == $uniqueFilePrefix) {

                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $data = file_get_contents($url);
                            $base64 = base64_encode($data);
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $fileData[]['fileData'] = [$name, $base64];

                        }

                    }

                    //Имейдж к материалам
                    $fileDataImidg = [];

                    if (!empty($arFields['UF_IMIDG']['VALUE'][$key]) && $arFields['UF_IMIDG']['VALUE'][$key] != 'Имейдж не выбран') {

                        $arOrder = ['NAME' => 'ASC'];
                        $arSelect = [
                            'PREVIEW_PICTURE',
                        ];
                        $arFilter = [
                            'IBLOCK_ID' => $iblockImidg,
                            'ID' => $arFields['UF_IMIDG']['VALUE'][$key],
                        ];

                        $imidgId = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect)->Fetch()['PREVIEW_PICTURE'];

                        if (!empty($imidgId)) {

                            $arFileInfo = \CFile::GetFileArray($imidgId);
                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $data = file_get_contents($url);
                            $base64 = base64_encode($data);
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $fileDataImidg[]['fileData'] = [$name, $base64];

                        }

                    }

                    //Имейдж к материалам (партнерский)
                    $fileDataImidgPartner = [];

                    if (!empty($arFields['UF_IMIDG_PARTNER']['VALUE'][$key]) && $arFields['UF_IMIDG_PARTNER']['VALUE'][$key] != 'Имейдж не выбран') {

                        $arOrder = ['NAME' => 'ASC'];
                        $arSelect = [
                            'PREVIEW_PICTURE',
                        ];
                        $arFilter = [
                            'IBLOCK_ID' => $iblockImidgPartner,
                            'ID' => $arFields['UF_IMIDG_PARTNER']['VALUE'][$key],
                        ];

                        $imidgIdPartner = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect)->Fetch()['PREVIEW_PICTURE'];

                        if (!empty($imidgIdPartner)) {

                            $arFileInfo = \CFile::GetFileArray($imidgIdPartner);
                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $data = file_get_contents($url);
                            $base64 = base64_encode($data);
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $fileDataImidgPartner[]['fileData'] = [$name, $base64];

                        }

                    }

                    //Товары
                    $arProduct['cmd_' . $cnt] = 'crm.product.add?' . http_build_query([
                            'FIELDS' => [
                                'NAME' => $arFields['UF_MATERIAL']['VALUE'][$key] . ' - ' . $arFields['UF_WIDTH']['VALUE'][$key] . ' мм x ' . $arFields['UF_HEIGHT']['VALUE'][$key] . ' мм, ' . $arFields['UF_AMOUNT']['VALUE'][$key] . ' шт. - ' . $arFields['UF_PROCESSING']['VALUE'][$key],
                                'CURRENCY_ID' => 'RUB',
                                'PRICE' => $arFields['UF_PRICE_DETAIL']['VALUE'][$key],
                                'SORT' => 500,
                                'MEASURE' => 5,
                                'DESCRIPTION' => 'Комментарий: ' . $arFields['UF_MATERIAL_COMMENT']['VALUE'][$key],
                                'PROPERTY_97' => $fileData, //Прикрепленный файл
                                'PROPERTY_98' => $arFields['UF_MARKIROVKA_JUMPICA']['VALUE'][$key], //Маркировка джампика
                                'PROPERTY_99' => $arFields['UF_POINT_NAME']['VALUE'][$key], //Код точки МТС
                                'PROPERTY_100' => $arFields['UF_WIDTH']['VALUE'][$key], //Ширина
                                'PROPERTY_101' => $arFields['UF_HEIGHT']['VALUE'][$key], //Высота
                                'PROPERTY_102' => $arFields['UF_MATERIAL']['VALUE'][$key], //Наименование материала
                                'PROPERTY_103' => ($arFields['UF_MATERIAL_PVH']['VALUE'][$key] == 'Y') ? '70' : '', //ПВХ материал
                                'PROPERTY_106' => $arFields['UF_AMOUNT']['VALUE'][$key], //Количество изделий
                                'PROPERTY_110' => $arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key], //Количество макетов
                                'PROPERTY_107' => $fileDataImidg, //Выбранный имидж
                                'PROPERTY_111' => $fileDataImidgPartner, //Выбранный имидж (партнерский)
                                'PROPERTY_108' => $arFields['UF_PROCESSING']['VALUE'][$key], //Обработка материала
                                'PROPERTY_109' => $arFields['UF_MARKIROVKA']['VALUE'][$key], //Маркировка для доставки
                            ],
                        ]);

                }

            }

            $arData = http_build_query(['cmd' => $arProduct]);

            return self::crmSend($arData, $bx24webhookUrl . 'batch.json');

        }

        return false;

    }

    /**
     * Добавляет товары в сделку
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function crmLeadProductrowsSet($arFields)
    {

        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_CRM');

        if (!empty($arFields) && !empty($bx24webhookUrl)) {

            $arProduct = [
                'id' => $arFields['UF_CRM_LEAD_ID']['VALUE'],
            ];

            //Заявка
            if ($arFields['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'product') {

                //Товары
                foreach ($arFields['UF_MATERIAL']['VALUE'] as $key => $value) {

                    $arProduct['rows'][] = [
                        'PRODUCT_ID' => $arFields['UF_CRM_PRODUCT_ID']['VALUE'][$key],
                        'PRICE' => $arFields['UF_PRICE_DETAIL']['VALUE'][$key],
                        'QUANTITY' => $arFields['UF_AMOUNT']['VALUE'][$key],
                    ];

                }

            }

            $arData = http_build_query($arProduct);

            return self::crmSend($arData, $bx24webhookUrl . 'crm.deal.productrows.set');

        }

        return false;

    }

    /**
     * Создает новую задачу
     * @param $arFields
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function tasksTaskAdd($arFields)
    {

        $urlAddressSite = Option::get('main', 'server_name');
        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_TASK');
        $bx24TaskManager = Option::get('jumpica.order', 'BX24_TASK_MANAGER');
        $layoutPriceDesigner = Option::get('jumpica.order', 'LAYOUT_PRICE_DESIGNER');
        $iblockImidg = Option::get('jumpica.order', 'IBLOCK_ID_IMIDG');
        $iblockImidgPartner = Option::get('jumpica.order', 'IBLOCK_ID_IMIDG_PARTNER');

        if (!empty($arFields) && !empty($bx24TaskManager) && !empty($bx24webhookUrl)) {

            //Заявка
            if ($arFields['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'product') {

                $taskName = $arFields['UF_PERSONAL_CITY']['VALUE'] . '_' . $arFields['UF_ORDER_NUMBER']['VALUE'] . '_Заявка';
                $taskDescription = "Описание задачи с материалами \n\n";

                $cnt = 0;
				$totalCountLayouts = 0;

                foreach ($arFields['UF_MATERIAL']['VALUE'] as $key => $value) {

                    $cnt++;

                    $taskDescription .= "№$cnt \n";
                    $taskDescription .= 'Материал: ' . $arFields['UF_MATERIAL']['VALUE'][$key] . "\n";
                    $taskDescription .= 'Способ обработки: ' . $arFields['UF_PROCESSING']['VALUE'][$key] . "\n";
                    $taskDescription .= 'Ширина: ' . $arFields['UF_WIDTH']['VALUE'][$key] . " мм. \n";
                    $taskDescription .= 'Высота: ' . $arFields['UF_HEIGHT']['VALUE'][$key] . " мм. \n";
                    $taskDescription .= 'Количество изделий: ' . $arFields['UF_AMOUNT']['VALUE'][$key] . " шт. \n";
                    $taskDescription .= 'Количество макетов: ' . $arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key] . " шт. \n";
                    $taskDescription .= 'Наименование: ' . $arFields['UF_MARKIROVKA']['VALUE'][$key] . "\n";
                    $taskDescription .= "\n";

                    //Комментарий к материалу
                    if (!empty($arFields['UF_MATERIAL_COMMENT']['VALUE'][$key]) && $arFields['UF_MATERIAL_COMMENT']['VALUE'][$key] != 'Нет комментария') {

                        $taskDescription .= 'Комментарий: ' . $arFields['UF_MATERIAL_COMMENT']['VALUE'][$key] . "\n";
                        $taskDescription .= "\n";

                    }

                    //Файлы к материалу
                    $materialFileName = '';
                    $materialFileUrl = '';

                    foreach ($arFields['UF_MATERIAL_FILES']['VALUE'] as $mKey => $mValue) {

                        $arFileInfo = \CFile::GetFileArray($mValue);
                        $fileDescription = explode('###', $arFileInfo['DESCRIPTION']);

                        //Уникальный префикс файла
                        //$uniqueFilePrefix = md5($arFields['UF_MATERIAL']['VALUE'][$key] . '_' . $arFields['UF_PROCESSING']['VALUE'][$key] . '_' . $arFields['UF_WIDTH']['VALUE'][$key] . '_' . $arFields['UF_HEIGHT']['VALUE'][$key] . '_' . $arFields['UF_AMOUNT']['VALUE'][$key] . '_' . $arFields['UF_PRICE_DETAIL']['VALUE'][$key]);
                        $uniqueFilePrefix = md5($arFields['UF_MATERIAL']['VALUE'][$key]);

                        if ($key > 0) {
                            $uniqueFilePrefix = $uniqueFilePrefix.$key;
                        }

                        if ($fileDescription[0] == $uniqueFilePrefix) {

                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $taskDescription .= 'Прикрепленные файлы: ' . "$name: $url" . "\n";
                            $taskDescription .= "\n";

                            $materialFileName = $name;
                            $materialFileUrl = $url;

                        }

                    }

                    //Имейдж к материалу
                    $materialImidgName = '';
                    $materialImidgUrl = '';

                    if (!empty($arFields['UF_IMIDG']['VALUE'][$key]) && $arFields['UF_IMIDG']['VALUE'][$key] != 'Имейдж не выбран') {

                        $arOrder = ['NAME' => 'ASC'];
                        $arSelect = [
                            'PREVIEW_PICTURE',
                        ];
                        $arFilter = [
                            'IBLOCK_ID' => $iblockImidg,
                            'ID' => $arFields['UF_IMIDG']['VALUE'][$key],
                        ];

                        $imidgId = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect)->Fetch()['PREVIEW_PICTURE'];

                        if (!empty($imidgId)) {

                            $arFileInfo = \CFile::GetFileArray($imidgId);
                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $imidgFile = "$name: $url" . "\n";

                            $taskDescription .= 'Выбранный имейдж: ' . $imidgFile . "\n";
                            //$taskDescription .= "\n";

                            $materialImidgName = $name;
                            $materialImidgUrl = $url;

                        }

                    }
					
                    //Имейдж к материалу (партнерский)
                    $materialImidgNamePartner = '';
                    $materialImidgUrlPartner = '';

                    if (!empty($arFields['UF_IMIDG_PARTNER']['VALUE'][$key]) && $arFields['UF_IMIDG_PARTNER']['VALUE'][$key] != 'Имейдж не выбран') {

                        $arOrder = ['NAME' => 'ASC'];
                        $arSelect = [
                            'PREVIEW_PICTURE',
                        ];
                        $arFilter = [
                            'IBLOCK_ID' => $iblockImidgPartner,
                            'ID' => $arFields['UF_IMIDG_PARTNER']['VALUE'][$key],
                        ];

                        $imidgIdPartner = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 1], $arSelect)->Fetch()['PREVIEW_PICTURE'];

                        if (!empty($imidgIdPartner)) {

                            $arFileInfo = \CFile::GetFileArray($imidgIdPartner);
                            $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                            $name = $arFileInfo['ORIGINAL_NAME'];

                            $imidgFilePartner = "$name: $url" . "\n";

                            $taskDescription .= 'Выбранный имейдж (партнерский): ' . $imidgFilePartner . "\n";
                            //$taskDescription .= "\n";

                            $materialImidgNamePartner = $name;
                            $materialImidgUrlPartner = $url;

                        }

                    }
					
					//Общее колличество макетов
					if (!empty($arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key])) {
						
						$totalCountLayouts += $arFields['UF_AMOUNT_LAYOUTS']['VALUE'][$key];
						
					} else {

						//По умолчанию 1 макет, чтобы корректно рассчитывались уже существующие заявки
						$totalCountLayouts += 1;
						
					}

                }

                $taskDescription .= "----------\n\n";

                //Комментарий к заявке
                if (!empty($arFields['UF_COMMENT']['VALUE'])) {

                    $taskDescription .= 'Комментарий к заявке: ' . $arFields['UF_COMMENT']['VALUE'] . "\n";
                    $taskDescription .= "\n";

                }

                //Файлы к заявке
                if (!empty($arFields['UF_FILES']['VALUE'])) {

                    $taskDescription .= 'Прикрепленные файлы к заявке: ' . "\n";

                    foreach ($arFields['UF_FILES']['VALUE'] as $key => $value) {

                        $arFileInfo = \CFile::GetFileArray($value);
                        $url = 'http://' . $urlAddressSite . $arFileInfo['SRC'];
                        $name = $arFileInfo['ORIGINAL_NAME'];

                        $taskDescription .= "$name: $url" . "\n";

                    }

                    $taskDescription .= "\n";

                }

            }

            $arTask = [
                'fields' => [
                    'TITLE' => $taskName,
                    'DESCRIPTION' => $taskDescription,
                    'RESPONSIBLE_ID' => $bx24TaskManager,
                    'UF_CRM_TASK' => ['D_' . $arFields['UF_CRM_LEAD_ID']['VALUE'] . ''],
                    'TAGS' => $arFields['UF_PERSONAL_CITY']['VALUE'] . '_' . $arFields['UF_ORDER_NUMBER']['VALUE'],
                    'UF_AUTO_547746847180' => $totalCountLayouts,
                    'UF_AUTO_548692232476' => $layoutPriceDesigner * $totalCountLayouts,
                ],
            ];

            $arData = http_build_query($arTask);

            return self::crmSend($arData, $bx24webhookUrl . 'tasks.task.add');

        }

        return false;

    }

    /**
     * Получает список сотрудников в Битрикс24
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function userEmployeeList()
    {

        $bx24webhookUrl = Option::get('jumpica.order', 'BX24_WEBHOOK_USER');

        if (!empty($bx24webhookUrl)) {

            $arUser = [
                'FILTER' => [
                    'USER_TYPE' => 'employee',
                ],
            ];

            $arData = http_build_query($arUser);

            return self::crmSend($arData, $bx24webhookUrl . 'user.get');

        }

        return false;

    }

    /**
     * Запускает бизнесс процесс утверждения макета в би24
     * @param $dealId
     * @param $userGroup
     * @param $userAction
     * @param $message
     * @param $userName
     * @param $fileUrl
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function bizprocStart($dealId, $userGroup, $userAction, $message, $userName, $fileUrl)
    {

        $bx24webhookBiz = Option::get('jumpica.order', 'BX24_WEBHOOK_BIZ');

        if (!empty($bx24webhookBiz)) {

            //Шаблон бизнесс процесса
            $bizCode = 57;

            $arParams  = [
                'TEMPLATE_ID' => $bizCode,
                'DOCUMENT_ID' => [
                    'crm',
                    'CCrmDocumentDeal',
                    $dealId,
                ],
                'PARAMETERS' => [
                    'group' => $userGroup,
                    'message' => $message,
                    'action' => $userAction,
                    'email' => implode(',', getEmailAdministratorJumpica()),
                    'name' => $userName,
                    'file' => $fileUrl,
                ],
            ];

            $arData = http_build_query($arParams);

            return self::crmSend($arData, $bx24webhookBiz . 'bizproc.workflow.start');

        }

        return false;

    }

    /**
     * Отправляет информацию в CRM
     * @param $arFields
     * @param $webhook
     * @return bool|mixed
     */
    public static function crmSend($arFields, $webhook)
    {

        if (!empty($arFields) && !empty($webhook)) {

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $webhook,
                CURLOPT_POSTFIELDS => $arFields,
            ]);

            $response = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            file_put_contents(getLogPath() . time() . '_curl_log.txt', $webhook . ' === ' . print_r($response, true) . "\n", FILE_APPEND);

            if ($statusCode != 200) {

                return false;

            }

            return json_decode($response, true);

        }

        return false;

    }

}

?>