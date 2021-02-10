<?php

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;
use livefactory\models\search\Lead;

use yii\helpers\ArrayHelper;
use livefactory\models\HistoryModel;

$link = Yii::$app->request->getQueryParam('r');
//var_dump($link);
$this->title = $link === 'sales/lead/useractivityinsurance' ? 'Bitácora de actividad de agentes de seguro' : 'Bitácora de actividad de agentes';

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
//var_dump(Yii::$app->request->getQueryParams());
?>

<?php require 'filteruser.php'; ?>

<?php
date_default_timezone_set(Yii::$app->params['TIME_ZONE']); ?>

<div class="row">
    <div class="col-sm-12">

            <?php Yii::$app->request->enableCsrfValidation = true;


                ?>

                <?php
                $payed = [
                    1 => "Yes",
                    2 => "No"
                ];

                try {
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

            'attribute' => 'name',

            'label' => Yii::t('app', 'User'),

            'format' => 'raw',

            'width' => '20%',
            'value' => function ($model,$key,$index,$wdiget){
                return '<a href="index.php?r=sales/lead/useractivityview&id=' . $model['user_id'] . '&date_activity='. $model['date_activity'] . '" >' . $model['name'] . '</a>';
            }


        ],

        [

            'attribute' => 'date_activity',
            'label' => 'Fecha de Actividad',
            'format' => 'raw',

            'width' => '20%',
            'value' => function ($model,$key,$index,$widget){
                return date('d/m/Y',strtotime($model['date_activity']));
            }


        ],

        [

            'attribute' => 'time_online',
            'label' => 'Tiempo en Linea',
            'format' => 'raw',

            'width' => '20%',
            'value' => function ($model, $key, $index, $widget)
            {
                $time_online = $model['time_online'] / 60 ;
                $time_hours = $time_online/60;
                $time_minutes = $time_online%60;

               return  number_format($time_hours,0)  . 'h ' . $time_minutes . 'm ';
            }

        ],
        [
                'attribute' => 'productivity_time',
                'label' => 'Tiempo de Productividad',
                'format' => 'raw',
                'width' => '20%',
                 'value' => function ($model, $key, $index, $widget)
            {
                $time_online = $model['productivity_time'] / 60 ;
                $time_hours = $time_online/60;
                $time_minutes = $time_online%60;

                return  number_format($time_hours,0)  . 'h ' . $time_minutes . 'm ';

            }

        ],
        [
            'label' => 'Tiempo Inactivo',
            'format' => 'raw',
            'width' => '20%',
            'value' => function ($model, $key, $index, $widget)
            {
                $time_free = ($model['time_online'] - $model['productivity_time'] ) / 60;
                $time_hours = $time_free/60;
                $time_minutes = $time_free%60;

                return  number_format($time_hours,0)  . 'h ' . $time_minutes . 'm ';
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

]);} catch (Exception $e){

}  ?>
</div>
</div>
