<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Communication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the communication page on Insura.
    |
    */

    'button'    => [
        'email'     => 'New Email',
        'profile'   => 'View Profile',
        'text'      => 'New Text / SMS'
    ],
    'input'     => [
        'label'         => [
            'message'       => 'Message',
            'recipients'    => 'Recipients',
            'subject'       => 'Subject'
        ],
        'option'        => [
            'admins'    => 'All Administrators',
            'default'   => 'Choose a recipient',
            'brokers'   => 'All Brokers',
            'clients'   => 'All Clients',
            'empty'     => 'No users are registered with :company_name',
            'staff'     => 'All Staff'
        ],
        'placeholder'   => [
            'message'       => 'Message',
            'subject'       => 'Subject'
        ]
    ],
    'label'     => [
        'address'   => 'Address',
        'birthday'  => 'Birthday',
        'email'     => 'Email',
        'phone'     => 'Phone Number'
    ],
    'message'   => [
        'error'     => [
            'invalid'   => [
                'phone' => 'Please use the correct format for your country'
            ],
            'missing'   => [
                'email' => 'We could not find the specified email',
                'text'  => 'We could not find the specified text message'
            ]
        ],
        'info'      => [
            'deleted'   => 'The :type was deleted!',
            'sent'      => 'Your :type is being sent!'
        ],
        'warning'   => [
            'text'  => 'Texting has been disabled for this company (:company_name)'
        ]
    ],
    'modal'     => [
        'button'        => [
            'cancel'    => 'Cancel',
            'confirm'   => 'Send',
            'dismiss'   => 'Dismiss',
            'or'        => 'OR'
        ],
        'content'       => [
            'yours'     => 'Yours'
        ],
        'header'        => [
            'email' => 'New Email',
            'text'  => 'New Text / SMS'
        ],
        'instruction'   => [
            'email' => 'Create a new email.',
            'send'  => 'Send :type to :name',
            'text'  => 'Create a new text / SMS.'
        ]
    ],
    'status'    => [
        'active'    => 'Active :role',
        'inactive'  => 'Inactive :role'
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'cancel'    => 'Cancel',
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The :type will be permanently deleted.'
            ]
        ]
    ],
    'table'     => [
        'data' => [
            'action'        => [
                'delete'    => 'Delete',
                'emails'    => 'Read Emails',
                'read'      => 'Read',
                'texts'     => 'Read Texts'
            ],
            'pagination'    => [
                'showing'   => 'Showing :start to :stop of :total'
            ]
        ],
        'header'    => [
            'actions'   => 'Actions',
            'date'      => 'Date',
            'email'     => 'Emails',
            'from'      => 'From',
            'message'   => 'Message',
            'name'      => 'Name',
            'number'    => '#No.',
            'subject'   => 'Subject',
            'text'      => 'Texts / SMSs',
            'type'      => 'Type'
        ],
        'message'   => [
            'empty' => 'You have not received or sent anything yet!'
        ],
        'title'     => [
            'emails'    => 'Emails with :name',
            'texts'     => 'Texts / SMS with :name'
        ]
    ],
    'title'     => [
        'emails'    => 'Emails',
        'main'      => 'Communication',
        'texts'     => 'Text Messages'
    ],

];
