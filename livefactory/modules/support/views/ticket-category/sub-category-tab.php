<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\searchTicketCategory $searchModel
 */

//$this->title = Yii::t('app', 'Ticket Categories');
///$this->params['breadcrumbs'][] = $this->title;
function statusLabel($status)
{
	if ($status !='1')
	{
		$label = "<span class=\"label label-danger\">".Yii::t('app', 'Inactive')."</span>";
	}
	else
	{
		$label = "<span class=\"label label-primary\">".Yii::t('app', 'Active')."</span>";
	}
	return $label;
}
?>
<div class="ticket-category-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           /// 'id',
            'name',
            'label',
           /// 'active',
			[
				'attribute'=>'active',
				'format'=>'raw',
				///'filter'=>ArrayHelper::map(Department::find()->where("active=1")->orderBy("sort_order")->all(),'id','label'),
				'value'=>function($model){
					return statusLabel($model->active);
						
				}
			],
			[
				'attribute'=>'description',
				'format'=>'raw',
			],
            //'description',
//            'parent_id', 
//    'department_id', 
			[
				'attribute'=>'department_id',
				///'filter'=>ArrayHelper::map(Department::find()->where("active=1")->orderBy("sort_order")->all(),'id','label'),
				'value'=>function($model){
					return $model->department->name;	
				}
			],
//            'sort_order', 
//            'added_at', 
//            'updated_at', 

            [
                'class' => '\kartik\grid\ActionColumn',
				'template' => ' {update} {delete}',
                'buttons' => [
				
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['support/ticket-category/sub-update','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);
												  }

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Sub Categories').' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create','parent_id'=>$_GET['id']], ['class' => 'btn btn-success btn-sm']),                                                                                                                                                          'after'=>false,
            'showFooter'=>false
        ],
		
		'toolbar' => [
					//'{toggleData}',
				//	'{export}',
				],
		
    ]); Pjax::end(); ?>

</div>
