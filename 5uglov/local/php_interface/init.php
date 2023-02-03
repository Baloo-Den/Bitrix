<?
if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php");
function dump($var)
{
?>	
<pre><? var_dump($var) ?></pre>
<?}
