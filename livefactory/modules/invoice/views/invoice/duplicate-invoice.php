<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Invoice $model
 */

if($_REQUEST['r'] == 'invoice/invoice/duplicate-invoice')
{
$this->title = Yii::t('app', 'Duplicate  {modelClass} ', [
    'modelClass' => 'Invoice',
]) . ' ' . $model->invoice_number;
}
else
{
	$this->title = Yii::t('app', '{modelClass}: ', [
    'modelClass' => 'Invoice',
]) . ' ' . $model->invoice_number;
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice'), 'url' => ['index']];
///$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Duplicate Invoice');
include_once('script.php');
?>
<script>
/*$(document).ready(function(e) {
	$('#invoice-date_created-disp').val($('#invoice-date_created').val()=='0000-00-00'?'':'<?=date('Y/m/d',strtotime($model->date_created))?>');
	$('#invoice-date_due-disp').val($('#invoice-date_due').val()=='0000-00-00'?'':'<?=date('Y/m/d',strtotime($model->date_due))?>');
});*/
</script>
<div class="invoice-update">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?></h5>

                        <div class="ibox-tools">

						    <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

                    <div class="ibox-content">

							 <?= $this->render('_form', [
								'model' => $model,
								'taxList'=>$taxList,
								'InvoiceDetails'=>$invoiceDetails,
							]) ?>

                    </div>

                </div>
</div>
