<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broker Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the brokers' pages on Insura.
    |
    */

    'button'        => [
        'delete'    => 'Delete',
        'edit'      => 'Edit',
        'email'     => 'Send Email',
        'new'       => 'New Broker',
        'text'      => 'Send Text'
    ],
    'input'         => [
        'label'         => [
            'address'           => 'Address',
            'birthday'          => 'Date of birth',
            'commission_rate'   => 'Commission rate',
            'company'           => 'Insurance company',
            'email'             => 'Email addess',
            'first_name'        => 'First name',
            'last_name'         => 'Last name',
            'phone'             => 'Phone number',
            'profile_image'     => 'Profile image'
        ],
        'placeholder'   => [
            'address'           => 'Address',
            'birthday'          => 'Date of Birth',
            'commission_rate'   => 'Commission Rate',
            'email'             => 'Email Addess',
            'first_name'        => 'First Name',
            'last_name'         => 'Last Name',
            'phone'             => 'Phone Number'
        ]
    ],
    'label'         => [
        'address'           => 'Address',
        'birthday'          => 'Birthday',
        'commission'        => 'Commission',
        'email'             => 'Email',
        'phone'             => 'Phone',
        'status'            => [
            'paid'  => 'Paid'
        ],
        'sales'             => 'Sales',
        'total_commission'  => 'Total Commission',
        'total_sales'       => 'Total Sales'
    ],
    'link'          => [
        'profile'   => 'Profile'
    ],
    'message'       => [
        'empty'     => 'No brokers have been added yet',
        'error'     => [
            'file'      => 'There was an error uploading a file - Profile Image: :filename',
            'missing'   => 'No such broker exists in your company!'
        ],
        'info'      => [
            'deleted'   => 'Broker deleted!'
        ],
        'success'   => [
            'added'     => 'Broker added! A welcome email has been sent to them.',
            'edited'    => 'Broker profile edited!'
        ]
    ],
    'menu'          => [
        'header'    => [
            'text'      => 'More Actions',
            'tooltip'   => 'More Actions'
        ],
        'item'      => [
            'chat'          => 'Chat',
            'delete'        => 'Delete',
            'edit_profile'  => 'Edit profile'
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
            'edit'      => 'Edit Broker',
            'new'       => 'New Broker',
            'policy'    => 'for :name'
        ],
        'instruction'   => [
            'edit'      => "Make changes to the broker's profile",
            'new'       => 'Add a new broker',
            'policy'    => 'with :name'
        ]
    ],
    'status'        => [
        'active'    => 'Active Broker',
        'inactive'  => 'Inactive Broker'
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The broker and all related data will be permanently deleted.'
            ]
        ]
    ],
    'table'         => [
        'data'      => [
            'action'    => [
                'view'  => 'View'
            ],
            'status'    => [
                'free'      => 'Free',
                'paid'      => 'Paid',
                'partial'   => 'Partial',
                'unpaid'    => 'Unpaid'
            ]
        ],
        'header'    => [
            'action'        => 'Action',
            'commission'    => 'Commission',
            'due'           => 'Due',
            'email'         => 'Email',
            'insurer'       => 'Insurer',
            'name'          => 'Name',
            'number'        => '#No',
            'premium'       => 'Premium',
            'policies'      => 'Policies',
            'product'       => 'Product',
            'ref_no'        => 'Ref No.',
            'status'        => 'Status',
            'total'         => 'Totals'
        ],
        'message'   => [
            'empty' => [
                'clients'   => ':name has no clients on board yet.',
                'policies'  => ':name has no clients with any policies.'
            ]
        ],
        'title'     => [
            'clients'   => 'Clients',
            'policies'  => 'Policies'
        ]
    ],
    'title'         => [
        'all'   => 'Brokers',
        'one'   => 'Broker Profile'
    ]

];
