<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | with attachments on Insura.
    |
    */

    'button'        => [
        'new'       => 'New Payment',
    ],
    'input'         => [
        'label'         => [
            'amount'    => 'Amount',
            'date'      => 'Date',
            'method'    => 'Payment method',
            'policy'    => 'Policy'
        ],
        'option'        => [
            'empty'     => [
                'policy'    => 'Add a policy for :name first'
            ],
            'method'    =>[
                'card'      => 'Card',
                'cash'      => 'Cash',
                'paypal'    => 'PayPal'
            ]
        ],
        'placeholder'   => [
            'amount'    => 'Amount',
            'date'      => 'Date',
            'method'    => 'Payment Method',
            'policy'    => 'Policy'
        ]
    ],
    'message'       => [
        'error'     => [
            'missing'   => 'No such payment exists in your records!'
        ],
        'info'      => [
            'deleted'   => 'Payment deleted!'
        ],
        'success'   => [
            'added'     => 'Payment added!.'
        ]
    ],
    'modal'         => [
        'button'        => [
            'confirm'   => [
                'new'   => 'Add'
            ],
            'or'        => 'OR',
            'cancel'    => [
                'new'   => 'Cancel'
            ]
        ],
        'header'        => [
            'new'       => 'New Payment'
        ],
        'instruction'   => [
            'new'       => "Add a payment to :name's policy"
        ]
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The payment and all related information will be parmanently deleted.'
            ]
        ]
    ],

];
