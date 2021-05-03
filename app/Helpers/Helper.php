<?php
function getButtons($dont_export = false)
{
    $buttons = [
        'pageLength' => 10,
        'buttons' => [
            [
                'text'      => '<i class="fa fa-cog"></i>',
                'className' => 'btn btn-primary dt-subheader-btn edit_table_btn',
                'action'    => 'function() { toggleTableEdit(); }',
            ],
        ],
    ];

    if (! $dont_export) {
//        $buttons['buttons'][] = [
//            'extend'    => 'postExcel',
//            'text'      => '<i class="fa fa-file-excel"></i>',
//            'className' => 'btn btn-primary dt-subheader-btn',
//        ];
//        $buttons['buttons'][] = [
//            'extend'    => 'postCsv',
//            'text'      => '<i class="fa fa-file-csv"></i>',
//            'className' => 'btn btn-primary dt-subheader-btn',
//        ];
//        $buttons['buttons'][] = [
//            'extend'    => 'postPdf',
//            'text'      => '<i class="fa fa-file-pdf"></i>',
//            'className' => 'btn btn-primary dt-subheader-btn',
//        ];

        // temp js btns
        $buttons['buttons'][] = [
            'extend'    => 'excel',
            'text'      => '<i class="fa fa-file-excel"></i>',
            'className' => 'btn btn-primary dt-subheader-btn',
        ];
        $buttons['buttons'][] = [
            'extend'    => 'csv',
            'text'      => '<i class="fa fa-file-csv"></i>',
            'className' => 'btn btn-primary dt-subheader-btn',
        ];
    }

    return $buttons;
}
