<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Policies Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the policies pages & modals on Insura.
    |
    */

    'button'        => [
        'clear'     => 'Clear',
        'delete'    => 'Delete',
        'edit'      => 'Edit',
        'filter'    => 'Filter',
        'email'     => 'Send Email',
        'new'       => 'New Policy',
        'profile'   => 'Profile',
        'text'      => 'Send Text'
    ],
    'input'         => [
        'label'         => [
            'amount'            => 'Amount paid',
            'beneficiaries'     => 'Beneficiaries',
            'due'               => 'Due',
            'expiry'            => 'Expiry date',
            'from'              => 'from',
            'max'               => 'Max',
            'min'               => 'Min',
            'owners'            => 'Policy owners',
            'payer'             => 'Policy payer',
            'payments'          => 'Payment Details for :name',
            'payment_date'      => 'Payment date',
            'payment_method'    => 'Payment method',
            'premium'           => 'Premium',
            'product'           => 'Product',
            'ref_no'            => 'Ref No.',
            'renewal'           => 'Renewal date',
            'special_remarks'   => 'Special remarks',
            'to'                => 'to',
            'type'              => 'Type'
        ],
        'option'        => [
            'empty'     => [
                'owners'    => 'No clients available',
                'product'   => 'No products available'
            ],
            'method'    => [
                'card'      => 'Card',
                'cash'      => 'Cash',
                'paypal'    => 'PayPal'
            ],
            'type'      => [
                'annually'  => 'Annually',
                'monthly'   => 'Monthly',
                'weekly'   => 'Weekly'
            ]
        ],
        'placeholder'   => [
            'amount'            => 'Amount Paid',
            'beneficiaries'     => 'Beneficiaries',
            'due'               => 'Due',
            'expiry'            => 'Expiry Date',
            'payer'             => 'Policy Payer',
            'payment_date'      => 'Payment Date',
            'payment_method'    => 'Payment Method',
            'policy_number'     => 'e.g. HG78YH67',
            'premium'           => 'Premium',
            'product'           => 'Product',
            'ref_no'            => 'Ref No.',
            'renewal'           => 'Renewal Date',
            'special_remarks'   => 'Special Remarks',
            'type'              => 'Type'
        ]
    ],
    'label'         => [
        'address'           => 'Address',
        'beneficiaries'     => 'Beneficiaries',
        'birthday'          => 'Birthday',
        'communication'     => 'Communication',
        'due'               => 'Due',
        'details'           => 'Details',
        'email'             => 'Email',
        'expired_and_due'   => 'Expired & Due',
        'expiry'            => 'Expiry Date',
        'free'              => 'Free',
        'paid_in_full'      => 'Paid In Full',
        'payer'             => 'Payer',
        'phone'             => 'Phone',
        'premium'           => 'Premium',
        'product'           => 'Product',
        'renewal'           => 'Renewal Date',
        'ref_no'            => 'Reference Number',
        'sales'             => 'Sales',
        'special_remarks'   => 'Special Remarks',
        'total_commission'  => 'Total Com.',
        'total_sales'       => 'Total Sales'
    ],
    'link'          => [
        'profile'   => 'Profile'
    ],
    'message'       => [
        'empty'     => 'No policies have been added yet',
        'error'     => [
            'missing'   => 'No such policy exists in your company!'
        ],
        'info'      => [
            'deleted'   => 'Policy deleted!'
        ],
        'success'   => [
            'added'     => ':count policies added',
            'edited'    => 'Policy edited successfully'
        ]
    ],
    'menu'          => [
        'header'    => [
            'button'    => 'More Actions',
            'text'      => 'More Actions',
            'tooltip'   => 'More Actions'
        ],
        'item'      => [
            'delete'        => 'Delete',
            'edit_policy'   => 'Edit policy'
        ]
    ],
    'modal'         => [
        'button'        => [
            'confirm'   => [
                'edit'  => 'Save',
                'new'   => 'Create'
            ],
            'or'        => 'OR',
            'cancel'    => [
                'edit'  => 'Cancel',
                'new'   => 'Cancel'
            ]
        ],
        'header'        => [
            'edit'      => 'Edit Policy',
            'new'       => 'New Policy'
        ],
        'instruction'   => [
            'edit'      => "Make changes to the policy",
            'new'       => 'Add a new policy'
        ]
    ],
    'ribbon'        => [
        'type'  => [
            'annually'  => 'Annually',
            'monthly'   => 'Monthly',
            'weekly'   => 'Weekly'
        ]
    ],
    'swal'          => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The policy and all related data will be permanently deleted.'
            ]
        ]
    ],
    'table'         => [
        'data'      => [
            'action'        => [
                'view'      => 'View',
                'delete'    => 'Delete'
            ],
            'pagination'    => 'Showing :start to :stop of :total',
            'status'        => [
                'free'      => 'Free',
                'paid'      => 'Paid',
                'partial'   => 'Partial',
                'unpaid'    => 'Unpaid'
            ]
        ],
        'header'    => [
            'action'    => 'Action',
            'actions'   => 'Actions',
            'amount'    => 'Amount',
            'client'    => 'Client',
            'date'      => 'Date',
            'due'       => 'Due',
            'file'      => 'File Name',
            'insurer'   => 'Insurer',
            'method'    => 'Method',
            'name'      => 'Name',
            'number'    => '#No',
            'premium'   => 'Premium',
            'policies'  => 'Policies',
            'product'   => 'Product',
            'ref_no'    => 'Ref No.',
            'status'    => 'Status',
            'total'     => 'Totals',
            'uploader'  => 'Uploaded By'
        ],
        'message'   => [
            'empty' => [
                'attachments'   => 'Policy :policy does not have any attachments.',
                'payments'      => ':name has not made any payments.'
            ]
        ],
        'title'     => [
            'attachments'   => 'Attachments',
            'payments'      => 'Payments'
        ]
    ],
    'title'         => [
        'all'   => 'Policies',
        'one'   => 'Policy Details'
    ],
    'tooltip'       => [
        'filter'    => 'Filter Policies'
    ],

];
