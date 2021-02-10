<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DefectPriority $model
 */

$this->title = Yii::t('app', 'Create Defect Priority');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Defect Priority'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defect-priority-create">
     <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5> <?=$this->title ?></h5>

            <div class="ibox-tools">

                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
               
                <a class="close-link" href="index.php?r=pmt/defect-priority/index">
                    <i class="fa fa-times"></i>
                </a>
            </div>
</div>
         <div class="ibox-content">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div></div></div>
