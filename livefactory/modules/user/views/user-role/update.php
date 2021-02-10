<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\UserRole $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User Role',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
