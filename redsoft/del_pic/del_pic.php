<?//Удаление файлов которых нет в таблице
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Удаление лишних картинок");
$result = $DB->Query('SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"');
while ($row = $result->Fetch()) 
	$all_pic[]=$row['SUBDIR'].'/'.$row['FILE_NAME'];//Запихиваем в массив ссылки на файлы картинок

	$dir=$_SERVER['DOCUMENT_ROOT'].'/upload/iblock/';
	if ((is_dir($dir)))//Проверка на существование директории
	$files = scandir($dir);//Считываем директорию
	if ($files)
	foreach($files as $el)
		{
		if ($el!='.' && $el!='..')
			{
				$pic_file=scandir($dir.$el);//Считываем вложенные директории
				foreach($pic_file as $pic)
				{
					if ($pic!='.' && $pic!='..')
					$s_pic='iblock/'.$el.'/'.$pic;
					if (!in_array($s_pic, $all_pic ))//Проверяем наличие файла в массиве из таблицы
					{

						unlink ($_SERVER['DOCUMENT_ROOT'].'/upload/'.$s_pic);//Удаляем пикчу
					}

				}
					
			}
		}	
//dump($all_file_pic);
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>