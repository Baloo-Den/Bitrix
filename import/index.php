<?
/*
Задание 3
Задача: написать скрипт, при вызове которого в bitrix создастся инфоблок и дальше в него загрузятся данные. При этом свойства инфоблока и тип данных
этих свойств определяется исходя из файла импорта. Например, у нас имеется файл импорта каталога книг:
[
  {
    "title": "Название книги номер 1",
    "cover": "http://images.com/book_1_cover.jpg",
    "description": "Описание книги 1",
    "pubic_count": 140,
    "price": 250,
    "pc": "шт",
  },
  {
    "title": "Название книги номер 2",
    "cover": "http://images.com/book_2_cover.jpg",
    "description": "Описание книги 2",
    "pubic_count": 250,
    "price": 120,
    "pc": "кг",
  },
  {
    "title": "Название книги номер 3",
    "cover": "http://images.com/book_3_cover.jpg",
    "description": "Описание книги 3",
    "pubic_count": 500,
    "price": 50,
    "pc": "км",
  },
]
 
В итоге ожидается, что на выходе у нас появится инфоблок со следующей структурой:
 
[
‘IBLOCK_TYPE’ => ‘content’, // Не обязательно, возможно другое название
‘NAME’ => ‘[Сюда попадает значение из поля title]’,
‘PREVIEW_TEXT’ => ‘[сюда попадает значение из description]’,
‘PREVIEW_PICTURE’ => [Картинка из поля cover: Важно!!! Именно не ссылка, а картинка через CFile]
‘PROPS’ => [
‘pubic_count’ => ‘Значение из соответствующего поля, а тип   «Number» определяется автоматически по содержимому’,
‘price’ => ‘Аналогично предыдущему пункту’,
‘pc’ => ‘Аналогично предыдущему пункту’,
],
]
 
После создания соответствующего по структуре инфоблока, в него необходимо загрузить данные их файла импорта.
*/
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Загрузка данных из файла в инфоблок");

use Bitrix\Main\IO,
    Bitrix\Main\Application;

$file = new IO\File( "import.txt");
$content = $file->getContents(); //Читаем файл

$text = explode("\r\n", $content);//Разбивает полученные данные по концу строки
$i=0;
foreach($text as $stroke)
{
	$pos= strripos($stroke, 'title');//Ищем title
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"title": "','",'),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['title']=$stroke;
		}
	$pos= strripos($stroke, 'cover');//Ищем cover
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"cover": "','",'),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['cover']=$stroke;
		}
	$pos= strripos($stroke, 'description');//Ищем description
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"description": "','",'),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['description']=$stroke;
		}
	$pos= strripos($stroke, 'pubic_count');//Ищем pubic_count
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"pubic_count": ',','),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['pubic_count']=$stroke;
			if (!is_numeric($stroke) || $pubic_count===false)//Если переменная не число или уже есть значение, которое не число
					$pubic_count=false;
		}
	$pos= strripos($stroke, 'price');//Ищем price
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"price": ',','),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['price']=$stroke;
			if (!is_numeric($stroke) || $price_count===false)//Если переменная не число или уже есть значение, которое не число
					$price_count=false;			
		}
	$pos= strripos($stroke, 'pc');//Ищем pc
		if($pos !== false)
		{
			$stroke=trim(str_replace(array('"pc": "','",'),"",$stroke));//Удаляем всё лишнее из строки
			$arr_content[$i]['pc']=$stroke;
			if (!is_numeric($stroke) || $pc_count===false)//Если переменная не число или уже есть значение, которое не число
					$pc_count=false;			
			$i++;
		}
}

$ib = new CIBlock;
$arFields = Array(
  "ACTIVE" => "Y",
  "NAME" => "Каталог книг",
  "CODE" => "",
  "IBLOCK_TYPE_ID" => "catalog",
  "SITE_ID" => Array("s1"),
  "SORT" => "500",
  "DESCRIPTION" => "",
  "DESCRIPTION_TYPE" => "text",
  "GROUP_ID" => Array("2"=>"R"),
);

if($ID = $ib->Add($arFields))//Создаём инфоблок 
{
	if ($pubic_count===false)//Если не число, то ставим тип свойств «строка»
		$pc='S';
	else//Иначе число
		$pc='N';
	  $ar_property_Fields[] = Array(
		  "NAME" => "pubic_count",
		  "ACTIVE" => "Y",
		  "CODE" => "pubic_count",
		  "PROPERTY_TYPE" => $pc,
		  "IBLOCK_ID" => $ID,
		  );
	if ($price_count===false)//Если не число, то ставим тип свойств «строка»
		$prc='S';
	else//Иначе число
		$prc='N';
	  $ar_property_Fields[] = Array(
		  "NAME" => "price",
		  "ACTIVE" => "Y",
		  "CODE" => "price",
		  "PROPERTY_TYPE" => $prc,
		  "IBLOCK_ID" => $ID,
		  );
	if ($pc_count===false)//Если не число, то ставим тип свойств «строка»
		$pc1='S';
	else//Иначе число
		$pc1='N';
	  $ar_property_Fields[] = Array(
		  "NAME" => "pc",
		  "ACTIVE" => "Y",
		  "CODE" => "pc",
		  "PROPERTY_TYPE" => $pc1,
		  "IBLOCK_ID" => $ID,
		  );

	$iblockproperty = new CIBlockProperty;//Создаём свойства инфоблока
	foreach($ar_property_Fields as $pf)//Проходимся по массиву
	   $PropertyID = $iblockproperty->Add($pf);//Создаём свойства инфоблока

	$el = new CIBlockElement;
	foreach($arr_content as $content) 
	{
		$PROP = array();
		$PROP['pubic_count'] = $content['pubic_count'];  
		$PROP['price'] = $content['price'];
		$PROP['pc'] = $content['pc'];

		$arLoadProductArray = Array(
		  "MODIFIED_BY"    => '', 
		  "IBLOCK_SECTION_ID" => false,
		  "IBLOCK_ID"      => $ID,
		  "PROPERTY_VALUES"=> $PROP,
		  "NAME"           => $content['title'],
		  "ACTIVE"         => "Y",
		  "PREVIEW_TEXT"   => $content['description'],
		  "DETAIL_TEXT"    => "",
		  "DETAIL_PICTURE" => CFile::MakeFileArray($content['cover'])
		  );

		if($PRODUCT_ID = $el->Add($arLoadProductArray))//Добавляем элементы в инфоблок из файла
		  echo "Добавлен: ".$PRODUCT_ID."<BR>";
		else
		  echo "Error: ".$el->LAST_ERROR."<BR>";	
	}	
}
else
	echo "Error: ".$ib->LAST_ERROR;
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
