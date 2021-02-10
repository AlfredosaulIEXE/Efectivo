<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use livefactory\models\UserType;
use livefactory\models\User;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Announcement $searchModel
 */

$this->title = Yii::t('app', 'Announcement');
$this->params['breadcrumbs'][] = $this->title;
function statusLabel($status)
{
	if ($status !='0')
	{
		$label = "<span class=\"label label-danger\">".Yii::t('app', 'Inactive')."</span>";
	}
	else
	{
		$label = "<span class=\"label label-primary\">".Yii::t('app', 'Active')."</span>";
	}
	return $label;
}
$status = array('0'=>Yii::t('app', 'Active'),'1'=>Yii::t('app', 'Inactive'));
?>
<div class="msg-of-day-index">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Msg Of Day',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        //    'msg_of_day_id',
            'message',
         /*   [ 
										'attribute' => 'user_type_id',
										'label' => 'User Type',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
									//	'width' => '250px',
										'filter' => ArrayHelper::map ( UserType::find ()->orderBy ( 'label' )->asArray ()->all (), 'id', 'label' ),
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

											// var_dump($model->user);

											if (isset ( $model->userType ) && ! empty ( $model->userType->label ))

												return $model->userType->label;

										} 
								],*/
            
            [ 
										'attribute' => 'created_by',
										//'label' => 'Project Type',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
									//	'width' => '250px',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'id' )->asArray ()->all (), 'id', 'username' ),
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

											// var_dump($model->user);

											if (isset ( $model->username ) && ! empty ( $model->username->username ))

												return $model->username->username;

										} 
								],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            'updated_by', 
         [ 
				'attribute' => 'is_status',
				'label' => 'Status',
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
						return statusLabel ( $model->is_status );
				} 
		], 
		  
            [
                'class' => '\kartik\grid\ActionColumn',
				'template' => '{update} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/announcement/update','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>'.Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success btn-sm']),                                       'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i>'.Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info btn-sm']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>


</div>
 