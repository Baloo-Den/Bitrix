<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

    $arComponentParameters = [
        "GROUPS" => [],
        "PARAMETERS" => [
            "IBLOCK_ID_CATALOG" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с заявками",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],
            "IBLOCK_ID_MATERIAL" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с материалами",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],
            "IBLOCK_ID_PROCESSING" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с обработкой материалов",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],
            "IBLOCK_ID_IMIDG" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с имиджами",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],
            "IBLOCK_ID_POINTCODE" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с точками доставки",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],
            "IBLOCK_ID_DELIVERY" => [
                "PARENT" => "BASE",
                "NAME" => "Выберите инфоблок с доставкой",
                "TYPE" => "LIST",
                "VALUES" => $arIblock,
                "DEFAULT" => "",
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH" => "Y",
                "MULTIPLE" => "N",
            ],

        ],
    ];

}


?>