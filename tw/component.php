<?
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();
include_once("profile.php");//Подключаем сущность с пользователями
include_once("workday.php");//Подключаем сущность учёта рабочего времени
include_once("workday_pause.php");//Подключаем сущность учёта пауз

global $USER;
if ($USER->IsAuthorized())//Если пользователь авторизован
{
	
	$result = \Bitrix\Main\UserTable::getList(array(
    'select' => array('LOGIN','NAME','LAST_NAME'), // Получаем данные о текущем юзере из Битрикса
	'filter' => array('LOGIN'=>$USER->GetLogin()),
));

	while ($arUser = $result->fetch()) 
	{
		$arr_user=array('login'=>$arUser['LOGIN'],'name'=>$arUser['NAME'], 'last_name'=>$arUser['LAST_NAME'] );

	}	
		
	$result_user = PROFILETable::GetList(array(
    'select' => array('id'), // Получаем id о текущем юзере из нашей сущности
	'filter' => array('login'=>$arr_user['login']),
))->fetchAll();	

	if (count($result_user)==0)//Если его нет, вставляем
		{
			
			$diff = (\CTimeZone::GetOffset())/3600;//Смещение времени в часах
			$result_insert_user=PROFILETable::add(array('login'=>$arr_user['login'],'name' =>$arr_user['name'], 'last_name' =>$arr_user['last_name'], 'offset' => $diff));//Вставляем
			if ($result_insert_user->isSuccess())//Если вставка хорошо пошла, получаем id
				$id_user = $result_insert_user->getId();//Получаем id
		}

		$id_user =$result_user[0]['id'];
		$date = date('Y-m-d');
		$timestamp = MakeTimeStamp($date, 'YYYY-MM-DD');
		$convertDateTime = ConvertTimeStamp($timestamp);//Получаем текущий день для фильтра

		$work_day = WORKDAYTable::GetList(array(
		'select' => array('id', 'date_stop'), // Получаем id и  окончание рабочего дня
		'filter' => array(">=date_start" => $convertDateTime, 'profile_id'=>$id_user ),//Проверяем начал ли юзер рабочий день
	))->fetchAll();	
	if (count($work_day)>0)//Если рабочий день начат и браузер был закрыт
	{
		if ($work_day[0]['date_stop']!=false)//Проверяем не закончил ли юзер рабочий день.
		{
			ShowError ("Вы закончили рабочий день. Отдыхайте!");//Если по условиям задачи, после того как юзер закончил рабочий день не давать начать по новой, выдаём ошибку.
			return;
		}
		$current_work_day=true;
		$_SESSION['id_work']=$work_day[0]['id'];
		$query_pause=WORKDAY_PAUSETable::GetList(array(
		'select' => array('id'), // Получаем id 
		'filter' => array(">=date_start" => $convertDateTime, 'workday_id'=>$work_day[0]['id'],'=date_stop'=>false ),//Если юзер нажал паузу и не закончил отдых

	))->fetchAll();	
		if (count($query_pause)>0)
		{
			$pause=true;
			$_SESSION['id_pause']=$query_pause[0]['id'];
			
		}
			
		else
			$pause=false;
	}
		
	else
		$current_work_day=false;
	$arResult["id_user"]=$id_user;
	$arResult["current_work_day"]=$current_work_day;
	$arResult["pause"]=$pause;
	$this->SetResultCacheKeys(array("id_user", "current_work_day", "pause"));

	$this->includeComponentTemplate();		
}

