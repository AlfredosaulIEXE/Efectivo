<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\EstimateStatus $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Estimate Status',
]) . ' ' . $model->status;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimate Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->status];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script>

	$(document).ready(function(e) {

		if('<?=$_GET['id']?>'){

        	$('#estimatestatus-status').attr('readonly',true);

		} 

    });

</script>
<div class="estimate-status-update">
<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5> <?=$this->title ?></h5>

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
    ]) ?>

</div></div>
</div>
