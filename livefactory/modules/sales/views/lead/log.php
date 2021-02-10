<?php

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;
use livefactory\models\search\Lead;

use yii\helpers\ArrayHelper;
use livefactory\models\HistoryModel;

$this->title = 'Bitácora de agentes';
$filter = Yii::$app->request->getQueryParam('r');
$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
if ($filter === 'sales/lead/logcustomer')
{
    $filter_user = ArrayHelper::map (User::find( )->leftJoin('auth_assignment','auth_assignment.user_id = tbl_user.id')->where('auth_assignment.item_name = "Customer" OR auth_assignment.item_name = "Customer.Service" OR auth_assignment.item_name = "Customer.Service2" OR auth_assignment.item_name = "Customer.Director"')->asArray ()->all (),'id',function ($user){
        $user_log = Yii::$app->user->identity->attributes;

        return $user['first_name'] . ' ' . $user['last_name']. ' ' . $user['middle_name'] . '(' .$user['username'] . ')';
    });

}else
    if ($filter === 'sales/lead/loginsurance')
    {
        $filter_user = ArrayHelper::map (User::find( )->leftJoin('auth_assignment','auth_assignment.user_id = tbl_user.id')->where('auth_assignment.item_name = "Insurance" OR auth_assignment.item_name = "Insurance.Customer"')->asArray ()->all (),'id',function ($user){
            $user_log = Yii::$app->user->identity->attributes;

            return $user['first_name'] . ' ' . $user['last_name']. ' ' . $user['middle_name'] . '(' .$user['username'] . ')';
        });
    }
?>
<?php
if ($filter != 'sales/lead/log')
require 'filterlog.php';
if ($filter === 'sales/lead/log')
    require 'filteruser.php';
?>
<?php
date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

Pjax::begin(); echo GridView::widget([

    'dataProvider' => $dataProvider,

    'filterModel' => $searchModel,

    'responsive' => true,'responsiveWrap' => false,

    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],

        [

            'attribute' => 'user_id',

            'label' => Yii::t('app', 'User'),

            'filterType' => GridView::FILTER_SELECT2,

            'format' => 'raw',

            'width' => '20%',

            'filter' => $filter_user,

            'filterWidgetOptions' => [

                'options' => [

                    'placeholder' => Yii::t('app', 'All...')

                ],

                'pluginOptions' => [

                    'allowClear' => true

                ]

            ],

            'value' => function ($model, $key, $index, $widget) {

                //var_dump($model->user);

                if(isset($model->user) && !empty($model->user->first_name))

                    return $model->user->first_name." ".$model->user->last_name . " " . $model->user->middle_name;

            }

        ],

        [

            'attribute' => 'entity_id',
            'label' => 'Lead',
            'format' => 'raw',

            'width' => '60%',

            'value' => function ($model) {
                if (isset($model->entity_id) && ! empty($model->entity_id)) {
                    $lead = Lead::findOne(['id' => $model->entity_id]);

                    return '<a href="index.php?r=sales/lead/view&id='.$lead->id.'"><strong>'.$lead->c_control.'</strong> '.$lead->lead_name.'</a>';
                }

                return '';
            }

        ],


        //          'id',

        //'task_id',

        //'task_name',

        [

            'attribute' => 'notes',
            'label' => 'Descripción',
            'format' => 'raw',

            'width' => '60%'

        ],

        [

            'attribute' => 'added_at',
            'label' => Yii::t('app','Date'),
            'width' => '20%',

            'value' => function ($model, $key, $index, $widget) {

                if(isset($model->added_at))

                    return date('d/m/Y h:i s A',$model->added_at);

            }





        ],

        [

            'attribute' => 'entity_id',
            'label' => 'Tipo',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => HistoryModel::actions(),
            'filterWidgetOptions' => [

                'options' => [

                    'placeholder' => Yii::t('app', 'All...')

                ],

                'pluginOptions' => [

                    'allowClear' => true

                ]

            ],
            'width' => '20%',
            'format' => 'raw',

            'value' => function ($model, $key, $index, $widget) {
                return HistoryModel::getAction($model->entity_id);
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

]); Pjax::end(); ?>