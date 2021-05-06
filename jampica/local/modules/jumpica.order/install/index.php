<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (!defined('B_PROLOG_INCLUDED')) {

    die();
}

Loc::loadMessages(__FILE__);

class jumpica_order extends CModule
{

    public $MODULE_ID = 'jumpica.order';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function jumpica_order()
    {

        self::__construct();

    }

    public function __construct()
    {

        $arModuleVersion = [];

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('JUMPICA_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('JUMPICA_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('JUMPICA_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('JUMPICA_MODULE_PARTNER_URI');

    }

    /**
     * Процесс установки модуля
     */
    public function DoInstall()
    {

        global $APPLICATION;

        //Если модуль не установлен
        if (!ModuleManager::isModuleInstalled($this->MODULE_ID)) {

            //Регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            $this->InstallMailEvent();
            $this->InstallModuleOptions();
            $this->InstallModuleAgents();

            $APPLICATION->IncludeAdminFile(Loc::getMessage('JUMPICA_MODULE_INSTALL_TITLE'), __DIR__ . '/step.php');

        }

    }

    /**
     * Процесс удаления модуля
     */
    public function DoUninstall()
    {

        global $APPLICATION;

        //Если модуль установлен
        if (ModuleManager::isModuleInstalled($this->MODULE_ID)) {

            $this->UnInstallDB();
            $this->UnInstallEvents();
            $this->UnInstallFiles();
            $this->UnInstallMailEvent();
            $this->UnInstallModuleOptions();
            $this->UnInstallModuleAgents();

            //Удаление модуля из системы
            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage('JUMPICA_MODULE_UNINSTALL_TITLE'), __DIR__ . '/unstep.php');

        }

    }

    /**
     * Создание файлов модуля
     */
    public function InstallFiles()
    {
        return true;
    }

    /**
     * Удаление файлов модуля
     */
    public function UnInstallFiles()
    {
        return true;
    }

    /**
     * Создание таблиц модуля
     */
    public function InstallDB()
    {
        return true;
    }

    /**
     * Удаление таблиц модуля
     */
    public function UnInstallDB()
    {
        return true;
    }

    /**
     * Добавление событий модуля
     */
    function InstallEvents()
    {
        return true;
    }

    /**
     * Удаление событий модуля
     */
    function UnInstallEvents()
    {
        return true;
    }

    /**
     * Создание почтовых шаблонов
     */
    function InstallMailEvent()
    {
        return true;
    }

    /**
     * Удаление почтовых шаблонов
     */
    function UnInstallMailEvent()
    {
        return true;

    }

    /**
     * Установка настроек модуля
     */
    function InstallModuleOptions()
    {
        return true;

    }

    /**
     * Удаление настроек модуля
     */
    function UnInstallModuleOptions()
    {
        return true;
    }

    /**
     * Добавление агентов модуля
     */
    function InstallModuleAgents()
    {
        return true;
    }

    /**
     * Удаление агентов модуля
     */
    function UnInstallModuleAgents()
    {
        return true;
    }

}

?>