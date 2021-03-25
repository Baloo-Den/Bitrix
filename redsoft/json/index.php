<?php
//Json замена
$filick = file_get_contents('chart.json' );
$data = json_decode($filick, TRUE); 
$i=0;
foreach($data as $el)//Перебираем все элемента файла
{
	$j=0;
	if($el[0]==100 && $el[1]==100 && $el[2]==100 && $el[3]==100)//Если четыре подряд равны 100
		foreach($el as $the_end)
		{
			if ($the_end==100)
				$data[$i][$j]=null;
			else
				break; //Если не равны прерываем цикл
			$j++;
		}
	$i++;
}

file_put_contents('chart_result.json', json_encode($data));//Кодируем и сохраняем

?>