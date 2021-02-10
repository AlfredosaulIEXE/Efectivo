<?php



use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\Office;
use livefactory\models\Payment;
use livefactory\models\SalesReport;
use yii\helpers\ArrayHelper;

use livefactory\models\LeadSource;
use livefactory\models\User;

/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel
 *

 */

$this->title = Yii::t ( 'app', 'Payments' );

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';

$by_office = Yii::$app->user->can('Office.NoLimit') == true;
$filter = Yii::$app->request->getQueryParam('Lead');
$office_id = $by_office ? Yii::$app->request->getQueryParam('office_id') : Yii::$app->user->identity->office_id;
list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());

$paymentOrigins = [
    1 => 'Efectivo',
    2 => 'Transferencia',
    3 => 'Depósito Bancario',
    4 => 'Tarjeta de Crédito/Débito',
    5 => 'Cheque',
];
$paymentTypes = [
    1 => 'Cobro contrato nuevo',
    2 => 'Cobro anticipo',
    3 => 'Cobro addendums',
    4 => 'Cobro incremento',
    5 => 'Cobro de seguro'
];
$generators = [];

function generatorId($model) {
    return $model->entity_id . '.' . $model->folio . '.' . $model->origin . '.' . $model->date;
}
?>
<?php

foreach ($totalAmount as $data)
{
    $totalAmounts1=$data;
}
foreach ($totalAmountCash as $data)
{
    $totalAmounts2=$data;
}
foreach ($totalAmountTransfer as $data)
{
    $totalAmounts3=$data;
}
foreach ($totalAmountDeposit as $data)
{
    $totalAmounts4=$data;
}
foreach ($totalAmountCredit as $data)
{
    $totalAmounts=$data;
}
?>
<div class="row">
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total de Pagos:</h5>
        </div>
        <div class="panel-body">
            <h1><?=$stats['payments']?></h1>

        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total Todos los Cobros :</h5>
        </div>
        <div class="panel-body">
            <h1>$<?=number_format($stats['total'], 2)?></h1>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total de Cobros en Firme y Validados, Sin Seguro:</h5>
        </div>
        <div class="panel-body">
            <h1>$<?=number_format($stats['total-validate'], 2)?></h1>
        </div>
    </div>
</div>
    <?php if (Yii::$app->user->can('Payment.Insurance')) { ?>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total de Cobro de Seguro:</h5>
        </div>
        <div class="panel-body">
            <h1>$<?=number_format($stats['insurance']['total'], 2)?></h1>
        </div>
    </div>
</div>
    <?php } ?>
</div>
<div class="row">
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total Efectivo (En Firme):</h5>
        </div>
        <div class="panel-body">

            <h1>$<?=number_format($stats['efectivo'], 2)?></h1>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total de Transferencias ya Validadas:</h5>
        </div>
        <div class="panel-body">
            <h1>$<?=number_format($stats['transferencia'], 2)?></h1>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total de Deposito Bancario ya Validado:</h5>
        </div>
        <div class="panel-body">
            <h1>$<?=number_format($stats['deposito'], 2)?></h1>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Total Tarjeta de Débito y Crédito ya Validadas:</h5>
        </div>
        <div class="panel-body">

            <h1>$<?=number_format($stats['tarjeta'], 2)?></h1>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5>Total en Firme ya Validados:</h5>
            </div>
            <div class="panel-body" >

                <h1>$<?=number_format($stats['total-validate'], 2)?></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h5>Total en Validación:</h5>
            </div>
            <div class="panel-body" >

                <h1>$<?=number_format($stats['validate'], 2)?></h1>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h5>Total de Declinados:</h5>
            </div>
            <div class="panel-body" >

                <h1>$<?=number_format($stats['declinated'], 2)?></h1>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <form id="report_form" action="index.php">
        <input type="hidden" name="r" value="sales/lead/payments">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Filtrar reporte de pagos</h5>
            </div>
            <div class="ibox-content">
                <?php require 'reports/filters.php'; ?>
            </div>
            <div class="ibox-content">
                <?php require  'filterpayments.php'; ?>
            </div>
        </div>
    </form>


    <?php Yii::$app->request->enableCsrfValidation = true;



    ?>

    <?php
    $payed = [
        1 => "Yes",
        2 => "No"
    ];

    Pjax::begin();

    try {

        echo GridView::widget([

            'dataProvider' => $dataProvider,
            'rowOptions' => function($dataProvider){
                if ($dataProvider['status'] == \livefactory\models\Payment::UNVALIDATED)
                {
                    return ['class' => 'gray'];
                }
                if ($dataProvider['status'] == \livefactory\models\Payment::DECLINED)
                {
                    return['class' => 'danger'];
                }
//                if ($dataProvider['status'] == \livefactory\models\Payment::VALIDATED)
//                {
//                    return['class' => 'success'];
//                }
            },

            'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,

            'pjax' => true,

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'lead.c_control',
                    'label' => 'Folio',
                    'width' => '5%',
                    'value' => 'lead.c_control'
                ],
                [
                    'attribute' => 'lead_name',
                    'label' => 'Nombre de Cliente',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a href="index.php?r=sales/lead/view&id=' . $model->lead->id . '">' . $model->lead->lead_name . '</a>';
                    }
                ],
                [
                        'attribute'=>'added_at',
                    'label'=>'Fecha de creación de lead',
                    'filterType' => GridView::FILTER_DATE,
                                'filterWidgetOptions' => [
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd ',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                    ]
                                ],
                    'value'=> function($model){
                        return date('d/m/Y',$model->lead->added_at);

                    }
                ],
                [
                    'attribute' => 'lead_source_id',
                     'label' => 'Fuente de lead',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '10%',
                    'filter' => ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],

                    'value' => function ($model, $key, $index, $widget) {
                        // var_dump($model->user);
                        //var_dump($model->leadStatus);
                        if (isset ($model->lead->leadSource) && !empty ($model->lead->leadSource->label))
                            return $model->lead->leadSource->label;
                    }
                ],
                [
                        'attribute' => 'folio',
                    'label' => 'No. de Pago',
                    'value' => 'folio'
                ],
                [
                    'attribute' => 'amount',
                    'label' => 'Monto',
                    'value' =>

                        function  ($model) use ($generators) {
                            global $amounts ;
                            global $total;
                        $amount = $model->amount;
                        if ( ! empty($model->co_generator_id)) {
                            $amount = $amount / 2;
                        }
                        return '$' . number_format($amount, 2);

                    }

                ],
                [
                    'attribute' => 'date',
                    'label' => 'Fecha de Pago',
                    'value' => function ($model) {
                        return date('d/m/Y', strtotime($model->date));
                    }
                ],
                [
                        'attribute' => 'office',
                        'label' => 'Oficina',
                        'value' => function ($model){
                            return $model->user->office->code;
                        }
                ],
                [
                    'attribute' => 'generator',
                    'label' => 'Generador',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',

                    'width' => '10%',
                    'filter' => ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1 ".$office_user_filter )->union('select * from tbl_user where id=173')->asArray()->all(),'id',function($user){
                        return $user['alias'].' ('.$user['username'].')';
                    }),

                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'value' => function ($model) use (&$generators) {

                        if ( $model->generator_id == null )
                            return $model->received;

                        if (isset($generators[generatorId($model)])) {
                            return $model->generator->first_name . ' '. $model->generator->last_name .' '. $model->generator->middle_name;
                        }

                        // Save
                        $generators[generatorId($model)] = true;

                        return $model->user->first_name . ' ' .$model->user->last_name . ' '.$model->user->middle_name;
                    }
                ],
                [
                    'attribute' => 'type',
                    'label' => 'Tipo de Pago',

                    'value' => function ($model) use ($paymentTypes) {
                        return  $paymentTypes[$model->type] ;
                    }
                ],
                [
                    'attribute' => 'origin',
                    'label' => 'Forma de Pago',
                    'value' => function ($model) use ($paymentOrigins) {
                        return $paymentOrigins[$model->origin];
                    }
                ],

                [

                    'attribute' => 'status',
                    'label'=> 'Estado',
                    'format' => 'raw',
                    'value' => function ($model) {
                        static $classes = [
                            Payment::UNVALIDATED => 'warning',
                            Payment::DECLINED => 'danger',
                            Payment::VALIDATED => 'success'
                        ];

                        return '<label class="label label-'.$classes[$model->status].'">'.Payment::paymentStatus($model->status).'</label>';
                    }

                ],
                    [
                        'attribute' => 'user_id',
                        'label' => 'Capturista',
                        'filterType' => GridView::FILTER_SELECT2,
                        'format' => 'raw',

                        'width' => '10%',
                        'filter' => ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1 ".$office_user_filter )->union('select * from tbl_user where id=173')->asArray()->all(),'id',function($user){
                            return $user['alias'].' ('.$user['username'].')';
                        }),

                        'filterWidgetOptions' => [
                            'options' => [
                                'placeholder' => Yii::t('app', 'All...')
                            ],

                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ],
                        'value' => function ($model) use ($paymentOrigins) {
                            return $model->lead->agent->first_name . ' ' . $model->lead->agent->last_name;
                        }
                    ]

            ],
            'responsive' => true,
            'hover' => true,
            'condensed' => true,
            'floatHeader' => false,

            'panel' => [

                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app', 'Leads') . ' </h3>',

                'type' => 'info',

                'after' => '</form>' . Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                        'payments'
                    ], [
                        'class' => 'btn btn-info  btn-sm'
                    ]),

                'showFooter' => false
            ]
        ]);
    } catch (Exception $e) {
    }

    Pjax::end();
    ?>
</div>
