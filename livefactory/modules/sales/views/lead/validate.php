<?php



use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\File;
use livefactory\models\SalesReport;
use livefactory\models\User;
use livefactory\models\Payment;

/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel
 *

 */

$this->title = 'Validación de pagos';

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

        <div class="ibox">
            <div class="ibox-title">
                <h5>Pagos pendientes de confirmar</h5>
            </div>
            <div class="ibox-content">
                <form id="report_form" action="index.php">
                    <input type="hidden" name="r" value="sales/lead/validate">
                    <?php require 'reports/filters.php'; ?>
                </form>
                <hr>
                <form action="<?php echo $_REQUEST['REQUEST_URI']; ?>" method="post">
                    <?php if (Yii::$app->request->getQueryParam('success') == 'true'): ?>
                    <div class="alert alert-success">
                        Los pagos fueron modificados correctamente.
                    </div>
                    <?php endif; ?>

                <div class="alert alert-warning js-payment-warning">
                    Selecciona los pagos que deseas validar o declinar.
                </div>
                <div class="alert alert-info js-payment-validate hide">
                    Haz seleccionado <strong><span class="js-payment-count">0</span> pagos</strong>, marcar como <select name="payment_status" required><option value="">Seleccionar...</option><option value="<?=Payment::VALIDATED?>">Validado</option><option value="<?=Payment::DECLINED?>">Declinado</option></select> <button type="submit">Enviar</button>
                </div>

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

            'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,

            'pjax' => true,

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => [
                    ]
                ],
                [
                    'attribute' => 'lead.c_control',
                    'label' => 'Folio',
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
                    'attribute' => 'folio',
                    'label' => 'No. de Pago',
                    'value' => 'folio'
                ],
                [
                    'attribute' => 'amount',
                    'label' => 'Monto',
                    'value' => function  ($model) use ($generators) {
                        return '$' . number_format($model->amount, 2);
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
                    'attribute' => 'generator',
                    'label' => 'Generador(es)',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',

                    'width' => '10%',

                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'value' => function ($model) use (&$generators) {

                        $generator = ! empty($model->generator_id) ? User::findOne($model->generator_id)  : $model->received;
                        $co_generator = ! empty($model->co_generator_id) ? User::findOne($model->co_generator_id) : '';

                        return (is_object($generator) ? $generator->first_name . ' '. $generator->last_name . ' ' . $generator->middle_name : $generator) . ($co_generator != '' ? ' / ' . ($co_generator->first_name . ' ' . $co_generator->last_name . ' ' . $co_generator->middle_name) : '');
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
                    'attribute' => 'note',
                    'label'=>Yii::t('app', 'Note'),
                    'format' => 'raw'
                ],

                [

                    'attribute' => 'code',
                    'label'=>Yii::t('app', 'Code'),
                    'format' => 'raw'

                ],
                [

                    'attribute' => 'file_id',
                    'label'=>Yii::t('app', 'File'),

                    'format' => 'raw',

                    'value' => function ($model, $key, $index, $widget) {
                        $f_model = File::findOne($model->file_id);
                        $icons['.php']='glyphicon glyphicon-file';
                        $icons['.txt']='glyphicon glyphicon-file';
                        $icons['.xlsx']='fa fa-file-excel-o';
                        $icons['.xls']='fa fa-file-excel-o';
                        $icons['.gif']='fa fa-image';
                        $icons['.png']='fa fa-image';
                        $icons['.jpg']='fa fa-image';
                        $icons['.jpeg']='fa fa-image';
                        $icons['.docx']='fa fa-file-word-o';
                        $icons['.doc']='fa fa-file-word-o';
                        $iconClass = array_key_exists(strrchr($f_model->file_name, "."),$icons)?$icons[strrchr($f_model->file_name, ".")]:'glyphicon glyphicon-file';
                        if(strrchr($f_model->file_name, ".")=='.php'){
                            return "
									<form name='frmx".$f_model->id."' action='../attachments/view_attachment.php?pagename=".$f_model->id.strrchr($f_model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$f_model->id.".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$f_model->file_title."</a></form>";
                        }else if ($f_model->file_name){
                            return "
									<form name='frmx".$f_model->id."' action='../attachments/".$f_model->id.strrchr($f_model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$f_model->id.".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$f_model->file_title."</a></form>";
                        } else {
                            return '<div class="alert alert-warning" style="margin: 0; padding: 5px;"> <i class="fa fa-warning"></i> No se ha cargado el comprobante.</div>';
                        }
                    },
                ],


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
                </form>
            </div>
        </div>
</div>