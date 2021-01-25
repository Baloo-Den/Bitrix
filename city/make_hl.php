<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
Loader::IncludeModule('highloadblock');

$arLangs = Array(
	'ru' => 'Города',
	'en'=>'City'
);
//создание HL-блока
$result = HL\HighloadBlockTable::add(array(
    'NAME' => 'City',//Название сущности
    'TABLE_NAME' => 'city_name',//Название таблицы в БД	
));
if ($result->isSuccess()) {
	$id = $result->getId();
	
	foreach($arLangs as $lang_key => $lang_val){
		HL\HighloadBlockLangTable::add(array(
			'ID' => $id,
			'LID' => $lang_key,
			'NAME' => $lang_val
		));	
	}
} else {
    $errors = $result->getErrorMessages();
	var_dump($errors);	
}

$UFObject = 'HLBLOCK_'.$id;

$arHlFields = Array(
	'UF_ADRESS'=>Array(
		'ENTITY_ID' => $UFObject,
		'FIELD_NAME' => 'UF_ADRESS',
		'USER_TYPE_ID' => 'string',
		'MANDATORY' => 'Y',
		"EDIT_FORM_LABEL" => Array('ru'=>'Адрес', 'en'=>'Adress'), 
		"LIST_COLUMN_LABEL" => Array('ru'=>'Адрес', 'en'=>'Adress'),
		"LIST_FILTER_LABEL" => Array('ru'=>'Адрес', 'en'=>'Adress'), 
		"ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
		"HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
	),
	'UF_TEL'=>Array(
		'ENTITY_ID' => $UFObject,
		'FIELD_NAME' => 'UF_TEL',
		'USER_TYPE_ID' => 'string',
		'MANDATORY' => 'Y',
		"EDIT_FORM_LABEL" => Array('ru'=>'Телефон', 'en'=>'Telephone'), 
		"LIST_COLUMN_LABEL" => Array('ru'=>'Телефон', 'en'=>'Telephone'),
		"LIST_FILTER_LABEL" => Array('ru'=>'Телефон', 'en'=>'Telephone'), 
		"ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
		"HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
	),
	'UF_NAME_EN'=>Array(
		'ENTITY_ID' => $UFObject,
		'FIELD_NAME' => 'UF_NAME_EN',
		'USER_TYPE_ID' => 'string',
		'MANDATORY' => 'Y',
		"EDIT_FORM_LABEL" => Array('ru'=>'Город на английском', 'en'=>'City in English'), 
		"LIST_COLUMN_LABEL" => Array('ru'=>'Город на английском', 'en'=>'City in English'),
		"LIST_FILTER_LABEL" => Array('ru'=>'Город на английском', 'en'=>'City in English'), 
		"ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
		"HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
	),
	'UF_NAME_RU'=>Array(
		'ENTITY_ID' => $UFObject,
		'FIELD_NAME' => 'UF_NAME_RU',
		'USER_TYPE_ID' => 'string',
		'MANDATORY' => 'Y',
		"EDIT_FORM_LABEL" => Array('ru'=>'Город на русском', 'en'=>'City in Russian'), 
		"LIST_COLUMN_LABEL" => Array('ru'=>'Город на русском', 'en'=>'City in Russian'),
		"LIST_FILTER_LABEL" => Array('ru'=>'Город на русском', 'en'=>'City in Russian'), 
		"ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
		"HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
	),	

);


foreach($arHlFields as $arCartField){
	$obUserField  = new CUserTypeEntity;
	
	$ID = $obUserField->Add($arCartField);//Добавляем поля
}

$entity = HL\HighloadBlockTable::compileEntity($id); 
$entity_data_class = $entity->getDataClass(); 

//Массив добавляемых параметров
$ar_city =array (
	array(
	  "UF_ADRESS"=>'просп. Леннона',
	  "UF_TEL"=>'8-32-52-32-00-00',
	  "UF_NAME_EN"=>'Shymkent',
	  "UF_NAME_RU"=>'Чимкент'
	),
	array(
	  "UF_ADRESS"=>'Шаболовка 37',
	  "UF_TEL"=>'8-495-212-85-06',
	  "UF_NAME_EN"=>'Moscow',
	  "UF_NAME_RU"=>'Москва'
	),
	array(
	  "UF_ADRESS"=>'ул. Бульбы, д. 1',
	  "UF_TEL"=>'8-0236-333-33-33',
	  "UF_NAME_EN"=>'Mosyr',
	  "UF_NAME_RU"=>'Мозырь'
	)	
);
foreach($ar_city as $data)
$result = $entity_data_class::add($data);//Вставляем наши данные
// подключение эпилога
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>