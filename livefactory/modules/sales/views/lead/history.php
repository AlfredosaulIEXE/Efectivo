<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\Office;
use livefactory\models\SalesReport;
use yii\helpers\ArrayHelper;
use livefactory\models\LeadSource;
use livefactory\models\User;

/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel

 */

$this->title = 'Reporte de trabajo';

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
date_default_timezone_set('America/Mexico_City');
$by_office = Yii::$app->user->can('Office.NoLimit') == true;
$filter = Yii::$app->request->getQueryParam('Lead');
$request = Yii::$app->request->getQueryParams();
$office_id = $by_office ? Yii::$app->request->getQueryParam('office_id') : Yii::$app->user->identity->office_id;
require 'filteruser.php';
?>
<div class="row">
    <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <?php
    $payed = [
        1 => "Yes",
        2 => "No"
    ];

    Pjax::begin ();

    try {
        echo GridView::widget([

            'dataProvider' => $dataProvider,

            'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,

            'pjax' => true,

            'columns' => [
                [
                        'attribute' => 'user_id',
                    'label' => 'ID',
                    'width' => '5%'
                ],
                [
                        'attribute' => 'code',
                    'label' => 'Oficina',
//                    'width' => '10%',
//                    'value' => function($model){
//                        $office = Office::findOne($model['office_id']);
//                        return $office->business_name;
//                    }

                ],
                [
                        'attribute' => 'user_id',
                  'label' => 'Agente',
                    'value' => function($model)
                    {
                        return $model['first_name'] . ' ' . $model['middle_name'] . ' ' . $model['last_name'];
                    }
                ],
                [
                        'attribute' => 'c_control',
                    'label'  => 'Folio',
                    'value' => function($model){
                        return $model['c_control'] == null ? '' : $model['c_control'];
                    }
                ],
                [
                        'attribute' => 'notes',
                    'label' => 'Notas',
                    'format' => 'raw'
                ],
                [
                        'attribute' => 'added_at',
                    'label' => 'Fecha',
                    'value' => function($model){
                        return date('d/m/Y H:i s', $model['added_at']);
                    }
                ]

            ],
            'responsive' => true,
            'hover' => true,
            'condensed' => true,
            'floatHeader' => false,
            'panel' => [

                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app', 'Agentes') . ' </h3>',

                'type' => 'info',

                /*'before' => '<form action=""  method="post" name="frm">
                <input type="hidden" name="_csrf" value="'.$csrf.'">
<input type="hidden" name="multiple_del" value="true">'.Html::a ( '<i class="glyphicon glyphicon-plus"></i> '.Yii::t ( 'app', 'Add' ), [
                        'create'
                ], [
                        'class' => 'btn btn-success  btn-sm'
                ] ).' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger  btn-sm"><i class="glyphicon glyphicon-trash"></i> '.Yii::t ( 'app', 'Delete Selected' ).'</a>',*/

//                'before' => '<form action=""  method="post" name="frm">
//                                <input type="hidden" name="_csrf" value="' . $csrf . '">
//   <input type="hidden" name="multiple_del" value="true">' . Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
//                        'create'
//                    ], [
//                        'class' => 'btn btn-success  btn-sm'
//                    ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger  btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', 'Delete Selected') . '</a>',
//                'after' => '</form>' . Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
//                        'index'
//                    ], [
//                        'class' => 'btn btn-info  btn-sm'
//                    ]),

                'showFooter' => false
            ]
        ]);

    }
    catch (Exception $e) {
    }

    Pjax::end ();
?>
</div>
<!--<div class="row">-->
<!--    <form id="report_form" action="index.php">-->
<!--        <input type="hidden" name="r" value="sales/lead/history">-->
<!--        <div class="ibox">-->
<!--            <div class="ibox-title">-->
<!--                <h5>Filtrar reporte de trabajo</h5>-->
<!--            </div>-->
<!--            <div class="ibox-content">-->
<!--                --><?php //require 'reports/filter_history.php'; ?>
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->
<!--    <div class="ibox">-->
<!--        <div class="ibox-content">-->
<!--            <table class="table table-bordered" id="dtHistory">-->
<!--                <thead>-->
<!--                    <tr>-->
<!--                        <th>ID</th>-->
<!--                        <th>Oficina</th>-->
<!--                        <th>Agente</th>-->
<!--                        <th>Folio Lead</th>-->
<!--                        <th>Lead</th>-->
<!--                        <th>Nota</th>-->
<!--                        <th>Fecha</th>-->
<!--                    </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                --><?php //$position = 1; ?>
<!--                --><?php //foreach ($dataProvider as $user):?>
<!--                <tr>-->
<!--                    <td>--><?//=$position?><!--</td>-->
<!--                    <td>--><?//= $user['code']?><!--</td>-->
<!--                    <td>--><?//= $user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['middle_name']?><!--</td>-->
<!--                    <td>--><?//= $user['c_control']?><!--</td>-->
<!--                    <td>--><?//= $user['lead_name']?><!--</td>-->
<!--                    <td>--><?//= $user['notes']?><!--</td>-->
<!--                    <td>--><?//=  date('d/m/Y H:i' , $user['added_at'])?><!--</td>-->
<!--                </tr>-->
<!--                --><?php //$position++?>
<!--                --><?php //endforeach;?>
<!--                </tbody>-->
<!--            </table>-->
<!---->
<!--        </div>-->
<!---->
<!--    </div>-->
<!--</div>-->

