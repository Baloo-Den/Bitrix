<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?
endif;

if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-note">
	<div class="vote-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"])?></div>
</div>
<?
endif;

if (empty($arResult["VOTE"])):
	return false;
elseif (empty($arResult["QUESTIONS"])):
	return true;
endif;

?>
<?
	$iCount = 0;
	foreach ($arResult["QUESTIONS"] as $arQuestion):
		$iCount++;
?>
<h1 class="page-title"><?=$arQuestion["QUESTION"]?></h1>
	<div class="content container">
		<div class="row">
			<div class="col">
				<section class="pt-0">

					<div class="container">
						<div class="row">
							<div class="col-lg-8 mb-5 mb-lg-0">
								<div class="detal_info mb-4 d-flex justify-content-between flex-wrap">
									<div class="d-flex align-items-center">
										<a href="#" class="d-flex btn-30 py-1 text-uppercase btn_cust__blue active tag ">+10
											баллов <img src="<?=SITE_TEMPLATE_PATH?>/img/question_b.svg" class="ml-1"></a>
										<div class="mr-0 mr-sm-3 d-flex"><img src="<?=SITE_TEMPLATE_PATH?>/img/question_b.svg" class="mr-1"><span>Проголосовало: <?=$arResult["QUESTIONS"][1]["COUNTER"]?> человек</span>
										</div>
									</div>
									<div>
										<a href="#" class=" btn-30 px-4 py-1 btn_cust__blue def_blue active tag ">Активный, завершится через 5 дней</a>
									</div>
									<div class="mt-4 w-50 text_sm detal_info__date"><?=FormatDate($DB->DateFormatToPHP("DD F HH:MI"), MakeTimeStamp($arVote["DATE_START"]))?></div>
									<div class="mt-4 w-50 d-flex detal_info__shared justify-content-xl-start justify-content-xl-end align-items-center">
										<span class="pr-4 text_sm">Поделилось: 1 097</span>
										<a href="#" class="pr-3"><img src="<?=SITE_TEMPLATE_PATH?>/img/soc/vk.svg" alt=""></a>
										<a href="#" class="pr-3"><img src="<?=SITE_TEMPLATE_PATH?>/img/soc/instagram.svg" alt=""></a>
										<a href="#" class=""><img src="<?=SITE_TEMPLATE_PATH?>/img/soc/faceb.svg" alt=""></a>
									</div>
								</div>

<form action="<?=POST_FORM_ACTION_URI?>" method="post" class="">
	<input type="hidden" name="vote" value="Y">
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<?=bitrix_sessid_post()?>
								<div class=" wrap_border px-4 py-5">
									<h4>Вопрос 1 из 2</h4>
									<div class="services-map-container">
										<p>Фонд проводит конкурс на выбор социально значимого проекта, который будет
											реализован в городе. Какой, на Ваш взгляд, проект, из представленных ниже,
											достоин реализации в Ханты-Мансийском автономном округе - Югры?</p>
										<p><img class="w-100 brorder_img" src="<?=SITE_TEMPLATE_PATH?>/img/poll_img1.jpg" alt=""></p>
										<p>Фонд проводит конкурс на выбор социально значимого проекта, который будет
											реализован в городе. Какой, на Ваш взгляд, проект, из представленных ниже,
											достоин реализации в Ханты-Мансийском автономном округе - Югры?</p>
										<h5 class="text-dark font-weight-boldy">
											Как повлияет на Вас отключение аналогового телевещания в России?
										</h5>
										<h6 class="font-weight-600">Результаты голосования:</h6>
										<p class="font_sm">Можно выбрать несколько ответов</p>
		<ul class="poll_wrapper p-0">
<?
		$iCountAnswers = 0;
		foreach ($arQuestion["ANSWERS"] as $arAnswer):
			$iCountAnswers++;
?>
			<li class="mr-0 d-flex align-items-center justify-content-between <?=($iCountAnswers == 1 ? " " : "")?><?
						?><?=($iCountAnswers == count($arQuestion["ANSWERS"]) ? " " : "")?><?
						?><?=($iCountAnswers%2 == 1 ? " " : " ")?>">
				
<?
			switch ($arAnswer["FIELD_TYPE"]):
					case 0://radio
						$value=(isset($_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]]) && 
							$_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ? 'checked="checked"' : '';
					break;
					case 1://checkbox
						$value=(isset($_REQUEST['vote_checkbox_'.$arAnswer["QUESTION_ID"]]) && 
							array_search($arAnswer["ID"],$_REQUEST['vote_checkbox_'.$arAnswer["QUESTION_ID"]])!==false) ? 'checked="checked"' : '';
					break;
					case 2://select
						$value=(isset($_REQUEST['vote_dropdown_'.$arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_dropdown_'.$arAnswer["QUESTION_ID"]] : false;
					break;
					case 3://multiselect
						$value=(isset($_REQUEST['vote_multiselect_'.$arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_multiselect_'.$arAnswer["QUESTION_ID"]] : array();
					break;
					case 4://text field
						$value = isset($_REQUEST['vote_field_'.$arAnswer["ID"]]) ? htmlspecialcharsbx($_REQUEST['vote_field_'.$arAnswer["ID"]]) : '';
					break;
					case 5://memo
						$value = isset($_REQUEST['vote_memo_'.$arAnswer["ID"]]) ?  htmlspecialcharsbx($_REQUEST['vote_memo_'.$arAnswer["ID"]]) : '';
					break;
				endswitch;
?>
<?
			switch ($arAnswer["FIELD_TYPE"]):
					case 0://radio
?>
<!--						<span class="">-->
						<label class="" for="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
						<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" <?=$value?> name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>" <?
									?>id="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?
									?>value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
							<label class="custom-control-label" for="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"></label>
						</div>
<!--						</span>-->
<?
					break;
					case 1://checkbox?>
						<span class="vote-answer-item vote-answer-item-checkbox">
							<input <?=$value?> type="checkbox" name="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>[]" value="<?=$arAnswer["ID"]?>" <?
								?> id="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
							<label for="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
						</span>
					<?break?>

					<?case 2://dropdown?>
						<span class="vote-answer-item vote-answer-item-dropdown">
							<select name="vote_dropdown_<?=$arAnswer["QUESTION_ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?>>
								<option value=""><?=GetMessage("VOTE_DROPDOWN_SET")?></option>
							<?foreach ($arAnswer["DROPDOWN"] as $arDropDown):?>
								<option value="<?=$arDropDown["ID"]?>" <?=($arDropDown["ID"] === $value)?'selected="selected"':''?>><?=$arDropDown["MESSAGE"]?></option>
							<?endforeach?>
							</select>
						</span>
					<?break?>

					<?case 3://multiselect?>
						<span class="vote-answer-item vote-answer-item-multiselect">
							<select name="vote_multiselect_<?=$arAnswer["QUESTION_ID"]?>[]" <?=$arAnswer["~FIELD_PARAM"]?> multiple="multiple">
							<?foreach ($arAnswer["MULTISELECT"] as $arMultiSelect):?>
								<option value="<?=$arMultiSelect["ID"]?>" <?=(array_search($arMultiSelect["ID"], $value)!==false)?'selected="selected"':''?>><?=$arMultiSelect["MESSAGE"]?></option>
							<?endforeach?>
							</select>
						</span>
					<?break?>

					<?case 4://text field?>
						<span class="vote-answer-item vote-answer-item-textfield">
							<label for="vote_field_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
							<input type="text" name="vote_field_<?=$arAnswer["ID"]?>" id="vote_field_<?=$arAnswer["ID"]?>" <?
								?>value="<?=$value?>" size="<?=$arAnswer["FIELD_WIDTH"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /></span>
					<?break?>

					<?case 5://memo?>
						<span class="vote-answer-item vote-answer-item-memo">
							<label for="vote_memo_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label><br />
							<textarea name="vote_memo_<?=$arAnswer["ID"]?>" id="vote_memo_<?=$arAnswer["ID"]?>" <?
								?><?=$arAnswer["~FIELD_PARAM"]?> cols="<?=$arAnswer["FIELD_WIDTH"]?>" <?
							?>rows="<?=$arAnswer["FIELD_HEIGHT"]?>"><?=$value?></textarea>
						</span>
					<?break;
				endswitch;
?>
			</li><span class="perc-back w-100" ></span>
<?
			endforeach
?>
<!--		</ol>-->
			</ul>
									</div>
								</div>
	
<?
		endforeach
?>	
<div class="vote-form-box-buttons vote-vote-footer">
	<span class="vote-form-box-button vote-form-box-button-first"><input type="submit" name="vote" value="<?=GetMessage("VOTE_SUBMIT_BUTTON")?>" /></span>
<?/*?>	<span class="vote-form-box-button vote-form-box-button-last"><input type="reset" value="<?=GetMessage("VOTE_RESET")?>" /></span><?*/?>
	<span class="vote-form-box-button vote-form-box-button-last">
		<a name="show_result" <?
			?>href="<?=$arResult["URL"]["RESULT"]?>"><?=GetMessage("VOTE_RESULTS")?></a>
	</span>
</div>
</form>	
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>