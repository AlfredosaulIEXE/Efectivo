<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\search\CommonModel;

$common = new CommonModel();
$address = $common->getAddress($lead->id, $entity_type);
$contact = $common->getContact($lead->id, $entity_type);

?>
    <input type="hidden" name="contact_id" value="<?php echo $contact->id; ?>">
    <input type="hidden" name="address_id" value="<?php echo $address->id; ?>">
    <div class="row">

        <?php if ($entity_type != 'lead.job'): ?>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', 'Mobile')?></label>
                <input type="text" name="contact[mobile]" class="form-control" value="<?=$contact->mobile?>" placeholder="<?=Yii::t('app', 'Enter Mobile')?>">
            </div>
        </div>

        <?php endif; ?>

        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', $entity_type != 'lead.job' ? 'Phone' : 'Phone Job')?></label>
                <input type="text" name="contact[phone]" class="form-control" value="<?=$contact->phone?>" placeholder="<?=Yii::t('app', 'Enter Phone')?>">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', 'Phone Ext')?></label>
                <input type="text" name="contact[phone_ext]" class="form-control" value="<?=$contact->phone_ext?>" placeholder="<?=Yii::t('app', 'Enter Phone Ext')?>">
            </div>
        </div>

        <?php if ($entity_type == 'lead.job'): ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app', 'Email')?></label>
                    <input type="text" name="contact[email]" class="form-control" value="<?=$contact->email?>" placeholder="<?=Yii::t('app', 'Enter Email')?>">
                </div>
            </div>
        <?php endif; ?>

    </div>
<?php if ($entity_type != 'lead.job'): ?>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', 'Email')?></label>
                <input type="text" name="contact[email]" class="form-control" value="<?=$contact->email?>" placeholder="<?=Yii::t('app', 'Enter Email')?>">
            </div>
        </div>
    </div>
<?php endif; ?>
    <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', $entity_type === 'lead.job' ? 'Job Address' : 'Personal Address'); ?></h3>
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

	 				<!--<div class="col-sm-4">

						<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'City').'</label>

						'.Html::dropDownList('address[city_id]',$address->city_id,

            ArrayHelper::map(City::find()->where('state_id=' . ($address->state_id ? $address->state_id : 0))->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--Seleccionar--','class'=>'form-control js-city-input']  ).'</div></div>--></div>';

    ?>