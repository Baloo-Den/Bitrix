<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
		</div> <!-- id="workarea" class="row" -->
	</div> <!-- id="page-wrapper" class="container" -->

	<footer>
        <div class="container my-5">
            <div class="row">
                <div class="col-12 text-center">
                    <?
                    $APPLICATION->IncludeFile(
                        SITE_DIR."include/copyright.php",
                        Array(),
                        Array("MODE"=>"html")
                    );
                    ?>
                </div>
            </div>
        </div>

	</footer>

	<div id="loader">
		<img src="<?=SITE_TEMPLATE_PATH?>/images/loader.svg" alt="">
	</div>

    <div id="toTop" ><i class="fas fa-angle-up fa-2x"></i></div>

    <? if($USER->IsAuthorized() && $_SESSION['UNFINISHED_ORDER'] > 0): ?>

        <div class="modal fade" id="unfinished-order" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Напоминание!</h5>
                        <button type="button" class="close unfinished-order" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>У вас есть незавершенные заявки - <?=$_SESSION['UNFINISHED_ORDER']?> шт.</p>
                        <p>Пожалуйста, отправьте заявки в обработку или удалите.</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn-cart unfinished-order">Показать заявки</a>
                    </div>
                </div>
            </div>
        </div>

    <? endif; ?>

<div class="modal fade" id="defect-order" role="dialog" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Рекламация на заказ № <span class="defect-order-id"></span></h5>
                <button type="button" class="close defect-order" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="defect-order-form">
                <input type="hidden" name="id" value="">
				<input type="hidden" name="code" value="">

                <div class="modal-body">
                    <p>Внесите информацию о браке или о других сложностях с материалами.</p>
                    <div class="form-group">
                        <textarea name="DEFECT_MESSAGE" class="form-control zayavka-form-input-textarea" rows="5" placeholder="" required></textarea>
                    </div>
                    <div class="form-group">
                        <input class="multifile" type="file" name="files[]" multiple>
                    </div>
                    <div class="defect-result"></div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn-cart float-right" value="Отправить">
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="rejected-order" role="dialog" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Отклонить заявку № <span class="rejected-order-id"></span></h5>
                <button type="button" class="close rejected-order" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="rejected-order-form">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="code" value="">

                <div class="modal-body">
                    <p>Введите причину отклонения заявки.</p>
                    <div class="form-group">
                        <textarea name="REJECTED_MESSAGE" class="form-control zayavka-form-input-textarea" rows="5" placeholder="" required></textarea>
                    </div>
                    <div class="rejected-result"></div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn-cart float-right" value="Отклонить">
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="layout-approval" role="dialog" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Заявка № <span class="layout-approval-id"></span></h5>
                <button type="button" class="close layout-approval" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="layout-approval-form" class="">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="deal" value="">
                <input type="hidden" name="code" value="">
                <input type="hidden" name="group" value="">
                <input type="hidden" name="action" value="">
                <input type="hidden" name="tm" value="">
                <input type="hidden" name="cb" value="">

                <div class="modal-body">
                    <div class="form-group photo"></div>
                    <? if (in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['manager_mts', 'supervisor', 'simple_user'])): ?>
                        <div class="form-group">
                            <textarea name="APPROVAL_MESSAGE" class="form-control zayavka-form-input-textarea" rows="5" placeholder="Введите комментарий" required></textarea>
                        </div>
                        <div class="form-group">
                            <input name="APPROVAL_FILE" type="file">
                        </div>
                        <div class="approval-result"></div>
                    <? endif; ?>
                </div>
                <div class="modal-footer">
                    <? if (in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['manager_mts', 'supervisor', 'simple_user'])): ?>
                        <? if (in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['manager_mts'])): ?>
                            <input type="button" data-group="TM" data-action="Y" class="btn-cart active float-right" value="Утвердить ТМ">
                            <input type="button" data-group="TM" data-action="N" class="btn-cart float-right" value="Внести правки">
                        <? else: ?>
                            <input type="button" data-group="CB" data-action="Y" class="btn-cart active float-right" value="Утвердить СВ/ТП">
                            <input type="button" data-group="CB" data-action="N" class="btn-cart float-right" value="Внести правки">
                        <? endif; ?>
                    <? else: ?>
                        Нет доступа к управлению макетом.
                    <? endif; ?>
                </div>
            </form>

        </div>
    </div>
</div>

<? if($USER->IsAuthorized()): ?>

    <div class="role-info-icon">
        <i class="fa fa-user-secret" aria-hidden="true"></i>
    </div>

<? endif; ?>

<div class="role-info">

    <? if($USER->IsAuthorized()): ?>

        <? if($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'general_manager_jampica'): ?>

            Ваша роль: Главный менеджер Jumpica

        <? elseif ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'manager_mts') : ?>

            Ваша роль: Менеджер Головного офиса МТС

        <? elseif ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'manager_jampica') : ?>

            Ваша роль: Менеджер Jumpica

        <? elseif ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'supervisor') : ?>

            Ваша роль: Супервайзер

        <? else: ?>

            Ваша роль: Пользователь

        <? endif; ?>

    <? endif; ?>

</div>

</body>
</html>
