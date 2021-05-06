<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

//Выгружаем отчет по заявкам
if ($_GET['type'] == 'bx24') {
    LocalRedirect('/ajax/export_order_full.php?id=' . $USER->GetID());
}

?>

<div class="col-12 col-lg-4 filter-block form1">
    <form action="<?= $APPLICATION->GetCurPageParam('', ['status', 'order', 'price_from', 'price_to', 'date_from', 'date_to', 'point_code']) ?>" method="get">

        <div class="form-row align-items-center">
            <div class="col my-1">
                <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Все заявки</label>
                <select class="custom-select mr-sm-2" id="filterStatus" name="status">
                    <option value="">Все заявки</option>
                    <? foreach ($arResult['STATUS_LIST'] as $value): ?>
                        <option value="<?= $value['ID'] ?>" <?= ($_GET['status'] == $value['ID']) ? 'selected' : '' ?>><?= $value['VALUE'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="col my-1">
                <label class="sr-only" for="inlineFormInputName">Номер заявки</label>
                <input type="text" class="form-control" name="order" value="<?= $_GET['order'] ?>" placeholder="Номер заявки">
                <div class="filter-number" id="filterId">
                    <i class="fas fa fa-search text-search"></i>
                </div>
            </div>
        </div>

        <input type="submit" class="filter-block-submit">

    </form>
</div>

<div class="col-12 col-lg-8 text-center text-lg-right pt-2">
    <div class="d-inline type-status"><a href="/zakazy/" class="btn-cart status-list all <?= (empty($_GET['status'])) ? 'active' : '' ?>">Все</a></div>
    <div class="d-inline type-status"><a href="/zakazy/?status=46" class="btn-cart status-list processing <?= ($_GET['status'] == 46) ? 'active' : '' ?>">В обработке</a></div>
    <div class="d-inline type-status"><a href="/zakazy/?status=47" class="btn-cart status-list production <?= ($_GET['status'] == 47) ? 'active' : '' ?>">В производстве</a></div>
    <div class="d-inline type-status"><a href="/zakazy/?status=48" class="btn-cart status-list completed <?= ($_GET['status'] == 48) ? 'active' : '' ?>">Выполнено</a></div>
    <div class="d-inline type-status"><a href="/zakazy/?status=51" class="btn-cart status-list unfinished <?= ($_GET['status'] == 51) ? 'active' : '' ?>">Незавершенные</a></div>
</div>

<div class="col-12 col-lg-4 filter-block form2">
    <form action="<?= $APPLICATION->GetCurPageParam('', ['status', 'order', 'price_from', 'price_to', 'date_from', 'date_to', 'point_code']) ?>" method="get">

        <div class="form-row align-items-center">
            <div class="col my-1">
                <input type="text" class="form-control" name="price_from" value="<?= $_GET['price_from'] ?>" placeholder="Стоимость от">
                <div class="filter-price" id="filterPrice">
                    <i class="fas fa fa-search text-search"></i>
                </div>
            </div>
            <div class="col my-1">
                <input type="text" class="form-control" name="price_to" value="<?= $_GET['price_to'] ?>" placeholder="Стоимость до">
                <div class="filter-price" id="filterPrice">
                    <i class="fas fa fa-search text-search"></i>
                </div>
            </div>
        </div>

        <div class="form-row align-items-center">
            <div class="col my-1">
                <input type="text" class="form-control" name="point_code" value="<?= $_GET['point_code'] ?>" placeholder="Введите код точки">
                <div class="point-code" id="pointCode">
                    <i class="fas fa fa-search text-search"></i>
                </div>
                <div class="point-code-container" id="pointCodeContainer"></div>
            </div>
        </div>

        <input type="submit" class="filter-block-submit">

    </form>
</div>

<div class="col-12 col-lg-8 text-md-right mt-4 date-block">
    <form action="<?= $APPLICATION->GetCurPageParam('', ['status', 'order', 'price_from', 'price_to', 'date_from', 'date_to', 'point_code']) ?>" method="get">

        <div class="date-from">
            <input type="text" class="form-control date" value="<?= $_GET['date_from'] ?>" name="date_from" placeholder="Дата с">
            <i class="fas fa fa-search text-search"></i>
        </div>
        <div class="date-to">
            <input type="text" class="form-control date" value="<?= $_GET['date_to'] ?>" name="date_to" placeholder="Дата по">
            <i class="fas fa fa-search text-search"></i>
        </div>

        <a href="#" id="reportfull" class="btn-cart order-list" data-user="<?= $USER->GetID() ?>" data-report="full">Выгрузить отчет по заявкам</a>

        <? if(in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['general_manager_jampica', 'manager_jampica', 'controller'])): ?>

			<?
			/*
            <a href="#" id="report1" class="btn-cart order-list" data-user="<?= $USER->GetID() ?>" data-report="report1">Отчет ИВ</a>
            <a href="#" id="report2" class="btn-cart order-list" data-user="<?= $USER->GetID() ?>" data-report="report2">Распределение БЕ</a>
			*/
			?>

        <? endif; ?>

    </form>
</div>

</div>
<div class="row">
    <div class="col-12 mt-5">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="">№ заказа:</th>
                    <th scope="col" class="text-center">Статус:</th>
                    <th scope="col" class="text-center">Дата:</th>
                    <th scope="col" class="text-center">Сумма:</th>
                    <th scope="col" class="text-center">Автор:</th>
                    <th scope="col" class="text-center">Действия:</th>
                </tr>
                </thead>
                <tbody>

                <? foreach ($arResult['ITEMS'] as $item): ?>

                    <?php $className = ($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] == 50) ? 'zayavka-delet' : '' ?>

                    <tr id="<?= $item['ID'] ?>">
                        <td class="<?=$className?>"><?=$item['PROPERTIES']['UF_ORDER_NAME']['VALUE']?></td>
                        <td class="text-center <?=$className?>">
                            <? if($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] == 50): ?>
                                <?= $item['PROPERTIES']['UF_STATUS']['VALUE'] ?>
                            <? else: ?>
                                <?= $item['PROPERTIES']['UF_STATUS']['VALUE'] ?>
                            <? endif; ?>
                        </td>
                        <td class="text-center <?=$className?>"><?= $item['DATE_CREATE'] ?></td>
                        <td class="text-center <?=$className?>"><?= number_format($item['PROPERTIES']['UF_PRICE']['VALUE'], 0, '.', ' ') ?> <i class="fas fa-ruble-sign"></i></td>
                        <td class="text-center <?=$className?>">
                            <div class="full-name">
                                <?= $item['PROPERTIES']['UF_LAST_NAME']['VALUE'] ?> <?= $item['PROPERTIES']['UF_NAME']['VALUE'] ?> <?= $item['PROPERTIES']['UF_SECOND_NAME']['VALUE'] ?>
                            </div>
                        </td>

                        <td class="text-center">
                            <? if($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] != 50): ?>

                                <a href="#" title="Список" data-order="<?= $item['ID'] ?>" class="order-info"><i class="fas fa-angle-up text-success"></i></a>
                                <a href="#" title="Копировать" data-order="<?= $item['ID'] ?>" class="order-copy"><i class="far fa-copy text-info"></i></a>

                                <? if(in_array($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'], [51, 83])): ?>

                                    <? if ($item['ORDER_PLAY'] == 'R'): ?>
                                        <a href="#" title="Заявка отклонена: <?= $item['PROPERTIES']['UF_REJECTED_INFO']['VALUE'] ?>" data-play="R" data-price="<?= $item['PROPERTIES']['UF_PRICE']['VALUE'] ?>" data-pricemin="<?= $arResult['PLAY_ORDER_PRICE_MIN'] ?>" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" data-type="<?=$_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE']?>" class="order-play"><i class="fa fa-play text-danger"></i></a>
                                    <? else: ?>

                                        <? if($item['ORDER_PLAY'] == 'Y'): ?>
                                            <a href="#" title="Отправить в обработку" data-play="<?= $item['ORDER_PLAY'] ?>" data-price="<?= $item['PROPERTIES']['UF_PRICE']['VALUE'] ?>" data-pricemin="<?= $arResult['PLAY_ORDER_PRICE_MIN'] ?>" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" data-type="<?=$_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE']?>" class="order-play"><i class="fas fa-play text-success"></i></a>
                                        <? else: ?>
                                            <a href="#" title="На проверке" data-play="<?= $item['ORDER_PLAY'] ?>" data-price="<?= $item['PROPERTIES']['UF_PRICE']['VALUE'] ?>" data-pricemin="<?= $arResult['PLAY_ORDER_PRICE_MIN'] ?>" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" data-type="<?=$_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE']?>" class="order-play"><i class="fa fa-play text-secondary"></i></a>
                                        <? endif; ?>

                                    <? endif; ?>

                                        <? if($item['ORDER_PLAY'] == 'N'): ?>
                                            <a href="#" title="На проверке" data-play="<?= $item['ORDER_PLAY'] ?>" data-order="<?= $item['ID'] ?>" data-user="<?= $item['CREATED_BY'] ?>" class="order-edit"><i class="fas fa-pencil-alt text-secondary"></i></a>
                                        <? else: ?>
                                            <a href="#" title="Редактировать" data-play="<?= $item['ORDER_PLAY'] ?>" data-order="<?= $item['ID'] ?>" data-user="<?= $item['CREATED_BY'] ?>" class="order-edit"><i class="fas fa-pencil-alt text-primary"></i></a>
                                        <? endif; ?>

                                    <a href="#" title="Удалить" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" class="order-delete"><i class="fas fa-trash-alt text-danger"></i></a>

                                    <? if(in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['manager_mts'])): ?>
                                        <a href="#" title="Отклонить заявку" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" data-name="<?=$item['PROPERTIES']['UF_ORDER_NAME']['VALUE']?>" class="order-rejected"><i class="fa fa-stop-circle text-warning"></i></a>
                                    <? endif; ?>

                                <? endif; ?>

                                <? if($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] == 47 && in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['general_manager_jampica', 'manager_jampica'])): ?>
                                    <a href="#" title="Удалить" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" class="order-delete"><i class="fas fa-trash-alt text-danger"></i></a>
                                <? endif; ?>

                                <? if($item['PROPERTIES']['UF_STATUS']['VALUE_ENUM_ID'] == 48 && $item['CREATED_BY'] == $USER->GetID()): ?>
                                    <a href="#" title="Отправить рекламацию" data-order="<?= $item['ID'] ?>" data-code="<?=md5(sha1(crc32($item['ID'])))?>" data-name="<?=$item['PROPERTIES']['UF_ORDER_NAME']['VALUE']?>" class="order-defect"><i class="fa fa-exclamation-triangle text-info"></i></a>
                                <? endif; ?>

                            <? endif; ?>
                        </td>

                    </tr>

                    <tr class="material <?= $item['ID'] ?>">

                        <td class="text-center" colspan="6">

                                <? foreach($item['PROPERTIES']['UF_MATERIAL']['VALUE'] as $key => $value):

                                    $arPointFields = Jumpica\Order\OrderFunction::getPointFields($item['PROPERTIES']['UF_POINT_CODE']['VALUE'][$key]);

                                    //Описание файлов
                                    $fileDescription = explode('###', $item['PROPERTIES']['UF_MATERIAL_FILES']['DESCRIPTION'][$key]);
                                    $uniqueFilePrefix = md5($item['PROPERTIES']['UF_MATERIAL']['VALUE'][$key]);
									
									if ($key > 0) {
										$uniqueFilePrefix = $uniqueFilePrefix.$key;
									}

                                    //Ссылка на файл
                                    $arFileInfo = [];

                                    if ($fileDescription[0] == $uniqueFilePrefix) {

                                        $arFileInfo = \CFile::GetFileArray($item['PROPERTIES']['UF_MATERIAL_FILES']['VALUE'][$key]);

                                    }

                                ?>

                                    <div class="group">

                                        <div class="text-center"><?=$value?></div> -
                                        <div class="text-center"><?=$item['PROPERTIES']['UF_WIDTH']['VALUE'][$key]?> мм x <?=$item['PROPERTIES']['UF_HEIGHT']['VALUE'][$key]?> мм</div>,
                                        <div class="text-center"><?=$item['PROPERTIES']['UF_AMOUNT']['VALUE'][$key]?> шт. - Код точки: <?=$arPointFields['NAME']?>;</div>

                                        <? if(!empty($arFileInfo['SRC'])): ?>

                                            <div class="text-center">
                                                Прикрепленный файл к материалу: <span class="filename"><a href="<?=$arFileInfo['SRC']?>" target="_blank"><?=$fileDescription[1]?></a></span>
                                            </div>

                                        <? endif; ?>

                                    </div>

                                <? endforeach; ?>

                                <? if(!empty($item['PROPERTIES']['UF_FILES']['VALUE'])): ?>

                                    <div class="text-left">

                                        <br />Прикрепленные файлы к заявке:<br />

                                        <? foreach ($item['PROPERTIES']['UF_FILES']['VALUE'] as $value):

                                            $arFileInfo = \CFile::GetFileArray($value);

                                        ?>

                                            <span class="filename"><a href="<?=$arFileInfo['SRC']?>" target="_blank"><?=$arFileInfo['ORIGINAL_NAME']?></a></span><br />

                                        <? endforeach; ?>

                                    </div>

                                <? endif; ?>

                                <? if(!empty($item['PROPERTIES']['UF_TRACK_NUMBER']['VALUE'])): ?>

                                    <div class="track-number">
                                        Трекинг-номер посылки: <?=$item['PROPERTIES']['UF_TRACK_NUMBER']['VALUE']?>
                                    </div>

                                <? else: ?>

                                    <div class="track-number">
                                        Трекинг-номер посылки: не установлен
                                    </div>

                                <? endif; ?>

                                <? if(!empty($item['PROPERTIES']['UF_REJECTED_INFO']['VALUE'])): ?>

                                    <div class="rejected-info">
                                        Заявка была отклонена <?= $item['PROPERTIES']['UF_REJECTED_DATE']['VALUE'] ?><br />
                                        Причина отклонения: <?=$item['PROPERTIES']['UF_REJECTED_INFO']['VALUE']?><br />
                                    </div>

                                <? endif; ?>

                                <? if(!empty($item['PROPERTIES']['UF_LINK_TO_LAYOUT']['VALUE']) && !empty($item['PROPERTIES']['UF_CRM_LEAD_ID']['VALUE'])): ?>

                                    <div class="layout-info">
                                        <a href="#" title="Открыть макет" data-tm="<?=$item['PROPERTIES']['UF_APPROVED_TM']['VALUE']?>" data-cb="<?=$item['PROPERTIES']['UF_APPROVED_CB']['VALUE']?>" data-photo="<?= $item['PROPERTIES']['UF_LINK_TO_LAYOUT']['VALUE'] ?>" data-link="<?= $item['PROPERTIES']['UF_LINK_TO_LAYOUT']['VALUE'] ?>" data-order="<?= $item['ID'] ?>" data-deal="<?= $item['PROPERTIES']['UF_CRM_LEAD_ID']['VALUE'] ?>" data-code="<?= md5(sha1(crc32($item['ID']))) ?>" data-name="<?= $item['PROPERTIES']['UF_ORDER_NAME']['VALUE'] ?>" class="btn-cart active layout-approval <?= $item['ID'] ?>">Открыть макет</a>
                                    </div>

                                <? endif; ?>

                        </td>

                    </tr>
                    <tr></tr>

                <? endforeach; ?>

                </tbody>
            </table>
        </div>

        <? if (empty($item["ID"])): ?>

            <div class="col-sm-12">
                Нет заявок.<br/><br/>
            </div>

        <? endif; ?>

        <div class="catalog-nav">
            <?= $arResult["NAV_STRING"] ?>
        </div>

    </div>
</div>
</div>
