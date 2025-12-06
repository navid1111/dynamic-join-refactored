<?php

namespace Tests\Unit;

use App\Services\ReportFilterService;
use PHPUnit\Framework\TestCase;

class ReportFilterServiceTest extends TestCase
{
    public function test_applies_dropdown_filter_with_binding()
    {
        $service = new ReportFilterService;

        $baseSql = 'SELECT users.id, users.name FROM users';

        $filterDefinitions = [
            [
                'id' => 'user_status',
                'type' => 'dropdown',
                'table' => 'users',
                'column' => 'status',
                'required' => false,
            ],
        ];

        $filterValues = [
            'user_status' => 'active',
        ];

        $result = $service->applyFilters($baseSql, $filterDefinitions, $filterValues);

        $expectedSql = 'SELECT users.id, users.name FROM users WHERE users.status = ?';
        $expectedBindings = ['active'];

        $this->assertSame($expectedSql, $result['sql']);
        $this->assertSame($expectedBindings, $result['bindings']);
    }

    public function test_applies_text_filter_with_like_and_wildcards()
    {
        $service = new ReportFilterService;

        $baseSql = 'SELECT products.title FROM products';

        $filterDefinitions = [
            [
                'id' => 'search',
                'type' => 'text',
                'table' => 'products',
                'column' => 'title',
                'required' => false,
            ],
        ];

        $filterValues = ['search' => 'laptop'];

        $result = $service->applyFilters($baseSql, $filterDefinitions, $filterValues);

        $expectedSql = 'SELECT products.title FROM products WHERE products.title LIKE ?';
        $expectedBindings = ['%laptop%'];

        $this->assertSame($expectedSql, $result['sql']);
        $this->assertSame($expectedBindings, $result['bindings']);
    }

    public function test_applies_date_range_filter()
    {
        $service = new ReportFilterService;

        $baseSql = 'SELECT orders.id FROM orders';

        $filterDefinitions = [
            [
                'id' => 'order_date',
                'type' => 'date_range',
                'table' => 'orders',
                'column' => 'created_at',
                'required' => false,
            ],
        ];

        $filterValues = [
            'order_date' => [
                'start' => '2024-01-01',
                'end' => '2024-12-31',
            ],
        ];

        $result = $service->applyFilters($baseSql, $filterDefinitions, $filterValues);

        $expectedSql = 'SELECT orders.id FROM orders WHERE (orders.created_at >= ? AND orders.created_at <= ?)';
        $expectedBindings = ['2024-01-01', '2024-12-31'];

        $this->assertSame($expectedSql, $result['sql']);
        $this->assertSame($expectedBindings, $result['bindings']);
    }

    public function test_skips_empty_optional_filter()
    {
        $service = new ReportFilterService;

        $baseSql = 'SELECT * FROM users';

        $filterDefinitions = [
            [
                'id' => 'status',
                'type' => 'dropdown',
                'table' => 'users',
                'column' => 'status',
                'required' => false, // ← optional
            ],
        ];

        $filterValues = ['status' => '']; // empty value

        $result = $service->applyFilters($baseSql, $filterDefinitions, $filterValues);

        // Should return base SQL unchanged
        $this->assertSame('SELECT * FROM users', $result['sql']);
        $this->assertEmpty($result['bindings']);
    }

    public function test_applies_multiple_filters_with_and()
    {
        $service = new ReportFilterService;

        $baseSql = 'SELECT u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id';

        $filterDefinitions = [
            ['id' => 'user_status', 'type' => 'dropdown', 'table' => 'u', 'column' => 'status'],
            ['id' => 'min_total', 'type' => 'number_range', 'table' => 'o', 'column' => 'total'],
        ];

        $filterValues = [
            'user_status' => 'active',
            'min_total' => ['min' => '100'],
        ];

        $result = $service->applyFilters($baseSql, $filterDefinitions, $filterValues);

        $expectedSql = 'SELECT u.name, o.total FROM users u JOIN orders o ON u.id = o.user_id WHERE u.status = ? AND (o.total >= ?)';
        $expectedBindings = ['active', '100'];

        $this->assertSame($expectedSql, $result['sql']);
        $this->assertSame($expectedBindings, $result['bindings']);
    }
}
