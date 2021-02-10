<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\EmailTemplate $model
 */

$this->title = Yii::t('app', 'Update Email Template: ', [
    'modelClass' => 'Email Template',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->template_name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<style>
#cke_1_contents{min-height:400px !important}
</style>
<div class="email-template-update">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5><?= $this->title ?> <small class="m-l-sm">  <?php echo Yii::t('app', 'Name').", ".Yii::t('app', 'Subject')." & ".Yii::t('app', 'Body'); ?></small></h5>
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

</div></div></div>
