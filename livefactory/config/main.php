<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [

		'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=efectivo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
			
	
        ],

		 'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
					 'clientId' => '461116531974-118ivgsdkm4ub245apklejvp717h3hke.apps.googleusercontent.com',
					 'clientSecret' => 'Q6_6mCNzPIdCDZwhrprP4x3J',
					 
                ],
				'linkedin' => [
					'class' => 'yii\authclient\clients\LinkedIn',
					'clientId' => '75hmdcdxtqdgc9',
					'clientSecret' => 'aryTKWuD2S0G0TQV',
				],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '191147674386187',
                    'clientSecret' => '4de62910d8029f4e5081b8704bbd829c',
                ],
            ],
        ],
		'i18n' => [
					'translations' => [
						'app*' => [
							'class' => 'yii\i18n\PhpMessageSource',
							'basePath' => '@livefactory/messages',
							'fileMap' => [
								'app' => 'app.php',
							],
						],
					],
				],

		
		
    ],

	'as beforeRequest' => [  //if guest user access site so, redirect to login page.
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login', 'error', 'request-password-reset', 'reset-password', 'support-email-automatic-tickets','database-backup','project-summary-report','daily-user-work','timely-spent-report'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
];
