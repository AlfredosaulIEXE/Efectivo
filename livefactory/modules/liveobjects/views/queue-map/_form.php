<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\Department;
use livefactory\models\TicketCategory;
use livefactory\models\Queue;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var livefactory\models\QueueMap $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="queue-map-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
	'department_id' => [ 
							'type' => Form::INPUT_DROPDOWN_LIST,
							'columnOptions'=>['colspan'=>1],
							'items'=>ArrayHelper::map(Department::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label')  , 
							'options' => [ 
									'prompt' => '--'.Yii::t('app', 'Select Department').'--'
							] 
					],
	'ticket_category_id_2' => [ 
							'type' => Form::INPUT_DROPDOWN_LIST,
							'columnOptions'=>['colspan'=>1],
							'items'=>array()  , 
							'options' => [ 
									'prompt' => '--'.Yii::t('app', 'Select  Category').'--'
							] 
					],
	'ticket_category_id_2_id' => [ 
							'type' => Form::INPUT_DROPDOWN_LIST,
							'columnOptions'=>['colspan'=>1],
							'items'=>array()  , 
							'options' => [ 
									'prompt' => '--'.Yii::t('app', 'Select Sub Category').'--'
							] 
					],
	'queue_id' => [ 
							'type' => Form::INPUT_DROPDOWN_LIST,
							'columnOptions'=>['colspan'=>1],
							'items'=>array()  , 
							'options' => [ 
									'prompt' => '--'.Yii::t('app', 'Select Queue').'--'
							] 
					],

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); 
	?>

</div>
