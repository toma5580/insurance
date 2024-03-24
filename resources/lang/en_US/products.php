<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Products Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match text used 
    | on the products page on Insura.
    |
    */

    'button'       => [
        'delete' => 'Delete',
        'edit'   => 'Edit',
        'new'    => 'New Product'
    ],
    'input'         => [
        'label'         => [
            'category'      => 'Category',
            'company'       => 'Insurance company',
            'insurer'       => 'Insurer',
            'name'          => 'Product name',
            'sub_category'  => 'Sub-category'
        ],
        'option'        => [
            'category'      => 'Add categories in the settings page',
            'sub_category'  => 'Add sub-categories in the settings page'
        ],
        'placeholder'   => [
            'insurer'   => 'Insurer',
            'name'      => 'Product Name'
        ]
    ],
    'message'       => [
        'error'     => [
            'missing'   => 'No such product exists in your company!'
        ],
        'info'      => [
            'deleted'   => 'Product deleted!'
        ],
        'success'   => [
            'added'     => 'Product added!',
            'edited'    => 'Product edited!'
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
            'edit'  => 'Edit Product',
            'new'   => 'New Product'
        ],
        'instruction'   => [
            'edit'  => 'Make changes to the product',
            'new'   => 'Create a new product'
        ]
    ],
    'swal'  => [
        'warning'   => [
            'delete'    => [
                'confirm'   => 'Yes, delete it!',
                'title'     => 'Are you sure',
                'text'      => 'The product and all related data will be permanently deleted.'
            ]
        ]
    ],
    'table'         => [
        'header'    => [
            'actions'       => 'Actions',
            'category'      => 'Category',
            'insurer'       => 'Insurer',
            'name'          => 'Name',
            'policies'      => 'Policies',
            'sub_category'  => 'Sub Category'
        ],
        'data'      => [
            'not_available' => 'No products have been added yet.',
            'pagination'    => 'Showing :start to :stop of :total'
        ]
    ],
    'title'         => 'Products',

];
