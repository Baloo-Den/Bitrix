<?php
use Bitrix\Main\Entity;
class PROFILETable extends Entity\DataManager//Класс для работы с рабочими паузами
{
    public static $table_name = "profile";
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
			'login' => new Entity\StringField('login'),
			'name' => new Entity\StringField('name'),
			'last_name' => new Entity\StringField('last_name'),
			'offset' => new Entity\StringField('offset'),

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
			`id` int auto_increment
					primary key,
			`login` varchar(255) not null,
			`name` varchar(255) null,
			`last_name` varchar(255) null,
				`offset`   varchar(10) null       # смещение часового пояса в формате +0300
			);
			");
		}		 
	 }
}


