Задания для «5 углов».
1.	ответ в файле index.php
2.	$login=\Bitrix\Main\Engine\CurrentUser::get()->getLogin();//Получаем логин
$proc =1+( preg_match_all('/[wrtpsdfghklzxcvbnmбвгджзйклмнпрстфхцчшщъь]/iu', $login, $result))/100;//Считаем количество согласных букв и делаем множитель
$new_price=$old_price*$proc;//Получаем новую цену
3.В папке local создаём папку modules, если её нет. Там уже создаём папку для нашего модуля. Внутри ещё три папки: install –для установки и удаления модуля, lang –языковые файлы, lib – библиотеки файлов. Кроме папок создаём в корне нашего модуля два файла: include.php – для вызова модуля, options.php – настройки в админке. В папке install создаём index.php – описание модуля, установка и удаление его. Там же version.php – версия модуля и дата обновления. Ещё step.php и unset.php для установки и удаления модуля. 
4. Ответ в папке local