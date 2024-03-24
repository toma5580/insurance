<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the settings page on Insura.
    |
    */

    'accordion' => [
        'header'    => [
            'reminder'  => 'Reminder'
        ]
    ],
    'button'    => [
        'add_client_field'  => 'Add Custom Client Field',
        'add_policy_field'  => 'Add Custom Policy Field',
        'add_reminder'      => 'Add Reminder',
        'save'              => 'Save Changes'
    ],
    'input'     => [
        'label'         => [
            'address'                   => 'Address',
            'aft_api_key'               => "Africa's Talking API key",
            'aft_username'              => "Africa's Talking username",
            'commission_rate'           => 'Commission rate (%)',
            'confirm_password'          => 'Confirm password',
            'current_password'          => 'Current password',
            'currency'                  => 'Currency',
            'custom_default'            => 'Default value',
            'custom_field'              => 'Custom Client Fields|Custom Policy Fields',
            'custom_label'              => 'Field name',
            'custom_options'            => 'List of options',
            'custom_required'           => 'Make field required',
            'custom_type'               => 'Type of field',
            'birthday'                  => 'Date of birth',
            'days'                      => 'Days on timeline',
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
            'message'                   => 'Message',
            'name'                      => 'Name',
            'new_password'              => 'New password',
            'phone'                     => 'Phone number',
            'product_categories'        => 'Product categories (comma separated)',
            'product_sub_categories'    => 'Product sub-categories (comma separated)',
            'profile_image'             => 'Profile image',
            'reminder_status'           => 'Enable reminders',
            'reminder_type'             => 'Reminder type',
            'sendmail_path'             => 'Sendmail path',
            'ses_key'                   => 'Amazon SES key',
            'ses_region'                => 'Amazon SES region',
            'ses_secret'                => 'Amazon SES secret',
            'smtp_host'                 => 'SMTP host',
            'smtp_password'             => 'SMTP password',
            'smtp_port'                 => 'SMTP port',
            'subject'                   => 'Subject',
            'text_provider'             => 'Text(SMS) provider',
            'text_signature'            => 'Text(SMS) signature',
            'timeline'                  => 'Timeline',
            'twilio_auth_token'         => 'Twilio auth token',
            'twilio_number'             => 'Twilio phone number',
            'twilio_sid'                => 'Twilio SID'
        ],
        'option'    => [
            'after_expiry'      => 'After Expiry',
            'before_expiry'     => 'Before Expiry',
            'custom_default'    => [
                'checked'   => 'Checked',
                'unchecked' => 'Unchecked'
            ],
            'custom_type'       => [
                'checkbox'  => 'Checkbox',
                'date'      => 'Date',
                'email'     => 'Email Address',
                'hidden'    => 'Hidden',
                'number'    => 'Number',
                'select'    => 'Options',
                'tel'       => 'Telephone Number',
                'text'      => 'Short Text',
                'textarea'  => 'Long Text'
            ],
            'email'             => 'Email',
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
            ],
            'text'              => 'Text'
        ],
        'placeholder'   => [
            'address'                   => 'Address',
            'aft_api_key'               => "Africa's Talking API Key",
            'aft_username'              => "Africa's Talking Username",
            'commission_rate'           => 'Commission Rate (%)',
            'birthday'                  => 'Date of Birth',
            'confirm_password'          => 'Confirm Password',
            'current_password'          => 'Current Password',
            'custom_default'            => 'Default Value',
            'custom_label'              => 'Field Name',
            'custom_options'            => 'List of Options',
            'custom_type'               => 'Type of Field',
            'days'                      => 'Days on Timeline',
            'email'                     => 'Email',
            'first_name'                => 'First Name',
            'last_name'                 => 'Last Name',
            'mail_username'             => 'Mail Username',
            'mailgun_domain'            => 'Mailgun Domain',
            'mailgun_secret'            => 'Mailgun Secret',
            'mandrill_secret'           => 'Mandrill Secret',
            'message'                   => 'Message',
            'name'                      => 'Name',
            'new_password'              => 'New Password',
            'phone'                     => 'Phone Number',
            'product_categories'        => 'Eg. One, Two, Three',
            'product_sub_categories'    => 'Eg. Lorem, Ipsum, Door',
            'sendmail_path'             => 'Sendmail Path',
            'ses_key'                   => 'Amazon SES Key',
            'ses_region'                => 'Amazon SES Region',
            'ses_secret'                => 'Amazon SES Secret',
            'smtp_host'                 => 'SMTP Host',
            'smtp_password'             => 'SMTP Password',
            'smtp_port'                 => 'SMTP Port',
            'subject'                   => 'Subject',
            'twilio_auth_token'         => 'Twilio Auth Token',
            'twilio_number'             => 'Twilio Phone Number',
            'twilio_sid'                => 'Twilio SID'
        ]
    ],
    'message'  => [
        'error'    => [
            'company'   => [
                'missing'   => 'No such company exists!'
            ],
            'file'      => 'There was an error uploading a file - :type: :filename',
            'files'     => [
                'favicon'       => 'Favicon: ',
                'logo'          => 'Logo: ',
                'profile_image' => 'Profile Image: '
            ],
            'password'  => [
                'change'    => 'The current password was incorrect!'
            ],
            'reminders' => [
                'fail'  => 'No reminders were added or updated.',
            ],
            'required'  => 'Please select an option'
        ],
        'warning'   => [
            'reminders' => [
                'update'    => 'Unable to update reminder #:id'
            ]
        ],
        'success'   => [
            'company'   => [
                'edit'  => 'Company settings changed!'
            ],
            'password' => [
                'change'    => 'Password changed successfully!'
            ],
            'profile'   => [
                'edit'  => 'Profile edited!'
            ],
            'reminders' => [
                'edit'  => 'Reminder settings saved!'
            ],
            'system'    => [
                'edit'  => 'System settings changed!'
            ]
        ]
    ],
    'tab'       => [
        'menu'      => [
            'company'   => 'Company',
            'profile'   => 'Profile',
            'reminders' => 'Reminders',
            'security'  => 'Security',
            'system'    => 'System'
        ],
        'message'   => [
            'company'   => 'Update company settings here',
            'profile'   => 'Update your profile account here',
            'reminders' => 'Reminders are emails or SMS sent to clients before or after a policy has expired to remind them to renew',
            'security'  => 'Change your account password here',
            'system'    => 'Update your system settings here'
        ]
    ],
    'title'     => 'Settings',

];
