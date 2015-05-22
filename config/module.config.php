<?php

return [
    'validators' => [
        'invokables' => [
            'DoctrineExt\Validator\NoObjectExists' => 'DoctrineExt\Validator\NoObjectExists',
        ],
        'aliases' => [
            'NoObjectExists' => 'DoctrineExt\Validator\NoObjectExists',
        ],
    ],
];