<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\Department;
use livefactory\models\TicketCategory;
use livefactory\models\Queue;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\QueueMap $searchModel
 */

$this->title = Yii::t('app', 'Queue Maps');
$this->params['breadcrumbs'][] = $this->title;
if(!empty($_REQUEST['QueueMap']["ticket_category_id_2"])){
		$id=$_REQUEST['QueueMap']["ticket_category_id_2"];
		$ticketCategory=ArrayHelper::map ( TicketCategory::find ()->where("active=1 and parent_id=$id")->orderBY("sort_order")->all(), 'id', 'label' );
	}else{
		$ticketCategory = ArrayHelper::map ( TicketCategory::find ()->where("id=0")->orderBY("sort_order")->all(), 'id', 'label' );
	}
	if(!empty($_REQUEST['QueueMap']["department_id"])){
		$id=$_REQUEST['QueueMap']["department_id"];
		$ticketCategory1 =ArrayHelper::map(TicketCategory::find()->where("active=1 and parent_id=0 and department_id=$id")->orderBY("sort_order")->all(),'id','label');
		$Queue=ArrayHelper::map (Queue::find ()->where("department_id=$id")->all(), 'id', 'queue_title' );
	}else{
		$ticketCategory1 = array();
		$Queue = ArrayHelper::map ( Queue::find ()->where("id=0")->all(), 'id', 'queue_title' );
	}
?>
<div class="queue-map-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           /// 'id',
           // 'department_id',
			[
				'attribute'=>'department_id',
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',

				'filter' => ArrayHelper::map(Department::find()->where("active=1")->orderBY("sort_order")->all(),'id','label'),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],
				'value'=>function($model){
					return $model->department->label;	
				}
			],
			[
				'attribute'=>'ticket_category_id_2',
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',

				'filter' => $ticketCategory1,

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],
				'value'=>function($model){
					return $model->ticketCategory1->label;	
				}
			],
			[
				'attribute'=>'ticket_category_id_2_id',
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',

				'filter' => $ticketCategory,

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],
				'value'=>function($model){
					return $model->ticketCategory2->label;	
				}
			],
			[
				'attribute'=>'queue_id',
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',

				'filter' => $Queue,

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],
				'value'=>function($model){
					return $model->queue->queue_title;	
				}
			],
           // 'ticket_category_id_2',
           // 'ticket_category_id_2_id',
          //  'queue_id',

            [
                'class' => '\kartik\grid\ActionColumn',
                'buttons' => [
				'view' => function ($url, $model) {
                                    return '';
								},
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/queue-map/update','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success btn-sm']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info btn-sm']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
