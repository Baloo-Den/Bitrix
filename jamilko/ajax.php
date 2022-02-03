<? 
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();

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
/*
//Ajax-запрос
    $.ajax( {
      type: "POST",
      url: "ajax.php",
		data: {artnumber: artnumber},
      	success: function(responce){ 
		$("#window_linkedItems").html(responce); //Недопонял - 3) Полученные данные сохранить в window.linkedItems. 
						} 
    });
*/

 if(CModule::IncludeModule("iblock"))
 {
	 $short_art=substr($_REQUEST['artnumber'],0,4);
	 $arSelect = Array("ID", "NAME","PROPERTY_ARTNUMBER");
	 $arFilter = Array('IBLOCK_ID' => CATALOG_IBLOCK_ID, "PROPERTY_ARTNUMBER"=>$short_art.'%', "ACTIVE"=>"Y");//Выбираем активные элементы каталога, совпадающие по первым четырём знакам артикула.
	 $res = CIBlockElement::GetList(Array(), $arFilter, false,false, $arSelect);
	 while ($arItem = $res->fetch())
	 {
		 //dump($arItem);
		 $ar[$arItem["PROPERTY_ARTNUMBER_VALUE"]][$arItem["ID"]]["ID"]=$arItem["ID"];
		 $ar[$arItem["PROPERTY_ARTNUMBER_VALUE"]][$arItem["ID"]]["NAME"]=$arItem["NAME"];
		 $ar[$arItem["PROPERTY_ARTNUMBER_VALUE"]][$arItem["ID"]]["ARTNUMBER"]=$arItem["PROPERTY_ARTNUMBER_VALUE"];
	 }
	 $arSelect = Array("ID", "NAME","PROPERTY_SIZE");
	 $arFilter = Array('IBLOCK_ID' => SKU_IBLOCK_ID, "NAME"=>$ar["ARTNUMBER"].'%', "ACTIVE"=>"Y");//Выбираем активные элементы торговых предложений, совпадающие по первым четырём знакам артикула.
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
 }


foreach ($ar as $key=>$value)
{
	if (!$ar[$key]['SKU'])// Если торговых предложений нет, удаляем из массива
		unset($ar[$key]);//
	if (count($ar[$key]['SKU']>1))//Если торговых предложений больше одного, то сортируем по размерам
		usort($ar[$key]['SKU'], "cmp");
}
echo json_encode($ar);
