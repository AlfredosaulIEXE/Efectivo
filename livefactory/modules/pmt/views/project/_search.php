<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\search\Project $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="project-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_project_id') ?>

    <?= $form->field($model, 'project_type_id') ?>

    <?= $form->field($model, 'project_name') ?>

    <?= $form->field($model, 'added_by') ?>

    <?php // echo $form->field($model, 'last_updated_by') ?>

    <?php // echo $form->field($model, 'project_owner') ?>

    <?php // echo $form->field($model, 'added_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'expected_start_date') ?>

    <?php // echo $form->field($model, 'expected_end_date') ?>

    <?php // echo $form->field($model, 'actual_start_date') ?>

    <?php // echo $form->field($model, 'actual_end_date') ?>

    <?php // echo $form->field($model, 'project_description') ?>

    <?php // echo $form->field($model, 'project_budget') ?>

    <?php // echo $form->field($model, 'project_currency') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'project_status_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
