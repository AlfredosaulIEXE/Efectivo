<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\CustomerType $model
 */

$this->title = Yii::t('app', 'Update Customer Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Types'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
	$(document).ready(function(e) {
        $('#customertype-type').attr('disabled',true);
    });
</script>
<div class="customer-type-update">
<div class="ibox float-e-margins">
                    <div class="ibox-title">
    					<h5> <?= Html::encode($this->title) ?></h5>

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
	</div>
    </div>
</div>
