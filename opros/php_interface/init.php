<?
if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php");
function dump($var)
{
	?> <font style="text-align: left; font-size: 14px"><pre><?php var_dump($var)?></pre></font> <?php
}
