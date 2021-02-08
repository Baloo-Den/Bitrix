<?php
use Bitrix\Main\Entity;
class WORKDAY_PAUSETable extends Entity\DataManager//Класс для работы с рабочими паузами
{
    public static $table_name = "workday_pause";
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
			'workday_id' => new Entity\IntegerField('workday_id'),
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
				workday_id int not null,        # id рабочего дня из таблицы workday
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


