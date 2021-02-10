<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\search\Task $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'task_name') ?>

    <?= $form->field($model, 'task_description') ?>

    <?= $form->field($model, 'actual_end_datetime') ?>

    <?php // echo $form->field($model, 'actual_start_datetime') ?>

    <?php // echo $form->field($model, 'time_spent') ?>

    <?php // echo $form->field($model, 'assigned_user_id') ?>

    <?php // echo $form->field($model, 'payment_rate') ?>

    <?php // echo $form->field($model, 'project_id') ?>

    <?php // echo $form->field($model, 'expected_start_datetime') ?>

    <?php // echo $form->field($model, 'expected_end_datetime') ?>

    <?php // echo $form->field($model, 'task_status_id') ?>

    <?php // echo $form->field($model, 'task_priority_id') ?>

    <?php // echo $form->field($model, 'date_added') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <?php // echo $form->field($model, 'progress') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'added_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
