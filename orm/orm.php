<?php
use Bitrix\Main\Entity;
class ORMTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'test_orm';
    }

    public static function getMap()
    {
        return array(
            'ID' => new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),

            'NAME' => new Entity\StringField('NAME'),
			'DATE_INSERT' => new Entity\DatetimeField('DATE_INSERT'),

        );
    }
}


