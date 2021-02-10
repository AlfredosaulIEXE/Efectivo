<?php
$config = [
    'components' => [
    ],
];


if (YII_DEBUG) {
	if ($_SESSION['SHOW_DEBUG_TOOLBAR'] =='Yes') {
		// configuration adjustments for 'dev' environment
		$config['bootstrap'][] = 'debug';
		$config['modules']['debug'] = [
			'class' => 'yii\debug\Module',
			'allowedIPs' => ['1.2.3.4', '127.0.0.1', '*']
		];

		$config['bootstrap'][] = 'gii';
		// changed by mohitg
		// $config['modules']['gii'] = 'yii\gii\Module';
		$config['modules']['gii']['class'] = 'yii\gii\Module';
	}
}

	// added by mohitg
	$config['modules']['gii']['generators'] = [
        'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
    ];
return $config;
