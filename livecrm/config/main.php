<?php
use kartik\mpdf\Pdf;
$params = array_merge ( require (__DIR__ . '/../../livefactory/config/params.php'), require (__DIR__ . '/../../livefactory/config/params-local.php'), require (__DIR__ . '/params.php'), require (__DIR__ . '/params-local.php') );

return [ 
		
		'id' => 'app-livecrm',
		'basePath' => dirname ( __DIR__ ),
		'controllerNamespace' => 'livecrm\controllers',
		'bootstrap' => [ 
				'log',
				'devicedetect' 
		],
		'modules' => [ 
				
				'gii' => [ 
						'class' => 'yii\gii\Module',
						'allowedIPs' => [ 
								'127.0.0.1',
								'::1',
								'192.168.0.*',
								'*' 
						]  // adjust this to your needs
				],
				
				// added by mohitg
				'gridview' => [ 
						'class' => 'kartik\grid\Module' 
				],
				
				'liveobjects' => [ 
						'class' => 'livefactory\modules\liveobjects\Module' 
				],
				'pmt' => [ 
						'class' => 'livefactory\modules\pmt\Module' 
				],
				'user' => [ 
						'class' => 'livefactory\modules\user\Module' 
				],
				'sales' => [ 
						'class' => 'livefactory\modules\sales\Module' 
				],
				'customer' => [ 
						'class' => 'livefactory\modules\customer\Module' 
				],
				'product' => [ 
						'class' => 'livefactory\modules\product\Module' 
				],
				'cron' => [ 
						'class' => 'livefactory\modules\cron\Module' 
				],
				'support' => [ 
						'class' => 'livefactory\modules\support\Module' 
				],
				'invoice' => [ 
						'class' => 'livefactory\modules\invoice\Module' 
				],
				'estimate' => [ 
						'class' => 'livefactory\modules\estimate\Module' 
				] 
		
		],
		'components' => [ 
				'request' => [ 
						// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
						'cookieValidationKey' => '_W9DJsw87u8W4cyNT65kPjRG82HxcbYT' 
				],
				'user' => [ 
						'identityClass' => 'livefactory\models\User',
						'enableAutoLogin' => true 
				],
				'log' => [ 
						'traceLevel' => YII_DEBUG ? 3 : 0,
						'targets' => [ 
								[ 
										'class' => 'yii\log\FileTarget',
										'levels' => [ 
												'error',
												'warning' 
										] 
								] 
						] 
				],
				'pdf' => [ 
						'class' => Pdf::classname (),
						'format' => Pdf::FORMAT_A4,
						'orientation' => Pdf::ORIENT_PORTRAIT,
						'destination' => Pdf::DEST_BROWSER 
					// refer settings section for all configuration options
				],
				'errorHandler' => [ 
						'errorAction' => 'site/error' 
				],
				'authManager' => [ 
						'class' => 'yii\rbac\DbManager',
						'defaultRoles' => [ 
								'guest' 
						] 
				],
				'as access' => [ 
						'class' => 'mdm\admin\components\AccessControl',
						'allowActions' => [ 
								'site/*'  // add or remove allowed actions to this list
						] 
				],
				'devicedetect' => [ 
						'class' => 'alexandernst\devicedetect\DeviceDetect' 
				] 
		
		],
		
		'params' => $params 

];