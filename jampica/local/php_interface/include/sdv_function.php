<?php

function get_gru()
{
	global $USER;
	$arFilter = array("ID" => $USER->GetID());
	$arParams["SELECT"] = array("UF_GRU");
	$arRes = CUser::GetList($by,$desc,$arFilter,$arParams);//Получаем ИД роли
		if ($res = $arRes->Fetch()) 
			{
				$rsGender = CUserFieldEnum::GetList(array(), array("ID" =>  $res["UF_GRU"]));
				if ($arCat = $rsGender->GetNext())
					$gru=$arCat["VALUE"];//получаем роль

			}
	return $gru;	
	}

?>
