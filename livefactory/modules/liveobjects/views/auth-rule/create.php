<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\AuthRule $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Auth Rule',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
