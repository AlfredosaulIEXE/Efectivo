<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\UserType;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Announcement $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="announcement-form">

        <?php $form = ActiveForm::begin(['id'=>'announcement-form','type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <?= $form->field($model, 'message')->textArea(['maxlength' => 100, 'placeholder'=>'Enter Message Details']) ?>

	<?php echo $form->field($model, 'is_status')->dropDownList(['0' => 'Active', '1' => 'Inactive'],['prompt' => 'Select Status']); ?>

    
    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>


</div>
