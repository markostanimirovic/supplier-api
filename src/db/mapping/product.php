<?php
return array(
    'tableName' => 'product',
    'columns' => array(
        'code' => array(
            'name' => 'code',
            'type' => \PDO::PARAM_STR,
            'size' => 10,
            'value' => null
        ),
        'name' => array(
            'name' => 'name',
            'type' => \PDO::PARAM_STR,
            'size' => 100,
            'value' => null
        ),
        'unit' => array(
            'name' => 'unit',
            'type' => \PDO::PARAM_STR,
            'size' => 50,
            'value' => null
        ),
        'price' => array(
            'name' => 'price',
            'type' => \PDO::PARAM_STR,
            'size' => 13,
            'value' => null
        )
    )
);