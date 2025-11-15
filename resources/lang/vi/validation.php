<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'unique' => 'Trường :attribute đã tồn tại. Vui lòng chọn một :attribute khác.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given rule attribute.
    |
    */

    'custom' => [
        'ma_tuyen' => [
            'unique' => 'Mã tuyến xe đã tồn tại. Vui lòng chọn mã khác.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'ma_tuyen' => 'mã tuyến xe',
        'diem_di' => 'điểm đi',
        'diem_den' => 'điểm đến',
        'ngay' => 'ngày',
        'thoi_gian_bat_dau' => 'thời gian bắt đầu',
        'thoi_gian_ket_thuc' => 'thời gian kết thúc',
    ],

];
