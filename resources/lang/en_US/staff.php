<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Staff Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the staff pages on Insura.
    |
    */

    'button'        => [
        'delete'    => 'Delete',
        'edit'      => 'Edit',
        'email'     => 'Send Email',
        'new'       => 'New Staff',
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
        'sales'             => 'Sales',
        'status'            => [
            'paid'  => 'Paid'
        ],
        'total_commission'  => 'Total Commission',
        'total_sales'       => 'Total Sales'
    ],
    'link'          => [
        'profile'   => 'Profile'
    ],
    'message'       => [
        'empty'     => 'No staff members have been added yet',
        'error'     => [
            'file'      => 'There was an error uploading a file - Profile Image: :filename',
            'missing'   => 'No such staff member exists in your company!'
        ],
        'info'      => [
            'deleted'   => 'Staff member deleted!'
        ],
        'success'   => [
            'added'     => 'Staff member added! A welcome email has been sent to them.',
            'edited'    => 'Staff profile edited!'
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
            'edit'      => 'Edit Staff',
            'new'       => 'New Staff'
        ],
        'instruction'   => [
            'edit'      => "Make changes to the staff's profile",
            'new'       => 'Add a new staff member',
            'policy'    => 'for :name'
        ]
    ],
    'status'        => [
        'active'    => 'Active Staff',
        'inactive'  => 'Inactive Staff'
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The staff member and all related data will be permanently deleted.'
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
                'clients'   => ':name has no active clients on board yet.',
                'policies'  => ':name has no clients with active policies.'
            ]
        ],
        'title'     => [
            'clients'   => 'Clients',
            'policies'  => 'Policies'
        ]
    ],
    'title'         => [
        'all'   => 'Staff',
        'one'   => 'Staff Profile'
    ]

];
