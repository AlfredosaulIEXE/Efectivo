<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use livefactory\models\Department;
use livefactory\models\TicketCategory;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\TicketImpact $searchModel
 */

$this->title = Yii::t('app', 'Ticket Category');
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
		<div class="alert alert-success"><?=Yii::t('app', 'Ticket Category is Added')?></div>
<?php	}
?>
<div class="ticket-category-index">
<!-- <form action="" method="post" name="frm"> -->
    <?php Yii::$app->request->enableCsrfValidation = true; ?>
<!--    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="actionType" id="actionType"> -->
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
//'pjax' => true,
        'columns' => [
        //    ['class' => 'yii\grid\SerialColumn'],
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
  //          'id',
            'name',
            'label',
	
            [
                
                'attribute' => 'department_id',
                
                'filterType' => GridView::FILTER_SELECT2,
                
                'format' => 'raw',
                
                'width' => '300px',
                
                'filter' => ArrayHelper::map(Department::find()->orderBy('name')
                    ->where("active=1")
                    ->asArray()
                    ->all(), 'id', 'name'),
                
                'filterWidgetOptions' => [
                    
                    'options' => [
                        
                        'placeholder' => Yii::t('app', 'All...')
                    ]
                    ,
                    
                    'pluginOptions' => [
                        
                        'allowClear' => true
                    ]
                    
                ]
                ,
                
                'value' => function ($model, $key, $index, $widget)
                
                {

					if (isset($model->department)) {
						return $model->department->name;
					}
                }
            ] ,
//            'modified_at', 
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
				'template'=>'{update}  {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['support/ticket-category/update','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Edit'),'data-pjax'=>"0"
                                                  ]);},
				

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
<!-- </form> -->
<script>
	function fillValue(val){
		document.getElementById('actionType').value=val;
	    document.frm.submit();
	}
</script>
</div>
