<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\InvoiceStatus $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Invoice Status',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-status-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
