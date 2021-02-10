<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\ProjectType $searchModel
 */

$this->title = Yii::t('app', 'Project Types');
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
		<div class="alert alert-success"><?=Yii::t('app', 'Project Type is Added')?> </div>
<?php	}
?>
<div class="project-type-index">
<!-- <form action="" method="post" name="frm"> -->
    <?php Yii::$app->request->enableCsrfValidation = true; ?>
<!--    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="actionType" id="actionType"> -->
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
//'pjax' => true,
        'columns' => [

   //         ['class' => 'yii\grid\SerialColumn'],
   [ 
				'attribute' => 'id',
				'label'=>'#',
				'width' => '10px' ,
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget)
				{
					return '<input type="radio" name="sort_order_update" value="'.$model->id.'"><input type="hidden" name="sort_order_update'.$model->id.'" value="'.$model->sort_order.'">';
				}
			],

//            'id',
            'type',
            'label',
         //   'sort_order',
            //'active',
			[ 
				'attribute' => 'active',
				//'label' => 'Active',
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
//            'added_at', 
//            'modified_at', 

            [
                'class' => '\kartik\grid\ActionColumn',
				'template'=>'{update} {view} {delete} {defaultValue}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['pmt/project-type/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);},
				'defaultValue' => function ($url, $model) {
					if(\livefactory\models\DefaultValueModule::checkDefaultValue('project_type',$model->id)){
						return Html::a('<span class="fa fa-eraser"></span>', Yii::$app->urlManager->createUrl(['pmt/project-type/index','del_id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Delete Default'),
                                                  ]);
					}else{
						return Html::a('<span class="fa fa-tag"></span>', Yii::$app->urlManager->createUrl(['pmt/project-type/index','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Make Default'),
                                                  ]);
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
          'before'=>'<form action="" method="post" name="frm">'.Html::a('<i class="glyphicon glyphicon-plus"></i>  '.Yii::t ( 'app', 'Add' ), ['create'], ['class' => 'btn btn-success btn-sm'])." ".'<button type="button" onClick="fillValue(\'Up\')" value="Up" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-arrow-up"> </span> '.Yii::t('app', 'Up').'</button>'." ".'<button type="button" onClick="fillValue(\'Down\')" value="Down" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-arrow-down"> </span> '.Yii::t('app', 'Down').'</button><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'">
			<input type="hidden" name="actionType" id="actionType">
',          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t ( 'app', 'Reset List' ), ['index'], ['class' => 'btn btn-info btn-sm']).' '.'</form>',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

<script>
	function fillValue(val){
		document.getElementById('actionType').value=val;
	    document.frm.submit();
	}
</script>
</div>
