<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var livefactory\models\search\Ticket $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ticket-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ticket_title') ?>

    <?= $form->field($model, 'ticket_description') ?>

    <?= $form->field($model, 'ticket_type_id') ?>

    <?= $form->field($model, 'ticket_priority_id') ?>

    <?php // echo $form->field($model, 'ticket_impact_id') ?>

    <?php // echo $form->field($model, 'queue_id') ?>

    <?php // echo $form->field($model, 'assigned_user_id') ?>

    <?php // echo $form->field($model, 'referenced_ticket_id') ?>

    <?php // echo $form->field($model, 'ticket_status_id') ?>

    <?php // echo $form->field($model, 'escalated_flag') ?>

    <?php // echo $form->field($model, 'added_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
