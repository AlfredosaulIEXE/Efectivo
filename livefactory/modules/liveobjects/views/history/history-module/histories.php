<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;

use yii\helpers\ArrayHelper;
use livefactory\models\User as UserDetail;
?>

    <?php

	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

	Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProviderHistory,

        //'filterModel' => $searchModelHistory,
    'rowOptions'=>function($model)  {
        //$sql="SELECT username, email FROM users";
        $sql = "select auth_assignment.item_name , auth_assignment.user_id from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=$model->user_id";
        $connection = \Yii::$app->db;
        $command=$connection->createCommand($sql);
        $dataReader=$command->queryOne();

        //var_dump($dataReader['item_name'] , $model->user_id);exit;
	   if($dataReader['item_name'] == "Admin" || $dataReader['item_name'] == 'Customer.Director' || $dataReader['item_name'] == 'Customer.Service' ) {

           return ['class' => 'warning'];
       }
	   else
	       if ($dataReader['item_name']  == 'Insurance.Customer' || $dataReader['item_name'] == 'Insurance.Director')
        {
            return ['class' => 'secondary'];
        }
	       else{
	           return ['class' => 'info'];
           }

    },

		'responsive' => true,'responsiveWrap' => false,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



  //          'id',

            //'task_id',

            //'task_name',

			[

					'attribute' => 'notes',
					'label' => Yii::t('app', 'Notes'),
					'format' => 'raw',

					'width' => '60%'

			],

			[

					'attribute' => 'added_at',
					'label' => Yii::t('app','Date'),
					'width' => '20%',

					'value' => function ($model, $key, $index, $widget) {

					if(isset($model->added_at))

						return date('d/m/Y h:i A',$model->added_at);

					}





			],

			/*[

					'attribute' => 'updated_at',

					'width' => '20%',
					'format' => 'raw',

					'value' => function ($model, $key, $index, $widget) {

					if($model->updated_at !='0000-00-00 00:00:00') {

						return date('jS \of F Y H:i:s',$model->updated_at);

						}  else{
							return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
						}
					}





			],*/







			[

				'attribute' => 'user_id',

				'label' => Yii::t('app', 'User'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '20%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [

						'options' => [

								'placeholder' => Yii::t('app', 'All...')

						],

						'pluginOptions' => [

								'allowClear' => true

						]

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->first_name))

					return $model->user->first_name." ".$model->user->last_name;

				}

		],

        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'History').' </h3>',


            'type'=>'info',

            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>