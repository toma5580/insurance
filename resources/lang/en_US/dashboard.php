<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the dashboard page on Insura.
    |
    */

    'counter'    => [
        'clients'       => 'Clients',
        'conversions'   => 'Conversions',
        'covers'        => 'Covers',
        'due'           => 'Due',
        'emails'        => 'Emails',
        'expiring'      => 'Expiring Policies',
        'income'        => 'Income',
        'paid'          => 'Paid',
        'policies'      => 'Policies',
        'sales'         => 'Sales'
    ],
    'graph'     => [
        'header'    => [
            'annual'    => 'Income ' . date('Y'),
            'monthly'   => 'This Month'
        ],
        'label'     => [
            'annual'    => [
                'jan' => 'Jan',
                'feb' => 'Feb',
                'mar' => 'Mar',
                'apr' => 'Apr',
                'may' => 'May',
                'jun' => 'Jun',
                'jul' => 'Jul',
                'aug' => 'Aug',
                'sep' => 'Sep',
                'oct' => 'Oct',
                'nov' => 'Nov',
                'dec' => 'Dec'
            ],
            'monthly'   => [
                'due'   => 'Due',
                'paid'  => 'Paid'
            ]
        ],
        'pop_over'  => [
            'annual' => 'Income'
        ]
    ],
    'table'     => [
        'header' => [
            'action'        => 'Action',
            'client'        => 'Client',
            'commission'    => 'Commission',
            'due'           => 'Due',
            'insurer'       => 'Insurer',
            'number'        => '#No',
            'premium'       => 'Premium',
            'product'       => 'Product',
            'ref_no'        => 'Ref No.',
            'status'        => 'Status'
        ],
        'data'  => [
            'action'        => 'View',
            'not_available' => 'No policies covered yet.',
            'status'        => [
                'free'      => 'Free',
                'paid'      => 'Paid',
                'partial'   => 'Partial',
                'unpaid'    => 'Unpaid'
            ]
        ],
        'title' => 'Latest Policies'
    ],
    'title'     => 'Dashboard',

];
