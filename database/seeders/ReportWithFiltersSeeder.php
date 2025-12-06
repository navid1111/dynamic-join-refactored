<?php

// database/seeders/ReportWithFiltersSeeder.php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportWithFiltersSeeder extends Seeder
{
    public function run(): void
    {
        Report::create([
            'name' => 'User Orders Report',
            'report_details' => [
                'tables' => [
                    ['users' => ['id', 'name', 'email']],
                    ['orders' => ['id', 'user_id', 'amount']],
                    ['products' => ['id', 'title', 'price']],
                ],
                'joins' => [
                    [
                        'join_type' => 'inner',
                        'left_table' => 'users',
                        'left_column' => 'id',
                        'right_table' => 'orders',
                        'right_column' => 'user_id',
                    ],
                    [
                        'join_type' => 'inner',
                        'left_table' => 'orders',
                        'left_column' => 'product_id',
                        'right_table' => 'products',
                        'right_column' => 'id',
                    ],
                ],
            ],
            'filters' => [
                [
                    'id' => 'user_name',
                    'label' => 'User Name',
                    'type' => 'dropdown',
                    'table' => 'users',
                    'column' => 'name',
                    'options_source' => 'query',
                    'options_query' => 'SELECT name as id, name FROM users ORDER BY name',
                    'required' => false,
                    'order' => 1,
                ],
                [
                    'id' => 'product_title',
                    'label' => 'Product',
                    'type' => 'dropdown',
                    'table' => 'products',
                    'column' => 'title',
                    'options_source' => 'query',
                    'options_query' => 'SELECT title as id, title FROM products ORDER BY title',
                    'required' => false,
                    'order' => 2,
                ],
                [
                    'id' => 'amount_range',
                    'label' => 'Order Amount Range',
                    'type' => 'number_range',
                    'table' => 'orders',
                    'column' => 'amount',
                    'required' => false,
                    'order' => 3,
                ],
                [
                    'id' => 'email_search',
                    'label' => 'Search by Email',
                    'type' => 'text',
                    'table' => 'users',
                    'column' => 'email',
                    'required' => false,
                    'order' => 4,
                ],
            ],
            'users' => [],
        ]);

        // Another example: Products Report
        Report::create([
            'name' => 'Products Price Report',
            'report_details' => [
                'tables' => [
                    ['products' => ['id', 'title', 'price']],
                ],
                'joins' => [],
            ],
            'filters' => [
                [
                    'id' => 'price_range',
                    'label' => 'Price Range',
                    'type' => 'number_range',
                    'table' => 'products',
                    'column' => 'price',
                    'required' => false,
                    'order' => 1,
                ],
                [
                    'id' => 'product_search',
                    'label' => 'Search Product',
                    'type' => 'text',
                    'table' => 'products',
                    'column' => 'title',
                    'required' => false,
                    'order' => 2,
                ],
            ],
            'users' => [],
        ]);
    }
}
