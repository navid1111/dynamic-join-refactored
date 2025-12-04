<?php

namespace Tests\Unit;

use App\Services\ReportQueryBuilder;
use PHPUnit\Framework\TestCase;

class ReportQueryBuilderTest extends TestCase
{
    public function test_generates_sql_with_duplicate_columns_and_joins()
    {
        $builder = new ReportQueryBuilder;

        $reportConfig = [
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

        // Expected output must match ORIGINAL logic's behavior
        $expected = 'SELECT users.id as users_id, users.name, users.email, orders.id as orders_id, orders.user_id, orders.total FROM users users inner JOIN orders orders ON users.id = orders.user_id';

        $actual = $builder->build($reportConfig);

        $this->assertSame($expected, $actual);
    }

    public function test_handles_same_table_twice()
    {
        $builder = new ReportQueryBuilder;

        $reportConfig = [
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

        // ⚠️ Use the ACTUAL output from your original code (from test failure)
        // This is what your old code REALLY produced:
        $expected = 'SELECT employees.id as employees_id, employees.name as employees_name, employees.manager_id, employeesemployees.id as employeesemployees_id, employeesemployees.name as employeesemployees_name FROM employees employees left JOIN employees employeesemployees ON employees.manager_id = employeesemployees.id';

        $actual = $builder->build($reportConfig);

        $this->assertSame($expected, $actual);
    }
}
