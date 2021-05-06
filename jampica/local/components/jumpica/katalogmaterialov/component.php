<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
{
    ShowError('Модуль «Информационные блоки» не установлен');
    return;
}


// запрещаем сохранение в сессии номера последней страницы
// при стандартной постраничной навигации
CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

// количество элементов на страницу
$arParams['PAGE_ELEMENT_COUNT'] = intval($arParams['PAGE_ELEMENT_COUNT']);
if ($arParams['PAGE_ELEMENT_COUNT'] <= 0) {
    $arParams['PAGE_ELEMENT_COUNT'] = 12;
}

// показывать постраничную навигацию вверху списка элементов?
$arParams['DISPLAY_TOP_PAGER'] = $arParams['DISPLAY_TOP_PAGER']=='Y';
// показывать постраничную навигацию внизу списка элементов?
$arParams['DISPLAY_BOTTOM_PAGER'] = $arParams['DISPLAY_BOTTOM_PAGER']=='Y';
// поясняющий текст для постраничной навигации
$arParams['PAGER_TITLE'] = trim($arParams['PAGER_TITLE']);
// всегда показывать постраничную навигацию, даже если в этом нет необходимости
$arParams['PAGER_SHOW_ALWAYS'] = $arParams['PAGER_SHOW_ALWAYS']=='Y';
// имя шаблона постраничной навигации
$arParams['PAGER_TEMPLATE'] = trim($arParams['PAGER_TEMPLATE']);
// показывать ссылку «Все элементы», с помощью которой можно показать все элементы списка?
$arParams['PAGER_SHOW_ALL'] = $arParams['PAGER_SHOW_ALL']=='Y';

// получаем все параметры постраничной навигации, от которых будет зависеть кеш
$arNavParams = null;
$arNavigation = false;
if ($arParams['DISPLAY_TOP_PAGER'] || $arParams['DISPLAY_BOTTOM_PAGER']) {
    $arNavParams = array(
        'nPageSize' => $arParams['PAGE_ELEMENT_COUNT'], // количество элементов на странице
        'bShowAll' => $arParams['PAGER_SHOW_ALL'], // показывать ссылку «Все элементы»?
    );
    $arNavigation = CDBResult::GetNavParams($arNavParams);
}



//******************************************************
$arParams['ACTION_VARIABLE'] = (isset($arParams['ACTION_VARIABLE']) ? trim($arParams['ACTION_VARIABLE']) : 'action');
if ($arParams["ACTION_VARIABLE"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
    $arParams["ACTION_VARIABLE"] = "action";

$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"]? $arParams["SEF_URL_TEMPLATES"]["section"]: "#SECTION_ID#/");
$arDefaultUrlTemplates404 = array(
    "sections" => "",
    "section" => "#SECTION_ID#/",
    "element" => "#SECTION_ID#/#ELEMENT_ID#/",
    "compare" => "compare.php?action=COMPARE",
    "smart_filter" => $smartBase."filter/#SMART_FILTER_PATH#/apply/"
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
    "SECTION_ID",
    "SECTION_CODE",
    "ELEMENT_ID",
    "ELEMENT_CODE",
    "action",
);

if($arParams["SEF_MODE"] == "Y")
{
    $arVariables = array();

    $engine = new CComponentEngine($this);
    if (\Bitrix\Main\Loader::includeModule('iblock'))
    {
        $engine->addGreedyPart("#SECTION_CODE_PATH#");
        $engine->addGreedyPart("#SMART_FILTER_PATH#");
        $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
    }
    $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
    $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

    $componentPage = $engine->guessComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );

    $b404 = false;
    if(!$componentPage)
    {
        $componentPage = "sections";
        $b404 = true;
    }

    if($componentPage == "section")
    {
        if (isset($arVariables["SECTION_ID"]))
            $b404 |= (intval($arVariables["SECTION_ID"])."" !== $arVariables["SECTION_ID"]);
        else
            $b404 |= !isset($arVariables["SECTION_CODE"]);
    }

    if($b404 && CModule::IncludeModule('iblock'))
    {
        $folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
        if ($folder404 != "/")
            $folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
        if (substr($folder404, -1) == "/")
            $folder404 .= "index.php";

        if ($folder404 != $APPLICATION->GetCurPage(true))
        {
            \Bitrix\Iblock\Component\Tools::process404(
                ""
                ,($arParams["SET_STATUS_404"] === "Y")
                ,($arParams["SET_STATUS_404"] === "Y")
                ,($arParams["SHOW_404"] === "Y")
                ,$arParams["FILE_404"]
            );
        }
    }

    CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
    $arResult["SEF_MODE"] = array(
        "FOLDER" => $arParams["SEF_FOLDER"],
        "URL_TEMPLATES" => $arUrlTemplates,
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases
    );
}
else
{
    $arVariables = array();

    $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    $componentPage = "";

    $arCompareCommands = array(
        "COMPARE",
        "DELETE_FEATURE",
        "ADD_FEATURE",
        "DELETE_FROM_COMPARE_RESULT",
        "ADD_TO_COMPARE_RESULT",
        "COMPARE_BUY",
        "COMPARE_ADD2BASKET"
    );

    if(isset($arVariables["action"]) && in_array($arVariables["action"], $arCompareCommands))
        $componentPage = "compare";
    elseif(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
        $componentPage = "element";
    elseif(isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
        $componentPage = "element";
    elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
        $componentPage = "section";
    elseif(isset($arVariables["SECTION_CODE"]) && strlen($arVariables["SECTION_CODE"]) > 0)
        $componentPage = "section";
    elseif(isset($_REQUEST["q"]))
        $componentPage = "search";
    else
        $componentPage = "sections";

    $currentPage = htmlspecialcharsbx($APPLICATION->GetCurPage())."?";
    $arResult["SEF_MODE"] = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => array(
            "section" => $currentPage.$arVariableAliases["SECTION_ID"]."=#SECTION_ID#",
            "element" => $currentPage.$arVariableAliases["SECTION_ID"]."=#SECTION_ID#"."&".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#",
            "compare" => $currentPage."action=COMPARE",
        ),
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases
    );
}
//***********************************


if($arParams["USE_MAIN_ELEMENT_SECTION"] == "Y" && $componentPage != "section") {
    $componentPage = "section";
} elseif($arParams["USE_MAIN_ELEMENT_SECTION"] == "Y" && $componentPage == "section") {
    $componentPage = "element";
    $arResult["SEF_MODE"]["VARIABLES"]["ELEMENT_CODE"] = $arResult["SEF_MODE"]["VARIABLES"]["SECTION_CODE"];
} else {}
////Запросы к БД

if ($componentPage == "sections") {
    $arSort= Array("NAME"=>"ASC");
    $arFilter = Array("IBLOCK_ID" => IntVal($arParams["IBLOCK_ID"]), "ACTIVE"=>"Y");

    $rsDB = CIBlockSection::GetList(
        $arSort,
        $arFilter,
        false
    );
}
if ($componentPage == "section") {
    if (0 < intval($arResult["SEF_MODE"]["VARIABLES"]["SECTION_ID"]))
    {
        $arFilterCode = "SECTION_ID";
        $arFilterRes = $arResult["SEF_MODE"]["VARIABLES"]["SECTION_ID"];
    }
    elseif ('' != $arResult["SEF_MODE"]["VARIABLES"]["SECTION_CODE"])
    {
        $arFilterCode = "SECTION_CODE";
        $arFilterRes = $arResult["SEF_MODE"]["VARIABLES"]["SECTION_CODE"];
    }

    $arSort = Array("NAME" => "ASC");
    $arSelect = Array("LANG_DIR", "IBLOCK_ID", "ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "PREVIEW_TEXT");
    $arFilter = Array("IBLOCK_ID" => IntVal($arParams["IBLOCK_ID"]),   $arFilterCode => $arFilterRes, "ACTIVE" => "Y");
    $arNavParams = Array(
        "bShowAll" => true,
        "iNumPage" => intval($_REQUEST["PAGEN_1"]),
        "nPageSize" => IntVal($arParams["PAGE_ELEMENT_COUNT"]),
        "nTopCount" => false,
    );
    $rsDB = CIBlockElement::GetList(
        $arSort,
        $arFilter,
        false,
        $arNavParams,
        $arSelect
    );

}
if ($componentPage == "element") {

    $objFindTools = new CIBlockFindTools();
    $elementID = $objFindTools->GetElementID(false, $arResult["SEF_MODE"]["VARIABLES"]["ELEMENT_CODE"], false, false, array("IBLOCK_ID" => IntVal($arParams["IBLOCK_ID"])));

    $rsDB = CIBlockElement::GetByID($elementID);


}
$arResult['ITEMS'] = array();
while($arFields = $rsDB->GetNext())
{
    $arResult['ITEMS'][] = $arFields;
}

if ($componentPage == "element") {
    foreach($arResult["ITEMS"] as $key => $arElement)
    {
        $db_props = CIBlockElement::GetProperty(IntVal($arResult["ITEMS"][$key]["IBLOCK_ID"]), $arResult["ITEMS"][$key]["ID"], array(), Array($arParams["PROPERTY_CODE"]));
        while($ar_props = $db_props->Fetch()) {
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["ID"] = $ar_props['ID'];
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["IBLOCK_ID"] = $ar_props['IBLOCK_ID'];
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["PROPERTY_VALUE_ID"] = $ar_props['PROPERTY_VALUE_ID'];
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["NAME"] = $ar_props['NAME'];
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["CODE"] = $ar_props['CODE'];
            $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["PROPERTY_TYPE"] = $ar_props['PROPERTY_TYPE'];
            if(in_array($ar_props["PROPERTY_TYPE"], array("L", "E", "F", "G"))) {
                $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["DISPLAY_VALUE"][] = $ar_props['VALUE'];
            }
            else {
                $arResult["ITEMS"][$key]["DISPLAY_PROPERTIES"][$ar_props['CODE']]["DISPLAY_VALUE"] = $ar_props['VALUE'];
            }
        }
    }
}

/*
 * Постраничная навигация
 */
$arResult['NAV_STRING'] = $rsDB->GetPageNavString(
    $arParams['PAGER_TITLE'],
    $arParams['PAGER_TEMPLATE'],
    $arParams['PAGER_SHOW_ALWAYS'],
    $this
);

//echo "<pre>";
//echo $componentPage;
//print_r($arIBlock);
//print_r($arParams);
//print_r($arResult);
//
//print_r($componentPage);
//echo "<br>";
//print_r($arVariables);
////print_r($arResult["SEF_MODE"]["VARIABLES"]["SECTION_CODE"]);
//print_r($arResult["ITEMS"]);

//echo "</pre>";

$this->includeComponentTemplate($componentPage);