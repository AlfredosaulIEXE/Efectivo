<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\search\CommonModel;

$common = new CommonModel();
$contact = $common->getContact($lead->id, 'lead.spouse');
?>
    <input type="hidden" name="contact_id" value="<?php echo $contact->id; ?>">
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
    <div class="row">

        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', 'Mobile')?></label>
                <input type="text" name="contact[mobile]" class="form-control" value="<?=$contact->mobile?>" placeholder="<?=Yii::t('app', 'Enter Mobile')?>">
            </div>
        </div>

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

    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', 'Email')?></label>
                <input type="text" name="contact[email]" class="form-control" value="<?=$contact->email?>" placeholder="<?=Yii::t('app', 'Enter Email')?>">
            </div>
        </div>
    </div>

    <?php
    echo Html::submitButton(Yii::t('app', 'Update'), [
        'class' => 'btn btn-primary btn-sm  lead_submit'
    ]);
    ?>