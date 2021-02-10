<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\Department;
use livefactory\models\TicketCategory;
use yii\helpers\ArrayHelper;
use dosamigos\ckeditor\CKEditor;
/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketCategory $model
 * @var yii\widgets\ActiveForm $form
 */

$dFlag=false;
if (!empty($_GET['parent_id']))
{
	$dFlag=true;
}

?>
<div class="ticket-category-form">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...', 'maxlength'=>255]], 
'label'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Label...', 'maxlength'=>255]], 
'active' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'Active',
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter State').' ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select Status').'--'
                                        ]
								], 
								
'department_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 
	'options'=>['placeholder'=>'Enter Department ID...','prompt'=>Yii::t('app','--Department--'), 'disabled'=>$dFlag],
	'items'=>ArrayHelper::map(Department::find()->where("active=1")->orderBy('sort_order')->all(),'id','name')], 
//'sort_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sort Order...']],  
///'description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Description...','rows'=> 6]],
//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 
//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 
    ]

    ]);
	if(!empty($_GET['parent_id'])){
		echo '<input type="hidden" value="'.$_GET['parent_id'].'"  name="TicketCategory[parent_id]" >';
		echo '<input type="hidden" value="'.$model->department_id.'"  name="TicketCategory[department_id]" >';
	}
	if(empty($_GET['id'])){
		
		
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
'description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Description...','rows'=> 6]],
    ]

    ]);
	$form->field ( $model, 'description' )->widget ( CKEditor::className (), [ 
						'options' => [ 
								'rows' => 10 
						],
						'preset' => 'custom',
						'preset' => 'custom',
				'clientOptions' => [
				'height' => 200,
				'toolbarGroups' => [
				['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
				['name' => 'editing', 'groups' => [ 'find', 'selection', 'spellchecker']],
				['name'=>'links','groups'=>['links']],
				['name'=>'insert','groups'=>['insert']],
				['name' => 'tools'],
				['name'=>'document','groups'=>['mode', 'document', 'doctools']],
				'/',
				['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
				['name' => 'paragraph', 'groups' => ['templates', 'list']],
				['name'=>'paragraph','groups'=>['list', 'indent', 'blocks']],
				['name'=>'styles','groups'=>['styles']],
				['name'=>'about','groups'=>['about']]
		
		],
		
    ]
				] );
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
     
	}
	
	ActiveForm::end();
	?>
</div>
