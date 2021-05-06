<?
if ($arResult["USER_SECOND_NAME"]) {
    $arResult["USER_SECOND_NAME"] = '';
} else {
    $arResult["USER_SECOND_NAME"] = htmlspecialcharsbx($_REQUEST["USER_SECOND_NAME"]);
}

if ($arResult["UF_DIVISION"]) {
    $arResult["UF_DIVISION"] = '';
} else {
    $arResult["UF_DIVISION"] = htmlspecialcharsbx($_REQUEST["UF_DIVISION"]);
}

if ($arResult["PERSONAL_STATE"]) {
    $arResult["PERSONAL_STATE"] = '';
} else {
    $arResult["PERSONAL_STATE"] = htmlspecialcharsbx($_REQUEST["PERSONAL_STATE"]);
}

if ($arResult["PERSONAL_CITY"]) {
    $arResult["PERSONAL_CITY"] = '';
} else {
    $arResult["PERSONAL_CITY"] = htmlspecialcharsbx($_REQUEST["PERSONAL_CITY"]);
}

if ($arResult["PERSONAL_STREET"]) {
    $arResult["PERSONAL_STREET"] = '';
} else {
    $arResult["PERSONAL_STREET"] = htmlspecialcharsbx($_REQUEST["PERSONAL_STREET"]);
}
?>