<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app', 'Duplicate  {modelClass}: ', [
    'modelClass' => 'Estimate',
]) . ' ' . $model->estimation_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index']];
///$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
include_once('script.php');
?>
<script>
$(document).ready(function(e) {
	$('#estimate-date_issued-disp').val($('#estimate-date_issued').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->date_issued))?>');
});
</script>
<div class="estimate-update">
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
								'estimateDetails'=>$estimateDetails
							]) ?>

                    </div>

                </div>
</div>
