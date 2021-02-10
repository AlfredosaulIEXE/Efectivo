<?php

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;
use livefactory\models\search\Lead;

use yii\helpers\ArrayHelper;
use livefactory\models\HistoryModel;

$this->title = 'Actividad de agente';

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
?>

<?php
date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

 echo GridView::widget([

    'dataProvider' => $dataProvider,

    'filterModel' => $searchModel,

    'responsive' => true,'responsiveWrap' => false,

    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'code',
            'label' => 'Oficina',
            'format' => 'raw',
            'width' => '20%',
        ],

        [

            'attribute' => 'lead_name',

            'label' => Yii::t('app', 'User'),

            'format' => 'raw',

            'width' => '20%',
            'value' => function ($model,$key,$index,$wdiget){
                return '<a href="index.php?r=sales/lead/view&id=' . $model['lead_id'] .'" >' . $model['lead_name'] . '</a>';
            }


        ],

        [

            'attribute' => 'notes',
            'label' => 'Nota de Actividad',
            'format' => 'raw',

            'width' => '20%',



        ],

        [

            'attribute' => 'prod_time',
            'label' => 'Tiempo de acción',
            'format' => 'raw',

            'width' => '20%',
            'value' => function ($model, $key, $index, $widget)
            {
                $time_online = $model['prod_time'] / 60 ;
                $time_hours = $time_online/60;
                $time_minutes = $time_online%60;

                return  number_format($time_hours,0)  . 'h ' . $time_minutes . 'm ';
            }

        ],

        [
            'label' => 'Fecha',
            'format' => 'raw',
            'width' => '20%',
            'value' => function ($model, $key, $index, $widget)
            {
                return date('d/m/Y H:i s', $model['added_at']);
            }
        ]



    ],

    'responsive'=>true,

    'hover'=>true,

    'condensed'=>true,

    //'floatHeader'=>true,









    'panel' => [

        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'History').' </h3>',


        'type'=>'info',

        /*'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

        'showFooter'=>false

    ],

]);  ?>
