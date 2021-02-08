<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');//Обязательная строка инициирующая движок Битрикса, но не подключающая шаблон
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();

include_once("workday.php");//Подключаем сущность учёта рабочего времени
include_once("workday_pause.php");//Подключаем сущность учёта пауз

if($_POST['dest']=='begin_day')//Если запустили рабочий день
{
	$result_begin_work=WORKDAYTable::add(array('profile_id'=>$_POST['id_user'],'date_start' =>new \Bitrix\Main\Type\DateTime()));//Вставляем
	if ($result_begin_work->isSuccess())//Если вставка хорошо пошла, получаем id
	{
		$id_work = $result_begin_work->getId();//Получаем id
		$_SESSION['id_work']=$id_work;//Присваиваем переменной сессии ИД.
	}
			
	echo 'Ура! Работа!';
	exit;
}

if($_POST['dest']=='the_end')//Кончили 
{
	$end = WORKDAYTable::update($_SESSION['id_work'], array('date_stop' => new \Bitrix\Main\Type\DateTime()));//Апдейтим сущность рабочего дня
	unset($_SESSION['id_work']);//Удаляем переменную сессии работы
	echo 'Кончил дело, гуляй смело!';
	exit;
}

if($_POST['dest']=='pause')//Если запустили паузу
{
	$result_pause=WORKDAY_PAUSETable::add(array('workday_id'=>$_SESSION['id_work'],'date_start' =>new \Bitrix\Main\Type\DateTime()));//Вставляем
	if ($result_pause->isSuccess())//Если вставка хорошо пошла, получаем id
	{
		$id_pause = $result_pause->getId();//Получаем id
		$_SESSION['id_pause']=$id_pause;//Присваиваем переменной сессии ИД.
	}
			
	echo 'Отдых!';
	exit;
}

if($_POST['dest']=='end_pause')//Кончили бездельничать
{
	$end = WORKDAY_PAUSETable::update($_SESSION['id_pause'], array('date_stop' => new \Bitrix\Main\Type\DateTime()));//Апдейтим сущность паузы
	unset($_SESSION['id_pause']);//Удаляем переменную сессии паузы
	echo 'Ну, за работу!';
	exit;
}
var_dump($_POST);


