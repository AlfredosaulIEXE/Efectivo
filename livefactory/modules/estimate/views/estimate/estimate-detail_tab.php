<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\EstimateDetails $searchModel
 */

?>
<div class="estimate-details-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'estimate_id',
           // 'product_id',
            'product_description',
            'description',
  'rate', 
    'quantity', 
//            'tax_id', 
         'tax_amount', 
       'total', 
//            'active', 
//            'added_at', 
//            'updated_at', 

            [
                'class' => '\kartik\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['estimate-details/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Estimate Details').' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),  			
			'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
