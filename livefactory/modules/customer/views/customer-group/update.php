<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\CustomerGroup $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer Group',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Groups'), 'url' => ['index']];
///$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-group-update">
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
