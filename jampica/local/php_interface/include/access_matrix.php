<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

function accessMatrix()
{

    global $USER;

    //Матрица доступа
    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $_SESSION['MATRIX'] = [];

        //Роль текущего пользователя
        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_ROLE_TYPE',
            'PROPERTY_UF_USER_REFERRAL',
            'PROPERTY_UF_SEE_POINTS_TP',
            'PROPERTY_UF_SEE_POINTS_ALL',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_USER_ROLE' => $USER->GetID(), //ID пользователя для которого создана роль
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            if ($arRes['PROPERTY_UF_ROLE_TYPE_ENUM_ID'] == 66) {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'manager_mts';

            } elseif ($arRes['PROPERTY_UF_ROLE_TYPE_ENUM_ID'] == 67) {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'manager_jampica';

            } elseif ($arRes['PROPERTY_UF_ROLE_TYPE_ENUM_ID'] == 68) {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'supervisor';

            } elseif ($arRes['PROPERTY_UF_ROLE_TYPE_ENUM_ID'] == 71) {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'general_manager_jampica';

            } elseif ($arRes['PROPERTY_UF_ROLE_TYPE_ENUM_ID'] == 82) {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'controller';

            } else {

                $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'simple_user';

            }

            //Закрепленные пользователи
            if (!empty($arRes['PROPERTY_UF_USER_REFERRAL_VALUE'])) {

                $_SESSION['MATRIX']['REFERRAL'][] = $arRes['PROPERTY_UF_USER_REFERRAL_VALUE'];

            }

            $_SESSION['MATRIX']['REFERRAL'][] = $USER->GetID();

            //Видит точки закрепленных пользователей
            if ($arRes['PROPERTY_UF_SEE_POINTS_TP_VALUE'] == 'Y') {

                $_SESSION['MATRIX']['ROLE']['SEE_POINTS_TP'] = $arRes['PROPERTY_UF_SEE_POINTS_TP_VALUE'];

            }

            //Видит все коды точек своего региона - Супервайзер-специалист
            if ($arRes['PROPERTY_UF_SEE_POINTS_ALL_VALUE'] == 'Y') {

                $_SESSION['MATRIX']['ROLE']['SEE_POINTS_ALL'] = $arRes['PROPERTY_UF_SEE_POINTS_ALL_VALUE'];

            }

        }

        //Нет роли, простой пользователь
        if (empty($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'])) {

            $_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] = 'simple_user';

        } else {

            //Есть роль
            if (!empty($_SESSION['MATRIX']['REFERRAL'])) {

                //Может создавать заявки только под закрепленными пользователями
                $res = Bitrix\Main\UserTable::getList([
                    'select' => [
                        'ID',
                        'EMAIL',
                        'NAME',
                        'SECOND_NAME',
                        'LAST_NAME',
                    ],
                    'filter' => [
                        'ACTIVE' => 'Y',
                        'ID' => $_SESSION['MATRIX']['REFERRAL'], //ID закрепленных пользователей текущего пользователя
                    ],
                    'limit' => false,
                ]);

                while ($arRes = $res->Fetch()) {

                    $_SESSION['MATRIX']['REFERRAL_INFO'][] = $arRes;

                }

            } else {

                //Может создавать заявки под любым пользователем
                if (in_array($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'], ['general_manager_jampica', 'manager_jampica'])) {

                    if ($_SESSION['MATRIX']['ROLE']['UF_ROLE_TYPE'] == 'manager_jampica') {

                        $arResultMerge = getUserIdGeneralManagerJampica();

                        $res = Bitrix\Main\UserTable::getList([
                            'select' => [
                                'ID',
                                'EMAIL',
                                'NAME',
                                'SECOND_NAME',
                                'LAST_NAME',
                            ],
                            'filter' => [
                                'ACTIVE' => 'Y',
                                '!ID' => $arResultMerge, //ID пользователей, кроме главных менеджеров
                            ],
                            'limit' => false,
                        ]);


                    } else {

                        $res = Bitrix\Main\UserTable::getList([
                            'select' => [
                                'ID',
                                'EMAIL',
                                'NAME',
                                'SECOND_NAME',
                                'LAST_NAME',
                            ],
                            'filter' => [
                                'ACTIVE' => 'Y', //ID пользователей, все активные пользователи
                            ],
                            'limit' => false,
                        ]);

                    }

                    while ($arRes = $res->Fetch()) {

                        $_SESSION['MATRIX']['REFERRAL_INFO'][] = $arRes;

                    }

                }

            }

        }

    }

}

/**
 * Возвращает Email Главный менеджер Jampica
 * @return array
 */
function getEmailAdministratorJumpica()
{

    $arEmail = [];

    $arFilter = [
        'ACTIVE' => 'Y',
        'GROUPS_ID' => 1,
    ];

    $arSelect = [
        'ID',
        'EMAIL',
        'NAME',
        'SECOND_NAME',
        'LAST_NAME',
    ];

    $res = CUser::GetList(($by = 'ID'), ($order = 'DESC'), $arFilter, $arSelect);

    while ($arRes = $res->Fetch()) {

        $arEmail[] = $arRes['EMAIL'];

    }

    return $arEmail;

}

/**
 * Возвращает Email Подтверждать активацию аккаунта
 * @return array
 */
function getEmailManagerActivationUser()
{

    $arEmail = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [67, 71], //Главный менеджер и менеджер
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arEmail[] = $arRes['EMAIL'];

            }

        }

    }

    return $arEmail;

}

/**
 * Возвращает Email Подтверждать изменение адреса доставки
 * @return array
 */
function getEmailManagerActivationAddress()
{

    $arEmail = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [66, 71], //Главный менеджер и менеджер офиса МТС
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arEmail[] = $arRes['EMAIL'];

            }

        }

    }

    return $arEmail;

}

/**
 * Возвращает Email Отправлять в производство больше 5000
 * @return array
 */
function getEmailManagerOrderMore5000()
{

    $arEmail = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [66], //Менеджер офиса МТС
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arEmail[] = $arRes['EMAIL'];

            }

        }

    }

    return $arEmail;

}

/**
 * Возвращает Email Отправлять в производство меньше 5000
 * @return array
 */
function getEmailManagerOrderLess5000()
{

    $arEmail = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [68], //Супервайзер
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arEmail[] = $arRes['EMAIL'];

            }

        }

    }

    return $arEmail;

}

/**
 * Возвращает ID пользователей Администратор 0
 * @return array
 */
function getUserIdGeneralManagerJampica()
{

    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [71], //Главный менеджер
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

    }

    return $arUserId;

}

/**
 * Возвращает ID пользователей Администратор 1
 * @return array
 */
function getUserIdManagerJampica()
{

    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [67], //Менеджер
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

    }

    return $arUserId;

}

/**
 * Возвращает ID пользователей Администратор 2
 * @return array
 */
function getUserIdManagerMts()
{

    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [66], //Менеджер офиса МТС
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

    }

    return $arUserId;

}

/**
 * Возвращает Email Супервайзера для пользователя
 * @return array
 */
function getEmailUserManagerOrderLess5000($userId)
{

    $arEmail = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order')) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [68], //Супервайзер
            'PROPERTY_UF_USER_REFERRAL' => $userId, //ID закрепленного пользователя
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID пользователей
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arEmail[] = $arRes['EMAIL'];

            }

        }

    }

    return $arEmail;

}

/**
 * Возвращает информацию о супервайзере пользователя
 * @return array
 */
function getUserManagerInfo($userId)
{

    $arManager = [];
    $arUserId = [];

    if (Loader::includeModule('iblock') && Loader::includeModule('jumpica.order') && !empty($userId)) {

        $iblockIdMatrix = Option::get('jumpica.order', 'IBLOCK_ID_MATRIX');

        $arOrder = ['ID' => 'ASC'];
        $arSelect = [
            'PROPERTY_UF_USER_ROLE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockIdMatrix,
            'PROPERTY_UF_ROLE_TYPE' => [68], //Супервайзер
            'PROPERTY_UF_USER_REFERRAL' => $userId, //ID закрепленного пользователя
        ];

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        while ($arRes = $res->Fetch()) {

            //ID супервайзера
            $arUserId[] = $arRes['PROPERTY_UF_USER_ROLE_VALUE'];

        }

        if (!empty($arUserId)) {

            $res = Bitrix\Main\UserTable::getList([
                'select' => [
                    'ID',
                    'EMAIL',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                    'PERSONAL_PHONE',
                    'UF_CRM_CONTACT_ID',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                    'ID' => $arUserId,
                ],
                'limit' => false,
            ]);

            while ($arRes = $res->Fetch()) {

                $arManager[] = $arRes;

            }

        }

    }

    return $arManager;

}

?>
