<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Setup Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the setup page on Insura.
    |
    */

    'button'    => [
        'finish'    => 'Finish',
        'next'      => 'Next',
        'prevoius'  => 'Prevoius'
    ],
    'input'     => [
        'label'         => [
            'address'                   => 'Address',
            'aft_api_key'               => "Africa's Talking API key",
            'aft_username'              => "Africa's Talking username",
            'confirm_password'          => 'Confirm password',
            'currency'                  => 'Currency',
            'birthday'                  => 'Date of birth',
            'db_database'               => 'Database name or file',
            'db_default'                => 'Database driver',
            'db_host'                   => 'Database host',
            'db_password'               => 'Database password',
            'db_username'               => 'Database username',
            'email'                     => 'Email',
            'email_signature'           => 'Email signature',
            'favicon'                   => 'Favicon image',
            'first_name'                => 'First name',
            'last_name'                 => 'Last name',
            'locale'                    => 'Language',
            'logo'                      => 'Logo image',
            'mail_driver'               => 'Mail driver',
            'mail_encryption'           => 'Mail encryption',
            'mail_username'             => 'Mail username',
            'mailgun_domain'            => 'Mailgun domain',
            'mailgun_secret'            => 'Mailgun secret',
            'mandrill_secret'           => 'Mandrill secret',
            'name'                      => 'Name',
            'password'                  => 'Password',
            'phone'                     => 'Phone number',
            'profile_image'             => 'Profile image',
            'sendmail_path'             => 'Sendmail path',
            'ses_key'                   => 'Amazon SES key',
            'ses_region'                => 'Amazon SES region',
            'ses_secret'                => 'Amazon SES secret',
            'smtp_host'                 => 'SMTP host',
            'smtp_password'             => 'SMTP password',
            'smtp_port'                 => 'SMTP port',
            'text_provider'             => 'Text(SMS) provider',
            'text_signature'            => 'Text(SMS) signature',
            'twilio_auth_token'         => 'Twilio auth token',
            'twilio_number'             => 'Twilio phone number',
            'twilio_sid'                => 'Twilio SID',
            'url'                       => 'Application url'
        ],
        'option'    => [
            'db_default'        => [
                'mysql'     => 'MySQL',
                'pgsql'     => 'PostgreSQL',
                'sqlite'    => 'SQLite',
                'sqlsrv'    => 'SQL'
            ],
            'mail_driver'       => [
                'mailgun'   => 'Mailgun',
                'mandrill'  => 'Mandrill',
                'sendmail'  => 'Sendmail',
                'ses'       => 'Amazon SES',
                'smtp'      => 'SMTP'
            ],
            'mail_encryption'   => [
                'none'  => 'None',
                'ssl'   => 'SSL',
                'tls'   => 'TLS'
            ]
        ],
        'placeholder'   => [
            'address'                   => 'Address',
            'aft_api_key'               => "Africa's Talking API Key",
            'aft_username'              => "Africa's Talking Username",
            'birthday'                  => 'Date of Birth',
            'confirm_password'          => 'Confirm Password',
            'db_database'               => 'Database Name or File',
            'db_default'                => 'Database Driver',
            'db_host'                   => 'Database Host',
            'db_password'               => 'Database Password',
            'db_username'               => 'Database Username',
            'email'                     => 'Email',
            'first_name'                => 'First Name',
            'last_name'                 => 'Last Name',
            'mail_username'             => 'Mail Username',
            'mailgun_domain'            => 'Mailgun Domain',
            'mailgun_secret'            => 'Mailgun Secret',
            'mandrill_secret'           => 'Mandrill Secret',
            'name'                      => 'Name',
            'password'                  => 'Password',
            'phone'                     => 'Phone Number',
            'sendmail_path'             => 'Sendmail Path',
            'ses_key'                   => 'Amazon SES Key',
            'ses_region'                => 'Amazon SES Region',
            'ses_secret'                => 'Amazon SES Secret',
            'smtp_host'                 => 'SMTP Host',
            'smtp_password'             => 'SMTP Password',
            'smtp_port'                 => 'SMTP Port',
            'twilio_auth_token'         => 'Twilio Auth Token',
            'twilio_number'             => 'Twilio Phone Number',
            'twilio_sid'                => 'Twilio SID',
            'url'                       => 'Application URL'
        ]
    ],
    'message'  => [
        'error'    => [
            'file'      => 'There was an error uploading a file - :type: :filename',
            'files'     => [
                'favicon'       => 'Favicon',
                'logo'          => 'Logo',
                'profile_image' => 'Profile Image'
            ],
            'unauthorised'  => 'You are not authorised to carry out an update. Contact the system admin to do so.'
        ],
        'info'      => 'Required fields are marked with a ',
        'success'   => [
            'same'      => 'Your Insura installation is already up to date. Version - :version',
            'setup'     => 'Setup is now complete. Please log in to continue',
            'update'    => 'Your Insura installation has been updated to version :version successfully'
        ]
    ],
    'sub_title' => "Looks like this is your first time here. Just a few things to configure first and you'll be good to go.",
    'tab'       => [
        'menu'      => [
            'step01'    => 'Database',
            'step02'    => 'Mail',
            'step03'    => 'System',
            'step04'    => 'Company',
            'step05'    => 'Account'
        ],
        'message'   => [
            'step01'    => "Let's start with the most important thing, the database!",
            'step02'    => 'These dictate how all mail is sent throughout the system.',
            'step03'    => 'These are some general settings for the system.',
            'step04'    => 'This will be set up the main system company.',
            'step05'    => 'This sets up the super/system admin account.'
        ]
    ],
    'title'     => 'Setup',

];
