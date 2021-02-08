<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init(array('jquery2'));
CAjax::Init();

$this->addExternalJS("/local/components/tw/script.js");

?>
<button type="button" id="begin_work">
 Начать рабочий день  
</button>
<button type="button" id="end_work">
 Закончить рабочий день  
</button>
<button type="button" id="pause">
 Пауза в работе  
</button>
<button type="button" id="end_pause">
 Конец паузы  
</button>
<div id="result"></div>
<script>
	  let id_user='<?php echo $arResult["id_user"]; ?>';
	  let current_work_day='<?php echo $arResult["current_work_day"]; ?>';
	  let pause='<?php echo $arResult["pause"]; ?>';
</script>
