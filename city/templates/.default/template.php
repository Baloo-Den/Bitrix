<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init(array('jquery2'));
CAjax::Init();
$this->addExternalJS("/local/components/city/script.js");
?>
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModalBox">
  Ваш город:  <span id="city"></span>
</button>
<!-- HTML-код модального окна -->
<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Ваш город</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <span id="modal-body_city"></span>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Да</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_form" id="open_form">
		  Нет
		</button>
      </div>
    </div>
  </div>
</div>
<!-- HTML-код модального окна -->
<div id="modal_form" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Выберите город</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       	<input id="text" type="text" class="form-control" placeholder="Название города русскими или английскими буквами">
		 <div id="result"></div>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
		
      </div>
    </div>
  </div>
</div>
<div>Телефон: <span id="tel"></span>
<br>	
Адрес: <span id="adress"></span>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
	  let city='<?php echo $arResult["city"]; ?>';
	  let tel='<?php echo $arResult["tel"]; ?>';
	  let adress='<?php echo $arResult["adress"]; ?>';
	  let id_block='<?php echo $arParams["IBLOCK_ID"];?>'
</script>
