<?php

namespace Tests\Feature;

use App\Services\ReportFilterService;
use App\Services\ReportQueryBuilder;
use Tests\TestCase;

class ReportGenerationIntegrationTest extends TestCase
{
    public function test_generates_filtered_report_sql_with_bindings()
    {
        // Arrange: Define a report config (like from your reports table)
        $reportConfig = [
            'tables' => [
                ['users' => ['id', 'name', 'email', 'status']],
                ['orders' => ['id', 'total', 'created_at']],
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

        $filterDefinitions = [
            [
                'id' => 'user_status',
                'label' => 'User Status',
                'type' => 'dropdown',
                'table' => 'users', // ← must match alias used in query (first table = 'users')
                'column' => 'status',
                'required' => false,
            ],
            [
                'id' => 'date_range',
                'label' => 'Order Date',
                'type' => 'date_range',
                'table' => 'orders', // ← second table alias = 'orders'
                'column' => 'created_at',
                'required' => false,
            ],
        ];

        $filterValues = [
            'user_status' => 'active',
            'date_range' => [
                'start' => '2024-01-01',
                'end' => '2024-12-31',
            ],
        ];

        // Act: Build query + apply filters
        $queryBuilder = new ReportQueryBuilder;
        $filterService = new ReportFilterService;

        $baseSql = $queryBuilder->build($reportConfig);
        $filteredResult = $filterService->applyFilters($baseSql, $filterDefinitions, $filterValues);

        // Assert: Final SQL structure and bindings
        $expectedSql =
            'SELECT users.id as users_id, users.name, users.email, users.status, orders.id as orders_id, orders.total, orders.created_at '.
            'FROM users users inner JOIN orders orders ON users.id = orders.user_id '.
            'WHERE users.status = ? AND (orders.created_at >= ? AND orders.created_at <= ?)';

        $expectedBindings = ['active', '2024-01-01', '2024-12-31'];

        $this->assertSame($expectedSql, $filteredResult['sql']);
        $this->assertSame($expectedBindings, $filteredResult['bindings']);
    }
}
