<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Jumpica\Order\OrderFunction;
use Bitrix\Main\UserTable;

$request = Application::getInstance()->getContext()->getRequest();

$orderName = $request->getQuery('order');
$statusId = (int)$request->getQuery('status');
$priceFrom = trim(strip_tags($request->getQuery('price_from')));
$priceTo = trim(strip_tags($request->getQuery('price_to')));
$dateFrom = trim(strip_tags($request->getQuery('date_from')));
$dateTo = trim(strip_tags($request->getQuery('date_to')));
$pointCode = trim(strip_tags($request->getQuery('point_code')));

if (Loader::includeModule('jumpica.order')) {

    $iblockIdCatalog = Option::get('jumpica.order', 'IBLOCK_ID_CATALOG');
	
	$controllerInfo = Jumpica\Order\OrderFunction::getUserFields($USER->GetID());


	//dump($gru_users); exit;

    //Параметры видимости заявок
    if (in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['general_manager_jampica', 'manager_jampica', 'manager_mts', 'controller'])) {

        if ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'manager_jampica') {

            $arResultMerge = getUserIdGeneralManagerJampica();

            $arrFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
                '!CREATED_BY' => $arResultMerge,
            ];

        } elseif ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'manager_mts') {

            //$arResultMerge = array_merge(getUserIdGeneralManagerJampica(), getUserIdManagerJampica());

            $arrFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
                '!CREATED_BY' => $arResultMerge,
            ];

        } elseif ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'controller') {

            $controllerInfo = Jumpica\Order\OrderFunction::getUserFields($USER->GetID());

            $arrFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
                'PROPERTY_UF_DIVISION' => $controllerInfo['PERSONAL_STATE'],
            ];

        } else {

            //Без фильтрации по пользователю
            $arrFilter = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockIdCatalog,
            ];

        }

    } else {

		/*$gru=$controllerInfo["UF_GRU"]; //Записываем к какой группе относится юзер
		$rsUsers = CUser::GetList($by,$order, Array ( 'UF_GRU' => $gru) );//Выбираем всех юзеров этой группы
		while($rs = $rsUsers->GetNext()) 
			$gru_users[]=$rs['ID'];*/  
		
		//С фильтрацией по пользователю 
        $arrFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdCatalog,
            //'CREATED_BY' => $gru_users,
			'CREATED_BY' => (!empty($_SESSION['MATRIX']['REFERRAL'])) ? $_SESSION['MATRIX']['REFERRAL'] : $USER->GetID(),
			//'PROPERTY_UF_DIVISION' => $controllerInfo['PERSONAL_STATE'],
        ];

    }

    //Фильтрация по статусу заявки
    if (!empty($statusId)) {

        $arrFilter[] = [
            'LOGIC' => 'AND',
            '!PROPERTY_UF_STATUS' => false,
            'PROPERTY_UF_STATUS' => $statusId,
        ];

    } else {

        //Выгружаем все кроме удаленных
        $arrFilter[] = [
            'LOGIC' => 'AND',
            '!PROPERTY_UF_STATUS' => false,
            '!PROPERTY_UF_STATUS' => OrderFunction::STATUS_DELETED,
        ];

    }

    //Фильтрация по номеру заявки
    if (!empty($orderName)) {

        $arrFilter[] = [
            'PROPERTY_UF_ORDER_NAME' => "%" . $orderName . "%",
        ];

    }

    //Фильтрация по коду точки
    if (!empty($pointCode)) {

        $arPointCode = OrderFunction::getPointFieldsInfo($pointCode);

        if (!empty($arPointCode['ID'])) {

            $arrFilter[] = [
                'PROPERTY_UF_POINT_CODE' => $arPointCode['ID'],
            ];

        } else {
			
            $arrFilter[] = [
                'PROPERTY_UF_POINT_CODE' => false,
            ];			
			
		}

    }

    //Фильтрация по стоимости
    if (!empty($priceFrom) || !empty($priceTo)) {

        if (!empty($priceFrom) && !empty($priceTo)) {

            $arrFilter[] = [
                'LOGIC' => 'AND',
                '>=PROPERTY_UF_PRICE' => $priceFrom,
                '<=PROPERTY_UF_PRICE' => $priceTo,
            ];

        } elseif (!empty($priceFrom) && empty($priceTo)) {

            $arrFilter[] = [
                '>=PROPERTY_UF_PRICE' => $priceFrom,
            ];

        } elseif (empty($priceFrom) && !empty($priceTo)) {

            $arrFilter[] = [
                '<=PROPERTY_UF_PRICE' => $priceTo,
            ];

        }

    }

    //Фильтрация по дате создания
    if (!empty($dateFrom) || !empty($dateTo)) {

        if (!empty($dateFrom)) {

            $arDateFrom = explode('.', $dateFrom);

            if (checkdate($arDateFrom[1], $arDateFrom[0], $arDateFrom[2])) {

                $arrFilter['>=DATE_CREATE'] = $dateFrom;

            }

        }

        if (!empty($dateTo)) {

            $arDateTo = explode('.', $dateTo);

            if (checkdate($arDateTo[1], $arDateTo[0], $arDateTo[2])) {

                $arrFilter['<=DATE_CREATE'] = $dateTo . ' 23:59:59';

            }

        }

    }

}

//echo '<pre>';
//print_r($arrFilter);
//echo '</pre>';

//echo '<pre>';
//print_r($_SESSION['MATRIX']);
//echo '</pre>';

?>
