<?php
use Bitrix\Main\Entity;
class WORKDAYTable extends Entity\DataManager//Класс для работы с рабочим временем
{
    public static $table_name = "workday";
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
			'date_start' => new Entity\DatetimeField('date_start'),
			'date_stop' => new Entity\DatetimeField('date_stop'),

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
					date_start datetime null,
					date_stop datetime null
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