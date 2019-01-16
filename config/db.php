<?php

return [
    /**
     * Не использовал БД из ВМ т.к. есть отдельный mysql server на VDS свой
     */
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mrlambert.ru;dbname=yii',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
