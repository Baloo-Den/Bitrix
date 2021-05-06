$(document).ready(function () {

    priceBlockPosition();

    //Список закрепленных пользователей
    $('body').on('change', '#inlineFormCustomSelectUser', function () {

        $('#loader').show();

        var userId = $(this).val();
        var orderId = $.getUrlVar('id');
        var type = $.getUrlVar('type');

        if (type == 'copy') {

            window.location.href = '/zayavka/add.php' + '?type=copy' + '&user=' + userId + '&id=' + orderId;

        } else {

            window.location.href = '/zayavka/add.php' + '?type=add' + '&user=' + userId;

        }

    });

    //Максимальная ширина
    $('body').on('click input', ".form-zayavka input[name='WIDTH[]']", function () {

        var wMin = parseInt($(this).data('min'));
        var wMax = parseInt($(this).data('max'));
        var cnt = parseInt($(this).val().replace(/[^0-9]/g, ''));
        var materialBlock = $(this).closest('.form-zayavka-new');

        if (cnt >= wMin && cnt <= wMax) {

            // $(this).val(cnt);
            $('.error-min-max', materialBlock).html('');

        } else if (cnt < wMin) {

            // $(this).val(cnt);
            $('.error-min-max', materialBlock).html('<span style="color: red; font-weight: bold;">!!!</span> Введите значение больше или равное ' + wMin + '.');

        } else if (cnt > wMax) {

            // $(this).val(wMax);
            $('.error-min-max', materialBlock).html('<span style="color: red; font-weight: bold;">!!!</span> Превышен максимальный размер материала. Готовое изделие будет сварено из частей.');

        }

        priceCalculate();

    });

    //Максимальная высота
    $('body').on('click input', ".form-zayavka input[name='HEIGHT[]']", function () {

        var hMin = parseInt($(this).data('min'));
        var hMax = parseInt($(this).data('max'));
        var cnt = parseInt($(this).val().replace(/[^0-9]/g, ''));
        var materialBlock = $(this).closest('.form-zayavka-new');

        if (cnt >= hMin && cnt <= hMax) {

            // $(this).val(cnt);
            $('.error-min-max', materialBlock).html('')

        } else if (cnt < hMin) {

            // $(this).val(cnt);
            $('.error-min-max', materialBlock).html('<span style="color: red; font-weight: bold;">!!!</span> Введите значение больше или равное ' + hMin + '.');

        } else if (cnt > hMax) {

            // $(this).val(hMax);
            $('.error-min-max', materialBlock).html('<span style="color: red; font-weight: bold;">!!!</span> Превышен максимальный размер материала. Готовое изделие будет сварено из частей.')

        }

        priceCalculate();

    });

    //Выбор доп.контактного лица и доп.телефонов
    var dopUser = $('#inlineFormCustomSelectDopUser').val();
    var dopPhone = $('#inlineFormCustomSelectDopPhone').val();

    if (!dopUser) {

        $('#inlineFormCustomSelectDopUser :last').attr('selected', 'selected');

    }
    if (!dopPhone) {

        $('#inlineFormCustomSelectDopPhone :last').attr('selected', 'selected');

    }

    //Выбор материала
    $('body').on('change', '#inlineFormCustomSelectMaterial', function () {

        priceCalculate();

    });

    //Выбор обработки
    $('body').on('change', '#inlineFormCustomSelectProcessing', function () {

        priceCalculate();

    });

    //Выбор высоты, ширины
    $('body').on('click input', '#inlineFormInputGroup.form-control', function () {

        //priceCalculate();

    });

    //Выбор колличества
    $('body').on('click input', '#inlineFormInput.form-control', function () {

        priceCalculate();

    });

    //Добавить материал
    $('body').on('click', '#import .new-material', function () {

        $('.form-zayavka-new:last').clone().appendTo('.form-zayavka');
        $('.import-form .delete-material').addClass('active');

        //При клонировании очищаем контейнер с файлами
        $('.form-zayavka-new:last .multifile_container').remove();
        $('.form-zayavka-new:last .multifile_container_delete').remove();

        //При клонировании очищаем комментарий
        $('.form-zayavka-new:last textarea').val('');
        $('.form-zayavka-new:last input').val('');

        $(".form-zayavka-new:last #inlineFormCustomSelectProcessing").val(null).trigger("change");

        //Превышение максимальных размеров
        $('.form-zayavka-new:last .error-min-max').html('');

        //Имейджи
        $('.form-zayavka-new:last .img-imidg').html('');
        $('.form-zayavka-new:last .img-imidg-partner').html('');

        priceCalculate();
        priceBlockPosition();
        indexMaterial();

    });

    //Удалить материал
    $('body').on('click', '#import .delete-material', function () {

        var orderId = $(this).attr('id');

        if (orderId) {

            $('#loader').show();

            $.ajax({
                type: 'POST',
                url: '/ajax/delete_card.php',
                data: {'id': orderId},
                success: function (data) {

                    var obj = JSON.parse(data);

                    if (obj.errors) {

                        $('#loader').hide();

                    } else {

                        $('#loader').hide();

                    }

                }
            });

        }

        $(this).parents().remove('.form-zayavka-new');

        var count = $('.form-zayavka-new').length;

        if (count == 1) {

            $('.import-form .delete-material').removeClass('active');

        }

        priceCalculate();
        priceBlockPosition();
        indexMaterial();

    });

    //Выбор имидж
    $('body').on('change', '#inlineFormCustomSelectImidg', function () {

        var imgId = $('option:selected', this).attr('data-id');
        var imgUrl = $('option:selected', this).attr('data-src');
        var material = $(this).closest('.form-zayavka-new');

        if (imgUrl) {

            $('#img-imidg', material).html('<a href="' + imgUrl + '" data-fancybox="gallery"><img src="' + imgUrl + '" class="imidg"></a>');

        } else {

            $('#img-imidg', material).html('');

        }

    });

    //Выбор имидж (партнерский)
    $('body').on('change', '#inlineFormCustomSelectImidgPartner', function () {

        var imgId = $('option:selected', this).attr('data-id');
        var imgUrl = $('option:selected', this).attr('data-src');
        var material = $(this).closest('.form-zayavka-new');

        if (imgUrl) {

            $('#img-imidg-partner', material).html('<a href="' + imgUrl + '" data-fancybox="gallery"><img src="' + imgUrl + '" class="imidg"></a>');

        } else {

            $('#img-imidg-partner', material).html('');

        }

    });

    //Прикрепляем файлы к заявке
    $('#import .multifile').multifile();

    $('body').on('click', '#import .add-file', function (e) {
        e.preventDefault();

        $('#import input.multifile:last').click();

    });

    //Удаление файлов при редактировании заявки
    $('body').on('click', '.file-block .multifile_container_delete .multifile_remove_input', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');
        $('#import').append('<input type="hidden" name="FILE_DELETE[]" value="' + id + '">');
        $(this).parents().remove('.uploaded_image');

    });

    //Отправка формы
    $('body').on('submit', '#import', function (e) {
        e.preventDefault();

        $('#loader').show();

        var form = $(this);
        formData = new FormData(form.get(0));

        var url = $("#import input[name='PATH_TO_AJAX']").val();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                var obj = JSON.parse(data);

                if (obj.errors) {

                    $('#loader').hide();
                    $('#import .import-result').html('Ошибки: ' + obj.errors).css('color', 'red');

                } else {

                    $('#loader').hide();
                    $('.import-form').css('display', 'none');
                    $('.container.zayavka-end-content .order-id').html(obj.success);
                    $('.container.zayavka-end-content').css('display', 'block');

                }

            }
        });

    });

    //Прикрепляем файлы к материалам
    $('body').on('click', '#import .add-multifile-material', function (e) {
        e.preventDefault();

        var index = $(this).attr('index');

        //Оставляем только один файл
        var allFile = $(this).closest('.material-file');

        $('.multifile_container', allFile).remove();
        $('.multifile-material', allFile).remove();

        $(allFile).append('<input class="multifile-material" type="file" name="MATERIAL_FILES_' + index + '[]">');
        $('.multifile-material:first', allFile).multifile();

        $.each(allFile, function (index, value) {

            $(".multifile_remove_input:first", value).click();
            $('.multifile-material:last', value).click();

        });

    });

    //Удаление файлов к материалам при редактировании заявки
    $('body').on('click', '.material-file .multifile_container_delete .multifile_remove_input', function (e) {
        e.preventDefault();

        var id = $(this).attr('id');
        $('#import').append('<input type="hidden" name="FILE_DELETE_MATERIAL[]" value="' + id + '">');
        $(this).parents().remove('.uploaded_image');

    });

    //Подсказка для маркировки
    $('.markirovka-toltip').tooltip({
        'trigger': 'hover',
        'placement': 'bottom'
    });

    //Выбор супервайзера
    $('body').on('change', '#inlineFormCustomSelectSupervisor', function () {

        var superSurName = $('option:selected', this).attr('data-surname');
        var superName = $('option:selected', this).attr('data-name');
        var superLastName = $('option:selected', this).attr('data-lastname');
        var superEmail = $('option:selected', this).attr('data-email');
        var superPhone = $('option:selected', this).attr('data-phone');
        var superUserId = $('option:selected', this).attr('data-id');
        var superContactId = $('option:selected', this).attr('data-contact');

        if (superContactId) {

            $("#import input[name='SUPERVISOR_SURNAME']").val(superSurName);
            $("#import input[name='SUPERVISOR_NAME']").val(superName);
            $("#import input[name='SUPERVISOR_LASTNAME']").val(superLastName);
            $("#import input[name='SUPERVISOR_EMAIL']").val(superEmail);
            $("#import input[name='SUPERVISOR_PHONE']").val(superPhone);
            $("#import input[name='SUPERVISOR_USER_ID']").val(superUserId);
            $("#import input[name='SUPERVISOR_CONTACT_ID']").val(superContactId);

        } else {

            $("#import input[name='SUPERVISOR_SURNAME']").val('');
            $("#import input[name='SUPERVISOR_NAME']").val('');
            $("#import input[name='SUPERVISOR_LASTNAME']").val('');
            $("#import input[name='SUPERVISOR_EMAIL']").val('');
            $("#import input[name='SUPERVISOR_PHONE']").val('');
            $("#import input[name='SUPERVISOR_USER_ID']").val('');
            $("#import input[name='SUPERVISOR_CONTACT_ID']").val('');

        }

    });

    priceCalculate();
    priceBlockPosition();
    indexMaterial();

});

//Расчет стоимости
function priceCalculate() {

    $('#total-price').html('<img src="/local/templates/jumpica/images/loader.svg">');

    //Расчет стоимости
    var allMaterial = $('.form-zayavka-new');
    var price = 0;
    var priceDelyvery = 0;
    var totalPrice = 0;
    var weight = 0;
    var weightNotRounded = 0;
    var weightGrams = 0;
    var priceDelyveryBefore1kg = $("#import input[name='DELYVERY_BEFORE']").val();
    var priceDelyveryAfter1kg = $("#import input[name='DELYVERY_AFTER']").val();
    var deliveryPlace = 0;
    var deliveryType = 0;
    var square = 0;
    var priceLayout = parseInt($("#import input[name='LAYOUT_PRICE']").val());

    var checkObrabotka = false; //Если не рассчитана постобработка, стоимость не выводим

    //Очищаем предыдущий расчет
    $('#price_detail').empty();
    $('#processing_detail').empty();
    $('#pvh_detail').empty();

    //Очищаем консоль
    console.clear();

    $.each(allMaterial, function (index, value) {
		
		//Стоимость верстки
		priceLayout = parseInt($("#import input[name='LAYOUT_PRICE']").val());

        var checkPvhMaterial = false; //Для ПВХ материалов рассчитываем постобработку только один раз, последующие варианты обработок ПВХ материалов ставим по нулям

        console.log('');
        console.log('####');
        console.log('');

        //Материал
        var priceMaterial = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-price');
        if (priceMaterial) {

            priceMaterial = parseInt(priceMaterial.replace('\D', ''));
        }

        console.log('Материал: ' + $("#inlineFormCustomSelectMaterial option:selected", value).val());
        console.log('Стоимость материала: ' + priceMaterial);

        /* Дополнительные расчеты */

        //Максимальные/минимальные значения ширины/высоты
        var wMin = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-wmin');
        var wMax = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-wmax');
        var hMin = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-hmin');
        var hMax = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-hmax');

        if (wMin == undefined || wMin.length < 1) {

            wMin = 1;
        }

        if (wMax == undefined || wMax.length < 1) {

            wMax = 2000;
        }

        if (hMin == undefined || hMin.length < 1) {

            hMin = 1;
        }

        if (hMax == undefined || hMax.length < 1) {

            hMax = 2000;
        }

        $("input[name='WIDTH[]']", value).attr('data-min', wMin);
        $("input[name='WIDTH[]']", value).attr('min', wMin);
        //$("input[name='WIDTH[]']", value).attr('placeholder', wMin);
        $('span.w-min', value).html(wMin);

        $("input[name='WIDTH[]']", value).attr('data-max', wMax);
        //$("input[name='WIDTH[]']", value).attr('placeholder', wMax);
        $('span.w-max', value).html(wMax);

        $("input[name='HEIGHT[]']", value).attr('data-min', hMin);
        $("input[name='HEIGHT[]']", value).attr('min', hMin);
        //$("input[name='HEIGHT[]']", value).attr('placeholder', hMin);
        $('span.h-min', value).html(hMin);

        $("input[name='HEIGHT[]']", value).attr('data-max', hMax);
        //$("input[name='HEIGHT[]']", value).attr('placeholder', hMax);
        $('span.h-max', value).html(hMax);

        //Доступные обработки для материала
        var arObrabotka = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-obrabotka');
        var arObrabotkaList = $("#inlineFormCustomSelectProcessing option", value);

        $.each(arObrabotkaList, function (index, value) {

            var obrabotkaid = $(value).attr('data-id');

            if (arObrabotka) {

                if (arObrabotka.indexOf(obrabotkaid) !== -1) {

                    $(value).prop('disabled', false);
                    $(value).css('display', 'block');

                } else {

                    $(value).prop('disabled', true);
                    $(value).css('display', 'none');

                    $(value).prop('selected', false);
                    $(value).removeAttr('selected');

                }

            } else {

                if (value.value) {

                    $(value).prop('disabled', true);
                    $(value).css('display', 'none');

                    $(value).prop('selected', false);
                    $(value).removeAttr('selected');

                }

            }

        });

        //Доступные имейджи для материала
        var arImidg = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-imidg');
        var arImidgList = $("#inlineFormCustomSelectImidg option", value);

        if (arImidgList) {

            $.each(arImidgList, function (index, value) {

                if (arImidg) {

                    if (arImidg.indexOf(value.value) !== -1) {

                        $(value).prop('disabled', false);
                        $(value).css('display', 'block');

                    } else {

                        $(value).prop('disabled', true);
                        $(value).css('display', 'none');

                        $(value).prop('selected', false);
                        $(value).removeAttr('selected');

                    }

                } else {

                    if (value.value) {

                        $(value).prop('disabled', true);
                        $(value).css('display', 'none');

                        $(value).prop('selected', false);
                        $(value).removeAttr('selected');

                    }

                }

            });

        }

        //Если не выбран имейдж
        var imidg = $("#inlineFormCustomSelectImidg option:selected", value).val();

        if (!imidg) {

            $("#inlineFormCustomSelectImidg option", value).prop('selectedIndex', 0);
            $('#img-imidg', value).html('');

        }

        //ПВХ материал
        var pvhMaterial = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-pvh');
        $('#pvh_detail').append('<input type="hidden" name="PVH_DETAIL[]" value="' + pvhMaterial + '">');

        /* Дополнительные расчеты */

        //Коэффициент
        var yardageMaterial = $("#inlineFormCustomSelectMaterial option:selected", value).attr('data-yardage');

        console.log('Коэффициент материала: ' + yardageMaterial);

        //Высота
        var heightMaterial = $("input[name='HEIGHT[]']", value).val();
        if (heightMaterial) {

            heightMaterial = parseInt(heightMaterial.replace('\D', ''));
        }

        //Ширина
        var widthMaterial = $("input[name='WIDTH[]']", value).val();
        if (widthMaterial) {

            widthMaterial = parseInt(widthMaterial.replace('\D', ''));
        }

        //Количество изделий
        var countMaterial = $("input[name='COUNT[]']", value).val();
        if (countMaterial) {

            countMaterial = parseInt(countMaterial.replace('\D', ''));
        }

        //Количество макетов
        var countLayouts = $("input[name='COUNT_LAYOUTS[]']", value).val();
        if (countLayouts) {

            countLayouts = parseInt(countLayouts.replace('\D', ''));

        } else {

            //По умолчанию 1 макет, чтобы корректно рассчитывались уже существующие заявки
            countLayouts = 1;

        }

        /* Расчет доставки */

        //Площадь материала
        var squareMaterial = ((widthMaterial * heightMaterial) / 1000000);

        console.log('Площадь материала: ' + widthMaterial + ' * ' + heightMaterial + ' / ' + 1000000 + ' = ' + squareMaterial);

        //Детальная стоимость обработки
        var priceProcessingStr = '';

        //Общая стоимость обработки
        var totalPriceProcessing = 0;

        //Расчет обработки по формулам
        var selectProcessing = $("#inlineFormCustomSelectProcessing option:selected", value);

        $.each(selectProcessing, function (index, value) {

            var priceProcessing = $(value).attr('data-price');
            if (priceProcessing) {

                priceProcessing = parseInt(priceProcessing.replace('\D', ''));

            }

            //Доп.коэффициент веса
            var yardageMaterialDop = $(value).attr('data-yardagedop');
            if (yardageMaterialDop) {

                yardageMaterial = yardageMaterialDop;
                console.log('>>> Коэффициент материала обновлен: ' + yardageMaterialDop);

            }

            //Формула расчета
            var formulaProcessing = $(value).attr('data-formula');

            if (widthMaterial || heightMaterial || countMaterial || squareMaterial || priceProcessing) {

                var calcResult = formulaProcessing;

                if (widthMaterial) {

                    var calcResult = calcResult.replace('#width#', widthMaterial);
                    var calcResult = calcResult.replace('#width#', widthMaterial);
                    var calcResult = calcResult.replace('#width#', widthMaterial);
                    var calcResult = calcResult.replace('#width#', widthMaterial);
                    var calcResult = calcResult.replace('#width#', widthMaterial);
                    var calcResult = calcResult.replace('#width#', widthMaterial);

                } else {

                    var calcResult = calcResult.replace('#width#', 0);
                    var calcResult = calcResult.replace('#width#', 0);
                    var calcResult = calcResult.replace('#width#', 0);
                    var calcResult = calcResult.replace('#width#', 0);
                    var calcResult = calcResult.replace('#width#', 0);
                    var calcResult = calcResult.replace('#width#', 0);

                }

                if (heightMaterial) {

                    var calcResult = calcResult.replace('#height#', heightMaterial);
                    var calcResult = calcResult.replace('#height#', heightMaterial);
                    var calcResult = calcResult.replace('#height#', heightMaterial);
                    var calcResult = calcResult.replace('#height#', heightMaterial);
                    var calcResult = calcResult.replace('#height#', heightMaterial);
                    var calcResult = calcResult.replace('#height#', heightMaterial);

                } else {

                    var calcResult = calcResult.replace('#height#', 0);
                    var calcResult = calcResult.replace('#height#', 0);
                    var calcResult = calcResult.replace('#height#', 0);
                    var calcResult = calcResult.replace('#height#', 0);
                    var calcResult = calcResult.replace('#height#', 0);
                    var calcResult = calcResult.replace('#height#', 0);

                }

                if (countMaterial) {

                    var calcResult = calcResult.replace('#count#', countMaterial);
                    var calcResult = calcResult.replace('#count#', countMaterial);
                    var calcResult = calcResult.replace('#count#', countMaterial);
                    var calcResult = calcResult.replace('#count#', countMaterial);
                    var calcResult = calcResult.replace('#count#', countMaterial);
                    var calcResult = calcResult.replace('#count#', countMaterial);

                } else {

                    var calcResult = calcResult.replace('#count#', 0);
                    var calcResult = calcResult.replace('#count#', 0);
                    var calcResult = calcResult.replace('#count#', 0);
                    var calcResult = calcResult.replace('#count#', 0);
                    var calcResult = calcResult.replace('#count#', 0);
                    var calcResult = calcResult.replace('#count#', 0);

                }

                if (squareMaterial) {

                    var calcResult = calcResult.replace('#square#', squareMaterial);
                    var calcResult = calcResult.replace('#square#', squareMaterial);
                    var calcResult = calcResult.replace('#square#', squareMaterial);
                    var calcResult = calcResult.replace('#square#', squareMaterial);
                    var calcResult = calcResult.replace('#square#', squareMaterial);
                    var calcResult = calcResult.replace('#square#', squareMaterial);

                } else {

                    var calcResult = calcResult.replace('#square#', 0);
                    var calcResult = calcResult.replace('#square#', 0);
                    var calcResult = calcResult.replace('#square#', 0);
                    var calcResult = calcResult.replace('#square#', 0);
                    var calcResult = calcResult.replace('#square#', 0);
                    var calcResult = calcResult.replace('#square#', 0);

                }

                if (priceProcessing) {

                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);
                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);
                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);
                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);
                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);
                    var calcResult = calcResult.replace('#price1m2#', priceProcessing);

                } else {

                    var calcResult = calcResult.replace('#price1m2#', 0);
                    var calcResult = calcResult.replace('#price1m2#', 0);
                    var calcResult = calcResult.replace('#price1m2#', 0);
                    var calcResult = calcResult.replace('#price1m2#', 0);
                    var calcResult = calcResult.replace('#price1m2#', 0);
                    var calcResult = calcResult.replace('#price1m2#', 0);

                }

                //Для ПВХ материалов рассчитываем постобработку только один раз, последующие варианты обработок ПВХ материалов ставим по нулям
                if (checkPvhMaterial) {

                    var resultPriceProcessing = 0;

                } else {

                    //var resultPriceProcessing = Math.ceil(math.evaluate(calcResult));
                    var resultPriceProcessing = math.evaluate(calcResult); //Убрано округление

                }

                totalPriceProcessing = totalPriceProcessing + resultPriceProcessing;

                console.log('Постобработка: ' + value.value + ', формула ' + calcResult + ' = ' + resultPriceProcessing);

                //Детальная стоимость обработки
                if (priceProcessingStr) {

                    priceProcessingStr += '###' + resultPriceProcessing;

                } else {

                    priceProcessingStr += resultPriceProcessing;

                }

                //Если не рассчитана постобработка, стоимость не выводим
                if (totalPriceProcessing > 0) {

                    checkObrabotka = true;

                }

                //Для ПВХ материалов рассчитываем постобработку только один раз, последующие варианты обработок ПВХ материалов ставим по нулям
                if (pvhMaterial == 'Y') {

                    checkPvhMaterial = true;

                }

            }

        });

        $('#processing_detail').append('<input type="hidden" name="PROCESSING_DETAIL[]" value="' + priceProcessingStr + '">');

        console.log('Верстка макета: ' + priceLayout);
		console.log('Количество изделий: ' + countMaterial);
		console.log('Количество макетов: ' + countLayouts);
		priceLayout = (priceLayout * countLayouts); //Пересчитываем стоимость верстки с учетом количество макетов

        //Стоимость материала
        //var priceMaterialOne = Math.ceil(squareMaterial * priceMaterial);
        var priceMaterialOne = squareMaterial * priceMaterial; //Убрано округление

        //var totalPriceMaterial = (priceMaterialOne * countMaterial) + priceLayout + totalPriceProcessing;
        var totalPriceMaterial = priceLayout + totalPriceProcessing; //Без стоимости материалов, только постобработка + стоимость верстки

        console.log('Стоимость материала: ' + squareMaterial + ' * ' + priceMaterial + ' = ' + priceMaterialOne);
        console.log('Общая стоимость материалов: ' + priceMaterialOne + ' * ' + countMaterial + ' = ' + priceMaterialOne * countMaterial);
        console.log('Стоимость верстки: ' + priceLayout);
        console.log('Общая стоимость постобработок: ' + totalPriceProcessing);

        $('#price_detail').append('<input type="hidden" name="PRICE_DETAIL[]" value="' + priceMaterialOne + '">');

        //Вес материала
        var weightMaterialOne = (squareMaterial * yardageMaterial);
        var totalWeightMaterial = (weightMaterialOne * countMaterial);

        console.log('Вес материала: ' + squareMaterial + ' * ' + yardageMaterial + ' = ' + weightMaterialOne);
        console.log('Общий вес материалов: ' + weightMaterialOne + ' * ' + countMaterial + ' = ' + totalWeightMaterial);

        //Общая стоимость и вес
        price += totalPriceMaterial;
        weight += totalWeightMaterial;

        //Общая площадь
        square += squareMaterial;

        console.log('Общая стоимость материалов + верстка + постобработки: ' + totalPriceMaterial);

    });

    //Неокругленный вес в граммах
    weightNotRounded = weight;

    //Общая площадь
    square = Math.ceil(square);

    //Округляем килограммы
    weight = Math.ceil(weight);

    //Общий вес материала (в граммах)
    weightGrams = weight * 1000;

    console.log('');
    console.log('Доставка:');
    console.log('');

    console.log('Общий вес в граммах (округленный): ' + weightGrams);
    console.log('Общий вес в килограммах (неокругленный): ' + weightNotRounded);

    //Стоимость доставки до 1 кг (в граммах)
    priceDelyveryBefore1kg = (priceDelyveryBefore1kg / 1000);

    //Стоимость доставки после 1 кг (в граммах)
    priceDelyveryAfter1kg = (priceDelyveryAfter1kg / 1000);

    //Если общий вес больше 1 кг
    if (weightGrams > 1000) {

        //Стоимость до 1 кг
        priceDelyvery += (priceDelyveryBefore1kg * 1000);

        //Стоимость за каждый последующий кг
        var weightGramsAfter1kg = (weightGrams - 1000);

        if (weightGramsAfter1kg > 0) {

            priceDelyvery += (weightGramsAfter1kg * priceDelyveryAfter1kg);

        }

    } else {

        //Стоимость до 1 кг
        priceDelyvery += (priceDelyveryBefore1kg * weightGrams);

    }

    console.log('Стоимость доставки: ' + priceDelyvery);

    //Общая стоимость материалов в заявке + общая стоимость доставки
    if (price > 0) {

        totalPrice = Math.ceil(price + priceDelyvery);
        priceDelyvery = Math.ceil(priceDelyvery);

    } else {

        price = 0;
        priceDelyvery = 0;
        totalPrice = 0;

    }

    console.log('Итого: ' + totalPrice);

    /* Количество грузомест */
    if (weightGrams > 12000) {

        deliveryPlace = Math.ceil((weightGrams / 12000));

    } else {

        deliveryPlace = 1;

    }

    /* Расчет кем доставлять */
    if (weightGrams >= 40000) {

        deliveryType = 'Сборный груз';

    } else {

        deliveryType = 'Экспресс';

    }

    $("#import input[name='PRICE']").val(totalPrice);
    $("#import input[name='DELIVERY_PRICE']").val(priceDelyvery);
    $("#import input[name='DELIVERY_PLACE']").val(deliveryPlace);
    $("#import input[name='DELIVERY_TYPE']").val(deliveryType);

    $("#import input[name='MATERIAL_VOLUME']").val(square);
    //$("#import input[name='MATERIAL_WEIGHT']").val(weight); //Общий вес в килограммах (округленный)
    $("#import input[name='MATERIAL_WEIGHT']").val(weightNotRounded); //Общий вес в килограммах (неокругленный)
    $("#import input[name='VOLUME_WEIGHT']").val('ОбъемныйВесКгМ3');

    //Если не рассчитана постобработка, стоимость не выводим
    if (checkObrabotka) {

        $('#total-price').html(totalPrice);

    } else {

        $('#total-price').html('0');

    }

    //Очищаем консоль
    var priceShow = $.getUrlVar('log');
    if (priceShow != 'Y') {

        console.clear();

    }

}

//Положение блока со стоимостью
function priceBlockPosition() {

    var priceBlock = $('#total-price').offset().top;
    var commentBlock = $('.zayavka-form-input-textarea').offset().top;
    var padding = $('.form-zayavka').height() + 60;

    if (commentBlock > priceBlock) {

        $('.file-block').css('margin-top', padding);

    }

}

//Простановка индексов для файлов и комментариев материала
function indexMaterial() {

    var allMaterial = $('.form-zayavka-new');

    $.each(allMaterial, function (index, value) {

        $('.material-comment textarea', value).attr('name', 'MATERIAL_COMMENT_' + index + '');
        $('.material-file .multifile-material', value).attr('name', 'MATERIAL_FILES_' + index + '[]');
        $('.material-file .add-multifile-material', value).attr('index', index);

        $('#inlineFormCustomSelectProcessing', value).attr('name', 'PROCESSING_' + index + '[]');
        $('#inlineFormCustomSelectImidg', value).attr('name', 'IMIDG_' + index + '');
        $('#inlineFormCustomSelectImidgPartner', value).attr('name', 'IMIDG_PARTNER_' + index + '');

    });

}

$.extend({

    getUrlVars: function () {

        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

        for (var i = 0; i < hashes.length; i++) {

            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];

        }

        return vars;

    },

    getUrlVar: function (name) {

        return $.getUrlVars()[name];

    }

});


