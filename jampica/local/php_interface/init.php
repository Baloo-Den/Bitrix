<?php

require_once __DIR__ . '/include/handler.php';
require_once __DIR__ . '/include/agents.php';
require_once __DIR__ . '/include/access_matrix.php';
require_once __DIR__ . '/include/sdv_function.php';
//if (file_exists($_SERVER["DOCUMENT_ROOT"]."/exl/Classes/PHPExcel.php"))
//require_once($_SERVER["DOCUMENT_ROOT"]."/exl/Classes/PHPExcel.php");


/**
 * Директория для логфайлов
 * @return string
 */
function getLogPath()
{

    $logPath = $_SERVER["DOCUMENT_ROOT"] . '/upload/error/' . date('Y/m/d/');
    $logPathDefault = $_SERVER["DOCUMENT_ROOT"] . '/upload/error/';

    if (mkdir($logPath, 0777, true) || is_dir($logPath)) {

        return $logPath;

    } else {

        return $logPathDefault;

    }

}
function dump($var)
{
	?> <font style="text-align: left; font-size: 14px"><pre><?php var_dump($var)?></pre></font> <?php
}
/*
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementHandler");

function OnBeforeIBlockElementHandler(&$arFields)
{
	
    if ($arFields['IBLOCK_ID'] == 8) {
				
		global $USER;
				
		if ($USER->IsAdmin()) {
			
			if (strlen($arFields['NAME']) == 6) {
				
				$arFields['NAME'] = '000'.$arFields['NAME'];
				
			}
			
		}
		
    }
	
}
*/

?>