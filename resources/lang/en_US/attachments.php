<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attachment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | with attachments on Insura.
    |
    */

    'button'        => [
        'new'       => 'New Attachment',
    ],
    'input'         => [
        'label'         => [
            'attachment'    => 'Attachment File',
            'name'          => 'Name'
        ],
        'placeholder'   => [
            'name'      => 'Name'
        ]
    ],
    'message'       => [
        'error'     => [
            'file'      => 'There was an error uploading a file - Attachment: :filename',
            'invalid'   => 'Cannot create an attachment for this entity',
            'missing'   => 'No such attachment exists!'
        ],
        'info'      => [
            'deleted'   => 'Attachment deleted!'
        ],
        'success'   => [
            'added'     => 'Attachment uploaded!.'
        ]
    ],
    'modal'         => [
        'button'        => [
            'confirm'   => [
                'new'   => 'Upload'
            ],
            'or'        => 'OR',
            'cancel'    => [
                'new'   => 'Cancel'
            ]
        ],
        'header'        => [
            'new'       => 'New Attachment'
        ]
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The attachment will be permanently deleted.'
            ]
        ]
    ],

];
