<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\File;

use livefactory\models\Lead;

use livefactory\models\Payment;

use livefactory\models\User;

?>

<?php
$lead = Lead::findOne($_REQUEST['id']);
$payment = Payment::find()->where(['entity_id' => $_REQUEST['id']] )->andWhere(' type != 5')->all();
$total = 0;
foreach ($payment as $pay){
    $total += $pay->amount;
}
$rest = $lead->loan_commission - $total;
?>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Saldo total</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins text-success">$<?php echo number_format($lead->loan_commission,2);?></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Pagado</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins text-primary">$<?php echo number_format($total,2); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Restante</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins text-danger">$<?php echo number_format($rest,2);?></h1>
                </div>
            </div>
        </div>
    </div>
</div>


    <?php

    Yii::$app->request->enableCsrfValidation = true;

    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');

    Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProviderPayment1,

        'responsive' => true,'responsiveWrap' => false,

        //'filterModel' => $searchModelAttch,

        'columns' => [

            [
                'attribute' => 'folio',
                'label'=>Yii::t('app', '#'),
                'format' => 'raw'
            ],

            [

                'attribute' => 'amount',
                'label'=>Yii::t('app', 'Amount'),
                'format' => 'raw',

                'value' => function ($model) {
                    return '$'.number_format($model->amount,2);
                }

            ],

            [

                'attribute' => 'type',
                'label'=>Yii::t('app', 'Type'),
                'format' => 'raw',

                'value' => function ($model) {
                    $paymentTypes = Payment::getTypes();
                    return $paymentTypes[$model->type];
                }

            ],

            [

                'attribute' => 'date',
                'label'=>Yii::t('app', 'Date'),
                'format' => 'raw'

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

                'attribute' => 'received',
                'label'=>Yii::t('app', 'Alias'),
                'format' => 'raw',
                'value' => function ($model) {
                    $generator = ! empty($model->generator_id) ? User::findOne($model->generator_id)->alias  : $model->received;
                    $co_generator = ! empty($model->co_generator_id) ? User::findOne($model->co_generator_id)->alias : '';

                    return $generator . ($co_generator != '' ? ' / ' . $co_generator : '');
                }

            ],

            [

                'attribute' => 'origin',
                'label'=>Yii::t('app', 'Origin'),
                'format' => 'raw',
                'value' => function ($model) {
                    $paymentOrigins = [
                        1 => 'Efectivo',
                        2 => 'Transferencia',
                        3 => 'Depósito Bancario',
                        4 => 'Tarjeta de Crédito/Débito',
                        5 => 'Cheque',
                    ];
                    return $paymentOrigins[$model->origin];
                }

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

            [

                'class' => '\kartik\grid\ActionColumn',

                //'template'=>'{view}{update}{delete}',

                //'class'=>'CButtonColumn',

                // 'class' => ActionColumn::className(),

                'template'=>'{update}  {print}  {delete}  {primary}',

                'buttons' => [

                    'width' => '10%',

                    'update' => function ($url, $model) {
                        if (Yii::$app->user->can('Payment.Update')) {
                            return "<form name='frm_payment" . $model->id . "' action='" . 'index.php?r=' . $_REQUEST['r'] . '&id=' . $_REQUEST['id'] . "&payment_edit=" . $model->id . "' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>

                                    <a href='#' onClick='document.frm_payment" . $model->id . ".submit()' title='" . Yii::t('app', 'Edit') . "' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";
                        } else {
                            return '';
                        }
                    },

                    'print' => function ($url, $model) {
                        return '<a href="#" class="js-print-btn" data-id="'.$model->id.'" data-type="receipt" title="'.Yii::t('app', 'Print').'"><span class="glyphicon glyphicon-print"></span></a>';
                    },

                    'delete' => function ($url, $model)
                    {
                        if (Yii::$app->user->can('Payment.Delete')) {
                            return '<a href="index.php?r=sales/lead/paymentdelete&id='.$_REQUEST['id'].'&payment_del='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete').'"><span class="glyphicon glyphicon-trash"></span></a>';
                        } else {
                            return '';
                        }

                    },

                ],

            ],

        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Payments').'  </h3>',

            'type'=>'info',

            'before'=> '<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.paymentae\').modal(\'show\');"><i class="glyphicon glyphicon glyphicon-usd"></i> '.Yii::t('app', 'New Payment').'</a>&nbsp;' .  (Yii::$app->user->can('Role.Manager')? '<a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="$(\'.paymentab\').modal(\'show\');">Agregar cargo/abono</a>' : ''),
            /*                                                                                                                                                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>

    <script>
        function get_confirm(){
            return confirm("<?=Yii::t ('app','Are you Sure!')?>");
        }
    </script>