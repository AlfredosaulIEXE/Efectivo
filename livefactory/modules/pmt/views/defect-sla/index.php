<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\DefectPriority;
use livefactory\models\DefectType;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\DefectSla $searchModel
 */

$this->title = Yii::t('app', 'Defect SLA');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defect-sla-index">
    
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[ 

					'attribute' => 'defect_priority_id',
					'label' => Yii::t('app','Priority'),
					'filterType' => GridView::FILTER_SELECT2,
					'format' => 'raw',
					'filter' => ArrayHelper::map ( DefectPriority::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
						return  $model->defectPriority->label ;
					} 
			],
			
			[ 

					'attribute' => 'defect_type_id',
					'label' => Yii::t('app','Type'),
					'filterType' => GridView::FILTER_SELECT2,
					'format' => 'raw',
					'filter' => ArrayHelper::map ( DefectType::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
						return  $model->defectType->label ;
					} 
			],

            'start_sla',
			'end_sla',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['pmt/defect-sla/view', 'id' => $model->id, 'edit' => 't']),
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
