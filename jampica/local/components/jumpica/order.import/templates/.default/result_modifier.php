<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

$arResult['USER']['UF_DOP_FIO'][] = $arResult['USER']['LAST_NAME'] . ' ' . $arResult['USER']['NAME'] . ' ' . $arResult['USER']['SECOND_NAME'];
$arResult['USER']['UF_DOP_TEL'][] = $arResult['USER']['PERSONAL_PHONE'];

?>
