$(document).ready(function () {

    /* Создание заявки из файла */

    //Прикрепляем файл
    $('body').on('click', 'span.import-file', function (e) {
        e.preventDefault();

        $('#import-file input').click();

    });

    //Проверяем прикрепление файла
    $('body').on('change', '#import-file .multifile', function (e) {
        e.preventDefault();

        var filePatch = $('#import-file .multifile').val();

        if (filePatch) {

            $('#loader').show();

            $('#import-file').submit();
        }

    });

    /* Фильтрация заявок */

    //Фильтрация по статусу заказа
    $('body').on('change', '#filterStatus', function () {

        $('#loader').show();

        var href = $('.filter-block form').attr('action');
        var statusId = $('#filterStatus option:selected').val();

        if (href.indexOf('?') == -1) {

            window.location.href = href + '?status=' + statusId;

        } else {

            window.location.href = href + '&status=' + statusId;

        }

    });

    //Фильтрация по номеру заявки
    $('body').on('click', '.filter-block .filter-number', function () {

        $('#loader').show();

        var href = $('.filter-block form').attr('action');
        var orderId = $(".filter-block form input[name='order']").val();

        if (href.indexOf('?') == -1) {

            window.location.href = href + '?order=' + orderId;

        } else {

            window.location.href = href + '&order=' + orderId;

        }

    });

    //Фильтрация по коду точки
    $('body').on('click', '.filter-block .point-code', function () {

        $('#loader').show();

        var href = $('.filter-block form').attr('action');
        var pointCode = $(".filter-block form input[name='point_code']").val();

        if (href.indexOf('?') == -1) {

            window.location.href = href + '?point_code=' + pointCode;

        } else {

            window.location.href = href + '&point_code=' + pointCode;

        }

    });

    //Подбор кода точки
    $('body').on('input click', ".filter-block form input[name='point_code']", function () {

        var name = $(this).val();

        $('#pointCodeContainer').css('display', 'block');

        if (name !== undefined && name.length >= 3) {

            $('#pointCodeContainer').html('Поиск...');

            $.ajax({
                type: 'POST',
                url: '/ajax/point_code_search.php',
                data: {'name': name},
                success: function (data) {

                    $('#pointCodeContainer').html(data);

                }
            });

        } else {

            $('#pointCodeContainer').html('Вводите не менее 3-х символов');

        }

    });

    //Выбор кода точки
    $('body').on('click', ".point-item", function () {

        var pointId = $(this).attr('data-id');
        var pointName = $(this).attr('data-name');

        if (pointName) {

            $(".filter-block form input[name='point_code']").val(pointName);
            $('#pointCodeContainer').css('display', 'none');

        }

    });

    //Скрыть блок с кодами точек
    $(document).mouseup(function (e) {

        var pointContainer = $('#pointCodeContainer');

        if (pointContainer.has(e.target).length === 0) {

            pointContainer.hide();

        }

    });

    //Фильтрация по стоимости
    $('body').on('click', '.filter-block .filter-price', function () {

        $('#loader').show();

        var href = $('.filter-block form').attr('action');
        var priceFrom = $(".filter-block form input[name='price_from']").val();
        var priceTo = $(".filter-block form input[name='price_to']").val();
        var dateFrom = $(".date-block input[name='date_from']").val();
        var dateTo = $(".date-block input[name='date_to']").val();

        if (href.indexOf('?') == -1) {

            window.location.href = href + '?price_from=' + priceFrom + '&price_to=' + priceTo + '&date_from=' + dateFrom + '&date_to=' + dateTo;

        } else {

            window.location.href = href + '&price_from=' + priceFrom + '&price_to=' + priceTo + '&date_from=' + dateFrom + '&date_to=' + dateTo;

        }

    });

    //Фильтрация по дате создания
    $('body').on('click', '.date-block .fa-search', function () {

        $('#loader').show();

        var href = $('.date-block form').attr('action');
        var priceFrom = $(".filter-block form input[name='price_from']").val();
        var priceTo = $(".filter-block form input[name='price_to']").val();
        var dateFrom = $(".date-block form input[name='date_from']").val();
        var dateTo = $(".date-block form input[name='date_to']").val();

        if (href.indexOf('?') == -1) {

            window.location.href = href + '?price_from=' + priceFrom + '&price_to=' + priceTo + '&date_from=' + dateFrom + '&date_to=' + dateTo;

        } else {

            window.location.href = href + '&price_from=' + priceFrom + '&price_to=' + priceTo + '&date_from=' + dateFrom + '&date_to=' + dateTo;

        }

    });

    //Быстрая фильтрация по статусам
    $('body').on('click', 'a.btn-cart.status-list', function (e) {

        $('#loader').show();

    });

    /* Управление заявками */

    //Список материалов
    $('body').on('click', 'a.order-info', function (e) {
        e.preventDefault();

        var orderId = $(this).attr('data-order');

        $(this).children('.text-success').toggleClass('fa-angle-down fa-angle-up');

        //Материалы заказа
        if ($(this).children('.text-success').hasClass('fa-angle-down')) {

            $('tr.material.' + orderId + '').show();

        } else {

            $('tr.material.' + orderId + '').hide();

        }

    });

    //Копировать заявку
    $('body').on('click', 'a.order-copy', function (e) {
        e.preventDefault();

        $('#loader').show();

        var orderId = $(this).attr('data-order');

        window.location.href = '/zayavka/add.php' + '?id=' + orderId + '&type=copy';

    });

    //Редактировать заявку
    $('body').on('click', 'a.order-edit', function (e) {
        e.preventDefault();

        $('#loader').show();

        var orderId = $(this).attr('data-order');
        var userId = $(this).attr('data-user');

        if (userId) {

            window.location.href = '/zayavka/add.php' + '?id=' + orderId + '&type=edit' + '&user=' + userId;

        } else {

            window.location.href = '/zayavka/add.php' + '?id=' + orderId + '&type=edit';

        }

    });

    //Удалить заявку
    $('body').on('click', 'a.order-delete', function (e) {
        e.preventDefault();

        if (confirm('Подтверждаете удаление заявки?')) {

            var orderId = $(this).attr('data-order');
            var orderCode = $(this).attr('data-code');

            if (orderId) {

                //Подсвечиваем
                $('tr#' + orderId + '').css('background', '#dc7168');
                $('tr#' + orderId + '').css('color', '#fff');

                $('#loader').show();

                $.ajax({
                    type: 'POST',
                    url: '/ajax/delete_order.php',
                    data: {'id': orderId, 'code': orderCode},
                    success: function (data) {

                        var obj = JSON.parse(data);

                        if (obj.errors) {

                            $('#loader').hide();

                        } else {

                            //$('#loader').hide();
                            $('tr#' + orderId + '').remove();

                            location.reload();

                        }

                    }
                });

            }

        }

    });

    //Отправить заявку в работу
    $('body').on('click', 'a.order-play', function (e) {
        e.preventDefault();

        var orderId = $(this).attr('data-order');
        var orderCode = $(this).attr('data-code');
        var orderPrice = parseInt($(this).attr('data-price'));
        var orderPriceMin = parseInt($(this).attr('data-pricemin'));
        var roleType = $(this).attr('data-type');
        var orderPlay = $(this).attr('data-play');

        if (orderPlay == 'Y') {

            //Добавляем новое значение атрибута и класса
            $(this).attr('data-play', 'N');
            $('i', this).removeClass().addClass('fa fa-play text-secondary');

            //Условие всегда выполняется
            if (true) {

                //Все заявки отправляем на подтверждение по Email
                $.ajax({
                    type: 'POST',
                    url: '/ajax/play_order_new.php',
                    data: {'id': orderId, 'code': orderCode},
                    success: function (data) {

                        alert('Заявка после проверки будет отправлена в работу');

                    }
                });

            } else {

                if (roleType == 'supervisor' && orderPrice <= orderPriceMin) {

                    if (orderId) {

                        //Подсвечиваем
                        $('tr#' + orderId + '').css('background', '#7bdc96');
                        $('tr#' + orderId + '').css('color', '#fff');

                        $('#loader').show();

                        $.ajax({
                            type: 'POST',
                            url: '/ajax/play_order.php',
                            data: {'id': orderId, 'code': orderCode},
                            success: function (data) {

                                var obj = JSON.parse(data);

                                if (obj.errors) {

                                    location.reload();

                                } else {

                                    location.reload();

                                }

                            }
                        });

                    }

                } else if (roleType == 'manager_mts' || roleType == 'general_manager_jampica') {

                    if (orderId) {

                        //Подсвечиваем
                        $('tr#' + orderId + '').css('background', '#7bdc96');
                        $('tr#' + orderId + '').css('color', '#fff');

                        $('#loader').show();

                        $.ajax({
                            type: 'POST',
                            url: '/ajax/play_order.php',
                            data: {'id': orderId, 'code': orderCode},
                            success: function (data) {

                                var obj = JSON.parse(data);

                                if (obj.errors) {

                                    location.reload();

                                } else {

                                    location.reload();

                                }

                            }
                        });

                    }

                } else if (orderPrice <= orderPriceMin) {

                    $.ajax({
                        type: 'POST',
                        url: '/ajax/order_less_5000.php',
                        data: {'id': orderId, 'code': orderCode},
                        success: function (data) {

                            alert('Заявка после проверки будет отправлена в работу');

                        }
                    });

                } else {

                    //От пользователя все заявки идут Супервайзерам
                    if (roleType == 'simple_user') {

                        $.ajax({
                            type: 'POST',
                            url: '/ajax/order_less_5000.php',
                            data: {'id': orderId, 'code': orderCode},
                            success: function (data) {

                                alert('Заявка после проверки будет отправлена в работу');

                            }
                        });

                    } else {

                        $.ajax({
                            type: 'POST',
                            url: '/ajax/order_more_5000.php',
                            data: {'id': orderId, 'code': orderCode},
                            success: function (data) {

                                alert('Заявка после проверки будет отправлена в работу');

                            }
                        });

                    }

                }

            }

        } else {

            alert('Заявка находится на проверке');

        }

    });

    /* Календарь */

    //Дата создания 'С'
    $('body').on('click', ".date-block input[name='date_from']", function (e) {
        e.preventDefault();

        $('body').click();
        BX.calendar({node: this, field: this, bTime: false})

    });
    $('body').on('click', '.date-from .fa-calendar', function (e) {
        e.preventDefault();

        $(".date-block input[name='date_from']").click();

    });

    //Дата создания 'По'
    $('body').on('click', ".date-block input[name='date_to']", function (e) {
        e.preventDefault();

        $('body').click();
        BX.calendar({node: this, field: this, bTime: false})

    });
    $('body').on('click', '.date-to .fa-calendar', function (e) {
        e.preventDefault();

        $(".date-block input[name='date_to']").click();

    });

    //Выгрузить заявки
    $('body').on('click', 'a.order-list', function (e) {
        e.preventDefault();

        var userId = $(this).attr('data-user');
        var statusId = $('#filterStatus option:selected').val();
        var orderName = $(".filter-block form input[name='order']").val();
        var priceFrom = $(".filter-block form input[name='price_from']").val();
        var priceTo = $(".filter-block form input[name='price_to']").val();
        var dateFrom = $(".date-block input[name='date_from']").val();
        var dateTo = $(".date-block input[name='date_to']").val();
        var report = $(this).attr('data-report');
        var pointCode = $(".filter-block form input[name='point_code']").val();

        window.open('/ajax/export_order.php' + '?id=' + userId + '&status=' + statusId + '&order=' + orderName + '&price_from=' + priceFrom + '&price_to=' + priceTo + '&date_from=' + dateFrom + '&date_to=' + dateTo + '&report=' + report + '&point_code=' + pointCode, '_blank');

    });

    /* Каталог материалов */

    //Добавить материал в заявку
    $('body').on('click', 'a.basket-add', function (e) {
        e.preventDefault();

        var orderId = $(this).attr('id');

        if (orderId) {

            $('#loader').show();

            $.ajax({
                type: 'POST',
                url: '/ajax/add_card.php',
                data: {'id': orderId},
                success: function (data) {

                    var obj = JSON.parse(data);

                    if (obj.errors) {

                        $('#loader').hide();

                    } else {

                        $('#loader').hide();
                        $('#' + orderId + '').text('Добавлена в заявку').addClass('active');

                        //Малая корзина
                        $('.katalog-materialov .small-basket').addClass('active');
                        $('.katalog-materialov .small-basket-count').html(obj.successes + ' ' + getNumEnding(obj.successes));

                    }

                }
            });

        }

    });

    //Напоминание, незавершенные заявки
    $('#unfinished-order').modal('show');

    //Закрываем напоминание (закрыть)
    $('body').on('click', '#unfinished-order .close.unfinished-order', function (e) {
        e.preventDefault();

        $('#loader').show();

        $.ajax({
            type: 'POST',
            url: '/ajax/reminder_close.php',
            success: function () {

                $('#loader').hide();
                $('#unfinished-order').modal('hide');

            }
        });

    });

    //Закрываем напоминание (посмотреть)
    $('body').on('click', '#unfinished-order .btn-cart.unfinished-order', function (e) {
        e.preventDefault();

        $('#loader').show();

        $.ajax({
            type: 'POST',
            url: '/ajax/reminder_close.php',
            success: function () {

                window.location.href = '/zakazy/?status=51';

            }
        });

    });

    //Открыть форму рекламации
    $('body').on('click', '.order-defect', function (e) {
        e.preventDefault();

        var orderId = $(this).attr('data-order');
        var orderCode = $(this).attr('data-code');
        var orderName = $(this).attr('data-name');

        $('#defect-order .defect-order-id').html(orderName);
        $('#defect-order-form .defect-result').html('');

        $("#defect-order-form input[name='id']").val(orderId);
        $("#defect-order-form input[name='code']").val(orderCode);
        $("#defect-order-form textarea[name='DEFECT_MESSAGE']").val('');

        if (orderId) {

            $('#defect-order').modal('show');

        }

    });

    //Отправить форму рекламации
    $('body').on('submit', '#defect-order-form', function (e) {
        e.preventDefault();

        $('#loader').show();

        var form = $(this);
        formData = new FormData(form.get(0));

        $.ajax({
            url: '/ajax/defect_order.php',
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                var obj = JSON.parse(data);

                if (obj.errors) {

                    $('#loader').hide();
                    $('#defect-order-form .defect-result').html('Ошибка: ' + obj.errors).css('color', 'red');

                } else {

                    $('#loader').hide();
                    $("#defect-order-form input[name='files[]']").val('');
                    $("#defect-order-form textarea[name='DEFECT_MESSAGE']").val('');
                    $('#defect-order-form .defect-result').html(obj.successes).css('color', 'green');

                }

            }
        });

    });

    //Информация о роли пользователя
    $('body').on('click', '.role-info-icon', function (e) {
        e.preventDefault();

        $('.role-info').toggle();

    });

});

//Правильное окончание
function getNumEnding(iNumber) {

    var sEnding, i;
    var aEndings = ['позиция', 'позиции', 'позиций'];

    //Целочисленный остаток от деления
    iNumber = iNumber % 100;

    if (iNumber >= 11 && iNumber <= 19) {
        sEnding = aEndings[2];
    } else {
        i = iNumber % 10;
        switch (i) {
            case (1):
                sEnding = aEndings[0];
                break;
            case (2):
            case (3):
            case (4):
                sEnding = aEndings[1];
                break;
            default:
                sEnding = aEndings[2];
        }
    }

    //Возвращаем результат
    return sEnding;
}