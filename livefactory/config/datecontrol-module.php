<?php
use kartik\datecontrol\Module;
if(!isset($_SESSION)) {
     session_start();
}
return [
    
	'modules' => [
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'yyyy/M/dd',
                'time' => 'H:i:s',
                'datetime' => 'yyyy/M/dd H:i:s',
            ],

						'autoWidgetSettings' => [ 

								Module::FORMAT_DATE => [ 

										'type' => 2,

										'pluginOptions' => [ 

												'autoclose' => true 

										] 

								], // example

								Module::FORMAT_DATETIME => [ 

										'type' => 2,

										'pluginOptions' => [ 

												'autoclose' => true 

										] 

								], // setup if needed

								Module::FORMAT_TIME => [ ] 

						],
 
            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'yyyy/M/dd', 
                'time' => 'H:i:s',
                'datetime' => 'yyyy/M/dd H:i:s',
            ],
 
 
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
 
        ]
    ],
];
