<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var livefactory\models\search\Lead $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="lead-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lead_name') ?>

    <?= $form->field($model, 'lead_description') ?>

    <?= $form->field($model, 'lead_type_id') ?>

    <?= $form->field($model, 'lead_owner_id') ?>

    <?php // echo $form->field($model, 'lead_status_id') ?>

    <?php // echo $form->field($model, 'lead_status_description') ?>

    <?php // echo $form->field($model, 'lead_source_id') ?>

    <?php // echo $form->field($model, 'lead_source_description') ?>

    <?php // echo $form->field($model, 'opportunity_amount') ?>

    <?php // echo $form->field($model, 'do_not_call') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'first_name') ?>

    <?php // echo $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'added_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
