<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?php

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Loader;
use Jumpica\Order\OrderFunction;
use Jumpica\Order\RestFunction;

$request = Application::getInstance()->getContext()->getRequest();
$arFiles = $request->getFileList()->toArray();

$arResult = [];


if ($request->isPost() && Loader::includeModule('iblock') && Loader::includeModule('jumpica.order') && $USER->IsAuthorized()) {

    $arResult = [];

    $userId = trim(strip_tags($request->getPost('CREATED_BY')));

    if (empty($userId)) {

        $userId = $USER->GetID();
    }

    $type = trim(strip_tags($request->getPost('TYPE')));
    $iblock = trim(strip_tags($request->getPost('IBLOCK')));
    $element = trim(strip_tags($request->getPost('ELEMENT')));

    $personalState = trim(strip_tags($request->getPost('PERSONAL_STATE')));
    $personalCity = trim(strip_tags($request->getPost('PERSONAL_CITY')));
    $personalStreet = trim(strip_tags($request->getPost('PERSONAL_STREET')));
    $devision = trim(strip_tags($request->getPost('DIVISION')));
    $name = trim(strip_tags($request->getPost('NAME')));
    $secondName = trim(strip_tags($request->getPost('SECOND_NAME')));
    $lastName = trim(strip_tags($request->getPost('LAST_NAME')));
    $phone = trim(strip_tags($request->getPost('PHONE')));
    $email = trim(strip_tags($request->getPost('EMAIL')));
    $notification = trim(strip_tags($request->getPost('NOTIFICATION')));
    $price = trim(strip_tags($request->getPost('PRICE')));
    $deliveryPrice = trim(strip_tags($request->getPost('DELIVERY_PRICE')));
    $deliveryPlace = trim(strip_tags($request->getPost('DELIVERY_PLACE')));
    $deliveryType = trim(strip_tags($request->getPost('DELIVERY_TYPE')));
    $message = trim(strip_tags($request->getPost('MESSAGE')));

    $materialVolume = trim(strip_tags($request->getPost('MATERIAL_VOLUME')));
    $materialWeight = trim(strip_tags($request->getPost('MATERIAL_WEIGHT')));
    $volumeWeight = trim(strip_tags($request->getPost('VOLUME_WEIGHT')));

    $material = $request->getPost('MATERIAL');
    $processing = $request->getPost('PROCESSING');
    $pointcode = $request->getPost('POINTCODE');
    $height = $request->getPost('HEIGHT');
    $width = $request->getPost('WIDTH');
    $count = $request->getPost('COUNT');
    $countLayouts = $request->getPost('COUNT_LAYOUTS');
    $priceDetail = $request->getPost('PRICE_DETAIL');
    $processingPriceDetail = $request->getPost('PROCESSING_DETAIL');
    $materialPvh = $request->getPost('PVH_DETAIL');
    $materialMarkirovka = $request->getPost('MATERIAL_MARKIROVKA');

    $dopUser = trim(strip_tags($request->getPost('DOP_USER')));
    $dopPhone = trim(strip_tags($request->getPost('DOP_PHONE')));
    $arFilesDelete = $request->getPost('FILE_DELETE');
    $arFilesDeleteMaterial = $request->getPost('FILE_DELETE_MATERIAL');

    //CRM
    $crmCompanyId = trim(strip_tags($request->getPost('CRM_COMPANY_ID')));
    $crmContactId = trim(strip_tags($request->getPost('CRM_CONTACT_ID')));
    $crmAssignedId = trim(strip_tags($request->getPost('CRM_ASSIGNED_ID')));

    //Данные супервайзера
    $supervisorSurName = trim(strip_tags($request->getPost('SUPERVISOR_SURNAME')));
    $supervisorName = trim(strip_tags($request->getPost('SUPERVISOR_NAME')));
    $supervisorLastName = trim(strip_tags($request->getPost('SUPERVISOR_LASTNAME')));
    $supervisorEmail = trim(strip_tags($request->getPost('SUPERVISOR_EMAIL')));
    $supervisorPhone = trim(strip_tags($request->getPost('SUPERVISOR_PHONE')));
    $supervisorUserId = trim(strip_tags($request->getPost('SUPERVISOR_USER_ID')));
    $supervisorContactId = trim(strip_tags($request->getPost('SUPERVISOR_CONTACT_ID')));
	
	//Логирование
	file_put_contents(getLogPath() . time() . '_order_add.txt', print_r($_POST, true) . print_r($_FILES, true) . $USER->GetID() . "\n", FILE_APPEND);

    if (empty($devision) || empty($personalState) || empty($personalCity) || empty($personalStreet)) {

        $arResult['errors'][] = 'Не указан адрес доставки, заполните профиль (дивизион, регион, город, улица)';

    } elseif ($devision == 'non' || $personalState == 'non' || $personalCity == 'non') {

        $arResult['errors'][] = 'Не указан адрес доставки, заполните профиль (дивизион, регион, город)';

    }

    if (empty($name) || empty($secondName) || empty($lastName)) {

        $arResult['errors'][] = 'Не указано контактное лицо, заполните профиль (имя, фамилия, отчество)';
    }

    if (empty($phone)) {

        $arResult['errors'][] = 'Не указан контактный номер телефона, заполните профиль';
    }

    //Проверка ФИО
    if ((strpos($name, '@') !== false) || (strpos($secondName, '@') !== false) || (strpos($lastName, '@') !== false)) {

        $arResult['errors'][] = 'Присутствуют недопустимые символы, заполните профиль (имя, фамилия, отчество)';

    }

    //Материал
    if (empty($material)) {

        //$arResult['errors'][] = 'Выберите материал';

    } elseif (is_array($material)) {

        foreach ($material as $key => $value) {

            if (empty($value)) {

                //$arResult['errors'][] = 'Не выбран материал';

            }

            //Комментарии для материалов
            $materialMessage = trim(strip_tags($request->getPost('MATERIAL_COMMENT_' . $key)));

            if (!empty($materialMessage)) {

                $arMaterialMessage[] = $materialMessage;

            } else {

                $arMaterialMessage[] = 'Нет комментария';

            }

            //Выбранный имейдж
            $materialImidg = trim(strip_tags($request->getPost('IMIDG_' . $key)));

            if (!empty($materialImidg)) {

                $arMaterialImidg[] = $materialImidg;

            } else {

                $arMaterialImidg[] = 'Имейдж не выбран';

            }

            //Выбранный имейдж (партнерский)
            $materialImidgPartner = trim(strip_tags($request->getPost('IMIDG_PARTNER_' . $key)));

            if (!empty($materialImidgPartner)) {

                $arMaterialImidgPartner[] = $materialImidgPartner;

            } else {

                $arMaterialImidgPartner[] = 'Имейдж не выбран';

            }

        }

    }

    //Множественная обработка
    $processing = [];

    foreach ($material as $key => $value) {

        $processing[] = implode('###', $request->getPost('PROCESSING_' . $key));

    }

    //Способ обработки
   /* if (empty($processing)) {

        $arResult['errors'][] = 'Выберите способ обработки';

    } elseif (is_array($processing)) {

        foreach ($processing as $key => $value) {

            if (empty($value)) {

                $arResult['errors'][] = 'Не выбран способ обработки';

            }

        }

    }*/

    //Код точки
   /* if (empty($pointcode)) {

        $arResult['errors'][] = 'Выберите код точки';

    } elseif (is_array($pointcode)) {

        foreach ($pointcode as $key => $value) {

            if (empty($value)) {

                $arResult['errors'][] = 'Не выбран код точки';

            }

        }

    }*/

    //Высота
    /*if (empty($height)) {

        $arResult['errors'][] = 'Не указана высота';

    } elseif (is_array($height)) {

        foreach ($height as $key => $value) {

            if (!is_numeric($value)) {

                $arResult['errors'][] = 'Не указана высота';

            }

        }

    }

    //Ширина
    if (empty($width)) {

        $arResult['errors'][] = 'Не указана ширина';

    } elseif (is_array($width)) {

        foreach ($width as $key => $value) {

            if (!is_numeric($value)) {

                $arResult['errors'][] = 'Не указана ширина';

            }

        }

    }*/

    //Количество изделий
   /* if (empty($count)) {

        $arResult['errors'][] = 'Введите количество изделий, шт';

    } elseif (is_array($count)) {

        foreach ($count as $key => $value) {

            if (!is_numeric($value)) {

                $arResult['errors'][] = 'Введите количество изделий, шт';

            }

        }

    }

    //Количество макетов
    if (empty($countLayouts)) {

        $arResult['errors'][] = 'Введите количество макетов, шт';

    } elseif (is_array($countLayouts)) {

        foreach ($countLayouts as $key => $value) {

            if (!is_numeric($value)) {

                $arResult['errors'][] = 'Введите количество макетов, шт';

            }

        }

    }

    //Маркировка
    if (empty($materialMarkirovka)) {
		
		$arResult['errors'][] = 'Введите маркировку';

    } elseif (is_array($materialMarkirovka)) {

        foreach ($materialMarkirovka as $key => $value) {

            if (empty($value)) {
				
				$arResult['errors'][] = 'Введите маркировку';

            }

        }

    }

    //Проверяем прикрепленные к материалам файлы (при создание новой заявки)
    if ($type == 'add') {

        //Список материалов
        foreach ($material as $mKey => $mValue) {

            //Список прикрепленных к материалам файлов
            if ($arFiles["MATERIAL_FILES_$mKey"]['error'][0] != 0) {

                $materialNumber = $mKey + 1;
                $arResult['errors'][] = 'Добавьте файл к материалу №' . $materialNumber;

            }

        }

    }
*/
    if (empty($arResult['errors'])) {

        //Удаляем старые прикрепленные файлы к заявке (общие файлы для заявки)
        if (!empty($arFilesDelete)) {

            $resProperty = CIBlockElement::GetProperty($iblock, $element, ['ID' => 'ASC'], ['ID' => 100]);
            while ($arResProperty = $resProperty->Fetch()) {

                if (in_array($arResProperty['VALUE'], $arFilesDelete)) {

                    //Удаляем запись
                    $arMakeFile[$arResProperty['PROPERTY_VALUE_ID']] = ['VALUE' => ['del' => 'Y']];

                    //Удаляем файл
                    CFile::Delete($arResProperty['VALUE']);

                }

            }

        }

        //Добавляем новые прикрепленные файлы к заявке (общие файлы для заявки)
        if (!empty($arFiles)) {

            foreach ($arFiles['files']['tmp_name'] as $key => $value) {

                if (!empty($value) && $arFiles['files']['error'][$key] == 0) {

                    $arFileInfo = [];
                    $arFileInfo = CFile::MakeFileArray($value);
                    $arFileInfo['name'] = $arFiles['files']['name'][$key];

                    $arMakeFile[] = [
                        'VALUE' => $arFileInfo,
                        'DESCRIPTION' => $arFiles['files']['name'][$key],
                    ];

                }

            }

        }

        //Удаляем старые прикрепленные файлы к материалам (файлы для конкретного материала)
        if (!empty($arFilesDeleteMaterial)) {

            $resProperty = CIBlockElement::GetProperty($iblock, $element, ['ID' => 'ASC'], ['ID' => 219]);
            while ($arResProperty = $resProperty->Fetch()) {

                if (in_array($arResProperty['VALUE'], $arFilesDeleteMaterial)) {

                    //Удаляем запись
                    $arMaterialMakeFile[$arResProperty['PROPERTY_VALUE_ID']] = ['VALUE' => ['del' => 'Y']];

                    //Удаляем файл
                    CFile::Delete($arResProperty['VALUE']);

                }

            }

        }

        //Добавляем новые прикрепленные файлы к материалам (файлы для конкретного материала)		
        if (!empty($arFiles)) {

            foreach ($material as $mKey => $mValue) {

                foreach ($arFiles["MATERIAL_FILES_$mKey"]['tmp_name'] as $key => $value) {

                    if (!empty($value) && $arFiles["MATERIAL_FILES_$mKey"]['error'][$key] == 0) {

                        $arMaterialFileInfo = [];
                        $arMaterialFileInfo = CFile::MakeFileArray($value);
                        $arMaterialFileInfo['name'] = $arFiles["MATERIAL_FILES_$mKey"]['name'][$key];

                        //Уникальный префикс файла
                        //$uniqueFilePrefix = md5($material[$mKey] . '_' . $processing[$mKey] . '_' . $width[$mKey] . '_' . $height[$mKey] . '_' . $count[$mKey] . '_' . $priceDetail[$mKey]);
                        $uniqueFilePrefix = md5($material[$mKey]);

                        if ($mKey > 0) {
                            $uniqueFilePrefix = $uniqueFilePrefix.$mKey;
                        }

                        $arMaterialMakeFile[] = [
                            'VALUE' => $arMaterialFileInfo,
                            'DESCRIPTION' => $uniqueFilePrefix . '###' . $arFiles["MATERIAL_FILES_$mKey"]['name'][$key],
                        ];

                    }

                }

            }

        }

        //Получаем актуальный номер завки
        $orderNumber = OrderFunction::getOrderNumber();

        if (is_numeric($orderNumber)) {

            $orderNumber++;

        } else {

            $orderNumber = 'ERROR';

        }

        if ($type == 'edit') {

            $orderNumber = OrderFunction::getOrderNumber($element);

            //Редактируем заявку
            CIBlockElement::SetPropertyValueCode($element, 86, $lastName); //Фамилия
            CIBlockElement::SetPropertyValueCode($element, 87, $name); //Имя
            CIBlockElement::SetPropertyValueCode($element, 88, $secondName); //Отчество
            CIBlockElement::SetPropertyValueCode($element, 89, $phone); //Телефон
            CIBlockElement::SetPropertyValueCode($element, 90, $email); //Email
            CIBlockElement::SetPropertyValueCode($element, 113, $devision); //Девизион
            CIBlockElement::SetPropertyValueCode($element, 91, $personalState); //Область
            CIBlockElement::SetPropertyValueCode($element, 92, $personalCity); //Город
            CIBlockElement::SetPropertyValueCode($element, 93, $personalStreet); //Улица
            CIBlockElement::SetPropertyValueCode($element, 94, $processing); //Способ обработки
            CIBlockElement::SetPropertyValueCode($element, 95, $material); //Материал
            CIBlockElement::SetPropertyValueCode($element, 105, $pointcode); //Код точки
            CIBlockElement::SetPropertyValueCode($element, 96, $width); //Ширина
            CIBlockElement::SetPropertyValueCode($element, 97, $height); //Высота
            CIBlockElement::SetPropertyValueCode($element, 98, $count); //Количество изделий
            CIBlockElement::SetPropertyValueCode($element, 281, $countLayouts); //Количество макетов
            CIBlockElement::SetPropertyValueCode($element, 120, $priceDetail); //Стоимость материала за 1 шт.
            CIBlockElement::SetPropertyValueCode($element, 99, $message); //Комментарий к заявке
            CIBlockElement::SetPropertyValueCode($element, 100, $arMakeFile); //Прикрепленные файлы
            CIBlockElement::SetPropertyValueCode($element, 101, $notification); //Оповещение о смене статуса
            CIBlockElement::SetPropertyValueCode($element, 102, $price); //Стоимость
            CIBlockElement::SetPropertyValueCode($element, 131, $deliveryPrice); //Стоимость доставки
            CIBlockElement::SetPropertyValueCode($element, 132, $deliveryPlace); //Количество грузомест
            CIBlockElement::SetPropertyValueCode($element, 133, $deliveryType); //Способ отправки
            CIBlockElement::SetPropertyValueCode($element, 108, $dopPhone); //Телефон (доп.)
            CIBlockElement::SetPropertyValueCode($element, 109, $dopUser); //Контактное лицо (доп.)
            CIBlockElement::SetPropertyValueCode($element, 121, $personalCity . '_' . $orderNumber); //Номер заказа
            CIBlockElement::SetPropertyValueCode($element, 218, $arMaterialMessage); //Комментарий (материал)
            CIBlockElement::SetPropertyValueCode($element, 219, $arMaterialMakeFile); //Прикрепленные файлы (материал)
            CIBlockElement::SetPropertyValueCode($element, 224, $materialVolume); //Объем (общая площадь)
            CIBlockElement::SetPropertyValueCode($element, 225, $materialWeight); //Вес (общий вес)
            CIBlockElement::SetPropertyValueCode($element, 226, $volumeWeight); //Объемный вес
            CIBlockElement::SetPropertyValueCode($element, 252, $arMaterialImidg); //Имейдж
            CIBlockElement::SetPropertyValueCode($element, 283, $arMaterialImidgPartner); //Имейдж (партнерский)
            CIBlockElement::SetPropertyValueCode($element, 259, $processingPriceDetail); //Стоимость множественной обработки материала
            CIBlockElement::SetPropertyValueCode($element, 258, $materialPvh); //ПВХ материал
            CIBlockElement::SetPropertyValueCode($element, 264, $crmAssignedId); //Идентификатор ответственного за сделку менеджера в CRM
            CIBlockElement::SetPropertyValueCode($element, 266, $materialMarkirovka); //Маркировка для доставки
            CIBlockElement::SetPropertyValueCode($element, 286, $supervisorName); //Имя супервайзера
            CIBlockElement::SetPropertyValueCode($element, 287, $supervisorSurName); //Фамилия супервайзера
            CIBlockElement::SetPropertyValueCode($element, 288, $supervisorLastName); //Отчество супервайзера
            CIBlockElement::SetPropertyValueCode($element, 289, $supervisorEmail); //Email супервайзера
            CIBlockElement::SetPropertyValueCode($element, 290, $supervisorPhone); //Телефон супервайзера
            CIBlockElement::SetPropertyValueCode($element, 291, $supervisorUserId); //ID супервайзера
            CIBlockElement::SetPropertyValueCode($element, 292, $supervisorContactId); //ID супервайзера (crm)

            //Возвращаем статус заявки 'Незавершенные' после редактирования
            CIBlockElement::SetPropertyValueCode($element, 84, 51);

            $arResult['success'] = $orderNumber;

        } else {

            //Добавляем новую заявку
            $newElement = new CIBlockElement;

            $PROP = [];
            $PROP[84] = 51; //Статус заявки
            $PROP[86] = $lastName; //Фамилия
            $PROP[87] = $name; //Имя
            $PROP[88] = $secondName; //Отчество
            $PROP[89] = $phone; //Телефон
            $PROP[90] = $email; //Email
            $PROP[113] = $devision; //Девизион
            $PROP[91] = $personalState; //Область
            $PROP[92] = $personalCity; //Город
            $PROP[93] = $personalStreet; //Улица
            $PROP[94] = $processing; //Способ обработки
            $PROP[95] = $material; //Материал
            $PROP[105] = $pointcode; //Код точки
            $PROP[96] = $width; //Ширина
            $PROP[97] = $height; //Высота
            $PROP[98] = $count; //Количество изделий
            $PROP[281] = $countLayouts; //Количество макетов
            $PROP[120] = $priceDetail; //Стоимость материала за 1 шт.
            $PROP[99] = $message; //Комментарий к заявке
            $PROP[100] = $arMakeFile; //Прикрепленные файлы
            $PROP[101] = $notification; //Оповещение о смене статуса
            $PROP[102] = $price; //Стоимость
            $PROP[131] = $deliveryPrice; //Стоимость доставки
            $PROP[132] = $deliveryPlace; //Количество грузомест
            $PROP[133] = $deliveryType; //Способ отправки
            $PROP[108] = $dopPhone; //Телефон (доп.)
            $PROP[109] = $dopUser; //Контактное лицо (доп.)
            $PROP[114] = $crmCompanyId; //ID компании (crm)
            $PROP[115] = $crmContactId; //ID контакта (crm)
            $PROP[218] = $arMaterialMessage; //Комментарий (материал)
            $PROP[219] = $arMaterialMakeFile; //Прикрепленные файлы (материал)
            $PROP[224] = $materialVolume; //Объем (общая площадь)
            $PROP[225] = $materialWeight; //Вес (общий вес)
            $PROP[226] = $volumeWeight; //Объемный вес
            $PROP[252] = $arMaterialImidg; //Имейдж
            $PROP[283] = $arMaterialImidgPartner; //Имейдж (партнерский)
            $PROP[259] = $processingPriceDetail; //Стоимость множественной обработки материала
            $PROP[258] = $materialPvh; //ПВХ материал
            $PROP[264] = $crmAssignedId; //Идентификатор ответственного за сделку менеджера в CRM
            $PROP[266] = $materialMarkirovka; //Маркировка для доставки
            $PROP[272] = $orderNumber; //Номер заявки
            $PROP[286] = $supervisorName; //Имя супервайзера
            $PROP[287] = $supervisorSurName; //Фамилия супервайзера
            $PROP[288] = $supervisorLastName; //Отчество супервайзера
            $PROP[289] = $supervisorEmail; //Email супервайзера
            $PROP[290] = $supervisorPhone; //Телефон супервайзера
            $PROP[291] = $supervisorUserId; //ID супервайзера
            $PROP[292] = $supervisorContactId; //ID супервайзера (crm)

            $arFields = [
                'CREATED_BY' => $userId,
                'MODIFIED_BY' => $userId,
                'IBLOCK_ID' => $iblock,
                'NAME' => 'Заявка от ' . $name . ' ' . $lastName . ' ' . $secondName,
                'PROPERTY_VALUES' => $PROP,
            ];

            $newElementId = $newElement->Add($arFields);

            //Проверяем
            if (empty($newElementId)) {

                $arResult['errors'][] = $newElement->LAST_ERROR;

            } else {

                CIBlockElement::SetPropertyValueCode($newElementId, 121, $personalCity . '_' . $orderNumber); //Номер заказа
                $arResult['success'] = $orderNumber;
				$MyarFields['ORDER_ID']=$orderNumber;
				$MyarFields['ID']=$newElementId;
				$MyarFields['PRICE']= $price; //Стоимость
				$MyarFields['FULL_NAME']=$lastName.' '.$name.' '.$secondName;
				$MyarFields['TELE']=$phone; //Телефон
				$MyarFields['MAIL']=$email; //Email
				$MyarFields['CITY']=$personalState.', '.$personalCity; //Область, Город
				$MyarFields['STREET']=$personalStreet; //Улица
				$MyarFields['PROCESSING']=$processing; //Способ обработки
				$MyarFields['MATERIAL']= $material; //Материал
				$MyarFields['WIDTH']=$width; //Ширина
				$MyarFields['HIGH']=$height; //Высота
				$MyarFields['COUNT']=$count; //Количество изделий
				$MyarFields['COUNT_LAY']=$countLayouts; //Количество макетов
				$MyarFields['COMMENT']=$arMaterialMessage; //Комментарий к заявке	
				//$MyarFields['MATERIAL_FILES']=$arMaterialFileInfo['name']; //Прикрепленные файлы  arMaterialFileInfo['name']
				$MyarFields['MATERIAL_IMG']=$arMaterialImidg; //Имейдж
				$MyarFields['MARK']=$materialMarkirovka; //Маркировка для доставки
				$MyarFields['IMIDG'] = $arMaterialImidg; //Имейдж
				$MyarFields['PARTNERIMIDG'] = $arMaterialImidgPartner; //Имейдж (партнерский)				
				
				$MyarFields['MATERIAL_FILES']='';
				$db_props = CIBlockElement::GetProperty($iblock, $newElementId, "sort", "asc", Array("CODE"=>"UF_MATERIAL_FILES")); // Выцепляем Прикрепленные файлы (материал)
				while ($pic_id = $db_props->GetNext())
				{
							$pic=CFile::GetFileArray($pic_id['VALUE']);
							$MyarFields['MATERIAL_FILES'].= ' http://'.$_SERVER['SERVER_NAME'].$pic['SRC'].' ';	
				}	
				
				$iblock_id=8;//Номер айблока с кодами точки
				$id_el=$pointcode;//125298;
				//$MyarFields['DIVISION'] =$pointcode;
				$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_*");
				$arFilter = Array("IBLOCK_ID"=>$iblock_id, "NAME"=>$id_el, "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);//Вытаскиваем все свойства по коду точки
				while($ob = $res->GetNextElement()){ 
				 $arProps = $ob->GetProperties();
					$MyarFields['DIVISION'] = $arProps["DIVISION"]["VALUE"]; 
					$MyarFields['CITY_POINT'] = $arProps["CITY_POINT"]["VALUE"];
					$MyarFields['REGION_POINT'] = $arProps["REGION_POINT"]["VALUE"];
					$MyarFields['ADDRESS_POINT'] = $arProps["ADDRESS_POINT"]["VALUE"];
					$MyarFields['KOD_SV'] = $arProps["KOD_SV"]["VALUE"];
					$MyarFields['KOD_TP'] = $arProps["KOD_TP"]["VALUE"];
					$MyarFields['KOD_IV'] = $arProps["KOD_IV"]["VALUE"];
				}	

					$iblock_id=11;
					$id_el=125469;
					$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_*");
					$arFilter = Array("IBLOCK_ID"=>$iblock_id, "ID"=>$newElementId, "ACTIVE"=>"Y");
					$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
					while($ob = $res->GetNextElement()){ 
					 $arProps = $ob->GetProperties();
						$mat=$arProps["UF_MATERIAL"]["VALUE"]; 
						$processing=$arProps["UF_PROCESSING"]["VALUE"]; 
						$width=$arProps["UF_WIDTH"]["VALUE"]; 
						$height=$arProps["UF_HEIGHT"]["VALUE"]; 
						$amount=$arProps["UF_AMOUNT"]["VALUE"]; 
						$am_lay=$arProps["UF_AMOUNT_LAYOUTS"]["VALUE"]; 
						$mark=$arProps["UF_MARKIROVKA"]["VALUE"]; 
						$comment=$arProps["UF_COMMENT"]["VALUE"]; 
						$pointcode=$arProps["UF_POINT_CODE"]["VALUE"];
						$imidg=$arProps["UF_IMIDG"]["VALUE"];
						$imidg_partner=$arProps["UF_IMIDG_PARTNER"]["VALUE"];

					}
									$db_props = CIBlockElement::GetProperty($iblock_id, $id_el, "sort", "asc", Array("CODE"=>"UF_MATERIAL_FILES")); // Выцепляем Прикрепленные файлы (материал)
									while ($pic_id = $db_props->GetNext())
									{
												$pic=CFile::GetFileArray($pic_id['VALUE']);
												$files[].= ' http://'.$_SERVER['SERVER_NAME'].$pic['SRC'].' ';	
									}

					if(count($mat)>1)
					{
						$text='';
						for ($i = 0; $i <count($mat); $i++)
						{
							unset($MyarFields['PROCESSING'],$MyarFields['MATERIAL'],$MyarFields['WIDTH'],$MyarFields['HIGH'],$MyarFields['COUNT'],$MyarFields['COUNT_LAY'], $MyarFields['COMMENT'],$MyarFields['MATERIAL_IMG'],$MyarFields['MARK'],$MyarFields['IMIDG'], $MyarFields['PARTNERIMIDG'], $MyarFields['MATERIAL_FILES'], $MyarFields['DIVISION'],$MyarFields['CITY_POINT'],$MyarFields['REGION_POINT'],$MyarFields['ADDRESS_POINT'],$MyarFields['ADDRESS_POINT'],$MyarFields['KOD_SV'], $MyarFields['KOD_TP'],$MyarFields['KOD_IV']);
							$text.= 'Материал:'.$mat[$i].PHP_EOL.
							'Способ обработки:'.$processing[$i].PHP_EOL.
							'Ширина:'.$width[$i].PHP_EOL.
							'Высота:'.$height[$i].PHP_EOL.
							'Количество:'.$amount[$i].PHP_EOL.
							'Количество макетов:'.$am_lay[$i].PHP_EOL.
							'Маркировка для доставки:'.$mark[$i].PHP_EOL.
							'Выбранный имейдж:'.$imidg[$i].PHP_EOL.
							'Выбранный имейдж (партнерский):'.$imidg_partner[$i].PHP_EOL.
							'Комментарий:'.$comment[$i].PHP_EOL.
							'Файл:'.$files[$i].PHP_EOL;
										$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_*");
										$iblock_dot=8;//Номер айблока с кодами точки
										$arFilter = Array("IBLOCK_ID"=>$iblock_dot, "NAME"=>$pointcode[$i], "ACTIVE"=>"Y");
										$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);//Вытаскиваем все свойства по коду точки
										while($ob = $res->GetNextElement()){ 
										 $arProps = $ob->GetProperties();
											$text.= 'Код точки: '.$pointcode[$i].PHP_EOL.
											'Дивизион:'.$arProps["DIVISION"]["VALUE"].PHP_EOL. 
											'Регион:'.$arProps["CITY_POINT"]["VALUE"].PHP_EOL.
											'Город:'.$arProps["REGION_POINT"]["VALUE"].PHP_EOL.
											'Адрес:'.$arProps["ADDRESS_POINT"]["VALUE"].PHP_EOL.
											'Код СВ:'.$arProps["KOD_SV"]["VALUE"].PHP_EOL.
											'Код ТП:'.$arProps["KOD_TP"]["VALUE"];//.PHP_EOL.'Продажи точки до ИВ, сим-карт в месяц:'.$arProps["KOD_IV"]["VALUE"]
										}	
							$text.= PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
						}
					}
				$MyarFields['OTHER_TEXT']=$text;
				
				\CEvent::SendImmediate('JUMPICA_NEW_USER_ACTIVATION', 's1', $MyarFields, 'Y', 98);	

            }

        }

        unset($_SESSION['USER_BASKET']);

    }

} else {

    $arResult['errors'][] = 'Ошибка, повторите попытку позже';
}

echo json_encode($arResult, true);

?>