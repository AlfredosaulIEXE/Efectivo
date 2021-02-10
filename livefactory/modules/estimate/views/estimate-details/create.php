<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\EstimateDetails $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Estimate Details',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimate Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estimate-details-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
