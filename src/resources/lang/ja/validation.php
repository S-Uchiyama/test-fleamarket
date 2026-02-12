<?php

return [
    'required'  => ':attributeを入力してください',
    'email'     => ':attributeはメール形式で入力してください',
    'unique'   => ':attributeはすでに使用されています',
    'min'       => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'confirmed' => ':attributeと一致しません',

    'custom' => [
        'name' => [
            'required' => 'お名前を入力してください',
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email'    => 'メールアドレスはメール形式で入力してください',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
        ],
    ],

    'attributes' => [
        'name'     => 'お名前',
        'email'    => 'メールアドレス',
        'password' => 'パスワード',
    ],
];