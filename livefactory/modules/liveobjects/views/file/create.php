<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\File $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'File',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
