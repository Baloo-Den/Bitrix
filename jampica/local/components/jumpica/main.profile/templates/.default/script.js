function removeElement(arr, sElement)
{
	var tmp = new Array();
	for (var i = 0; i<arr.length; i++) if (arr[i] != sElement) tmp[tmp.length] = arr[i];
	arr=null;
	arr=new Array();
	for (var i = 0; i<tmp.length; i++) arr[i] = tmp[i];
	tmp = null;
	return arr;
}

function SectionClick(id)
{
	var div = document.getElementById('user_div_'+id);
	if (div.className == "profile-block-hidden")
	{
		opened_sections[opened_sections.length]=id;
	}
	else
	{
		opened_sections = removeElement(opened_sections, id);
	}

	document.cookie = cookie_prefix + "_user_profile_open=" + opened_sections.join(",") + "; expires=Thu, 31 Dec 2020 23:59:59 GMT; path=/;";
	div.className = div.className == 'profile-block-hidden' ? 'profile-block-shown' : 'profile-block-hidden';
}

jQuery(document).ready(function ($) {

	$(".editthisrow").on('click', function () {
		//если у блока стоит режим редактирования
		if ($(this).parents('.endfindinput').hasClass('editthisinput')) {
			//удаляем режим редактирования
			$(this).parents('.endfindinput').removeClass('editthisinput');
			//находим поля ввода и удаляем класс подчёркивания
			$(this).parents('.endfindinput').find('.cool-line.activeborder').removeClass('activeborder');
			//полям ввода запрет редактирования
			$(this).parents('.endfindinput').find('input, select').prop('disabled', true);
			//если нажата кнопка отмена - сбрасываем
			if($(this).hasClass('btn-cancel')) {
				$('form .clonedop').remove();
				$('form .dopdelhidden').removeClass('dopdelhidden');
				$(this).parents('form').trigger("reset");
				ValInput();
			}
			//скрываем кнопки сохранить/отмена
			$(this).parents('.endfindinput').find('.btn-cart').addClass('btn-hidden');
			//скрываем кнопки добавить/удалить
			$(this).parents('.endfindinput').find('.dopfiobtn').addClass('btn-hidden');
			$(this).parents('.endfindinput').find('.doptelbtn').addClass('btn-hidden');
			//показываем копку изменить
			$(this).parents('.endfindinput').find('span.btn-cart').removeClass('btn-hidden');
		}
		//если у блока отключен режим редактирования
		else {
			//включаем режим редактирования
			$(this).parents('.endfindinput').addClass('editthisinput');
			//находим поля ввода и добавляем класс подчёркивания
			$(this).parents('.endfindinput').find('.cool-line').not('.activeborder').addClass('activeborder');
			//полям ввода разрешаем редактирование
			$(this).parents('.endfindinput').find('input, select').prop('disabled', false);
			//отображаем кнопки сохранить/отмена
			$(this).parents('.endfindinput').find('.btn-cart.btn-hidden').removeClass('btn-hidden');
			//отображаем кнопки добавить/удалить
			$(this).parents('.endfindinput').find('.dopfiobtn.btn-hidden').removeClass('btn-hidden');
			$(this).parents('.endfindinput').find('.doptelbtn.btn-hidden').removeClass('btn-hidden');

			//скрываем копку изменить
			$(this).addClass('btn-hidden');
		}
	});

	$("form input[type=submit]").on('click', function () {
		$('form .dopdelhidden').remove();
		// $('#UF_NOTIFICATION').prop("disabled", false);
	});

    $("input[type='checkbox']").on('click', function () {
        var thisValue = $(this).val();
        if (thisValue==1) {
            $(this).val(0);
        } else {
            $(this).val(1);
        }
    });
    
    
    $(document).on('click', '.addfio', function () {
		$('.new-fio:last').clone().appendTo('.row-fio').addClass('clonedop').removeClass('dopdelhidden');
		$('.new-fio:last input').val('');

	});
	$(document).on('click', '.delfio', function () {
		$(this).parents('.new-fio').addClass('dopdelhidden');

	});
	$(document).on('click', '.addtel', function () {
		$('.new-tel:last').clone().appendTo('.row-tel').addClass('clonedop').removeClass('dopdelhidden');
		$('.new-tel:last input').val('');
		$(".telephone").mask("+7 (999) 999-99-99");
	});
	$(document).on('click', '.deltel', function () {
		$(this).parents('.new-tel').addClass('dopdelhidden');

	});


	function ValInput() {
		$('form.profil input[type=text]').each(function () {
			if($(this).val()==''){
				$(this).parents('.cool-line').find('.profil-error').remove('.profil-error');
				$(this).parents('.cool-line').append('<span class="profil-error"><i class="fas fa-exclamation"></i></span>');
			} else {
				$(this).parents('.cool-line').find('.profil-error').remove('.profil-error');
			}
		});
	}

	$('form.profil input[type=text]').on('change, input, keyup, keypress, keydown', function () {
		ValInput();
	});

	ValInput();



	$('#EMAIL').on('change, input, keyup, keypress, keydown, focusout', function () {
		$('#LOGIN').val($(this).val());
	});




});
