<?
if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true) die ();

include_once("orm.php");

if ($this->startResultCache()) 
{
$connection = \Bitrix\Main\Application::getConnection();
$tableName=ORMTable::getTableName();//Получаем название таблицы
$result = $connection->isTableExists($tableName);//Проверяем на существование нашей таблицы
if ($result==false)//Если таблицы нет, то создаём её
{
	$connection->queryExecute("CREATE TABLE `".$tableName."` (
    `ID` int NOT NULL AUTO_INCREMENT,
    `NAME` varchar(255) NOT NULL,
    `DATE_INSERT` datetime NOT NULL,
    PRIMARY KEY(`ID`)
);");
}
else 
$el=ORMTable::GetList()->fetchAll();//Получаем элементы из нашей таблицы

if (count($el)==0)//Если пусто
{
	$my_test_el=array(//Массив вставляемыми данными
		array(
		 'NAME' =>'Иванов',
		),
		array(
		 'NAME' =>'Петров',
		),
		array(
		 'NAME' =>'Сидоров',
		),		
	);
	foreach($my_test_el as $data)
		ORMTable::add(array('NAME' =>$data['NAME'], 'DATE_INSERT' => new \Bitrix\Main\Type\DateTime() ));//Вставляем наши данные
}
else
	$arResult["el"]=$el;
	$this->SetResultCacheKeys(array("el",));
	$this->includeComponentTemplate();
} 