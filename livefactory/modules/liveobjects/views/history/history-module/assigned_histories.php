<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;

use yii\helpers\ArrayHelper;

?>

    <?php 

	Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProviderAssHis,

        //'filterModel' => $searchModelAssHis,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



  //          'id',

            //'task_id',

            //'task_name',

			[ 

				'attribute' => 'from_user_id',

				'label' => Yii::t('app','From Users'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

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

				if(isset($model->user1) && !empty($model->user1->first_name)) 

					return $model->user1->first_name." ".$model->user1->last_name;

				} 

		],

		

		[ 

				'attribute' => 'to_user_id',

				'label' => Yii::t('app','To Users'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user2) && !empty($model->user2->first_name)) 

					return $model->user2->first_name." ".$model->user2->last_name;

				} 

		],

			

			[ 

					'attribute' => 'added_at',
					'label' => Yii::t('app','Added'),
					'width' => '20%',
					'format' => 'raw',

					'value' => function ($model, $key, $index, $widget) {

					if(isset($model->added_at)) {

						return date('jS \of F Y H:i:s',$model->added_at);
					}  else{
							return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
						}

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

				'label' => Yii::t('app', 'Assigned by'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '20%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user3) && !empty($model->user3->first_name)) 

					return $model->user3->first_name." ".$model->user3->last_name;

				} 

		],

        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Assignment History').'</h3>',

            'type'=>'info',

            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>