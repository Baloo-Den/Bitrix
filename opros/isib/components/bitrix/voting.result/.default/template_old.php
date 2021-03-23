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
								
<?

$iCount = 0;
foreach ($arResult["QUESTIONS"] as $arQuestion):
	$iCount++;
?>
	<li class=" <?=($iCount == 1 ? "vote-item-vote-first " : "")?><?
				?><?=($iCount == count($arResult["QUESTIONS"]) ? "vote-item-vote-last " : "")?><?
				?><?=($iCount%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?><?
				?>">

			<h5 class="text-dark font-weight-boldy"><?=$arQuestion["QUESTION"]?></h5>

		<div class=" wrap_border px-4 py-5">
			<div class="services-map-container">
<?
		if ($arQuestion["IMAGE"] !== false):
?>
			<p><img class="w-100 brorder_img" src="<?=$arQuestion["IMAGE"]["SRC"]?>" alt=""></p>
<?
		endif;
?>				
										<h6 class="font-weight-600">Результаты голосования:</h6>
										<p class="font_sm">(результаты голосования в % округлены)</p>
										<ul class="poll_wrapper p-0">	
											

<?
	
	foreach ($arQuestion["ANSWERS"] as $arAnswer):
?>
											<li><span class="perc-back" style="width: <?=$arAnswer["PERCENT"]?>%"></span>
												<label for="answer<?=$iCount?>"><?=$arAnswer["MESSAGE"]?></label>
												<span class="perc-number d-flex flex-column"><span><?=$arAnswer["PERCENT"]?>%</span><BR><span><?=$arAnswer["COUNTER"]?>чел</span></span>
											</li>			

<?
	endforeach;
?>
											 </ul>
											
			</div>

	</div>
	</li>
<?
endforeach; 
?>
</div></div></div>
				</section>
				</div></div>
		</div>