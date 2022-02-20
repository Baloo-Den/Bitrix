<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();
//var_dump($_REQUEST);
\Bitrix\Main\Loader::includeModule('iblock');
$number_tree=$_REQUEST["number_tree"]+1;
//echo $_REQUEST["section"];
if ($_REQUEST["section"]!='all')
{
	$entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($_REQUEST["iblock_id"]);
	$rsSection = $entity::getList(array(

		"filter" => array(
			"IBLOCK_ID" => $_REQUEST["iblock_id"], //Выбираем только из указанного блока
			'DEPTH_LEVEL' => $number_tree,//На уровень выше по дереву разделов
			'ACTIVE' => 'Y',
			'IBLOCK_SECTION_ID' =>$_REQUEST["section"],
		),

		"select" => array(
			'NAME'=> 'NAME',
			'ID' => 'ID', 
		),
	));
		
 	$count_section=$rsSection->getSelectedRowsCount();
	if ($count_section>0)//Если есть вложенные подразделы
	{?>
		<form action="" method="post"> 
		<input type="hidden" name="number_tree" id="number_tree" value='<?php echo $number_tree ?>' >
		<select name="section_<?php echo $number_tree ?>" id="section_<?php echo $number_tree ?>">
		<option value='all'>все</option>	
		<?
		 while ($arSection=$rsSection->fetch())
		  {
			?>
				<option value='<?=$arSection["ID"]?>'><?=$arSection["NAME"]?></option>
		   <?
		  }
		?>
		</select>
		</form>
	<div id="result<?php echo $number_tree ?>"></div>
	<?
					
	}
	else
	{
		$Items = \Bitrix\Iblock\ElementTable::getList(array(
		'select' => array('NAME'), // выбираемые 
		'filter' => array('IBLOCK_ID' => $_REQUEST["iblock_id"],'IBLOCK_SECTION_ID' =>$_REQUEST["section"]), 
		'cache' => array( // Кеш запроса  
			'ttl' => 3600,
			'cache_joins' => true
		),
		))->fetchAll();	
	}
}

else//Если выбрали всё выше основания!
{
	if (!$_REQUEST["current_section"])
	{
		$Items = \Bitrix\Iblock\ElementTable::getList(array(
		'select' => array('NAME'), // выбираемые 
		'filter' => array('IBLOCK_ID' => $_REQUEST["iblock_id"]), 
		'cache' => array( // Кеш запроса  
			'ttl' => 3600,
			'cache_joins' => true
		),
		))->fetchAll();		
	}
	else
	{
		$section = \Bitrix\Iblock\SectionTable::getByPrimary($_REQUEST["current_section"], [
			'filter' => ['IBLOCK_ID' =>$_REQUEST["iblock_id"]],
			'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN'],
		])->fetch();
		
		$Items = \Bitrix\Iblock\ElementTable::getList([
			'select' => ['ID', 'NAME', 'IBLOCK_ID'],
			'filter' => [
				'IBLOCK_ID' => $_REQUEST["iblock_id"],
				'>=IBLOCK_SECTION.LEFT_MARGIN' => $section['LEFT_MARGIN'],
				'<=IBLOCK_SECTION.RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
			],
		]);		
	}
}
if (count($Items)>0)	
{
	echo "<BR>";
	foreach($Items as $el)
		echo "Артикул: ".$el['NAME']."<BR>";	
}

?>
<script>
	$('#section_<?php echo $number_tree ?>> option').click(function() {//
			let id = $("#section_<?php echo $number_tree ?>").val(); //Выбранный раздел
			let iblock_id = <?php echo $_REQUEST["iblock_id"]; ?>; //Разделы айблока 
			let number_tree = <?php echo $number_tree ?>; //
			let current_section=<?php echo $_REQUEST["section"]; ?>;
			$.ajax({ 
				url: '/local/components/varton_catalog_menu/output_menu.php', 
				method: 'post',
				data: {section: id,iblock_id:iblock_id,number_tree:number_tree,current_section:current_section},
						success: function(html){  
						$("#result<?php echo $number_tree ?>").html(html);
						} 
			});		
		}); 
</script>  


