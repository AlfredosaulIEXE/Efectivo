<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;



/**

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\UserRole $searchModel

 */



$this->title = Yii::t('app', 'User Roles');

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
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success">User Role is Added </div>
<?php	}
?>
<div class="user-role-index">

<!--

    <div class="page-header">

            <h1><?= Html::encode($this->title) ?></h1>

    </div>-->



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <!--<p> -->

        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [

    'modelClass' => 'User Role',

]), ['create'], ['class' => 'btn btn-success'])*/  ?>

<!--    </p> -->



    <?php Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProvider,

        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



//            'id',

            'role',

            'label',

           // 'status',

		   //'active',

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

//            'created_at',

//            'updated_at', 



            [

                'class' => '\kartik\grid\ActionColumn',

                'buttons' => [

                'update' => function ($url, $model) {

                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['user/user-role/view','id' => $model->id,'edit'=>'t']), [

                                                    'title' => Yii::t('app', 'Edit'),

                                                  ]);},
				'delete' => function ($url, $model) {
						
						if($model->id =='1' || $model->label =='Admin'){

														return '';

													}else{

													return Html::a ( '<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl ( [ 

															'user/user-role/delete',

															'id' => $model->id

													] ), [ 

															'title' => Yii::t('app', 'Delete' ) ,
															'data-method'=>"post",
															'onclick'=>'return confirm("Are you Sure!")'

													] );

													}
											}



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

            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), [ 'create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app', 'Reset List'),  ['index'], ['class' => 'btn btn-info']),

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>



</div>

