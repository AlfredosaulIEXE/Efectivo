<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
?>
    <?php 
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderHistory,
        //'filterModel' => $searchModelHistory,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

  //          'id',
            //'task_id',
            //'task_name',
			[ 
					'attribute' => 'notes',
					'width' => '60%' 
			],
			[ 
					'attribute' => 'added_at',
					'width' => '20%',
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->added_at)) 
						return date('Y-m-d',$model->added_at);
					}  
					
					
			],
			
			
			 
			[ 
				'attribute' => 'user_id',
				'label' => 'User',
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> History</h3>',
            'type'=>'info',
            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>