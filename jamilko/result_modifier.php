<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

$rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
    'filter' => array('IBLOCK_ID'=>CATALOG_IBLOCK_ID,'CODE'=>'ARTNUMBER'),//Ищем артикул в нашем инфоблоке
	    'select' => array('ID')//Выбираем только его ID
));
$ar_result=$rsProperty->fetchRaw();
$id_property=$ar_result["ID"]; 

$connection = Bitrix\Main\Application::getConnection();

// Подготовка строки для использования в sql-запросе
$query="select VALUE, IBLOCK_ELEMENT_ID, e.ACTIVE  as act, e.NAME as NAME from b_iblock_element_property p1 LEFT JOIN b_iblock_element e ON p1.IBLOCK_ELEMENT_ID=e.ID where IBLOCK_PROPERTY_ID=".$id_property." AND exists (
  select 1 from b_iblock_element_property p2 where 
    p2.VALUE like CONCAT( SUBSTRING(p1.VALUE, 1, 4), '%') 
    and p1.VALUE <> p2.VALUE ) ORDER BY IBLOCK_ELEMENT_ID DESC";//Выбираем ID, VALUE в таблице свойств, и активность в таблице элементов прямым SQL-запросом
$result = $connection->query($query);

while ($itog = $result->fetch()) {
	if ($itog['act']==='Y')//Если элемент активен, добавляем его в массив
		{
			$ar[$itog['VALUE']][$itog["IBLOCK_ELEMENT_ID"]]["ID"]=$itog['IBLOCK_ELEMENT_ID'];//
			$ar[$itog['VALUE']][$itog["IBLOCK_ELEMENT_ID"]]["NAME"]=$itog['NAME'];//наименование
			$ar[$itog['VALUE']][$itog["IBLOCK_ELEMENT_ID"]]["ARTNUMBER"]=$itog['VALUE'];//артикул
		}
}

$arSelect = Array("ID", "NAME","PROPERTY_SIZE");
$arFilter = Array('IBLOCK_ID' => SKU_IBLOCK_ID, "NAME"=>$ar["ARTNUMBER"].'%', "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false,false, $arSelect);
while ($arItem = $res->fetch())
{
	$size=$arItem["PROPERTY_SIZE_VALUE"];//Получаем размер
	$name=$arItem["NAME"].'<BR>';//
	$art=explode($size, $name);//Вытаскиваем артикул из названия
	$ar[$art[0]]['SKU'][$arItem["ID"]]["ID"]=$arItem["ID"];
	$ar[$art[0]]['SKU'][$arItem["ID"]]["NAME"]=$name;
	$ar[$art[0]]['SKU'][$arItem["ID"]]["SIZE"]=$size;
}
function cmp($a, $b)//Функция сравнения размеров
{
	$sizes = array(
		"XS" => 0,
		"S" => 1,
		"M" => 2,
		"L" => 3,
		"XL" => 4,
		"XXL" => 5,
		"3XL" => 6,
		"4XL" => 7,
		"5XL" => 8,
	);
	$asize = $sizes[$a['SIZE']];
	$bsize = $sizes[$b['SIZE']];

	if ($asize == $bsize) 
		{
			return 0;
		}

	return ($asize > $bsize) ? 1 : -1;
}

foreach ($ar as $key=>$value)
{
	if (!$ar[$key]['SKU'])// Если торговых предложений нет, удаляем из массива
		unset($ar[$key]);//
	if (count($ar[$key]['SKU']>1))//Если торговых предложений больше одного, то сортируем по размерам
		usort($ar[$key]['SKU'], "cmp");
}
$arResult['LINKED_ITEMS']=$ar;
?>