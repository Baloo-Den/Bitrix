<?
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NO_AGENT_CHECK", true);
//define("NOT_CHECK_PERMISSIONS", true);

use Bitrix\Main;
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('sale');

require_once(dirname(__FILE__).'/class.php');

$result = true;
$errors = array();
$data = array();

try
{
	CUtil::JSPostUnescape();

	if($request['version'] == '2')
		$data = CBitrixLocationSelectorSearchComponent::processSearchRequestV2($_REQUEST);
	else
		$data = CBitrixLocationSelectorSearchComponent::processSearchRequest();
}
catch(Main\SystemException $e)
{
	$result = false;
	$errors[] = $e->getMessage();
}

//\Adelfo\Utils\Log::addMessageToLog('saleLocationSelectorSearch_get',[
//	$_POST,
//	$_GET,
//	$_SESSION,
//	$_SERVER,
//	'res' => [
//		'result' => $result,
//		'errors' => $errors,
//		'data' => $data
//	],
//]);

header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);
print(CUtil::PhpToJSObject(array(
	'result' => $result,
	'errors' => $errors,
	'data' => $data
), false, false, true));