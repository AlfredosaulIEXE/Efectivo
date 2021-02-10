<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\ProductCategory;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Product $searchModel
 */

$this->title = Yii::t('app', 'Products');
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
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>


            <?php Yii::$app->request->enableCsrfValidation = true; ?>

<div class="product-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
//'pjax' => true,
        'columns' => [
			['class' => '\kartik\grid\CheckboxColumn'],
          //  ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'product_name',
			[ 
				'attribute' => 'product_name',
				'width' => '200px',
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget)
				{
					return '<a href="index.php?r=product/product/product-view&id=' . $model->id . '">' . $model->product_name . '</a>';
				} 
		],
            //'product_description:ntext',
			[ 
				'attribute' => 'product_description',
				//'format' => 'raw',
				'value'=>function($model){
                    return $model->product_description;
               }
			],
            //'product_category_id',
			[ 
				'attribute' => 'product_category_id',
				//'label' => 'Project Type',
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',
			//	'width' => '250px',
				'filter' => ArrayHelper::map ( ProductCategory::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
					// var_dump($model->user);
					if (isset ( $model->productCategory ) && ! empty ( $model->productCategory->label ))
						return $model->productCategory->label;
				} 
		],
           // 'product_price',
		   [ 
				'attribute' => 'product_price',
				'width' => '200px',
				'format' => 'raw',
			],
//            'added_at', 
//            'updated_at', 
			[ 
				'attribute' => 'active',
			//	'label' => 'Active',
				'format' => 'raw',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => $status,
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
						return statusLabel ( $model->active );
				} 
		],

            [
                'class' => '\kartik\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['product/product/product-view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);},
				'view' => function ($url, $model) {
                                    return '';}

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
            'before' => '<form action="" method="post" name="frm"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_del" value="true">'.Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
                'create'
            ], [
                'class' => 'btn btn-success btn-sm'
            ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', "Delete Selected") . '</a>',
            'after' => '</form>'.Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                'index'
            ], [
                'class' => 'btn btn-info btn-sm'
            ]),
            'showFooter' => false
        ],
    ]); Pjax::end(); ?>

</div>

<script>
function all_del(){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			document.frm.submit()

		} else {

			

		}	

	}
</script>