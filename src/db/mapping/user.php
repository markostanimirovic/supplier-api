<?php
return array(
    'tableName' => 'user',
    'columns' => array(
        'username' => array(
            'name' => 'username',
            'type' => \PDO::PARAM_STR,
            'size' => 50,
            'value' => null
        ),
        'password' => array(
            'name' => 'password',
            'type' => \PDO::PARAM_STR,
            'size' => 50,
            'value' => null
        )
    )
);