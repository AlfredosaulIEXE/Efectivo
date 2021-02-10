<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;



/**

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var livefactory\models\search\ConfigItem $searchModel

 */



$this->title = Yii::t('app', 'Config Items');

$this->params['breadcrumbs'][] = $this->title;
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

$status = array('0'=>Yii::t('app', 'Inactive'),'1'=>Yii::t('app', 'Active'));
?>

<div class="config-item-index">

<!--

    <div class="page-header">

            <h1><?= Html::encode($this->title) ?></h1>

    </div>

	-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



  

        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [

    'modelClass' => 'Config Item',

]), ['create'], ['class' => 'btn btn-success'])*/  ?>





    <?php Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProvider,

        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



//            'id',

//            'created_at',

//            'updated_at',

            'config_item_name',

            'config_item_value',

            'config_item_description', 

            [ 

				'attribute' => 'active',

				//'label' => 'Active',

				'format' => 'raw',

				'filterType' => GridView::FILTER_SELECT2,

				'filter' => $status,

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget)

				{

						return statusLabel ( $model->active );

				} 

		],



            [

                'class' => '\kartik\grid\ActionColumn',

                'buttons' => [

                'update' => function ($url, $model) {

                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/config-item/view','id' => $model->id,'edit'=>'t']), [

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

            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>



</div>