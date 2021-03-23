<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//var_dump($arResult["QUESTIONS"]);
if (empty($arResult["VOTE"]) || empty($arResult["QUESTIONS"])):
	return true;
endif;
//dump ($arResult["QUESTIONS"]);
?>
<h1 class="page-title"><?=$arResult["QUESTIONS"][1]["QUESTION"]?></h1>
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
<!--Результаты опроса-->
<?php foreach ($arResult["QUESTIONS"] as $arQuestion): ?>								
								<div class=" wrap_border px-4 py-5">
									<h4>Вопрос 1</h4>
									<div class="services-map-container">
										<p>Фонд проводит конкурс на выбор социально значимого проекта, который будет
											реализован в городе. Какой, на Ваш взгляд, проект, из представленных ниже,
											достоин реализации в Ханты-Мансийском автономном округе - Югры?</p>
										<?	if ($arQuestion["IMAGE"] !== false): ?>
													<p><img class="w-100 brorder_img" src="<?=$arQuestion["IMAGE"]["SRC"]?>" alt=""></p>
										<?	endif; 	?>											
										<p>Фонд проводит конкурс на выбор социально значимого проекта, который будет
											реализован в городе. Какой, на Ваш взгляд, проект, из представленных ниже,
											достоин реализации в Ханты-Мансийском автономном округе - Югры?</p>
										<h5 class="text-dark font-weight-boldy">
											<?=$arQuestion["QUESTION"]?>
										</h5>
										<h6 class="font-weight-600">Результаты голосования:</h6>
										<p class="font_sm">(результаты голосования в % округлены)</p>
										<ul class="poll_wrapper p-0">	
											<? 	foreach ($arQuestion["ANSWERS"] as $arAnswer): ?>
											<li><span class="perc-back" style="width: <?=$arAnswer["PERCENT"]?>%"></span>
												<label for="answer<?=$iCount?>"><?=$arAnswer["MESSAGE"]?></label>
												<span class="perc-number d-flex flex-column"><span><?=$arAnswer["PERCENT"]?>%</span><span><?=$arAnswer["COUNTER"]?> чел</span></span>
											</li>			
											<? 	endforeach; ?>
											</ul>
									</div>
								</div>
<?php endforeach; ?>
<!--Конец результатов								-->
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
<!--Другие опросы-->
<?php
	   
$db_res = GetVoteList("");
	while ($arVote = $db_res->Fetch())
	{
		if ($arVote["ID"]!=$arResult["VOTE"]["ID"])//Выбираем другие опросы
		{
			$other_vote["VOTES"][]=$arVote;
		
		}
		
	}
//dump($other_vote["VOTES"]);			
?>
				<div class="mb-5 our_team">
					<section class="pb-5 pt-4">
						<div class="container">
							<div class="d-sides align-items-start border-bottom">
								<h4>Другие опросы</h4>
								<a href="polls.php" class="aux-link grey">Все опросы <img src="/img/arrow-white.svg"></a>
							</div>
							<div class="crowd-slider crowd-slider-main pt-4 margin_slider_row ">
								<? foreach ($other_vote["VOTES"] as $arVote): ?>
<?php
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
								<div class="px-3 pb-4 pt-4">
									<div class="card white card_cust_el">
										<div class="card-body h-100 d-flex flex-column justify-content-between p-2">
											<div>
												<a href="#" tabindex="-1">
													<img src="<?php echo $pic ?>" class="card-image mb-2">
												</a>
												<div class="info-row mb-4">
													<a href="/services/sotsiologicheskaya-sluzhba/oprosy"
													   class=" btn-30 text-uppercase btn_cust__blue tag ">Общество</a>

													<a href="/services/sotsiologicheskaya-sluzhba/oprosy"
													   class=" btn-30 cust_bg text-uppercase btn_cust__blue tag ">+10
														баллов
														<img src="./img/question.svg" class="ml-1"></a>

												</div>
												<a href="#" tabindex="-1">
													<h5 class="mb-4 text-dark"><?=$arVote["TITLE"];?></h5>
												</a>
												<div class="mb-4">

													<div class="info-row">
														<div class="text-muted">Срок голосования</div>
														<div class="dots"></div>
														<div><?=FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('SHORT')), MakeTimeStamp($arVote["DATE_START"]))?> по <?=FormatDate($DB->DateFormatToPHP(CSite::GetDateFormat('SHORT')), MakeTimeStamp($arVote["DATE_END"]))?></div>
													</div>
													<div class="info-row">
														<div class="text-muted">Проголосовало</div>
														<div class="dots"></div>
														<div><?=$arVote["COUNTER"]?></div>
													</div>


												</div>
											</div>
											<div class="bt_wrap text-center d-flex justify-content-between">

												<a href="<?php echo 'vote_new.php?VOTE_ID='.$arVote["ID"]; ?>"
												   class="btn-m btn-40 px-2 px-lg-0 px-xl-2 mt-2 text-uppercase font-weight-boldy">
													Участвовать </a>
												<a href="<?php echo 'vote_result.php?VOTE_ID='.$arVote["ID"]; ?>"
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
				</div>