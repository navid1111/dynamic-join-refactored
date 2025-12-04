<?php

namespace Tests\Unit;

use App\Services\SqlQueryGenerator;
use PHPUnit\Framework\TestCase;

class SqlQueryGeneratorTest extends TestCase
{
    public function test_generates_sql_with_duplicate_columns_and_joins()
    {
        $generator = new SqlQueryGenerator;

        $data = [
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

        $expected = 'SELECT users.id as users_id, users.name, users.email, orders.id as orders_id, orders.user_id, orders.total FROM users inner JOIN orders orders ON users.id = orders.user_id ';

        $actual = $generator->generateSqlQuery($data, $duplicateKeys);

        $this->assertSame($expected, $actual);
    }

    public function test_handles_same_table_twice()
    {
        $generator = new SqlQueryGenerator;

        $data = [
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

        $duplicateKeys = ['id', 'name'];

        $expected = 'SELECT employees.id as employees_id, employees.name as employees_name, employees.manager_id, employeesemployees.id as employeesemployees_id, employeesemployees.name as employeesemployees_name FROM employees left JOIN employees employeesemployees ON employees.manager_id = employees.id ';

        $actual = $generator->generateSqlQuery($data, $duplicateKeys);

        $this->assertSame($expected, $actual);
    }
}
