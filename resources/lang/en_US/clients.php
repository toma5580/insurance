<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the clients' pages on Insura.
    |
    */

    'button'        => [
        'delete'    => 'Delete',
        'edit'      => 'Edit',
        'email'     => 'Send Email',
        'new'       => 'New Client',
        'notes'     => 'Write note',
        'text'      => 'Send Text'
    ],
    'input'         => [
        'label'         => [
            'address'       => 'Address',
            'birthday'      => 'Date of birth',
            'company'       => 'Company',
            'email'         => 'Email addess',
            'first_name'    => 'First name',
            'inviter'       => 'Client Inviter',
            'last_name'     => 'Last name',
            'phone'         => 'Phone number',
            'profile_image' => 'Profile image'
        ],
        'option'        => [
            'empty'     => [
                'brokers'   => 'No brokers are registered with :company_name',
                'staff'     => 'No staff are registered with :company_name'
            ],
            'header'    => [
                'brokers'   => 'Brokers',
                'company'   => 'Company',
                'staff'     => 'Staff'
            ],
            'you'       => '(You)'
        ],
        'placeholder'   => [
            'address'       => 'Address',
            'birthday'      => 'Date of Birth',
            'email'         => 'Email Addess',
            'first_name'    => 'First Name',
            'inviter'       => 'Client Inviter',
            'last_name'     => 'Last Name',
            'phone'         => 'Phone Number'
        ]
    ],
    'label'         => [
        'address'           => 'Address',
        'birthday'          => 'Birthday',
        'communication'     => 'Communication',
        'details'           => 'Details',
        'due'               => 'Due',
        'email'             => 'Email',
        'name'              => 'Name',
        'notes'             => 'Notes',
        'phone'             => 'Phone',
        'policies'          => 'Policies',
        'status'            => [
            'paid'          => 'Paid'
        ],
        'total_commission'  => 'Total Com.',
        'total_sales'       => 'Total Sales'
    ],
    'link'          => [
        'profile'   => 'Profile'
    ],
    'message'       => [
        'empty'     => [
            'clients'   => 'You have not added any clients'
        ],
        'error'     => [
            'file'      => 'There was an error uploading a file - :type: :filename',
            'missing'   => 'No such client exists!'
        ],
        'info'      => [
            'deleted'   => 'Client deleted!'
        ],
        'success'   => [
            'added'     => 'Client added! A welcome email is being sent to them.',
            'edited'    => 'Client profile edited!'
        ]
    ],
    'menu'          => [
        'header'    => [
            'button'   => 'More Actions',
            'text'      => 'More Actions',
            'tooltip'   => 'More Actions'
        ],
        'item'      => [
            'chat'          => 'Chat',
            'delete'        => 'Delete',
            'edit_profile'  => 'Edit profile',
            'email'         => 'Send Email',
            'text'          => 'Send Text'
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
            'edit'      => 'Edit Client',
            'new'       => 'New Client'
        ],
        'instruction'   => [
            'edit'      => "Make changes to the client's profile",
            'new'       => 'Add a new client',
            'policy'    => 'for :name'
        ]
    ],
    'status'        => [
        'active'    => 'Active Client',
        'inactive'  => 'Inactive Client'
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The client and all related data will be permanently deleted.'
            ]
        ]
    ],
    'table'         => [
        'button'    => [
            'attachments'   => 'New Attachment',
            'payments'      => 'New Payment',
            'policies'      => 'New Policy'
        ],
        'data'      => [
            'action'    => [
                'delete'    => 'Delete',
                'read'      => 'Read',
                'view'      => 'View'
            ],
            'method'    => [
                'card'      => 'Card',
                'cash'      => 'Cash',
                'paypal'    => 'PayPal'
            ],
            'status'    => [
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
            'date'      => 'Date',
            'due'       => 'Due',
            'file'      => 'File Name',
            'from'      => 'From',
            'insurer'   => 'Insurer',
            'message'   => 'Message',
            'method'    => 'Method',
            'name'      => 'Name',
            'number'    => '#No',
            'policy'    => 'Policy',
            'premium'   => 'Premium',
            'product'   => 'Product',
            'ref_no'    => 'Ref No.',
            'status'    => 'Status',
            'total'     => 'Totals',
            'uploader'  => 'Uploaded By'
        ],
        'message'   => [
            'empty' => [
                'attachments'   => ':name does not have any attachments.',
                'notes'         => 'Write notes about :name which other colleagues can see',
                'payments'      => ':name has not made any payments.',
                'policies'      => ':name has not taken up any policies yet.'
            ]
        ],
        'title'     => [
            'attachments'   => 'Attachments',
            'notes'         => 'Notes to :name',
            'payments'      => 'Payments',
            'policies'      => 'Policies'
        ]
    ],
    'title'         => [
        'all'   => 'Clients',
        'one'   => 'Client Profile'
    ]

];
