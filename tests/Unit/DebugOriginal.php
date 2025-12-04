<?php
$data1 = [
    'tables' => [
        ['users' => ['id', 'name', 'email']],
        ['orders' => ['id', 'user_id', 'total']],
    ],
    'joins' => [
        [
            'join_type' => 'inner',
            'left_table' => 'users',
            'left_column' => 'id',
            'right_table' => 'orders',
            'right_column' => 'user_id',
        ],
    ],
];

$duplicateKeys = ['id'];

// Paste your ORIGINAL generateSqlQuery method here as a function
function generateSqlQuery($data, $duplicateKeys) { /* ... */ }

echo "Test 1:\n";
echo generateSqlQuery($data1, $duplicateKeys) . "\n\n";

$data2 = [
    'tables' => [
        ['employees' => ['id', 'name', 'manager_id']],
        ['employees' => ['id', 'name']],
    ],
    'joins' => [
        [
            'join_type' => 'left',
            'left_table' => 'employees',
            'left_column' => 'manager_id',
            'right_table' => 'employees',
            'right_column' => 'id',
        ],
    ],
];
$duplicateKeys2 = ['id', 'name'];

echo "Test 2:\n";
echo generateSqlQuery($data2, $duplicateKeys2) . "\n";