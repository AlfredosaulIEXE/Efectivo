<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\PaymentDetails $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Payment Details',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-details-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
