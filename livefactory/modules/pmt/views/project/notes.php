<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
?>
    <?php 
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderNotes,
        //'filterModel' => $searchModelNotes,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

  //          'id',
            //'task_id',
            //'task_name',
			[ 
					'attribute' => 'notes',
					'format' => 'raw',
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
				'width' => '10%',
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
					return $model->user->username;
				} 
		],

            [
               'class' => '\kartik\grid\ActionColumn',
				//'template'=>'{view}{update}{delete}',
				//'class'=>'CButtonColumn',
				// 'class' => ActionColumn::className(),
				
    			'template'=>'{update} {view} {delete}',
                'buttons' => [
				'width' => '150px',
                'update' => function ($url, $model) {
                                   return "<form name='frm".$model->id."' action='index.php?r=pmt/project/project-view&id=".$_REQUEST['id']."&note_id=".$model->id."' method='post' style='display:inline'><input type='hidden' value='Zm1UbFY1djgQJh4/CU9PcQFbBR4fAzUNEAgMDzoETkoMXD8nHWQHVA==' name='_csrf'>
									<a href='#' onClick='document.frm".$model->id.".submit()' title='Edit' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";},
				'view' => function ($url, $model) {
                                    return "";},
				'delete' => function ($url, $model) {
                                    return '<a href="index.php?r=pmt/project/project-view&id='.$_REQUEST['id'].'&note_del_id='.$model->id.'" onClick="return confirm(\'Are you Sure!\')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';}
				

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Notes </h3>',
            'type'=>'info',
            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>