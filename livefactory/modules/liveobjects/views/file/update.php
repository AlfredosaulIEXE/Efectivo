<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\File $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'File',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="file-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
