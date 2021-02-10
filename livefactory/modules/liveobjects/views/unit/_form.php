<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;


/**
 * @var yii\web\View $this
 * @var livefactory\models\UnitGenerate $model
 * @var yii\widgets\ActiveForm $form
 */
$form = ActiveForm::begin([

    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']],  //this helps to show icons in validation messages

]);
?>
<?php

try {

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => [
            'name' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'name'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter name') . '...',
                    'maxlength' => 255
                ]
            ],


            'description' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'description'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter description') . '...',
                    'maxlength' => 255
                ]
            ],

        ]
    ]);

} catch (Exception $e) {}
?>
