<script>
	  $('.full_info').click(function() {//Если выбрали точку
		  let id =$(this).data('id');
		  let name =$(this).data('name');
		  $('#text_dot').val(name);//Показываем NAME
		  $('#result').empty();//Очистка блока со списком точек
		});	
	
$('.full_info').bind({//Навели курсор на элемент точки
	'mouseover':function() { $(this).css("background-color", "lightblue"); },
	'mouseout':function() { $(this).css("background-color", "white"); },
});	
</script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); 
\Bitrix\Main\Loader::includeModule('iblock');
?>
<?php
if($_POST['code_point'])//Пришёл запрос на добавления новой точки
{
	//dump ($_POST);exit;
	$el = new CIBlockElement;
	$PROP = array();
	$PROP['DIVISION'] = $_POST["division"];  // 
	$PROP['REGION_POINT'] = $_POST["region"]; //
	$PROP['CITY_POINT'] = $_POST["city"]; //
	$PROP['ADDRESS_POINT'] = $_POST["adress"]; //
	$PROP['KOD_SV'] = $_POST["code_sv"]; //
	$PROP['KOD_TP'] = $_POST["code_tp"]; //
	//$PROP['KOD_IV'] = $_POST["code_iv"]; //
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => $GLOBALS['USER']->GetID(), // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => 8,//Блок с кодами точек
	   'PROPERTY_VALUES' => $PROP,  
	   'NAME' => $_POST["code_point"],  
	   'ACTIVE' => 'Y', // активен  

	);

	if($PRODUCT_ID = $el->Add($arLoadProductArray))//Если точка добавилась в блок
	{
		echo 'Добавлена точка '.$_POST["code_point"]."<BR>";
		echo '<button type="button" id="new_point" class="btn btn-primary" data-dismiss="modal">Использовать эту точку в заявке</button><BR><BR>';
			?>
		<script>
		$("#new_point").click(function(){
		  $("#text_dot").val("<?php echo $_POST["code_point"];?>");
			 $("#output_save").hide();
		});		
		</script>	
		<?php	
	} 
	else 
	{
	   echo 'Error: '.$el->LAST_ERROR;
	}	
	exit;
}

?>
<?php
CJSCore::Init(array('jquery2'));
if($_POST['text_dot'])
{
	
	//echo $_POST['text_dot'];
$dbItem = \Bitrix\Iblock\ElementTable::getList(array(
    'select' => array('ID', 'NAME'),
    'filter' => array('IBLOCK_ID' => 8,'NAME' =>$_POST['text_dot'].'%'),

));
while ($arItem = $dbItem->fetch()) {

    $arItems[] = $arItem;
}

if (count($arItems)==0)
{
	echo 'Такая точка не найдена<BR>';
	echo '';
?>
  <button type="button" class="btn-cart " data-toggle="modal" data-target="#myModal"> 
    Добавить новую точку
  </button>	
  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">

          <h4 class="modal-title">Создание новой точки</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body"><div id="output_save"></div>
			<form method="post" action="#" autocomplete="off" id="form_new_point">
				
			  <div class="form-group">
				<label for="code_point">Код точки</label>
				<input name="code_point" type="text" class="form-control" id="code_point" placeholder="Код точки">
			  </div>
				
			  <div class="form-group">
				<label for="division">Дивизион</label>
				<input name="division" type="text" class="form-control" id="division" placeholder="Дивизион">
			  </div>
				
			  <div class="form-group">
				<label for="region">Регион</label>
				<input name="region" type="text" class="form-control" id="region" placeholder="Регион">
			  </div>
								
			  <div class="form-group">
				<label for="city">Город</label>
				<input name="city" type="text" class="form-control" id="city" placeholder="Город">
			  </div>

								
			  <div class="form-group">
				<label for="adress">Адрес</label>
				<input name="adress" type="text" class="form-control" id="adress" placeholder="Адрес">
			  </div>

								
			  <div class="form-group">
				<label for="code_sv">Код СВ</label>
				<input name="code_sv" type="text" class="form-control" id="code_sv" placeholder="Код СВ">
			  </div>
								
			  <div class="form-group">
				<label for="code_tp">Код ТП</label>
				<input name="code_tp" type="text" class="form-control" id="code_tp" placeholder="Код ТП">
			  </div>
<!--
								
			  <div class="form-group">
				<label for="code_iv">Продажи точки до ИВ, сим-карт в месяц</label>
				<input name="code_iv" type="text" class="form-control" id="code_iv" placeholder="Продажи точки до ИВ, сим-карт в месяц">
			  </div>																				
			  
-->
			</form>  
			<button  id="save_point">Сохранить изменения</button>
        </div>
        <!--type="submit"-->
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">закрыть</button>
        </div>
        
      </div>
    </div>
  </div>
	<script>
$("#save_point").click(function(){

	let msg=$('#form_new_point').serialize();//Считываем поля формы
    $.ajax( {
      type: "POST",
      url: "<?php echo ($_SERVER["PHP_SELF"]) ; ?>",
		data: msg, 
      	success: function(html){  
        $("#output_save").html(html);
		$('#form_new_point')[0].reset();
		//$('#save_point').hide();
						} 
    });   
});	
</script>
<?php
	
}

else
{
	echo '<ul>';
	foreach($arItems as $dot)
	{
		echo '<li class="full_info" data-id="'.$dot['ID'].'" data-name="'.$dot['NAME'].'">'.$dot['NAME'].'</li>';
	}
	echo '</ul>';
}	
	exit;
}
?>
 