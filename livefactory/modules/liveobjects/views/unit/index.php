<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

?>
    <div class="msg-of-day-index">
<?php

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

Pjax::begin();
try{
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,
        'pjax' => true,
        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'width' => '10%',
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'width' => '15%'
            ],
            [
                    'attribute' => 'description',
                'format' => 'raw',
                'width' => '15%'

            ],
            [
                'attribute'=>'active',
                'label'=>'Estatus',
                'format'=>'raw',
                'width' => '15%',
                'filter'=> $status,
                'value'=> function($model, $key, $index, $widget){
                    return statusLabel($model->active);
                }
            ],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/unit/update', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('app', 'Edit'),
                        ]);

                    },
                    'delete' => function ($url, $model) {
                        $view = isset($_GET['view']) && $_GET['view'] != '' ? '&view=' . $_GET['view'] : '';
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['liveobjects/unit/delete', 'id' => $model->id . $view]), [
                            'title' => Yii::t('app', 'Delete'),
                            'data' => [
                                'method' => 'post',
                                'confirm' => Yii::t('app', 'Are you sure?')],
                        ]);
                    },

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
} catch (Exception $e){
}
Pjax::end() ?>


    </div>
