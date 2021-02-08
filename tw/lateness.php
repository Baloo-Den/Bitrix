<?php
use Bitrix\Main\Entity;
class LATENESS extends Entity\DataManager//Класс для работы с рабочими паузами
{
    public static $table_name = "lateness";
	public static function getTableName()
    {
        return self::$table_name;
    }

    public static function getMap()
    {
        return array(
            'id' => new Entity\IntegerField('id', array(
                'primary' => true,
                'autocomplete' => true
            )),
			'profile_id' => new Entity\IntegerField('profile_id'),
			'date' => new Entity\DatetimeField('date'),
        );
    }
	
	 public static function create_table()//Создание таблицы
	 {
		$connection = \Bitrix\Main\Application::getConnection();
		$table_name= self::$table_name;
		$result = $connection->isTableExists($table_name);//Проверяем на существование нашей таблицы
		if ($result==false)//Если таблицы нет, то создаём её
		{
			$connection->queryExecute("create table `".$table_name."`
			(
			id int auto_increment
			primary key,
			profile_id int not null,        # id пользователи из таблицы profile
			date date not null              # дата опоздания
			);
			");
		}		 
	 }
	public static function drop_table()//Удаление таблицы
	{
		$connection = \Bitrix\Main\Application::getConnection();
		$table_name= self::$table_name;
		$connection->dropTable($table_name);
	}	
}