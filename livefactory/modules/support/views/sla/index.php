<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Sla $searchModel
 */

$this->title = Yii::t('app', 'Ticket SLA');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sla">
    

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'ticket_priority_id',
            //'ticket_impact_id',
			[ 

					'attribute' => 'ticket_priority_id',
					'label' => Yii::t('app','Priority'),
					'filterType' => GridView::FILTER_SELECT2,
					'format' => 'raw',
					'filter' => ArrayHelper::map ( TicketPriority::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
					'filterWidgetOptions' => [ 
							'options' => [ 
									'placeholder' => Yii::t('app', 'All...') 
							],
							'pluginOptions' => [ 
									'allowClear' => true 
							] 
					],

					'value' => function ($model, $key, $index, $widget)
					{
						return  $model->ticketPriority->label ;
					} 
			],
			[ 

					'attribute' => 'ticket_impact_id',
					'label' => Yii::t('app','Impact'),
					'filterType' => GridView::FILTER_SELECT2,
					'format' => 'raw',
					'filter' => ArrayHelper::map ( TicketImpact::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
					'filterWidgetOptions' => [ 
							'options' => [ 
									'placeholder' => Yii::t('app', 'All...') 
							],
							'pluginOptions' => [ 
									'allowClear' => true 
							] 
					],

					'value' => function ($model, $key, $index, $widget)
					{
						return  $model->ticketImpact->label ;
					} 
			],
            'sla',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['support/sla/view', 'id' => $model->id, 'edit' => 't']),
                            ['title' => Yii::t('app', 'Edit'),]
                        );
                    }
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,

        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type' => 'info',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success btn-sm']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]); Pjax::end(); ?>

</div>
