<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;



/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\PaymentDetails $searchModel
 */

//$this->title = Yii::t('app', 'Payment Details');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-details-index">
    <!--<div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Payment Details',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'invoice_id',
			/*['attribute'=>'invoice_id',
				'value'=>function($model){
					return $model->invoice->invoice_number;
				}
			],*/
            ['attribute' => 'payment_date','format' => ['date', 'php:d-M-Y']],
            'amount',
         //   'payment_method',
//            'notes', 
//            'added_at', 
//            'updated_at', 

            /*[
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['invoice/payment-details/view', 'id' => $model->id, 'edit' => 't']),
                            ['title' => Yii::t('app', 'Edit'),]
                        );
                    }
                ],
            ],*/
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,

        'panel' => [
            //'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type' => 'info',
            //'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
           // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false,
        ],

		'toolbar' => [],
    ]); Pjax::end(); ?>

</div>
