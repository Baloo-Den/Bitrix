<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array('jquery2'));
CAjax::Init();
$this->addExternalJS("/local/components/varton_catalog_menu/script.js");
?>
<div>
<form action="" method="post"> 
<input type="hidden" name="iblock_id" id="iblock_id" value='<?php echo $arResult["IBLOCK_ID"]?>' >
<input type="hidden" name="number_tree" id="number_tree" value='1' >
<select name="section" id="section"/>
<option value='all'>все</option>	
<?
 foreach($arResult["Section"] as $section)
  {
    ?>
        <option value='<?=$section["SECTION"]?>'><?=$section["NAME"]?></option>
   <?
  }
?>
</select>
</form> 
</div>
<div id="result"></div>
<script>

</script>
