<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');//Обязательная строка инициирующая движок Битрикса, но не подключающая шаблон

if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();
//echo $_POST['hblockId'];
//$this->addExternalJS("/local/components/city/script.js");
if (\Bitrix\Main\Loader::includeModule("highloadblock")) { //Проверяем подключение модуля

    $hblockId =  $_POST['hblockId']; //Мой блок с городами

    $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($hblockId)->fetch();
    $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock); 
    $strEntityDataClass = $obEntity->getDataClass();
	
$arFilter = Array(
   Array(
      "LOGIC"=>"OR",
      Array(
         "UF_NAME_RU" =>$_POST['text'].'%'//Ищем название города по первым вводимым русским буквам
      ),
      Array(         
         "UF_NAME_EN" =>$_POST['text'].'%' //Ищем название города по первым вводимым английским буквам        
      )
   )
);	

    $rsData = $strEntityDataClass::getList(array(
        'select' => array('UF_NAME_RU','UF_TEL', 'UF_ADRESS'), // Выберем название города на русском
        'filter' => $arFilter, //Ищем название города
        //'order' => array('ID' => 'ASC'), // Сортировка
        //'limit' => '1',
     ));

     while ($arItem = $rsData->Fetch()) 
	 { 
		 $arItems[]=array('name'=>$arItem["UF_NAME_RU"], 'tel'=>$arItem["UF_TEL"], 'adress'=>$arItem["UF_ADRESS"]);//;
		 //$city=$arItem["UF_NAME_RU"];
		 //$tel=$arItem["UF_TEL"];
     }

}
if (count($arItems)==0)
	echo 'Ваш город не найден';
else
{
	echo '<ul>';
	foreach($arItems as $city)
	{
		echo '<li class="full_info" data-tel="'.$city['tel'].'" data-adress="'.$city['adress'].'">'.$city['name'].'</li>';
	}
	echo '</ul>';
}
//var_dump($arItems);
?>
<script>
 
	  $('.full_info').click(function() {//
		  let city = $(this).text();
		  let tel =$(this).data('tel');
		  let adress =$(this).data('adress');
		  $('#city').text(city);
		  $('#modal-body_city').text(city);
		  $('#tel').text(tel);
		  $('#adress').text(adress);
		  $("#modal_form").modal('hide');
		});			  
</script>  


