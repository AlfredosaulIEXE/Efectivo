<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\QueueUsers $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Queue Users',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Queue Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-users-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
