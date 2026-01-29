<?php

return [
    /*
    |--------------------------------------------------------------------------
    | All available roles
    |--------------------------------------------------------------------------
    */
    'all' => [
        'ADMIN',
        'PRICE_STRATEGY',
        'PRICE_ENTRY',
        'PRICE_APPROVER',
        'FINANCE',
        'PHARMACY',
        'PROCUREMENT',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Monitoring menu permissions per role
    | Keys: stock, adjustment_stock, data_monitoring, list_item_pricing
    | If role has 'all' => true, user can access all Data Monitoring menus.
    |--------------------------------------------------------------------------
    */
    'data_monitoring_permissions' => [
        'ADMIN' => ['all' => true],
        'FINANCE' => ['data_monitoring' => true, 
                    'data_monitoring_billing' => true, 
                    'data_monitoring_stock' => true, 
                    'data_monitoring_rejected' => true, 
                    'data_monitoring_stock_management' => true
                    ],
        'PHARMACY' => [
            'data_monitoring' => true,
            'data_monitoring_stock' => true,
            'adjustment_stock' => true,
            'list_item_pricing' => true,
        ],
        'PROCUREMENT' => [
            'list_item_pricing' => true,
        ],
        'PRICE_STRATEGY' => ['list_item_pricing' => true],
        'PRICE_ENTRY' => ['list_item_pricing' => true],
        'PRICE_APPROVER' => ['list_item_pricing' => true],
    ],
];
