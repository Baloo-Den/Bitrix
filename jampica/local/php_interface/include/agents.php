<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Jumpica\Order\RestFunction;
use Jumpica\Order\OrderFunction;

function orderCrmSend()
{

    //Подключаем модуль
    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdCatalogCrm = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG_CRM');

        $arResult = [];

        $arOrder = ['PROPERTY_UF_DATE_CREATED' => 'ASC'];
        $arSelect = [
            '*',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdCatalogCrm,
            'PROPERTY_UF_ORDER_TYPE' => 54, //Объединение сделок, работаем только с одним типом заявки (товар)
        ];

        $arFilter[] = [
            'LOGIC' => 'OR',
            'PROPERTY_UF_CRM_LEAD_ID' => false, //ID лида (crm)
            'PROPERTY_UF_CRM_PRODUCT_ID' => false, //ID товаров (crm)
            'PROPERTY_UF_CRM_DATE_CREATED' => false, //Дата добавления в CRM
            'PROPERTY_UF_CRM_DATE_TASK' => false, //Дата добавления задач в CRM
        ];

        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 5], $arSelect);
        while ($arRes = $res->GetNextElement()) {

            $arFields = $arRes->GetFields();
            $arProperties = $arRes->GetProperties();

            $arResult[] = array_merge($arFields, $arProperties);

        }

        if (!empty($arResult)) {

            foreach ($arResult as $key => $value) {

                //ID лида (crm)
                if (empty($value['UF_CRM_LEAD_ID']['VALUE']) && empty($value['UF_CRM_PRODUCT_ID']['VALUE']) && empty($value['UF_CRM_DATE_CREATED']['VALUE'])) {

                    if (!empty($value['UF_SENDING_CRM']['VALUE'])) {

                        if ($value['UF_SENDING_CRM']['VALUE'] > time()) {

                            continue;

                        }

                    }

                    //Отправка данных в CRM
                    CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_SENDING_CRM', time() + 180);

                    //Добавляем лид
                    $arLead = RestFunction::crmLeadAdd($value);

                    //Логирование
                    file_put_contents(getLogPath() . time() . '_add_lead.txt', ' <=== ' . print_r($value, true) . ' === ' . print_r($arLead, true) . ' ===> ' . "\n", FILE_APPEND);

                    if (!empty($arLead['result'])) {

                        //Сохраняем ID лида
                        CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_CRM_LEAD_ID', $arLead['result']); //ID лида (crm)

                    }

                }

                //ID товаров (crm)
                if (!empty($value['UF_CRM_LEAD_ID']['VALUE']) && empty($value['UF_CRM_PRODUCT_ID']['VALUE']) && empty($value['UF_CRM_DATE_CREATED']['VALUE'])) {

                    //Добавляем товары
                    $arProduct = RestFunction::crmProductAdd($value);

                    //Логирование
					file_put_contents(getLogPath() . time() . '_add_product.txt', ' <=== ' . print_r($value, true) . ' === ' . print_r($arProduct, true) . ' ===> ' . "\n", FILE_APPEND);

                    if (!empty($arProduct['result']['result'])) {

                        //Сохраняем ID товаров
                        CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_CRM_PRODUCT_ID', $arProduct['result']['result']); //ID товаров (crm)

                    }

                }

                //Дата добавления в CRM
                if (!empty($value['UF_CRM_LEAD_ID']['VALUE']) && !empty($value['UF_CRM_PRODUCT_ID']['VALUE']) && empty($value['UF_CRM_DATE_CREATED']['VALUE'])) {

                    //Добавляем товары к сделке
                    $arLeadProduct = RestFunction::crmLeadProductrowsSet($value);

                    //Логирование
					file_put_contents(getLogPath() . time() . '_add_lead_product.txt', ' <=== ' . print_r($value, true) . ' === ' . print_r($arLeadProduct, true) . ' ===> ' . "\n", FILE_APPEND);

                    if (!empty($arLeadProduct['result'])) {

                        //Дата добавления в CRM
                        CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_CRM_DATE_CREATED', ConvertTimeStamp(time(), 'FULL')); //Дата добавления в CRM

                    }

                }

                //Создание задач
                if (!empty($value['UF_CRM_LEAD_ID']['VALUE']) && !empty($value['UF_CRM_PRODUCT_ID']['VALUE']) && !empty($value['UF_CRM_DATE_CREATED']['VALUE']) && empty($value['UF_CRM_DATE_TASK']['VALUE'])) {

                    //Не создаем задачи для доставки и верстки
                    if ($value['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'delivery' || $value['UF_ORDER_TYPE']['VALUE_XML_ID'] == 'processing') {

                        //Дата добавления задач в CRM
                        CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_CRM_DATE_TASK', ConvertTimeStamp(time(), 'FULL')); //Дата добавления задач в CRM

                    } else {

                        //Создаем задачи
                        $arTask = RestFunction::tasksTaskAdd($value);

                        //Логирование
						file_put_contents(getLogPath() . time() . '_add_task.txt', ' <=== ' . print_r($value, true) . ' === ' . print_r($arTask, true) . ' ===> ' . "\n", FILE_APPEND);

                        if (!empty($arTask['result'])) {

                            //Дата добавления задач в CRM
                            CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_CRM_DATE_TASK', ConvertTimeStamp(time(), 'FULL')); //Дата добавления задач в CRM

                        }

                    }

                }

                //Обновляем дату добавления на сайте
                CIBlockElement::SetPropertyValueCode($value['ID'], 'UF_DATE_CREATED', ConvertTimeStamp(time(), 'FULL'));

            }

        } else {

            //echo 'N';

        }

    }

    //Возвращаем вызов агента
    return 'orderCrmSend();';

}

?>
