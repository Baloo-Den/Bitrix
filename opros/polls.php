<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опросы жителей");
//if (CModule::IncludeModule("vote")) require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/vote/include.php");
if($_GET['id'])
{

	if (CModule::IncludeModule("vote"))
	{
	   $db_res = GetVoteList("");
		//dump($db_res); exit;
	   if (!!$db_res)
	   {
			while ($arVote = $db_res->Fetch()) 
			{
				/*echo $arVote["TITLE"];
				echo $arVote["ID"];
				echo '<BR>';*/
				if($_GET['id']=='projects' && $arVote["LAMP"]=='green')//Если запрос только на активные опросы
					$arResult["VOTES"][]=$arVote;
				if($_GET['id']=='all' )// 
					$arResult["VOTES"][]=$arVote;
				if($_GET['id']=='end' && $arVote["LAMP"]=='red')//Выбираем завершенные
					$arResult["VOTES"][]=$arVote;
				if($_GET['id']=='filter')// Поиск по фильтру
				{
					if(empty($_GET['date1']))//Если начальная дата не пустая
					{
						$_GET['date1']='1970-01-01';
						//if (strtotime($_GET['date1'])<strtotime($arVote["DATE_START"]))
							//$arResult["VOTES"][]=$arVote;

					}
					if(empty($_GET['date2']))//
					{
						$_GET['date2']='2970-01-01';
						//if (strtotime($_GET['date2'])>strtotime($arVote["DATE_END"]))
							//$arResult["VOTES"][]=$arVote;
					}
					if (!empty($_GET['name_pull']))//Если не пустое слово для поиска
					{
						
						if((mb_stristr($arVote["TITLE"], $_GET['name_pull']) !== FALSE) && strtotime($_GET['date1'])<=strtotime($arVote["DATE_START"]) && strtotime($_GET['date2'])>=strtotime($arVote["DATE_END"])) //
							
							$arResult["VOTES"][]=$arVote;
					}
					else
						if(strtotime($_GET['date1'])<=strtotime($arVote["DATE_START"]) && strtotime($_GET['date2'])>=strtotime($arVote["DATE_END"]))
							$arResult["VOTES"][]=$arVote;
				}
			}
	   }	
	}
}
else//Если пусто, то пхаем всё
{
	if (CModule::IncludeModule("vote"))
	{
	   $db_res = GetVoteList("");
		//dump($db_res); exit;
		while ($arVote = $db_res->Fetch())
			$arResult["VOTES"][]=$arVote;
		
	}
}


//dump($arResult["VOTES"]); exit;
 
?>
	<!-- обертка контента -->
	<div class="content container">
		<!-- include нужных компонентов -->

		<!-- Хлебные крошки -->
        <div class="breadcrumbs" itemprop="http://schema.org/breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
	<div id="bx_breadcrumb_0" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
		<a href="/" title="Главная" itemprop="item">
			<span itemprop="name">Главная</span>
		</a>
		<meta itemprop="position" content="1"></div>
	<div id="bx_breadcrumb_1" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
		<span class="sep">/</span>
		<a href="/best/" title="Категория 1" itemprop="item">
			<span itemprop="name">Категория</span>
		</a>
		<meta itemprop="position" content="2"></div>
	<div class="bx-breadcrumb-item">
		<span class="sep">/</span>
		<span>Название</span>
	</div>
	<div style="clear:both"></div>
</div>
		<h1 class="page-title">Опросы жителей</h1>

		<div class="row">
			<div class="col">

				<div class=" custom_tab_item">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item"><a class="nav-link active"  href="?id=all" aria-selected="true">Все опросы</a></li>
						<li class="nav-item"><a class="nav-link" href="?id=projects" aria-selected="false">Активные</a>	</li>
						<li class="nav-item"><a class="nav-link" href="?id=end" aria-selected="false">Завершенные</a>
						</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="meropr" role="tabpanel" aria-labelledby="home-tab">
							<div class="row ">

								<div class="col-12 wrap_block_custom">
									<p class="filtPolls">
										<span class="filtPolls__title mr-4">Фильтр опросов</span>
										<a class="filtPolls__toggle" data-toggle="collapse" href="#collapseExample"
										   aria-expanded="false" aria-controls="collapseExample">
											Свернуть <i class="fa fa-chevron-up"></i>
										</a>
									</p>
									<div class="collapseCust collapse" id="collapseExample">
										<div class="collapseCust__card card card-body">
											<div class="collapseCust collapseCust_input">
												<form action=""  method="get">
													<div class="row align-items-center">
														<div class="col-sm-12 col-md-4 col-lg pr-3 pr-md-2 mb-3 mb-md-0">
															<div class="d-flex">

																<input type="hidden" name="id" value="filter">
																<input class="w-100" type="text" name="name_pull"
																	   placeholder="Название опроса" value="">
															</div>
														</div>
														<div class="col-sm-12 col-md-3 col-lg-3 pr-3 pr-md-2 mb-3 mb-md-0">
															<div class="d-flex">

																<input class="w-100" type="date" name="date1"
																	   value="">
															</div>
														</div>
														<div class="col-sm-12 col-md-4 col-lg-4 ">
															<div class="d-flex align-items-center position-relative">
																<input class="w-100 mr-4" type="date" name="date2"
																	   value="">
																	<button input type="submit" class="btn-m btn-40 px-2 px-lg-0 px-xl-2 text-uppercase font-weight-boldy"><img src="<?=SITE_TEMPLATE_PATH?>/img/icon-search-white.svg"></button>

																
																<a href="#"
																   class="reset_filter position-absolute t-0 r-0 d-flex align-items-center"><img
																			src="<?=SITE_TEMPLATE_PATH?>/img/exit.svg" class="mr-1"> Сбросить
																	фильтры</a>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>		
<?
require_once ("./".SITE_TEMPLATE_PATH."/components/bitrix/voting.list/.default/template.php"); // шаблон показа 
/*{
	$APPLICATION->IncludeComponent(
		"bitrix:voting.list",
		"",
		Array(
			"CHANNEL_SID" => array(),
			"VOTE_FORM_TEMPLATE" => "vote_new.php?VOTE_ID=#VOTE_ID#",
			"VOTE_RESULT_TEMPLATE" => "vote_result.php?VOTE_ID=#VOTE_ID#"
		)
	);
}*/
?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
								
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>