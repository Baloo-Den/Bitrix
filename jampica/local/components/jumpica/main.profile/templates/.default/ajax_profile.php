<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?php
//echo 19849648666;

//dump($_POST);  //["old_password"] ["new_password"]
global $USER;
$id = intval($USER->GetID());
if($_POST ["email"])//Если меняют емейл
{
	$user = new CUser;
	$fields = Array(
		  "EMAIL"             => $_POST["email"],
		  "PASSWORD"          => $_POST["new_password"],
		  "CONFIRM_PASSWORD"  => $_POST["new_password"],
		  );
		if($user->Update($id, $fields))
			echo '<h1>Пароль и емейл изменены</h1>';
		else
			echo $strError .= $user->LAST_ERROR;	
}
else //Меняют только пароль
{

	$user = new CUser;
	$fields = Array(
		  "PASSWORD"          => $_POST["new_password"],
		  "CONFIRM_PASSWORD"  => $_POST["new_password"],
		  );
		if($user->Update($id, $fields))
			echo '<h1>Пароль изменен</h1>';
		else
			echo $strError .= $user->LAST_ERROR;
		//echo $user->LAST_ERROR;
		//dump($fields);
}	
exit;
?>
