<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\History $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'History',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
