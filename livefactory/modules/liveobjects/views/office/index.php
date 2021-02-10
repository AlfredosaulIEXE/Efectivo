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
 * @var livefactory\models\search\Office $searchModel
 */

$this->title = Yii::t('app', 'Offices');
$this->params['breadcrumbs'][] = $this->title;
function statusLabel($status)
{
	if ($status !='0')
	{
        $label = "<span class=\"label label-primary\">".Yii::t('app', 'Active')."</span>";
	}
	else
	{
        $label = "<span class=\"label label-danger\">".Yii::t('app', 'Inactive')."</span>";

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

    <?php Pjax::begin();
    try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'code',
                'description',
                'rfc',
                [
                    'attribute' => 'weekly_goal',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget)
                    {
                        return '$'.number_format($model->weekly_goal, 2);
                    }
                ],
                'business_name',
                'website',
                'folio',
                [
                    'attribute'=>'active',
                    'label'=>'Estatus',
                    'format'=>'raw',
                    'filter'=> $status,
                    'value'=> function($model, $key, $index, $widget){
                        return statusLabel($model->active);
                    }
                ],
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{update} {time_clock}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/office/update', 'id' => $model->id, 'edit' => 't']), [
                                'title' => Yii::t('app', 'Edit'),
                            ]);
                        },
                        'time_clock' => function ($url, $model) {
                            return '<a href="'.Yii::$app->urlManager->createUrl(['liveobjects/office/timeclock', 'id' => $model->id]).'"><span class="glyphicon glyphicon-time"></span></a>';
                        },
                        'delete' => function ($url, $model) {
                            return $model->corporate == 0 && Yii::$app->user->can('Office.Delete') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['liveobjects/office/delete', 'id' => $model->id]), [
                                'title' => Yii::t('app', 'Delete'),
                            ]) : '';
                        }
                    ],
                ],

            ],
            'responsive' => true,
            'hover' => true,
            'condensed' => true,
            'floatHeader' => false,


            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
                'type' => 'info',
                'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success btn-sm']),
                'showFooter' => false
            ],
        ]);
    } catch (Exception $e) {
    }
    Pjax::end(); ?>


</div>
 