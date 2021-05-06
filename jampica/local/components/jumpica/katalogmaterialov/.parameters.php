<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if( !Loader::includeModule("iblock") ) {
    throw new \Exception('Не загружены модули необходимые для работы компонента');
}


if(!CModule::IncludeModule("iblock"))
    return;


// типы инфоблоков
$arIBlockType = CIBlockParameters::GetIBlockTypes();

// инфоблоки выбранного типа
$arIBlock = [];
$offersIblock = array();

$iblockFilter = !empty($arCurrentValues['IBLOCK_TYPE'])
    ? ['TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y']
    : ['ACTIVE' => 'Y'];

$rsIBlock = CIBlock::GetList(['SORT' => 'ASC'], $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
    $id = (int)$arr['ID'];
    if (isset($offersIblock[$id]))
        continue;
    $arIBlock[$id] = '['.$id.'] '.$arr['NAME'];


//    $arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>($arCurrentValues["IBLOCK_ID"])));
while ($arr=$rsProp->Fetch())
{
    $arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "E", "F", "G")))
    {
        $arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    }
}

unset($arr, $rsIBlock, $iblockFilter, $rsProp, $offersIblock);

$arComponentParameters = [
    // группы в левой части окна
    "GROUPS" => [
        "SETTINGS" => [
            "NAME" => Loc::getMessage('EXAMPLE_CATALOGMATERIALOV_PROP_SETTINGS'),
            "SORT" => 550,
        ],
    ],
    // поля для ввода параметров в правой части
    "PARAMETERS" => [
        // Произвольный параметр типа СПИСОК
        "IBLOCK_TYPE" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('EXAMPLE_CATALOGMATERIALOV_PROP_IBLOCK_TYPE'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y"
        ],
        "IBLOCK_ID" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('EXAMPLE_CATALOGMATERIALOV_PROP_IBLOCK_ID'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y"
        ],
        // Произвольный параметр типа СТРОКА
        "SECTION_IDS" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage('EXAMPLE_CATALOGMATERIALOV_PROP_SECTION_IDS'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "COLS" => 25
        ],
        // Настройки кэширования
        'CACHE_TIME' => ['DEFAULT' => 3600],
        "SET_TITLE" => array(),
        //хлебные крошки
        "ADD_SECTIONS_CHAIN" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("CP_BC_ADD_SECTIONS_CHAIN"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "ADD_ELEMENT_CHAIN" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("CP_BC_ADD_ELEMENT_CHAIN"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N"
        ),
        //
        "USE_MAIN_ELEMENT_SECTION" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("CP_BC_USE_MAIN_ELEMENT_SECTION"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),

        //Настройки Ajax
        "AJAX_MODE" => array(),
        "SEF_MODE" => array(
            "section" => array(
                "NAME" => GetMessage("SECTION_PAGE"),
                "DEFAULT" => "#SECTION_CODE#/",
                "VARIABLES" => array(
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ),
            ),
            "element" => array(
                "NAME" => GetMessage("DETAIL_PAGE"),
                "DEFAULT" => "#SECTION_CODE#/#ELEMENT_CODE#/",
                "VARIABLES" => array(
                    "ELEMENT_ID",
                    "ELEMENT_CODE",
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ),
            )
        ),
        //Настройки полей
        "PROPERTY_CODE" => array(
            "PARENT" => "SETTINGS",
            "NAME" => "Укажите свойства для вывода",
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE" => "Y"
        ),

        "PAGE_ELEMENT_COUNT" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage("IBLOCK_PAGE_ELEMENT_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "12"
        )


    ]
];
// настройка постраничной навигации
CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    'Элементы',  // $pager_title
    false,       // $bDescNumbering
    true        // $bShowAllParam
);

// настройка чпу
if($arCurrentValues["SEF_MODE"]=="Y")
{
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"] = array();
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["ELEMENT_ID"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_ELEMENT_ID"),
        "TEMPLATE" => "#ELEMENT_ID#",
    );
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["ELEMENT_CODE"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_ELEMENT_CODE"),
        "TEMPLATE" => "#ELEMENT_CODE#",
    );
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_ID"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_ID"),
        "TEMPLATE" => "#SECTION_ID#",
    );
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_CODE"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_CODE"),
        "TEMPLATE" => "#SECTION_CODE#",
    );
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SECTION_CODE_PATH"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SECTION_CODE_PATH"),
        "TEMPLATE" => "#SECTION_CODE_PATH#",
    );
    $arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]["SMART_FILTER_PATH"] = array(
        "NAME" => GetMessage("CP_BC_VARIABLE_ALIASES_SMART_FILTER_PATH"),
        "TEMPLATE" => "#SMART_FILTER_PATH#",
    );

    $smartBase = ($arCurrentValues["SEF_URL_TEMPLATES"]["section"]? $arCurrentValues["SEF_URL_TEMPLATES"]["section"]: "#SECTION_ID#/");
    $arComponentParameters["PARAMETERS"]["SEF_MODE"]["smart_filter"] = array(
        "NAME" => GetMessage("CP_BC_SEF_MODE_SMART_FILTER"),
        "DEFAULT" => $smartBase."filter/#SMART_FILTER_PATH#/apply/",
        "VARIABLES" => array(
            "SECTION_ID",
            "SECTION_CODE",
            "SECTION_CODE_PATH",
            "SMART_FILTER_PATH",
        ),
    );
}
