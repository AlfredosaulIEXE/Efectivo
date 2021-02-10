<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\PaymentDetails $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="payment-details-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'invoice_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Invoice ID...']],

            'payment_date' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

            'amount' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Amount...', 'maxlength' => 10]],

            'payment_method' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Payment Method...', 'maxlength' => 25]],

            'notes' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Notes...', 'maxlength' => 255]],

            'added_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Added At...']],

            'updated_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Updated At...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
