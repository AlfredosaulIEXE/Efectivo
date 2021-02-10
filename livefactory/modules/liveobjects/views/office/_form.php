<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use livefactory\models\Address;
use livefactory\models\Contact;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Office $model
 * @var yii\widgets\ActiveForm $form
 */

$address = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'office']);
$address = $address ? $address : new Address();
$contact = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'office']);
$contact = $contact ? $contact : new Contact();
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
    $(document).ready(function () {
        $('.js-state-input').change(function (e) {
            $.post('index.php?r=liveobjects/address/ajax-load-cities&state_id=' + $(this).val(), function (result) {
                $(e.target).closest('.row').find('.js-city-input').html(result);
            })
        })
    });
</script>
<?php

$form = ActiveForm::begin([

    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']],  //this helps to show icons in validation messages

]);
?>

<input type="hidden" name="address_id" value="<?=$address->id; ?>">
<input type="hidden" name="contact_id" value="<?=$contact->id; ?>">
<div class="row">
    <div class="col-md-4">
        <div id="picture_preview"></div>
        <label><?php echo Yii::t('app', 'Photo'); ?>
            <?php if(file_exists('../office/'.$model->id.'.png')){?>
                <a href="index.php?r=liveobjects/office/update&id=<?=$model->id?>&edit=t&img_del=yes" class="btn btn-danger btn-xs" onClick="return confirm('Are you Sure!')"><?php echo Yii::t('app', 'Delete'); ?></a>
            <?php } ?>
        </label><br/>
        <?php
        if(file_exists('../office/'.$model->id.'.png')){?>
            <img src="../office/<?=$model->id?>.png?v<?=$model->updated_at?>" height="170" class="upload  img-responsive">
        <?php }else{?>
            <img src="../office/nophoto.jpg" height="170" class="upload  img-responsive">
        <?php }
        ?>
        <input type="file" name="office_image" class="inp">
        <br/><br/>
    </div>
    <div class="col-md-3">
            <label>Altura de imagen en documentos(Carta compromiso,Contrato , Viabilidad)</label>
            <input type="number" id="height_document" name="height_document" class="form-control" placeholder="<?= isset($model->height_document) ? $model->height_document : ''?>">
            <label>Defecto 140</label>
            <label>Altura de imagen en documentos(impresión de pagos, Carátula)</label>
            <input type="number" id="height_custom"  name="height_custom" class="form-control" placeholder="<?= isset($model->height_custom) ? $model->height_custom : ''?>">
            <label>Defecto 90</label>
    </div>
</div>


<?php

try {

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => [
            'code' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'Code'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter Office Code') . '...',
                    'maxlength' => 255
                ]
            ],
            'description' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'Office Description'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter Office Description') . '...',
                    'maxlength' => 255
                ]
            ],
            'rfc' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'RFC'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter RFC') . '...',
                    'maxlength' => 13
                ]
            ],
            'business_name' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'Business Name'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter Business Name') . '...',
                    'maxlength' => 255
                ]
            ],
            'website' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'Website'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter Website') . '...',
                    'maxlength' => 255
                ]
            ],
            'weekly_goal' => [
                'type' => Form::INPUT_TEXT,
                'label' => Yii::t('app', 'Weekly Goal'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Enter Weekly Goal') . '...',
                    'class' => 'currency',
                    'maxlength' => 13
                ]
            ]
        ]
    ]);

} catch (Exception $e) {}
?>
<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Phone')?></label>
            <input type="text" name="contact[phone]" class="form-control" value="<?=$contact->phone?>" placeholder="<?=Yii::t('app', 'Enter Phone')?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Phone Ext')?></label>
            <input type="text" name="contact[phone_ext]" class="form-control" value="<?=$contact->phone_ext?>" placeholder="<?=Yii::t('app', 'Enter Phone Ext')?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Email')?></label>
            <input type="text" name="contact[email]" class="form-control" value="<?=$contact->email?>" placeholder="<?=Yii::t('app', 'Enter Email')?>">
        </div>
    </div>
</div>
<h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Personal Address'); ?></h3>
<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Address 1')?></label>
            <input type="text" name="address[address_1]" data-validation="required" class="form-control" value="<?=$address->address_1?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Num Ext')?></label>
            <input type="text" name="address[num_ext]" class="form-control" value="<?=$address->num_ext?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Num Int')?></label>
            <input type="text" name="address[num_int]" class="form-control" value="<?=$address->num_int?>">
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Address Block')?></label>
            <input type="text" name="address[block]" class="form-control" value="<?=$address->block?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Zipcode')?>:</label>
            <input type="text" name="address[zipcode]" data-validation="required" class="form-control"  value="<?=$address->zipcode?>" placeholder="<?=Yii::t('app', 'Enter Zipcode')?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Delegation')?>:</label>
            <input type="text" name="address[delegation]" data-validation="required" class="form-control"  value="<?=$address->delegation?>">
        </div>
    </div>
</div>
<?php

echo '<div class="row">

						<div class="col-sm-4">

							<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'Country').'</label>

						'.Html::dropDownList('address[country_id]',($address->country_id ? $address->country_id : 156),

        ArrayHelper::map(Country::find()->orderBy('country')->asArray()->all(), 'id', 'country'), ['prompt' => '--Seleccionar--','class'=>'form-control','data-validation'=>'required', 'disabled' => 'disabled' ]  ).'</div></div>

	 					<div class="col-sm-4">

						<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'State').'</label>

						'.Html::dropDownList('address[state_id]',$address->state_id,

        ArrayHelper::map(State::find()->where('country_id=' . ($address->country_id ? $address->country_id : 156))->orderBy('state')->asArray()->all(), 'id', 'state'), ['prompt' => '--Seleccionar--','class'=>'form-control js-state-input']  ).'</div></div>

	 				<div class="col-sm-4">

						<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'City').'</label>

						'.Html::dropDownList('address[city_id]',$address->city_id,

        ArrayHelper::map(City::find()->where('state_id=' . ($address->state_id ? $address->state_id : 0))->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--Seleccionar--','class'=>'form-control js-city-input']  ).'</div></div></div>';

?>
<h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Office Manager'); ?></h3>
<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'First Name')?></label>
            <input type="text" name="contact[first_name]" class="form-control" value="<?=$contact->first_name?>" placeholder="<?=Yii::t('app', 'Enter First Name')?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Last Name')?></label>
            <input type="text" name="contact[last_name]" class="form-control" value="<?=$contact->last_name?>" placeholder="<?=Yii::t('app', 'Enter Last Name')?>">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Middle Name')?></label>
            <input type="text" name="contact[middle_name]" class="form-control" value="<?=$contact->middle_name?>" placeholder="<?=Yii::t('app', 'Enter Middle Name')?>">
        </div>
    </div>

</div>

<h3 class="page-header" style="margin-top: 1em">Registro notarial</h3>
<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Nombre del Notario Público</label>
            <input type="text" name="Office[notary]" class="form-control" value="<?=$model->notary?>" placeholder="">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label>No. de Notaría</label>
            <input type="text" name="Office[notary_number]" class="form-control" value="<?=$model->notary_number?>" placeholder="">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label>Ciudad dónde se ubica la Notaría</label>
            <input type="text" name="Office[notary_state]" class="form-control" value="<?=$model->notary_state?>" placeholder="">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Número de Instrumento Notarial</label>
            <input type="text" name="Office[notary_id]" class="form-control" value="<?=$model->notary_id?>" placeholder="">
        </div>
    </div>

</div>

    
<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])." ";
if (Yii::$app->user->can('Office.Delete')) {
?>
<a href="index.php?r=liveobjects%2Foffice%2Fupdate&id=<?=$model->id?>&edit=t&active=<?=$model->active !='1'?'yes':'no'?>" onClick="return confirm('Are you Sure?')" class="btn <?=$model->active !='1'?'btn-primary btn-sm':'btn-danger btn-sm'?>"><?=Yii::t('app', $model->active !='1'?'Activar Oficina':'Desactivar Oficina')?></a>
<?php }
ActiveForm::end(); ?>
