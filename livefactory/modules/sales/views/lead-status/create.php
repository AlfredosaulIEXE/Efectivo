<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\LeadStatus $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Lead Status',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lead Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lead-status-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
