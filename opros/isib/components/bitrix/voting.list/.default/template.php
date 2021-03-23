<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if ($arResult["NAV_STRING"] <> ''):
?>
<div class="vote-navigation-box vote-navigation-top">
	<div class="vote-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="vote-clear-float"></div>
</div>
<?
endif;
//dump($arResult);
$i=0;		
if ($APPLICATION->GetCurPage(false) === '/')
	$the_end=3;
	
?>
	<section class="">
		<div class="container">
			<div class="d-sides align-items-start border-bottom">
				<h4>Галерея опросов</h4>
				<a href="polls.php" class="aux-link grey">Все опросы <img src="<?=SITE_TEMPLATE_PATH?>/img/arrow-white.svg"></a>
			</div>
			<div class="row ">
<? foreach ($arResult["VOTES"] as $arVote): ?>	
<?php
				

if ($i==3 && $the_end==3)//Если третий раз и главная прерываем цикл				
	break;
	$i++;			
				//Получаем картинки
				
$rsQuestions = CVoteQuestion::GetList($arVote["ID"], $by, $order, array(), $is_filtered);
if (intval($rsQuestions->SelectedRowsCount())>0)//Если пикча есть
{
	while ($arQuestion = $rsQuestions->Fetch())
	{

		$img=CFile::GetFileArray($arQuestion["IMAGE_ID"]); 
		$pic=$img["SRC"];
	}
}				
else//Ну, а если нет!
	$pic=SITE_TEMPLATE_PATH.'/img/Rectangle_422.jpg';
?>				
				<div class="col-12 col-md-6 col-lg-4 pt-4">
					<div class="card white card_cust_el">
						<div class="card-body h-100 d-flex flex-column justify-content-between p-2">
							<div>
								<a href="#" tabindex="-1">
									<img src="<?=$pic?>"
										 class="card-image mb-2">
								</a>
								<div class="info-row mb-4">
									<a href="/services/sotsiologicheskaya-sluzhba/oprosy"
									   class=" btn-30 text-uppercase btn_cust__blue tag ">Общество</a>

									<a href="/services/sotsiologicheskaya-sluzhba/oprosy"
									   class=" btn-30 cust_bg text-uppercase btn_cust__blue tag ">+10 баллов
										<img src="./img/question.svg" class="ml-1"></a>

								</div>
								<a href="#" tabindex="-1">
									<h5 class="mb-4 text-dark"><?=$arVote["TITLE"];?></h5>
								</a>
								<div class="mb-4">

									<div class="info-row">
										<div class="text-muted">Срок голосования</div>
										<div class="dots"></div>
										<div>с <?=FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('SHORT')), MakeTimeStamp($arVote["DATE_START"]))?> по <?=FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('SHORT')), MakeTimeStamp($arVote["DATE_END"]))?></div>
									</div>
									<div class="info-row">
										<div class="text-muted">Проголосовало</div>
										<div class="dots"></div>
										<div><?=$arVote["COUNTER"]?></div>
									</div>


								</div>
							</div>
							<div class="bt_wrap text-center d-flex justify-content-between">

								<a href="<?
									if ($arVote["VOTE_FORM_URL"])
										echo $arVote["VOTE_FORM_URL"];
									else
										echo 'vote_new.php?VOTE_ID='.$arVote["ID"];
								?>"
								   class="btn-m btn-40 px-2 px-lg-0 px-xl-2 mt-2 text-uppercase font-weight-boldy">
									Участвовать </a>
								<a href="<?
										 	if($arVote["VOTE_RESULT_URL"])
										 		echo $arVote["VOTE_RESULT_URL"];
										 	else
												echo 'vote_result.php?VOTE_ID='.$arVote["ID"];
										 ?>"
								   class="btn-o btn-40 px-2 px-lg-0 px-xl-2 mt-2 text-uppercase font-weight-boldy">
									Результаты </a>

							</div>
						</div>
					</div>
				</div>				
<?
endforeach;
?>				
			</div>	
		</div>
	</section>
<?

if ($arResult["NAV_STRING"] <> ''):
?>
<div class="vote-navigation-box vote-navigation-bottom">
	<div class="vote-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="vote-clear-float"></div>
</div>
<?
endif;
?>