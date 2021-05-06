<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;

if (Loader::includeModule('iblock')) {

    //Получаем список типов инфоблоков
    $arOrder = ["SORT" => "ASC"];
    $arFilter = [
        "ACTIVE" => "Y",
    ];

    $res = CIBlockType::GetList($arOrder, $arFilter);
    while ($arRes = $res->GetNext()) {
        //Настройки типа информационных блоков по ID
        if ($arResType = CIBlockType::GetByIDLang($arRes["ID"], LANG, true)) {

            $iblockTypeId = $arResType["ID"];
            $iblockTypeName = $arResType["NAME"];
            $arIblockType[$iblockTypeId] = $iblockTypeName;
        }
    }
    //Получаем список инфоблоков выбранного типа
    $arOrder = ["SORT" => "ASC"];
    $arFilter = [
        "ACTIVE" => "Y",
        "TYPE" => $arCurrentValues["IBLOCK_TYPE"],
    ];

    $res = CIBlock::GetList($arOrder, $arFilter, true);
    while ($arRes = $res->Fetch()) {
        $iblockId = $arRes["ID"];
        $iblockName = $arRes["NAME"];
        $arIblock[$iblockId] = $iblockName;
    }

}

$arRes = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("USER", 0, LANGUAGE_ID);
$userProp = array();
if (!empty($arRes))
{
	foreach ($arRes as $key => $val)
		$userProp[$val["FIELD_NAME"]] = (strLen($val["EDIT_FORM_LABEL"]) > 0 ? $val["EDIT_FORM_LABEL"] : $val["FIELD_NAME"]);
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"SET_TITLE" => array(),
		"USER_PROPERTY"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("USER_PROPERTY"),
			"TYPE" => "LIST",
			"VALUES" => $userProp,
			"MULTIPLE" => "Y",
			"DEFAULT" => array(),
		),
		"SEND_INFO"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SEND_INFO"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CHECK_RIGHTS"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CHECK_RIGHTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
        "IBLOCK_ID_DELIVERY" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("ADDRESS_LIST"),
            "TYPE" => "LIST",
            "VALUES" => $arIblock,
            "DEFAULT" => "",
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
            "MULTIPLE" => "N",
        ],

    ),
);
?>