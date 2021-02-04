<?
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();

$arParams["IBLOCK_ID"] = intval(trim($arParams["IBLOCK_ID"]));
if(!($arParams["IBLOCK_ID"] > 0))
{
	ShowError ("не указан иблок");
	return;
}
use \Bitrix\Main\Service\GeoIp;
use Bitrix\Main\Context;
use Bitrix\Main\UI\Extension;
use Bitrix\Highloadblock\HighloadBlockTable; // Импортируем класс
Extension::load('ui.bootstrap4');
$IP = GeoIp\Manager::getRealIp();//Получаем IP юзера

$user_sity = GeoIp\Manager::getCityName($IP);//Получаем название города на английском



if (\Bitrix\Main\Loader::includeModule("highloadblock")) { //Проверяем подключение модуля

    $hblockId = $arParams["IBLOCK_ID"]; //Мой блок с городами

    $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($hblockId)->fetch();
    $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock); 
    $strEntityDataClass = $obEntity->getDataClass();

    $rsData = $strEntityDataClass::getList(array(
        'select' => array('UF_NAME_RU','UF_TEL', 'UF_ADRESS'), // Выберем название города на русском и телефон соответствующий этому городу
        'filter' => array("UF_NAME_EN" =>$user_sity), //Ищем английское название города
     ));
	if (intval($rsData->getSelectedRowsCount())==0)//Если город в базе не найден, принудительно выставляем Москву.
	{
		$rsData = $strEntityDataClass::getList(array(
			'select' => array('UF_NAME_RU','UF_TEL', 'UF_ADRESS'), // 
			'filter' => array("UF_NAME_RU" =>'Москва'), //Выберем Москву
		 ));
		
	}

		 while ($arItem = $rsData->Fetch()) 
		 { 
			 $city=$arItem["UF_NAME_RU"];
			 $tel=$arItem["UF_TEL"];
			 $adress=$arItem["UF_ADRESS"];
		 }
}

$arResult["city"]=$city;
$arResult["tel"]=$tel;
$arResult["adress"]=$adress;

$this->includeComponentTemplate();
