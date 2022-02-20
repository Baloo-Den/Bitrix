<? 
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die (); 
use Bitrix\Main\Loader, Bitrix\Iblock;

if (!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 36000000;
}

$arParams["IBLOCK_ID"] = intval(trim($arParams["IBLOCK_ID"]));
if(!($arParams["IBLOCK_ID"] > 0))
{
	ShowError ("не указан иблок");
	return;
}

if ($this->StartResultCache())
{
	if (!Loader::includeModule("iblock")) 
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
$arResult["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
$entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($arParams["IBLOCK_ID"]);

$rsSection = $entity::getList(array(

    "filter" => array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"], //Выбираем только из указанного блока
		'DEPTH_LEVEL' => 1,
		'ACTIVE' => 'Y',

    ),

    "select" => array(
		'NAME',
		'ID' => 'ID', 
	),//Берём только имя и дополнительное свойство раздела

));

	while ($arSection=$rsSection->fetch()) 
		$Section[]=array('NAME'=>$arSection['NAME'],'SECTION'=>$arSection['ID']);
	
	$arResult["Section"] = $Section;
	$this->SetResultCacheKeys(array(
			"Section",
	));
	$this->includeComponentTemplate();
} 
else 
{
	$this->abortResultCache();
} 