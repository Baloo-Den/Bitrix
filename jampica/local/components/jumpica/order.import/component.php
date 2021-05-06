<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arFiles = $request->getFileList()->toArray();

$arResult = [];

if (Bitrix\Main\Loader::includeModule('iblock') && Bitrix\Main\Loader::includeModule('jumpica.order')) {

    if (!empty($arParams['IBLOCK_ID_CATALOG']) && !empty($arParams['IBLOCK_ID_MATERIAL']) && !empty($arParams['IBLOCK_ID_PROCESSING']) && !empty($arParams['IBLOCK_ID_POINTCODE']) && !empty($arParams['IBLOCK_ID_DELIVERY'])) {

        $userId = (int)$request->getQuery('user');

        if (empty($userId)) {

            $userId = $USER->GetID();

        }

        //Стоимость верстки материала
        $arResult['LAYOUT_PRICE'] = Bitrix\Main\Config\Option::get('jumpica.order', 'LAYOUT_PRICE');

        //Пользователь
        $arResult['USER'] = Bitrix\Main\UserTable::getList([
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
				'UF_REGION',
            ],
            'filter' => [
                'ACTIVE' => 'Y',
                'ID' => $userId,
            ],
            'limit' => 1,
        ])->fetch();

				$ryg = CUserFieldEnum::GetList(array(), array("ID" =>  $arResult['USER']["UF_REGION"]));
   				if ($arCat = $ryg->GetNext())
					$region=$arCat["VALUE"];//получаем Название региона юзера
 
        //Идентификатор ответственного за сделку менеджера в CRM
        $arResult['USER']['UF_CRM_ASSIGNED_ID'] = Jumpica\Order\OrderFunction::getCrmAssigned($arResult['USER']['PERSONAL_STATE']);

        //Доставка
        $arOrder = ['NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_DIVISION',
            'PROPERTY_REGION',
            'PROPERTY_CITY',
            'PROPERTY_SALE_DO_1KG',
            'PROPERTY_SALE_OT_1KG',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID_DELIVERY'],
            'PROPERTY_DIVISION' => $arResult['USER']['UF_DIVISION'],
            'PROPERTY_REGION' => $arResult['USER']['PERSONAL_STATE'],
            'PROPERTY_CITY' => $arResult['USER']['PERSONAL_CITY'],
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arRes = $res->Fetch()) {

            $arResult['DELIVERY_LIST'] = $arRes;

        }

        //Материалы
        $arOrder = ['NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_SALE',
            'PROPERTY_YARDAGE',
            'PROPERTY_WIDTH_MIN',
            'PROPERTY_WIDTH_MAX',
            'PROPERTY_HEIGHT_MIN',
            'PROPERTY_HEIGHT_MAX',
            'PROPERTY_IMIDG',
            'PROPERTY_OBRABOTKAMATERIALA',
            'PROPERTY_MATERIAL_PVH',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID_MATERIAL'],
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            $arResult['MATERIAL_LIST'][$arRes['ID']] = $arRes;

            //Имиджи
            if (!empty($arRes['PROPERTY_IMIDG_VALUE'])) {

                $arResult['MATERIAL_LIST_IMIDG'][$arRes['ID']][] = $arRes['PROPERTY_IMIDG_VALUE'];
                $arResult['MATERIAL_LIST'][$arRes['ID']]['PROPERTY_IMIDG_VALUE'] = array_values(array_unique($arResult['MATERIAL_LIST_IMIDG'][$arRes['ID']]));

            }

            //Постобработка
            if (!empty($arRes['PROPERTY_OBRABOTKAMATERIALA_VALUE'])) {

                $arResult['MATERIAL_LIST_OBRABOTKAMATERIALA'][$arRes['ID']][] = $arRes['PROPERTY_OBRABOTKAMATERIALA_VALUE'];
                $arResult['MATERIAL_LIST'][$arRes['ID']]['PROPERTY_OBRABOTKAMATERIALA_VALUE'] = array_values(array_unique($arResult['MATERIAL_LIST_OBRABOTKAMATERIALA'][$arRes['ID']]));

            }

        }

        unset($arResult['MATERIAL_LIST_IMIDG']);
        unset($arResult['MATERIAL_LIST_OBRABOTKAMATERIALA']);

        //Имиджи
        $arOrder = ['SORT' => 'ASC', 'NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID_IMIDG'],
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arRes = $res->Fetch()) {

            $arRes['SRC'] = (!empty($arRes['PREVIEW_PICTURE'])) ? CFile::GetFileArray($arRes['PREVIEW_PICTURE'])['SRC'] : $componentPath . '/images/nophoto.jpg';

            $arResult['IMIDG_LIST'][] = $arRes;

        }

        //Имиджи (партнерские)
        $arOrder = ['SORT' => 'ASC', 'NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID_IMIDG_PARTNER'],
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arRes = $res->Fetch()) {

            $arRes['SRC'] = (!empty($arRes['PREVIEW_PICTURE'])) ? CFile::GetFileArray($arRes['PREVIEW_PICTURE'])['SRC'] : $componentPath . '/images/nophoto.jpg';

            $arResult['IMIDG_PARTNER_LIST'][] = $arRes;

        }

        //Список супервайзеров пользователя
        $arResult['SUPERVISOR_LIST'] = getUserManagerInfo($userId);
		
		//Выбираем цену в зависимости от региона
		if ($region=='Москва')
			$price='PROPERTY_cena';
		if ($region=='Восток')
			$price= 'PROPERTY_PRICE_EAST'; 
		if ($region=='Запад')
			$price='PROPERTY_PRICE_WEST';
		if ($region=='Центр')
			$price= 'PROPERTY_PRICE_CENTER'; 		
		if ($region=='Юг')
			$price='PROPERTY_PRICE_SOUTH';
		
        //Вид обработки
        $arOrder = ['NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'DETAIL_TEXT',
            $price,
            'PROPERTY_UF_FORMULA_CALC',
            'PROPERTY_UF_FORMULA_STEP',
            'PROPERTY_UF_FORMULA_DESC',
            'PROPERTY_UF_YARDAGE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['IBLOCK_ID_PROCESSING'],
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arRes = $res->Fetch()) {
			//dump($arRes); echo '<br>';
			if ($region!='Москва')
			{
				if ($region=='Центр')
				{
					$arRes["PROPERTY_CENA_VALUE"]=$arRes["PROPERTY_PRICE_CENTER_VALUE"];
					unset($arRes["PROPERTY_PRICE_CENTER_VALUE"]);
				}
				if ($region=='Восток')
				{
					$arRes["PROPERTY_CENA_VALUE"]=$arRes["PROPERTY_PRICE_EAST_VALUE"];
					unset($arRes["PROPERTY_PRICE_EAST_VALUE"]);
				}	
				if ($region=='Запад')
				{
					$arRes["PROPERTY_CENA_VALUE"]=$arRes["PROPERTY_PRICE_WEST_VALUE"];
					unset($arRes["PROPERTY_PRICE_WEST_VALUE"]);
				}	
				if ($region=='Юг')
				{
					$arRes["PROPERTY_CENA_VALUE"]=$arRes["PROPERTY_PRICE_SOUTH_VALUE"];
					unset($arRes["PROPERTY_PRICE_SOUTH_VALUE"]);
				}					
					
			}
				
            $arResult['PROCESSING_LIST'][] = $arRes;
			
			//$arResult['PROCESSING_LIST']['PROPERTY_cena_VALUE'][]=$arRes["PROPERTY_PRICE_CENTER_VALUE"];
			//dump($arRes); echo '<br>';

        }
			//echo $region;
		//echo $arResult['PROCESSING_LIST']["PROPERTY_PRICE_CENTER_VALUE"];
		//dump($arResult['PROCESSING_LIST']); exit;
		
		
        //Код точки
        $arOrder = ['NAME' => 'ASC'];
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_REGION_POINT',
            'PROPERTY_CITY_POINT',
            'PROPERTY_ADDRESS_POINT',
            'PROPERTY_DIVISION',
            'PROPERTY_REGION_POINT',
            'PROPERTY_CITY_POINT',
            'PROPERTY_ADDRESS_POINT',
            'PROPERTY_KOD_SV',
            'PROPERTY_KOD_TP',
            'PROPERTY_KOD_IV',
        ];

        //Идентификатры закрепленных регионов - Супервайзер-специалист
        $arUserRegionID = [];
        $arUserRegionValue = [];

        if (!empty($arResult['USER']['PERSONAL_STATE'])) {

            $arUserRegionValue[] = $arResult['USER']['PERSONAL_STATE'];

        }

        /*
        $arUserRegionID = CUser::GetList($by = 'ID', $order = 'ASC',
            [
                'ID' => $USER->GetID(),
            ],
            [
                'SELECT' => [
                    'UF_REGION',
                ],
            ]
        )->Fetch()['UF_REGION'];

        if (!empty($arUserRegionID)) {

            //Название закрепленных регионов
            $resPropFields = CUserFieldEnum::GetList([], ['USER_FIELD_NAME' => 'UF_REGION', 'ID' => $arUserRegionID]);

            while ($arPropFields = $resPropFields->GetNext()) {

                $arUserRegionValue[] = $arPropFields['VALUE'];

            }

        }
        */

        //Видит все коды точек своего региона - Супервайзер-специалист
        if ($_SESSION['MATRIX']['ROLE']['SEE_POINTS_ALL'] == 'Y' && !empty($arUserRegionValue)) {

            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arParams['IBLOCK_ID_POINTCODE'],
                [
                    'LOGIC' => 'OR',
                    'PROPERTY_REGION_POINT' => $arUserRegionValue,
                    'CREATED_BY' => $userId,
                    'NAME' => 'Нет кода точки',
                ],
            ];

        } else {

            $arFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arParams['IBLOCK_ID_POINTCODE'],
                [
                    'LOGIC' => 'OR',
                    'CREATED_BY' => ($_SESSION['MATRIX']['ROLE']['SEE_POINTS_TP'] == 'Y') ? $_SESSION['MATRIX']['REFERRAL'] : $userId,
                    'NAME' => 'Нет кода точки',
                ],
            ];

        }

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arRes = $res->Fetch()) {

            $arResult['POINTCODE_LIST'][] = $arRes;

        }

        //Материалы в корзине
        $arResult['USER_BASKET'] = $_SESSION['USER_BASKET'];

        //Редактирование, копирование заявок
        $orderId = (int)$request->getQuery('id');

        if (!empty($orderId)) {

            $arResult['USER_ORDER'] = Jumpica\Order\OrderFunction::getOrderFields($orderId);

        }

        //Создание заявки из файла
        if (!empty($arFiles)) {

            $arResult['USER_ORDER']['ID'] = md5(time());

            //Определяем тип файла
            $finfoOpen = finfo_open(FILEINFO_MIME, false);
            $finfoFile = finfo_file($finfoOpen, $arFiles['files']['tmp_name']);
            $arMimeTypeFile = explode(';', $finfoFile);

            if ($arFiles['files']['error'] == 0 && $arMimeTypeFile[0] == 'text/plain') {

                //Обрабатываем прикрепленный файл
                $handle = fopen($arFiles['files']['tmp_name'], 'r');

                $count = 0;

                while (($str = fgets($handle)) !== false) {

                    $count++;

                    if ($count == 1) {

                        continue;

                    }

                    //Перекодируем в UTF-8
                    $str = mb_convert_encoding($str, 'UTF-8', 'windows-1251');

                    //Разбиваем строку в массив
                    $item = explode(';', $str);

                    if (!empty($item)) {

                        $strMaterial = trim(strip_tags(str_replace('  ', ' ', $item[14])));
                        $strWidth = trim(strip_tags(str_replace('  ', ' ', $item[8])));
                        $strHeight = trim(strip_tags(str_replace('  ', ' ', $item[9])));
                        $strAmount = trim(strip_tags(str_replace('  ', ' ', $item[0])));
                        $strPoint = trim(strip_tags(str_replace('  ', ' ', $item[2])));

                        //Материал
                        if (!empty($strMaterial)) {

                            $arResult['USER_ORDER']['UF_MATERIAL']['VALUE'][] = $strMaterial;

                        } else {

                            $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указан материал';

                        }

                        //Ширина
                        if (!empty($strWidth)) {

                            if (!is_numeric($strWidth)) {

                                $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указана ширина';

                            } else {

                                $arResult['USER_ORDER']['UF_WIDTH']['VALUE'][] = $strWidth;

                            }

                        } else {

                            $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указана ширина';

                        }

                        //Высота
                        if (!empty($strHeight)) {

                            if (!is_numeric($strHeight)) {

                                $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указана высота';

                            } else {

                                $arResult['USER_ORDER']['UF_HEIGHT']['VALUE'][] = $strHeight;

                            }

                        } else {

                            $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указана высота';

                        }

                        //Количество
                        if (!empty($strAmount)) {

                            if (!is_numeric($strAmount)) {

                                $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указано количество, вводите только цифры';

                            } else {

                                $arResult['USER_ORDER']['UF_AMOUNT']['VALUE'][] = $strAmount;

                            }

                        } else {

                            $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указано количество';

                        }

                        //Код точки
                        if (!empty($strPoint)) {

                            $arResult['USER_ORDER']['UF_POINT_CODE']['VALUE'][] = $strPoint;

                        } else {

                            $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count . ' - не указан код точки';

                        }

                    } else {

                        $arResult['ERROR_MESSAGE'][$count][] = 'Ошибка в строке №' . $count;

                    }

                }

                fclose($handle);

                //Проверяем
                if ($count <= 1) {

                    $arResult['ERROR_MESSAGE'] = 'Не верный формат файла или пустой файл';

                }

            } else {

                $arResult['ERROR_MESSAGE'] = 'Не верный формат файла или пустой файл';

            }

        }

    } else {

        $arResult['ERROR_MESSAGE'] = 'Выполните настройку компонента';

    }

} else {

    $arResult['ERROR_MESSAGE'] = 'Модуль iblock не подключен';

}

//Подключаем шаблон
$this->IncludeComponentTemplate();

?>