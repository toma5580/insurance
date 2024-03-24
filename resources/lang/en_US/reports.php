<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reports Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the reports page on Insura.
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
    'dropdown'  => [
        'header'    => 'More Years'   
    ],
    'graph'     => [
        'header'    => [
            'annual'                => 'Income',
            'clients_vs_policies'   => 'Clients vs Policies',
            'monthly'               => 'This Month',
            'payments'              => 'Payments'
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
            'annual'    => 'Income :currency_symbol',
            'clients'   => 'New Clients',
            'policies'  => 'New Policies',
            'payments'  => 'Paid :currency_symbol'
        ]
    ],
    'title'     => 'Reports :year',

];
