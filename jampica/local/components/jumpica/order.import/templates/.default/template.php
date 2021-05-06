<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

//CJSCore::Init(array('jquery2'));//Подключаем JQuery
use Bitrix\Main\Localization\Loc;

$pathToAjax = $templateFolder . '/ajax.php';
$pathToAjaxSDV = $templateFolder . '/ajax_sdv.php';
//$this->addExternalJs($templateFolder . '/select2/select2.js');
//$this->addExternalCss($templateFolder . '/select2/select2.css');
$this->addExternalJs($templateFolder . '/multifile/jquery.multifile.js');
$this->addExternalJs($templateFolder . '/math/math.min.js');

//Тип операции
$operationType = ($_GET['type'] == 'edit') ? 'edit' : 'add';

//Редактирование заявки запрещено
if (!in_array($arResult['USER_ORDER']['UF_STATUS']['VALUE_ENUM_ID'], [51, 83]) && $operationType == 'edit') {

    $arResult['ERROR_MESSAGE'] = 'Редактирование заявки № ' . $arResult['USER_ORDER']['UF_ORDER_NAME']['VALUE'] . ' запрещено';

}

//Подтверждение адреса доставки
if (empty($arResult['USER']['UF_VERIFIED_ADDRESS'])) {

    $arResult['ERROR_MESSAGE'] = 'Ваш адрес доставки еще не подтвержден';

}
$gru=get_gru();
/*if($gru=='СВ' || $gru=='ТС')//Костыль. Если надо спрятать цену, то прячем id
	$id_price='<p class="mt-4" style="display: none"><strong>Стоимость:</strong> <span id="total-price">0</span> <i class="fas fa-ruble-sign"></i></p>';
else*/
	$id_price='<p class="mt-4"><strong>Стоимость:</strong> <span id="total-price">0</span> <i class="fas fa-ruble-sign"></i></p>';
?>

<? if(!empty($arResult['ERROR_MESSAGE'])): ?>

    <div class="col-12">
        <div class="errortext text-center">

            <h2>Внимание</h2>

            <? if(is_array($arResult['ERROR_MESSAGE'])): ?>

                <? foreach($arResult['ERROR_MESSAGE'] as $key => $value): ?>

                     <?=implode('<br />', $value)?>

                    <br />

                <? endforeach; ?>

            <? else: ?>

                <?=$arResult['ERROR_MESSAGE']?>

            <? endif; ?>

            <div class="text-center my-4">
                <a href="/zayavka/" class="btn-cart">Вернуться в раздел заявки</a>
            </div>

        </div>
    </div>

<? else: ?>

    <div class="container zayavka-end-content">
        <div class="row">
            
			<? if($operationType == 'edit'): ?>
			
				<div class="col-12 text-center">
					<p>Ваш заказ отредактирован.</p>
					<p>Перейдите в раздел Заказы и отправьте заказ <a href="/zakazy/">№ <?=$arResult['USER']['PERSONAL_CITY']?>_<span class="order-id"></span></a> в обработку менеджеру.</p>
				</div>
			
			<? else: ?>
			
				<div class="col-12 text-center">
					<p>Ваш заказ создан.</p>
					<p>Перейдите в раздел Заказы и отправьте заказ <a href="/zakazy/">№ <?=$arResult['USER']['PERSONAL_CITY']?>_<span class="order-id"></span></a> в обработку менеджеру.</p>
				</div>
			
			<? endif; ?>
			
        </div>
    </div>

    <div class="import-form">
        <div class="container my-5">

            <div class="row">
                <div class="col-12 text-center">

                    <? if($operationType == 'edit'): ?>

                        Пожалуйста, заполните поля для редактирования заявки № <?=$arResult['USER_ORDER']['UF_ORDER_NAME']['VALUE']?>

                    <? else: ?>

                        Пожалуйста, заполните поля для создания новой заявки.

                    <? endif; ?>

                </div>
            </div>

			<form id="import">
				<div class="row">
					<div class="col-12 col-lg-8">

                            <input type="hidden" name="CREATED_BY" value="<?=$arResult['USER']['ID']?>">
                            <input type="hidden" name="LAYOUT_PRICE" value="<?=$arResult['LAYOUT_PRICE']?>">

                            <input type="hidden" name="PATH_TO_AJAX" value="<?= $pathToAjax ?>">
							<input type="hidden" name="TYPE" value="<?=$operationType?>">
							<input type="hidden" name="IBLOCK" value="<?=$arParams['IBLOCK_ID_CATALOG']?>">
                            <input type="hidden" name="ELEMENT" value="<?=$arResult['USER_ORDER']['ID']?>">
                            <input type="hidden" name="DELYVERY_BEFORE" value="<?=$arResult['DELIVERY_LIST']['PROPERTY_SALE_DO_1KG_VALUE']?>">
                            <input type="hidden" name="DELYVERY_AFTER" value="<?=$arResult['DELIVERY_LIST']['PROPERTY_SALE_OT_1KG_VALUE']?>">

                            <input type="hidden" name="PERSONAL_STATE" value="<?=$arResult['USER']['PERSONAL_STATE']?>">
							<input type="hidden" name="PERSONAL_CITY" value="<?=$arResult['USER']['PERSONAL_CITY']?>">
							<input type="hidden" name="PERSONAL_STREET" value="<?=$arResult['USER']['PERSONAL_STREET']?>">
							<input type="hidden" name="NAME" value="<?=$arResult['USER']['NAME']?>">
							<input type="hidden" name="SECOND_NAME" value="<?=$arResult['USER']['SECOND_NAME']?>">
							<input type="hidden" name="LAST_NAME" value="<?=$arResult['USER']['LAST_NAME']?>">
							<input type="hidden" name="PHONE" value="<?=$arResult['USER']['PERSONAL_PHONE']?>">
							<input type="hidden" name="EMAIL" value="<?=$arResult['USER']['EMAIL']?>">
							<input type="hidden" name="NOTIFICATION" value="<?=(!empty($arResult['USER']['UF_NOTIFICATION'])) ? 45 : ''?>">
                            <input type="hidden" name="DIVISION" value="<?=$arResult['USER']['UF_DIVISION']?>">

                            <input type="hidden" name="CRM_COMPANY_ID" value="<?=$arResult['USER']['UF_CRM_COMPANY_ID']?>">
                            <input type="hidden" name="CRM_CONTACT_ID" value="<?=$arResult['USER']['UF_CRM_CONTACT_ID']?>">
                            <input type="hidden" name="CRM_ASSIGNED_ID" value="<?=$arResult['USER']['UF_CRM_ASSIGNED_ID']?>">

                            <div id="price_detail"></div>
                            <div id="processing_detail"></div>
                            <div id="pvh_detail"></div>

                            <input type="hidden" name="PRICE" value="">
                            <input type="hidden" name="DELIVERY_PRICE" value="">
                            <input type="hidden" name="DELIVERY_PLACE" value="">
                            <input type="hidden" name="DELIVERY_TYPE" value="">

                            <input type="hidden" name="MATERIAL_VOLUME" value="">
                            <input type="hidden" name="MATERIAL_WEIGHT" value="">
                            <input type="hidden" name="VOLUME_WEIGHT" value="">

                            <input type="hidden" name="SUPERVISOR_SURNAME" value="<?=$arResult['USER_ORDER']['UF_SUPER_SURNAME']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_NAME" value="<?=$arResult['USER_ORDER']['UF_SUPER_NAME']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_LASTNAME" value="<?=$arResult['USER_ORDER']['UF_SUPER_LASTNAME']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_EMAIL" value="<?=$arResult['USER_ORDER']['UF_SUPER_EMAIL']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_PHONE" value="<?=$arResult['USER_ORDER']['UF_SUPER_PHONE']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_USER_ID" value="<?=$arResult['USER_ORDER']['UF_SUPER_USER_ID']['VALUE']?>">
                            <input type="hidden" name="SUPERVISOR_CONTACT_ID" value="<?=$arResult['USER_ORDER']['UF_SUPER_CRM_ID']['VALUE']?>">

                            <p class="mt-4">
                                <? if(!empty($_SESSION['MATRIX']['REFERRAL_INFO']) && $operationType == 'add'): ?>

                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelectUser">Выберите пользователя, для которого будет создана заявка</label>
                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectUser">
                                            <option value="">Выберите пользователя, для которого будет создана заявка</option>
                                            <? foreach ($_SESSION['MATRIX']['REFERRAL_INFO'] as $value): ?>
                                                <option value="<?=$value['ID']?>" <?=($value['ID'] == $_GET['user']) ? 'selected' : ''?>><?=$value['LAST_NAME']?> <?=$value['NAME']?> <?=$value['SECOND_NAME']?> (<?=$value['EMAIL']?>)</option>
                                            <? endforeach; ?>
                                        </select>

                                <? endif; ?>
                            </p>

							<div class="form-zayavka">

                                <? if(!empty($arResult['USER_BASKET']) || !empty($arResult['USER_ORDER'])):

                                    //Редактирование заявки

                                    ?>

                                    <? if(!empty($arResult['USER_ORDER']['ID'])): ?>

                                        <? foreach($arResult['USER_ORDER']['UF_MATERIAL']['VALUE'] as $key => $item): ?>

                                            <div class="form-zayavka-new d-block p-4">
                                                <div class="form-row align-items-center">

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите материал</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectMaterial" name="MATERIAL[]" required>
                                                            <option value="">Выберите материал</option>
                                                            <? foreach ($arResult['MATERIAL_LIST'] as $value): ?>
                                                                <option value="<?=$value['NAME']?>" <?=($value['NAME'] == $arResult['USER_ORDER']['UF_MATERIAL']['VALUE'][$key]) ? 'selected' : ''?> data-pvh="<?=(!empty($value['PROPERTY_MATERIAL_PVH_VALUE'])) ? 'Y' : 'N'?>" data-obrabotka='<?=json_encode($value['PROPERTY_OBRABOTKAMATERIALA_VALUE'], true)?>' data-imidg='<?=json_encode($value['PROPERTY_IMIDG_VALUE'], true)?>' data-price="<?=str_replace(',', '.', $value['PROPERTY_SALE_VALUE'])?>" data-yardage="<?=str_replace(',', '.', $value['PROPERTY_YARDAGE_VALUE'])?>" data-wmin="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MIN_VALUE'])?>" data-wmax="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MAX_VALUE'])?>" data-hmin="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MIN_VALUE'])?>" data-hmax="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MAX_VALUE'])?>"><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4 processing-hidden1">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите способ обработки</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectProcessing" name="PROCESSING[]" multiple required>
                                                            <!--<option value="">Выберите способ обработки</option>-->
															
                                                            <? foreach ($arResult['PROCESSING_LIST'] as $value):

                                                                $arProcessing = explode('###', $arResult['USER_ORDER']['UF_PROCESSING']['VALUE'][$key]);

                                                                if (in_array($value['NAME'], $arProcessing)) {

                                                                    $selected = 'selected';

                                                                } else {

                                                                    $selected = '';

                                                                }

                                                                ?>
                                                                <option data-id="<?=$value['ID']?>" value="<?=$value['NAME']?>" <?=$selected?> data-yardagedop="<?=str_replace(',', '.', $value['PROPERTY_UF_YARDAGE_VALUE'])?>" data-price="<?=str_replace(',', '.', $value['PROPERTY_CENA_VALUE'])?>" data-formula="<?=str_replace(' ', '', str_replace(',', '.', $value['PROPERTY_UF_FORMULA_CALC_VALUE']))?>"><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4">

                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите код точки</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPointcode" name="POINTCODE[]" required>
                                                            <option value="">Выберите код точки</option>
                                                            <? foreach ($arResult['POINTCODE_LIST'] as $value):

                                                                $valueName = $value['ID'];

                                                                if (in_array($arResult['USER_ORDER']['UF_POINT_CODE']['VALUE'][$key], [$value['ID'], $value['NAME']])) {

                                                                    $selected = 'selected';

                                                                } else {

                                                                    $selected = '';

                                                                }

                                                                ?>
                                                                <option value="<?=$valueName?>" <?=$selected?>><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>
												
													<div class="col-sm-12 delimiter"></div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите имидж</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidg" name="IMIDG">
                                                            <option value="">Выберите имидж</option>
                                                            <? foreach ($arResult['IMIDG_LIST'] as $value):

                                                                if ($value['ID'] == $arResult['USER_ORDER']['UF_IMIDG']['VALUE'][$key]) {

                                                                    $selected = 'selected';
                                                                    $imidg = '<a href="' . $value['SRC'] . '" data-fancybox="gallery"><img src="' . $value['SRC'] . '" class="imidg"></a>';

                                                                } else {

                                                                    $selected = '';

                                                                }

                                                                ?>
                                                                <option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>" <?=$selected?>><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

													<div class="col-12 col-md-6 mb-4">
														<label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите партнерский имидж</label>
														<select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidgPartner" name="IMIDG_PARTNER">
															<option value="">Выберите партнерский имидж</option>
															<? foreach ($arResult['IMIDG_PARTNER_LIST'] as $value): 
															
                                                                if ($value['ID'] == $arResult['USER_ORDER']['UF_IMIDG_PARTNER']['VALUE'][$key]) {

                                                                    $selected = 'selected';
                                                                    $imidgPartner = '<a href="' . $value['SRC'] . '" data-fancybox="gallery"><img src="' . $value['SRC'] . '" class="imidg"></a>';

                                                                } else {

                                                                    $selected = '';

                                                                }
															
															?>
																<option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>" <?=$selected?>><?=$value['NAME']?></option>
															<? endforeach; ?>
														</select>
													</div>
													
                                                    <div class="col-6 col-md-6 img-imidg" id="img-imidg"><?=$imidg?></div>
													<div class="col-6 col-md-6 img-imidg-partner" id="img-imidg-partner"><?=$imidgPartner?></div>

													<div class="col-sm-12 delimiter"></div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="sr-only" for="inlineFormInputGroup">Высота max: <span class="h-max">2000</span> мм</label>
                                                        <div class="input-group mb-2 zayavka-form-input">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text text-left">Высота<br/>max: <span class="h-max">2000</span> мм</div>
                                                            </div>
                                                            <input name="HEIGHT[]" type="number" value="<?=$arResult['USER_ORDER']['UF_HEIGHT']['VALUE'][$key]?>" class="form-control" id="inlineFormInputGroup" placeholder="2000" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="sr-only" for="inlineFormInputGroup">Ширина max: <span class="w-max">2000</span> мм</label>
                                                        <div class="input-group mb-2 zayavka-form-input">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text text-left">Ширина<br/>max: <span class="w-max">2000</span> мм</div>
                                                            </div>
                                                            <input name="WIDTH[]" type="number" value="<?=$arResult['USER_ORDER']['UF_WIDTH']['VALUE'][$key]?>" class="form-control" id="inlineFormInputGroup" placeholder="2000" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4 error-min-max"></div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="sr-only" for="inlineFormInput">Количество изделий, шт.</label>
                                                        <input name="COUNT[]" type="text" value="<?=$arResult['USER_ORDER']['UF_AMOUNT']['VALUE'][$key]?>" class="form-control mb-2" id="inlineFormInput" placeholder="Количество изделий, шт." required>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="sr-only" for="inlineFormInput">Количество макетов, шт.</label>
                                                        <input name="COUNT_LAYOUTS[]" type="text" value="<?=$arResult['USER_ORDER']['UF_AMOUNT_LAYOUTS']['VALUE'][$key]?>" class="form-control mb-2" id="inlineFormInput" placeholder="Количество макетов, шт." required>
                                                    </div>

                                                    <div class="col-12 col-md-12 markirovka-toltip" title="<?= Loc::getMessage('MARKIROVKA_TOLTIP') ?>">
                                                        <label class="sr-only" for="inlineFormInput">Маркировка</label>
                                                        <div class="markirovka-info">В этом поле Вы можете указать данные, которые будут отражены на маркировке материалов</div>
                                                        <input name="MATERIAL_MARKIROVKA[]" type="text" value="<?=$arResult['USER_ORDER']['UF_MARKIROVKA']['VALUE'][$key]?>" class="form-control mb-2" id="inlineFormInput" placeholder="Маркировка" required>
                                                    </div>

                                                    <div class="col-12 col-md-12 material-comment">
                                                        <textarea name="MATERIAL_COMMENT" class="form-control" rows="5" placeholder="<?= Loc::getMessage('MATERIAL_PLACEHOLDER') ?>" required><?=$arResult['USER_ORDER']['UF_MATERIAL_COMMENT']['VALUE'][$key]?></textarea>
                                                    </div>

                                                    <div class="col-12 col-md-12 material-file">
                                                        <a href="#" class="btn-cart add-multifile-material">Добавить файл к материалу</a>
                                                        <input class="multifile-material" type="file" name="MATERIAL_FILES[]">

                                                        <? if($operationType == 'edit'): ?>

                                                            <div class="multifile_container_delete">

                                                                <? foreach($arResult['USER_ORDER']['UF_MATERIAL_FILES']['VALUE'] as $mKey => $mValue):

                                                                    $fileDescription = explode('###', $arResult['USER_ORDER']['UF_MATERIAL_FILES']['DESCRIPTION'][$mKey]);

                                                                    //Уникальный префикс файла
                                                                    //$uniqueFilePrefix = md5($arResult['USER_ORDER']['UF_MATERIAL']['VALUE'][$key] . '_' . $arResult['USER_ORDER']['UF_PROCESSING']['VALUE'][$key] . '_' . $arResult['USER_ORDER']['UF_WIDTH']['VALUE'][$key] . '_' . $arResult['USER_ORDER']['UF_HEIGHT']['VALUE'][$key] . '_' . $arResult['USER_ORDER']['UF_AMOUNT']['VALUE'][$key] . '_' . $arResult['USER_ORDER']['UF_PRICE_DETAIL']['VALUE'][$key]);
																	$uniqueFilePrefix = md5($arResult['USER_ORDER']['UF_MATERIAL']['VALUE'][$key]);

                                                                    if ($key > 0) {
                                                                        $uniqueFilePrefix = $uniqueFilePrefix.$key;
                                                                    }

																	//Ссылка на файл
                                                                    $arFileInfo = \CFile::GetFileArray($arResult['USER_ORDER']['UF_MATERIAL_FILES']['VALUE'][$mKey]);

                                                                    ?>

                                                                    <? if($fileDescription[0] == $uniqueFilePrefix): ?>

                                                                        <p class="uploaded_image">
                                                                            <a href="#" class="multifile_remove_input" id="<?=$arResult['USER_ORDER']['UF_MATERIAL_FILES']['VALUE'][$mKey]?>">x</a>
                                                                            <span class="filename"><a href="<?=$arFileInfo['SRC']?>" target="_blank"><?=$fileDescription[1]?></a></span>
                                                                        </p>

                                                                    <? endif; ?>

                                                                <? endforeach; ?>

                                                            </div>

                                                        <? endif; ?>

                                                    </div>

                                                    <i class="fas fa-minus border-gyrey delete-material <?=(count($arResult['USER_ORDER']['UF_MATERIAL']['VALUE']) > 1) ? 'active' : ''?>"></i>

                                                </div>
                                            </div>

                                        <? endforeach; ?>

                                    <? else: ?>

                                        <? foreach($arResult['USER_BASKET'] as $item):

                                            //Создание заявки из материалов корзины

                                            ?>

                                            <div class="form-zayavka-new d-block p-4">
                                                <div class="form-row align-items-center">

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите материал</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectMaterial" name="MATERIAL[]" required>
                                                            <option value="">Выберите материал</option>
                                                            <? foreach ($arResult['MATERIAL_LIST'] as $value): ?>
                                                                <option value="<?=$value['NAME']?>" <?=($value['ID'] == $item) ? 'selected' : ''?> data-pvh="<?=(!empty($value['PROPERTY_MATERIAL_PVH_VALUE'])) ? 'Y' : 'N'?>" data-obrabotka='<?=json_encode($value['PROPERTY_OBRABOTKAMATERIALA_VALUE'], true)?>' data-imidg='<?=json_encode($value['PROPERTY_IMIDG_VALUE'], true)?>' data-price="<?=str_replace(',', '.', $value['PROPERTY_SALE_VALUE'])?>" data-yardage="<?=str_replace(',', '.', $value['PROPERTY_YARDAGE_VALUE'])?>" data-wmin="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MIN_VALUE'])?>" data-wmax="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MAX_VALUE'])?>" data-hmin="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MIN_VALUE'])?>" data-hmax="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MAX_VALUE'])?>"><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4 processing-hidden1">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите способ обработки</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectProcessing" name="PROCESSING[]" multiple required>
                                                            <!--<option value="">Выберите способ обработки</option>-->
                                                            <? foreach ($arResult['PROCESSING_LIST'] as $value): ?>
                                                                <option data-id="<?=$value['ID']?>" value="<?=$value['NAME']?>" data-yardagedop="<?=str_replace(',', '.', $value['PROPERTY_UF_YARDAGE_VALUE'])?>" data-price="<?=str_replace(',', '.', $value['PROPERTY_CENA_VALUE'])?>" data-formula="<?=str_replace(' ', '', str_replace(',', '.', $value['PROPERTY_UF_FORMULA_CALC_VALUE']))?>"><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите код точки</label>
                                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPointcode" name="POINTCODE[]" required>
                                                            <option value="">Выберите код точки</option>
                                                            <? foreach ($arResult['POINTCODE_LIST'] as $value):

                                                                $valueName = $value['ID'];

                                                                ?>
                                                                <option  data-id="<?=$valueName?>" value="<?=$valueName?>"><?=$value['NAME']?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 delimiter"></div>

													<div class="col-12 col-md-6 mb-4">
														<label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите имидж</label>
														<select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidg" name="IMIDG">
															<option value="">Выберите имидж</option>
															<? foreach ($arResult['IMIDG_LIST'] as $value): ?>
																<option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
															<? endforeach; ?>
														</select>
													</div>

													<div class="col-12 col-md-6 mb-4">
														<label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите партнерский имидж</label>
														<select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidgPartner" name="IMIDG_PARTNER">
															<option value="">Выберите партнерский имидж</option>
															<? foreach ($arResult['IMIDG_PARTNER_LIST'] as $value): ?>
																<option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
															<? endforeach; ?>
														</select>
													</div>
													
													<div class="col-6 col-md-6 img-imidg" id="img-imidg"></div>
													<div class="col-6 col-md-6 img-imidg-partner" id="img-imidg-partner"></div>

													<div class="col-sm-12 delimiter"></div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="sr-only" for="inlineFormInputGroup">Высота max: <span class="h-max">2000</span> мм</label>
                                                        <div class="input-group mb-2 zayavka-form-input">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text text-left">Высота<br/>max: <span class="h-max">2000</span> мм</div>
                                                            </div>
                                                            <input name="HEIGHT[]" type="number" class="form-control" id="inlineFormInputGroup" placeholder="2000" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 mb-4">
                                                        <label class="sr-only" for="inlineFormInputGroup">Ширина max: <span class="w-max">2000</span> мм</label>
                                                        <div class="input-group mb-2 zayavka-form-input">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text text-left">Ширина<br/>max: <span class="w-max">2000</span> мм</div>
                                                            </div>
                                                            <input name="WIDTH[]" type="number" class="form-control" id="inlineFormInputGroup" placeholder="2000" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4 error-min-max"></div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="sr-only" for="inlineFormInput">Количество изделий, шт.</label>
                                                        <input name="COUNT[]" type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Количество изделий, шт." required>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="sr-only" for="inlineFormInput">Количество макетов, шт.</label>
                                                        <input name="COUNT_LAYOUTS[]" type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Количество макетов, шт." required>
                                                    </div>

                                                    <div class="col-12 col-md-12 markirovka-toltip" title="<?= Loc::getMessage('MARKIROVKA_TOLTIP') ?>">
                                                        <label class="sr-only" for="inlineFormInput">Маркировка</label>
                                                        <div class="markirovka-info">В этом поле Вы можете указать данные, которые будут отражены на маркировке материалов</div>
                                                        <input name="MATERIAL_MARKIROVKA[]" type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Маркировка" required>
                                                    </div>

                                                    <div class="col-12 col-md-12 material-comment">
                                                        <textarea name="MATERIAL_COMMENT" class="form-control" rows="5" placeholder="<?= Loc::getMessage('MATERIAL_PLACEHOLDER') ?>" required></textarea>
                                                    </div>

                                                    <div class="col-12 col-md-12 material-file">
                                                        <a href="#" class="btn-cart add-multifile-material">Добавить файл к материалу</a>
                                                        <input class="multifile-material" type="file" name="MATERIAL_FILES[]">
                                                    </div>

                                                    <i id="<?=$item?>" class="fas fa-minus border-gyrey delete-material <?=(count($arResult['USER_BASKET']) > 1) ? 'active' : ''?>"></i>

                                                </div>
                                            </div>

                                        <? endforeach; ?>

                                    <? endif; ?>

                                <? else:

                                    //Создание новой заявки

                                    ?>

                                    <div class="form-zayavka-new d-block p-4">
                                        <div class="form-row align-items-center">

                                            <div class="col-12 col-md-6 mb-4">
                                               <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите материал</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectMaterial" name="MATERIAL[]" >
                                                    <option value="">Выберите материал</option>
                                                    <? foreach ($arResult['MATERIAL_LIST'] as $value): ?>
                                                        <option value="<?=$value['NAME']?>" data-pvh="<?=(!empty($value['PROPERTY_MATERIAL_PVH_VALUE'])) ? 'Y' : 'N'?>" data-obrabotka='<?=json_encode($value['PROPERTY_OBRABOTKAMATERIALA_VALUE'], true)?>' data-imidg='<?=json_encode($value['PROPERTY_IMIDG_VALUE'], true)?>' data-price="<?=str_replace(',', '.', $value['PROPERTY_SALE_VALUE'])?>" data-yardage="<?=str_replace(',', '.', $value['PROPERTY_YARDAGE_VALUE'])?>" data-wmin="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MIN_VALUE'])?>" data-wmax="<?=str_replace(',', '.', $value['PROPERTY_WIDTH_MAX_VALUE'])?>" data-hmin="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MIN_VALUE'])?>" data-hmax="<?=str_replace(',', '.', $value['PROPERTY_HEIGHT_MAX_VALUE'])?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6 mb-4 processing-hidden1">
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите способ обработки</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectProcessing" name="PROCESSING[]" multiple >
                                                    <!--<option value="">Выберите способ обработки</option>-->
                                                    <? foreach ($arResult['PROCESSING_LIST'] as $value): ?>
                                                        <option data-id="<?=$value['ID']?>" value="<?=$value['NAME']?>" data-yardagedop="<?=str_replace(',', '.', $value['PROPERTY_UF_YARDAGE_VALUE'])?>" data-price="<?=str_replace(',', '.', $value['PROPERTY_CENA_VALUE'])?>" data-formula="<?=str_replace(' ', '', str_replace(',', '.', $value['PROPERTY_UF_FORMULA_CALC_VALUE']))?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6 mb-4">

														       	<div id="the_end_dot"><input id="text_dot" type="text" class="form-control" placeholder="Введите код точки" name="POINTCODE[]"  value=""></div>
																	 <div id="result"></div>  
<script>
		$('#text_dot').bind('input', function(){ 
			if($(this).val().length >2)
				{
			$.ajax({  
				url: '<?php echo $pathToAjaxSDV; ?>', 
				method: 'post',
				data: {text_dot: $('#text_dot').val()},

						success: function(html){  
						$("#result").html(html);
		
						} 
			});
				}
		});															
</script> 												
<!--
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите код точки</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPointcode" name="POINTCODE[]" required>
                                                    <option value="">Выберите код точки</option>
                                                    <? foreach ($arResult['POINTCODE_LIST'] as $value):

                                                        $valueName = $value['ID'];

                                                        ?>
                                                        <option data-id="<?=$valueName?>" value="<?=$valueName?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
-->
                                            </div>

                                                    <div class="col-12 col-md-6 mb-4">

                                                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Введите город</label>
                                                        <input name="" type="text" value="" class="form-control mb-2" id="" placeholder="Введите город" required>
                                                    </div>
														
											
											<div class="col-sm-12 delimiter"></div>

                                            <div class="col-12 col-md-6 mb-4">
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите имидж</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidg" name="IMIDG">
                                                    <option value="">Выберите имидж</option>
                                                    <? foreach ($arResult['IMIDG_LIST'] as $value): ?>
                                                        <option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-6 mb-4">
											<div >
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите партнерский имидж</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidgPartner" name="IMIDG_PARTNER">
                                                    <option value="">Выберите партнерский имидж</option>
                                                    <? foreach ($arResult['IMIDG_PARTNER_LIST'] as $value): ?>
                                                        <option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите партнерский имидж</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidgPartner" name="IMIDG_PARTNER">
                                                    <option value="">Выберите партнерский имидж</option>
                                                    <? foreach ($arResult['IMIDG_PARTNER_LIST'] as $value): ?>
                                                        <option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>	
                                            <div>
                                                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Выберите партнерский имидж</label>
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelectImidgPartner" name="IMIDG_PARTNER">
                                                    <option value="">Выберите партнерский имидж</option>
                                                    <? foreach ($arResult['IMIDG_PARTNER_LIST'] as $value): ?>
                                                        <option value="<?=$value['ID']?>" data-id="<?=$value['PREVIEW_PICTURE']?>" data-src="<?=$value['SRC']?>"><?=$value['NAME']?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>													
											</div>
                                            <div class="col-6 col-md-6 img-imidg" id="img-imidg"></div>
                                            <div class="col-6 col-md-6 img-imidg-partner" id="img-imidg-partner"></div>

                                            <div class="col-sm-12 delimiter"></div>

                                            <div class="col-12 col-md-6 mb-4">
                                                <label class="sr-only" for="inlineFormInputGroup">Высота max: <span class="h-max">2000</span> мм</label>
                                                <div class="input-group mb-2 zayavka-form-input">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text text-left">Высота<br/>max: <span class="h-max">2000</span> мм</div>
                                                    </div>
                                                    <input name="HEIGHT[]" type="number" class="form-control" id="inlineFormInputGroup" placeholder="2000" >
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 mb-4">
                                                <label class="sr-only" for="inlineFormInputGroup">Ширина max: <span class="w-max">2000</span> мм</label>
                                                <div class="input-group mb-2 zayavka-form-input">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text text-left">Ширина<br/>max: <span class="w-max">2000</span> мм</div>
                                                    </div>
                                                    <input name="WIDTH[]" type="number" class="form-control" id="inlineFormInputGroup" placeholder="2000" >
                                                </div>
                                            </div>

                                            <div class="col-12 mb-4 error-min-max"></div>

                                            <div class="col-12 col-md-6">
                                                <label class="sr-only" for="inlineFormInput">Количество изделий, шт.</label>
                                                <input name="COUNT[]" type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Количество изделий, шт." >
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="sr-only" for="inlineFormInput">Количество макетов, шт.</label>
                                                <input name="COUNT_LAYOUTS[]" type="text" class="form-control mb-2" id="inlineFormInput" placeholder="Количество макетов, шт." >
                                            </div>

                                            <div class="col-12 col-md-12 markirovka-toltip" title="<?= Loc::getMessage('MARKIROVKA_TOLTIP') ?>">
                                                <label class="sr-only" for="inlineFormInput">Маркировка</label>
                                                <div class="markirovka-info">В этом поле Вы можете указать данные, которые будут отражены на маркировке материалов</div>
                               						<input name="MATERIAL_MARKIROVKA[]" type="text" value="<?=$arResult['USER_ORDER']['UF_MARKIROVKA']['VALUE'][$key]?>"  id="inlineFormInput" placeholder="Маркировка" >
													<input name="MATERIAL_MARKIROVKA[]1" type="text" value="<?=$arResult['USER_ORDER']['UF_MARKIROVKA']['VALUE'][$key]?>"  id="inlineFormInput" placeholder="Маркировка" >
                                                
                                            </div>

                                            <div class="col-12 col-md-12 material-comment">
                                                <textarea name="MATERIAL_COMMENT" class="form-control" rows="5" placeholder="<?= Loc::getMessage('MATERIAL_PLACEHOLDER') ?>" ></textarea>
                                            </div>

                                            <div class="col-12 col-md-12 material-file">
                                                <a href="#" class="btn-cart add-multifile-material">Добавить файл к материалу</a>
                                                <input class="multifile-material" type="file" name="MATERIAL_FILES[]">
                                            </div>

                                            <i class="fas fa-minus border-gyrey delete-material"></i>

                                        </div>
                                    </div>

                                <? endif; ?>

							</div>

							<p class="mt-4">Добавить материал в заказ <i class="fas fa-plus border-gyrey new-material"></i></p>
							<p class="mt-4">Адрес доставки: <?=$arResult['USER']['UF_DIVISION']?>, <?=$arResult['USER']['PERSONAL_STATE']?>, <?=$arResult['USER']['PERSONAL_CITY']?>, <?=$arResult['USER']['PERSONAL_STREET']?></p>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-4">
                                    <label class="mr-sm-2 sr-only" for="inlineFormCustomSelectDop">Дополнительные контактные лица</label>
                                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelectDopUser" name="DOP_USER">
                                        <option value="">Дополнительные контактные лица</option>
                                        <? foreach ($arResult['USER']['UF_DOP_FIO'] as $value): ?>
                                            <option value="<?=$value?>" <?=($value == $arResult['USER_ORDER']['UF_DOP_USER']['VALUE']) ? 'selected' : ''?>><?=$value?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-4">
                                    <label class="mr-sm-2 sr-only" for="inlineFormCustomSelectDop">Дополнительные контактные телефоны</label>
                                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelectDopPhone" name="DOP_PHONE">
                                        <option value="">Дополнительные контактные телефоны</option>
                                        <? foreach ($arResult['USER']['UF_DOP_TEL'] as $value): ?>
                                            <option value="<?=$value?>" <?=($value == $arResult['USER_ORDER']['UF_DOP_PHONE']['VALUE']) ? 'selected' : ''?>><?=$value?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <? if(!in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['supervisor', 'controller'])): ?>
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-4">
                                        <label class="mr-sm-12 sr-only" for="inlineFormCustomSelectDop">Выберите супервайзера</label>
                                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelectSupervisor" name="DOP_SUPERVISOR" >
                                            <option value="">Выберите супервайзера</option>
                                            <? foreach ($arResult['SUPERVISOR_LIST'] as $value): ?>
                                                <? if(!empty($value['UF_CRM_CONTACT_ID'])): ?>
                                                    <option value="<?=$value['ID']?>" <?=($value['ID'] == $arResult['USER_ORDER']['UF_SUPER_USER_ID']['VALUE']) ? 'selected' : ''?> data-id="<?=$value['ID']?>" data-contact="<?=$value['UF_CRM_CONTACT_ID']?>" data-email="<?=$value['EMAIL']?>" data-phone="<?=$value['PERSONAL_PHONE']?>" data-surname="<?=$value['LAST_NAME']?>" data-name="<?=$value['NAME']?>" data-lastname="<?=$value['SECOND_NAME']?>"><?=$value['LAST_NAME']?> <?=$value['NAME']?> <?=$value['SECOND_NAME']?></option>
                                                <? endif; ?>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <? endif; ?>

							<div class="form-group">
								
								<textarea name="MESSAGE" class="" id="exampleFormControlTextarea1" rows="5" cols="50" placeholder="Оставьте свой комментарий к заказу" ><?=$arResult['USER_ORDER']['UF_COMMENT']['VALUE']?></textarea>
								<input type="checkbox" name="ssn_reverse" value="yes" id="">Фотопривязка
							</div>

							<div class="import-result"></div>

                        <? if($operationType == 'edit'): ?>

                            <input type="submit" class="btn-cart float-right" value="Редактировать">

                        <? else: ?>

                            <input type="submit" class="btn-cart float-right" value="Создать">

                        <? endif; ?>

					</div>

					<div class="col-12 col-lg-4 mt-5 pt-4">
						
						<div class="d-block">

                            <a href="/katalog-materialov/" target="blank" class="orange d-flex align-items-center">
								<i class="fab fa-slack fa-2x mr-3 text-dark text-gradient"></i> Каталог материалов
							</a>

<!--                            <p class="mt-4"><strong>Стоимость:</strong> <span id="total-price">0</span> <i class="fas fa-ruble-sign"></i></p>-->
<?php echo $id_price ?>
                            <p class="">(стоимость является ориентировочной)</p>

                            <div class="file-block">

                                <span class="d-block my-4 add-file">
                                    <a href="#" class="orange d-flex align-items-center">
                                        <i class="fas fa-file-upload fa-2x text-dark mr-4 text-gradient"></i> Добавить файл
                                    </a>
                                </span>

                                <input class="multifile" type="file" name="files[]">

                                <? if($operationType == 'edit'): ?>

                                    <div class="multifile_container_delete">

                                        <? foreach($arResult['USER_ORDER']['UF_FILES']['VALUE'] as $key => $item):

                                            //Ссылка на файл
                                            $arFileInfo = \CFile::GetFileArray($arResult['USER_ORDER']['UF_FILES']['VALUE'][$key]);

                                            ?>

                                            <p class="uploaded_image">
                                                <a href="#" class="multifile_remove_input" id="<?=$arResult['USER_ORDER']['UF_FILES']['VALUE'][$key]?>">x</a>
                                                <span class="filename"><a href="<?=$arFileInfo['SRC']?>" target="_blank"><?=$arResult['USER_ORDER']['UF_FILES']['DESCRIPTION'][$key]?></a></span>
                                            </p>

                                        <? endforeach; ?>

                                    </div>

                                <? endif; ?>

                            </div>

						</div>
						
					</div>

				</div>
			</form>
			
        </div>
    </div>

<? endif; ?>