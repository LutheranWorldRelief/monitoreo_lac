<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=dbname',
    'username' => 'user',
    'password' => '',
    'charset' => 'utf8',
    'attributes' => [PDO::MYSQL_ATTR_LOCAL_INFILE => true],

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',

];
