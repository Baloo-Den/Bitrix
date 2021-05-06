<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$this->addExternalJs($templateFolder . '/js/jquery.maskedinput.min.js');

$arResult['INCLUDE_FORUM'] = 'N';
$arResult["INCLUDE_BLOG"] = 'N';
$arResult["INCLUDE_LEARNING"] = 'N';

if($arResult["SHOW_SMS_FIELD"] == true)
{
    CJSCore::Init('phone_auth');
}
?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script>
    //массив по регионам и городам
    jQuery(document).ready(function ($) {

        AddressListArrFull = $.makeArray(<?php echo json_encode($arResult["DELIVERY_ADDRESS_LIST"]); ?>);

        UF_DIVISION = '<?=$arResult["arUser"]["UF_DIVISION"]?>';
        PERSONAL_STATE = '<?=$arResult["arUser"]["PERSONAL_STATE"]?>';
        PERSONAL_CITY = '<?=$arResult["arUser"]["PERSONAL_CITY"]?>';


        //выборка по региона из дивизиона
        function UpdateDivision(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#UF_DIVISION').find('option').remove();

            if ($('#UF_DIVISION').find('option').length < 1 ) {
                $('#UF_DIVISION').append("<option value='non'><?=GetMessage('selected_division')?></option>");
            }

            $.each(AddressListArrFull, function (i, val) {
                var selected = '';

                if ($('#UF_DIVISION').find('option[value="' + val.PROPERTY_DIVISION_VALUE + '"]').length == 0) {
                    if (val.PROPERTY_DIVISION_VALUE == UF_DIVISION) {
                        selected = 'selected';
                    }
                    $('#UF_DIVISION').append("<option value='" + val.PROPERTY_DIVISION_VALUE + "' " + selected + ">" + val.PROPERTY_DIVISION_VALUE + "</option>");
                }

            });

            SelectRegion(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);
            // SelectRegionCity(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);
        }

        //выборка городов по региону
        function SelectRegion(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#PERSONAL_STATE').find('option').remove();

            if ($('#PERSONAL_STATE').find('option').length < 1 ) {
                $('#PERSONAL_STATE').append("<option value='non'><?=GetMessage('selected_region')?></option>");
            }

                $.each(AddressListArrFull, function (i, val) {
                    if (val.PROPERTY_DIVISION_VALUE == UF_DIVISION) {
                        var selected = '';

                        if ($('#PERSONAL_STATE').find('option[value="' + val.PROPERTY_REGION_VALUE + '"]').length == 0) {
                            if (val.PROPERTY_REGION_VALUE == PERSONAL_STATE) {
                                selected = 'selected';
                            }
                            $('#PERSONAL_STATE').append("<option value='" + val.PROPERTY_REGION_VALUE + "' " + selected + ">" + val.PROPERTY_REGION_VALUE + "</option>");
                        }
                    }
                });
            if(UF_DIVISION == 'non') {
                PERSONAL_STATE = 'non';
            }
            SelectRegionCity(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);
        }

        //выборка городов по городу
        function SelectRegionCity(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY) {
            $('#PERSONAL_CITY').find('option').remove();

            if ($('#PERSONAL_CITY').find('option').length < 1 ) {
                $('#PERSONAL_CITY').append("<option value='non'><?=GetMessage('selected_city')?></option>");
            }
            if (PERSONAL_STATE=='non') {

            } else {
                $.each(AddressListArrFull, function (i, val) {
                    if (val.PROPERTY_REGION_VALUE == PERSONAL_STATE) {
                        var selected = '';
                        if (val.PROPERTY_CITY_VALUE == PERSONAL_CITY && PERSONAL_STATE != 'non') {
                            selected = 'selected';
                        }
                        $('#PERSONAL_CITY').append("<option value='" + val.PROPERTY_CITY_VALUE + "' " + selected + ">" + val.PROPERTY_CITY_VALUE + "</option>");
                    }
                });
            }
        }

        $('#UF_DIVISION').on('change', function () {
            UpdateDivision(AddressListArrFull, $('#UF_DIVISION').val(), PERSONAL_STATE, PERSONAL_CITY);
        });
        $('#PERSONAL_STATE').on('change', function () {
            SelectRegion(AddressListArrFull, $('#UF_DIVISION').val(), $('#PERSONAL_STATE').val(), PERSONAL_CITY);
        });

        UpdateDivision(AddressListArrFull, UF_DIVISION, PERSONAL_STATE, PERSONAL_CITY);

    });
</script>

<select class="d-none" id="inlineFormCustomSelectAddressList" name="AddressList[]" required>
    <? foreach ($arResult['DELIVERY_ADDRESS_LIST'] as $value): ?>
        <option value="<?=$value['ID']?>" data-price="<?=(int)$value['PROPERTY_SALE_VALUE']?>" data-yardage="<?=(int)$value['PROPERTY_YARDAGE_VALUE']?>"><?=$value['NAME']?></option>
    <? endforeach; ?>
</select>


<div class="col-12">
    <p class="text-center mb-5"><?echo GetMessage("profil_welcome")?></p>


<?ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
    ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>

<?if($arResult["SHOW_SMS_FIELD"] == true):?>

    <form method="post" action="<?=$arResult["FORM_TARGET"]?>">
    <?=$arResult["BX_SESSION_CHECK"]?>
    <input type="hidden" name="lang" value="<?=LANG?>" />
    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
    <input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />
    <table class="profile-table data-table">
        <tbody>
            <tr>
                <td><?echo GetMessage("main_profile_code")?><span class="starrequired">*</span></td>
                <td><input size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>" autocomplete="off" /></td>
            </tr>
        </tbody>
    </table>

    <p><input type="submit" name="code_submit_button" value="<?echo GetMessage("main_profile_send")?>" /></p>

    </form>

    <script>
    new BX.PhoneAuth({
        containerId: 'bx_profile_resend',
        errorContainerId: 'bx_profile_error',
        interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
        data:
            <?=CUtil::PhpToJSObject([
                'signedData' => $arResult["SIGNED_DATA"],
            ])?>,
        onError:
            function(response)
            {
                var errorDiv = BX('bx_profile_error');
                var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                errorNode.innerHTML = '';
                for(var i = 0; i < response.errors.length; i++)
                {
                    errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                }
                errorDiv.style.display = '';
            }
    });
    </script>

    <div id="bx_profile_error" style="display:none"><?ShowError("error")?></div>

<?else:?>

<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->

var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>
	
	<script>
$( document ).ready(function(){	
	
	$('#save_pass').click(function()
	{

		let old_mail='<? echo $arResult["arUser"]["EMAIL"]?>';
		let email=$("#email").val();
		let old_password=$("#old_password").val();
		let new_password=$("#new_password").val();
		let re_new_password=$("#re_new_password").val();
		if (old_mail!=email && email!='' )//Если меняют mail
			{
				if(old_password=='')//Если старый пароль не ввели
					{
						
						$('#error-box').text('Введите старый пароль')
												  .css('color','red')
												  .animate({'paddingLeft':'10px'},400)
												  .animate({'paddingLeft':'5px'},400);						
					}
				else
					{
						if(new_password=='')//Если новый пароль не ввели
							{

								$('#error-box').text('Введите новый пароль')
														  .css('color','red')
														  .animate({'paddingLeft':'10px'},400)
														  .animate({'paddingLeft':'5px'},400);						
							}	
						else
							{
							if(re_new_password=='')//Если повтор нового пароля не ввели
								{

									$('#error-box').text('Повторите новый пароль')
															  .css('color','red')
															  .animate({'paddingLeft':'10px'},400)
															  .animate({'paddingLeft':'5px'},400);						
								}
								else
									{
										if(new_password!=re_new_password)
											{
												$('#error-box').text('Пароли не совпадают')
																		  .css('color','red')
																		  .animate({'paddingLeft':'10px'},400)
																		  .animate({'paddingLeft':'5px'},400);												
											}
										else//Если всё верно шлём запрос
											{ 
												$.ajax( { 
												  type: "POST",
												  url: "<?php echo $templateFolder ?>/ajax_profile.php",
													data: "old_password="+old_password+"&new_password="+new_password+"&email="+email,  
													success: function(html){  
													$("#error-box").html(html);
																	} 
												}); 												
											}
									}
							}
					}
			}
		else//Если меняют только пароль
				if(old_password=='')//Если старый пароль не ввели
					{
						
						$('#error-box').text('Введите старый пароль')
												  .css('color','red')
												  .animate({'paddingLeft':'10px'},400)
												  .animate({'paddingLeft':'5px'},400);						
					}
				else
					{
						if(new_password=='')//Если новый пароль не ввели
							{

								$('#error-box').text('Введите новый пароль')
														  .css('color','red')
														  .animate({'paddingLeft':'10px'},400)
														  .animate({'paddingLeft':'5px'},400);						
							}	
						else
							{
							if(re_new_password=='')//Если повтор нового пароля не ввели
								{

									$('#error-box').text('Повторите новый пароль')
															  .css('color','red')
															  .animate({'paddingLeft':'10px'},400)
															  .animate({'paddingLeft':'5px'},400);						
								}
								else
									{
										if(new_password!=re_new_password)
											{
												$('#error-box').text('Пароли не совпадают')
																		  .css('color','red')
																		  .animate({'paddingLeft':'10px'},400)
																		  .animate({'paddingLeft':'5px'},400);												
											}
										else//Если всё верно шлём запрос
											{
												$.ajax( { 
												  type: "POST",
												  url: "<?php echo $templateFolder ?>/ajax_profile.php",
													data: "old_password="+old_password+"&new_password="+new_password, 
													success: function(html){  
													$("#error-box").html(html);
																	} 
												}); 												
											}										
									}
							}
					}			
	});	
});		

</script>


  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">

          <h4 class="modal-title">Изменение пароля или емейла</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body"><div id="error-box"></div>
			<form method="post" action="#" autocomplete="off" id="form_pass_mail">
				
			  <div class="form-group">
				<label for="email">Email</label>
				<input name="email" type="email" class="form-control" id="email" placeholder="Введите email">
			  </div>
				
			  <div class="form-group">
				<label for="password">Старый пароль</label>
				<input name="password" type="password" class="form-control" id="old_password" placeholder="Введите старый пароль">
			  </div>
				
			  <div class="form-group">
				<label for="password">Новый пароль</label>
				<input name="password" type="password" class="form-control" id="new_password" placeholder="Введите новый пароль">
			  </div>
								
			  <div class="form-group">
				<label for="password">Новый пароль</label>
				<input name="password" type="password" class="form-control" id="re_new_password" placeholder="Повторите новый пароль">
			  </div>
				
			  
			</form>  
			<button  class="btn btn-primary" id="save_pass">Сохранить изменения</button>
        </div>
        <!--type="submit"-->
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">закрыть</button>
        </div>
        
      </div>
    </div>
  </div>
<form method="post" class="profil" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />



    <div class="row my-2 endfindinput">
		
		
		
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('USER_CONTACT')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                <span class="cool-line col-12 col-lg-6 col-xl-4">
                    <input type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>" placeholder="<?=GetMessage('NAME')?>" disabled="disabled" class="w-100" />
                </span>
                <span class="cool-line col-12 col-lg-6 col-xl-4">
                    <input type="text" name="LAST_NAME" maxlength="50" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" placeholder="<?=GetMessage('LAST_NAME')?>" disabled="disabled" class="w-100" />
                </span>
                <span class="cool-line col-12 col-lg-6 col-xl-4">
                    <input type="text" name="SECOND_NAME" maxlength="50" value="<?= $arResult["arUser"]["SECOND_NAME"] ?>" placeholder="<?=GetMessage('SECOND_NAME')?>" disabled="disabled" class="w-100" />
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">

        </div>
    </div>

    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('DELIVERY_ADDRESS')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                <span class="col-12 col-lg-6">
                    <select id="UF_DIVISION" name="UF_DIVISION" class="custom-select mr-sm-2" disabled="disabled">
                        <option value='non'><?=GetMessage('selected_division')?></option>
                    </select>
                </span>
                <span class="col-12 col-lg-6">
                    <select id="PERSONAL_STATE" name="PERSONAL_STATE" class="custom-select mr-sm-2" disabled="disabled">
                        <option value='non'><?=GetMessage('selected_region')?></option>
                    </select>
                </span>
                <span class="col-12 col-lg-6 mt-2">
                    <select id="PERSONAL_CITY" name="PERSONAL_CITY" class="custom-select mr-sm-2" disabled="disabled">
                        <option value='non'><?=GetMessage('selected_city')?></option>
                    </select>
                </span>
                <span class="cool-line col-12 col-lg-6 mt-3">
                    <input type="text" name="PERSONAL_STREET" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_STREET"]?>" placeholder="<?=GetMessage("USER_STREET")?>" disabled="disabled" class="w-100" />
                </span>
                <span class="col-12 col-lg-6  mt-2">
                    <a href="zapros.php" class="btn-cart btn-hidden w-100 text-center">Моего города нет в списке</a>
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">
        </div>
    </div>



    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('USER_PHONE')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                <span class="cool-line col-12 col-lg-6 col-xl-4">
                    <input type="text" name="PERSONAL_PHONE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" placeholder="<?=GetMessage('USER_PHONE')?>" disabled="disabled" class="w-100 telephone" />
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">
        </div>
    </div>

    <div class="row my-2 endfindinput d-none">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('LOGIN')?><span class="starrequired">*</span>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                    <span class="cool-line col-12 col-lg-6 col-xl-4">
                        <input type="text" id="LOGIN" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" class="w-100" />
                    </span>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">
        </div>
    </div>

    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('EMAIL')?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                <span class="cool-line col-12 col-lg-6">
                    <input type="text" id="EMAIL" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" disabled="disabled" class="w-100" />
                </span>
			
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
  <!-- Button to Open the Modal -->
  <button type="button" class="btn-cart " data-toggle="modal" data-target="#myModal"> 
    Изменить
  </button>			
<!--
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">
-->
        </div>
    </div>





    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('main_profile_uf_notification')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6">
            <div class="row">
                <span class="cool-line col-12 col-lg-6 col-xl-4">
                    <input type="hidden" value="0" name="UF_NOTIFICATION">
                    <input type="checkbox" id="UF_NOTIFICATION" name="UF_NOTIFICATION" value="<?= $arResult["arUser"]["UF_NOTIFICATION"] ?>" <?= $arResult["arUser"]["UF_NOTIFICATION"] ? " checked=\"checked\"" : "" ?>  class="" />
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
<!--            <span class="btn-cart editthisrow">--><?//=GetMessage('main_profile_editing')?><!--</span>-->
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart">
<!--            <input type="reset" value="--><?//=GetMessage('main_profile_cancel');?><!--" class="btn-cart btn-hidden editthisrow btn-cancel">-->

        </div>
    </div>


    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('DOP_FIO')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6 row-fio">
            <input type="hidden" name="UF_DOP_FIO[]" value="" disabled="disabled"  />
            <?
            if ($arResult["arUser"]["UF_DOP_FIO"]) {
                foreach($arResult["arUser"]["UF_DOP_FIO"] as $value) {

                    ?>
                    <div class="row mb-2 new-fio">
                        <span class="cool-line col-12 col-lg-6">
                            <input type="text" name="UF_DOP_FIO[]" maxlength="255" value="<?=$value?>" placeholder="<?=GetMessage('dop_fio_add')?>" disabled="disabled" class="w-100" />
                        </span>
                        <span class="col-12  col-lg-6 text-right">
                             <div class="delfio dopfiobtn btn-hidden">
                                <i class="fas fa-minus  border-gyrey" title="<?=GetMessage('btn_del')?>"></i>
                            </div>
                            <div class="addfio dopfiobtn btn-hidden">
                                <i class="fas fa-plus border-gyrey" title="<?=GetMessage('btn_add')?>"></i>
                            </div>
                        </span>
                    </div>

            <?  }
            } else { ?>
                <div class="row mb-2 new-fio">
                        <span class="cool-line col-12 col-lg-6">
                            <input type="text" name="UF_DOP_FIO[]" maxlength="255" value="" placeholder="<?=GetMessage('dop_fio_add')?>" disabled="disabled" class="w-100" />
                        </span>
                    <span class="col-12  col-lg-6 text-right">
                             <div class="delfio dopfiobtn btn-hidden">
                                <i class="fas fa-minus  border-gyrey" title="<?=GetMessage('btn_del')?>"></i>
                            </div>
                            <div class="addfio dopfiobtn btn-hidden">
                                <i class="fas fa-plus border-gyrey" title="<?=GetMessage('btn_add')?>"></i>
                            </div>
                        </span>
                </div>
            <?
            }
            ?>


        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden btn-save">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">

        </div>
    </div>





    <div class="row my-2 endfindinput">
        <div class="col-12 col-lg-3 font-weight-bold">
            <?=GetMessage('DOP_TEL')?>
        </div>
        <div class="col-12 col-lg-5 col-xl-6 row-tel">
            <input type="hidden" name="UF_DOP_TEL[]" value="" disabled="disabled"  />
            <?if ($arResult["arUser"]["UF_DOP_TEL"]) {
                foreach($arResult["arUser"]["UF_DOP_TEL"] as $value) {

                    ?>
                    <div class="row mb-2 new-tel">
                        <span class="cool-line col-12 col-lg-6">
                            <input type="text" name="UF_DOP_TEL[]" maxlength="255" value="<?=$value?>" placeholder="<?=GetMessage('dop_tel_add')?>" disabled="disabled" class="w-100 telephone" />
                        </span>
                        <span class="col-12  col-lg-6 text-right">
                             <div class="deltel doptelbtn btn-hidden">
                                <i class="fas fa-minus  border-gyrey" title="<?=GetMessage('btn_del')?>"></i>
                            </div>
                            <div class="addtel doptelbtn btn-hidden">
                                <i class="fas fa-plus border-gyrey" title="<?=GetMessage('btn_add')?>"></i>
                            </div>
                        </span>
                    </div>

                <?  }
            } else { ?>
                <div class="row mb-2 new-tel">
                        <span class="cool-line col-12 col-lg-6">
                            <input type="text" name="UF_DOP_TEL[]" maxlength="255" value="" placeholder="<?=GetMessage('dop_tel_add')?>" disabled="disabled" class="w-100 telephone" />
                        </span>
                    <span class="col-12  col-lg-6 text-right">
                             <div class="deltel doptelbtn btn-hidden">
                                <i class="fas fa-minus  border-gyrey" title="<?=GetMessage('btn_del')?>"></i>
                            </div>
                            <div class="addtel doptelbtn btn-hidden">
                                <i class="fas fa-plus border-gyrey" title="<?=GetMessage('btn_add')?>"></i>
                            </div>
                        </span>
                </div>
                <?
            }
            ?>


        </div>
        <div class="col-12 col-lg-4 col-xl-3 mt-2 mt-lg-0 text-right">
            <span class="btn-cart editthisrow"><?=GetMessage('main_profile_editing')?></span>
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("main_profile_save") : GetMessage("MAIN_ADD"))?>" class="btn-cart btn-hidden btn-save">
            <input type="reset" value="<?=GetMessage('main_profile_cancel');?>" class="btn-cart btn-hidden editthisrow btn-cancel">

        </div>
    </div>


    <?if($arResult['CAN_EDIT_PASSWORD']):?>
    <!--
        <div class="row my-2">
            <div class="col-12 col-lg-3">
                <?=GetMessage('NEW_PASSWORD_REQ')?>
            </div>
            <div class="col-12 col-lg-9">
                <input type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="bx-auth-input" />
                <?if($arResult["SECURE_AUTH"]):?>
                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
                    <noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
                    </noscript>
                    <script type="text/javascript">
                        document.getElementById('bx_auth_secure').style.display = 'inline-block';
                    </script>
                    </td>
                    </tr>
                <?endif?>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12 col-lg-3">
                <?=GetMessage('NEW_PASSWORD_CONFIRM')?>
            </div>
            <div class="col-12 col-lg-9">
                <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
            </div>
        </div>
        -->
    <?endif?>

	<div class="row mt-4 d-none">
        <div class="col-6 text-left text-md-right">
            <input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>" class="btn-cart">
        </div>
        <div class="col-6 text-right text-md-left">
            <input type="reset" value="<?=GetMessage('MAIN_RESET');?>" class="btn-cart">
        </div>
    </div>
</form>

<?endif?>


</div>

<script>
    //mask tel
    $(".telephone").mask("+7 (999) 999-99-99");
</script>
