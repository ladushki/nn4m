<?php

return [
    'presenter' => 'Laraplus\Form\Presenters\Bootstrap4Presenter',
    'styles'    => [
        'horizontal' => [
            'form'     => 'form-horizontal',
            'label'    => 'col-12 col-sm-4 control-label font-weight-bold',
            'element'  => 'col-12  col-sm-8',
            'no_label' => 'col-12 col-sm-12 text-right'
        ],
        'horizontal_narrow' => [
            'form'     => 'form-horizontal',
            'label'    => 'col-sm-3 control-label',
            'element'  => 'col-sm-8',
            'no_label' => 'col-sm-8 col-sm-offset-3'
        ],
        'vertical'   => [
            'form'    => 'form-vertical',
            'label'   => 'font-weight-bold',
            'element' => null
        ],
        'inline'     => [
            'form'    => 'form-inline',
            'label'   => null,
            'element' => null
        ],
    ]
];
