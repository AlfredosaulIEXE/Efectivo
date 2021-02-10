<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
?>
    <?php 
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderAttach,
        //'filterModel' => $searchModelAttch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

  //          'id',
            //'task_id',
            //'task_name',
			[ 
					'attribute' => 'file_title',
					'width' => '20%' 
			],
			[ 
					'attribute' => 'file_name',
					'width' => '20%' 
			],
			[ 
					'attribute' => 'entity_type',
					'width' => '20%' 
			],
			
			
			 
			[ 
				'attribute' => 'uploaded_user_id',
				'label' => 'User',
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
				if(isset($model->user) && !empty($model->user->first_name)) 
					return $model->user->username;
				} 
		],
            [
               'class' => '\kartik\grid\ActionColumn',
				//'template'=>'{view}{update}{delete}',
				//'class'=>'CButtonColumn',
				// 'class' => ActionColumn::className(),
    			'template'=>'{update} {view} {mail} {delete}',
                'buttons' => [
				'width' => '100px',
                'update' => function ($url, $model) {
                                    return"<form name='frm_att".$model->id."' action='index.php?r=pmt/task/task-view&id=".$_REQUEST['id']."&attach_update=".$model->id."' method='post' style='display:inline'><input type='hidden' value='QXQuN2JNQ0x3N2tEBH9zDiM3V2gwNQgtGRBaTiEJMgEXJlRWWikIOw==' name='_csrf'>
									<a href='#' onClick='document.frm_att".$model->id.".submit()' title='Edit' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";},
				'view' => function ($url, $model) {
                                    return "
									<form name='frm".$model->id."' action='../attachments/".$model->id.strrchr($model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frm".$model->id.".submit()' title='View' target='_parent'><span class='glyphicon glyphicon-eye-open'></span></a></form>";},
				'mail' => function($url,$model){
					 return '<a href="javascript:void(0)" onClick="sendAttachment(\''.$model->file_name.'\',\''.$model->id.strrchr($model->file_name, ".").'\')" title="Delete"><span class="glyphicon glyphicon-envelope"></span></a>';
				},
				'delete' => function ($url, $model) {
                                    return '<a href="index.php?r=pmt/task%2Ftask-view&id='.$_REQUEST['id'].'&attachment_del_id='.$model->id.'" onClick="return confirm(\'Are you Sure!\')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
									
									/* Html::a(' <span class="glyphicon glyphicon-trash danger"></span>', Yii::$app->urlManager->createUrl(['task/task-view','id'=>$_REQUEST['id'],'attachment_id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                  ]);*/}
				

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Attachments </h3>',
            'type'=>'info',
            /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>