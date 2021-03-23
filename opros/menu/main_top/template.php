<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
    <div class="header-main">
        <div class="container">
            <div class="header-main-logo">
                <a href="/">
                    <img src="/img/main_logo.svg" alt="Инициативное бюджетирование Югры"></a>
						</div>
			<div class="header-main-menu d-none d-lg-flex">
			<ul>

				<?foreach($arResult as $arItem):?>
				<li><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></li>
				<?endforeach?>

			</ul>
							<a href="/search/" title="Поиск" class="header-search-main d-md-inline-block d-none">
								<img src="/img/icon-search.svg"></a> 				

			</div>	
			
            <div class="header-main-actions d-md-none">
                <div class="d-inline-flex mobile-menu-toggle  avatar avatar-50">
                    <div><i class="fa fa-navicon"></i>
                    </div>
                </div>

                <div class="mobile-main-menu">
                    <div class="mobile-top-menu">
                        <div class="mobile-menu-toggle mobile-main-menu-close"><i class="fa fa-times"></i>
                        </div>
							<ul>

								<?foreach($arResult as $arItem):?>
								<li><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></li>
								<?endforeach?>

							</ul>						
                    </div>	
                </div>
            </div>					
        </div>
    </div>			
<?endif?>