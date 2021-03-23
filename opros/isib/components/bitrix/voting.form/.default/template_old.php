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
<div class="content container">
	<div class="row">
		<div class="col">
 <section class="pt-0">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 mb-5 mb-lg-0">
						<div>
							<div class=" wrap_border px-4 py-5">
								<div class="services-map-container">
<div class="">
<form action="<?=POST_FORM_ACTION_URI?>" method="post" class="">
	<input type="hidden" name="vote" value="Y">
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<?=bitrix_sessid_post()?>
	
<!--<ol class="">-->
<?
	$iCount = 0;
	foreach ($arResult["QUESTIONS"] as $arQuestion):
		$iCount++;
	  

?>
	<li class=" <?=($iCount == 1 ? "vote-item-vote-first " : "")?><?
				?><?=($iCount == count($arResult["QUESTIONS"]) ? "vote-item-vote-last " : "")?><?
				?><?=($iCount%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?><?
				?>">

		<div class="vote-item-header">

<?

?>

	<h5 class="text-dark font-weight-boldy"><?=$arQuestion["QUESTION"]?><?if($arQuestion["REQUIRED"]=="Y"){echo "<span class='starrequired'>*</span>";}?></h5>			
			<div class="vote-clear-float"></div>
		</div>
		
<!--		<ol class="vote-items-list vote-answers-list">-->
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
	</li>
<?
		endforeach
?>
<!--</ol>-->

<? if (isset($arResult["CAPTCHA_CODE"])):  ?>
<div class="vote-item-header">
	<div class="vote-item-title vote-item-question"><?=GetMessage("F_CAPTCHA_TITLE")?></div>
	<div class="vote-clear-float"></div>
</div>
<div class="vote-form-captcha">
	<input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
	<div class="vote-reply-field-captcha-image">
		<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" alt="<?=GetMessage("F_CAPTCHA_TITLE")?>" />
	</div>
	<div class="vote-reply-field-captcha-label">
		<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?><span class='starrequired'>*</span></label><br />
		<input type="text" size="20" name="captcha_word" autocomplete="off" />
	</div>
</div>
<? endif // CAPTCHA_CODE ?>

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
						</div>
					</div>
				</div>
			</div>
 </section>
		</div>
	</div>
</div>