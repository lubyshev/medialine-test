<?php

$host   = getenv('DB_HOST');
$port   = (int)getenv('DB_PORT');
$dbName = getenv('DB_NAME');

return [
    'class'    => 'yii\db\Connection',
    'dsn'      => "mysql:host={$host};port={$port};dbname={$dbName}",
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset'  => 'utf8',
];
