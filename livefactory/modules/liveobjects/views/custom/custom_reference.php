<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\search\CommonModel;

$common = new CommonModel();
$contact = $common->getContact($lead->id, $entity_type);
?>
<div class="row">

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'First Name')?></label>
            <input type="text" name="contact<?=$entity_type?>[first_name]" class="form-control" value="<?=$contact->first_name?>" placeholder="<?=Yii::t('app', 'Enter First Name')?>" required>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Last Name')?></label>
            <input type="text" name="contact<?=$entity_type?>[last_name]" class="form-control" value="<?=$contact->last_name?>" placeholder="<?=Yii::t('app', 'Enter Last Name')?>" required>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label"><?=Yii::t('app', 'Middle Name')?></label>
            <input type="text" name="contact<?=$entity_type?>[middle_name]" class="form-control" value="<?=$contact->middle_name?>" placeholder="<?=Yii::t('app', 'Enter Middle Name')?>" required>
        </div>
    </div>
    <input type="hidden" name="contact_id<?=$entity_type?>" value="<?php echo $contact->id; ?>">
    <input type="hidden" name="address_id<?=$entity_type?>" value="<?php echo $address->id; ?>">


        <?php if ($entity_type != 'lead.job'): ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app', 'Mobile')?></label>
                    <input type="text" name="contact<?=$entity_type?>[mobile]" class="form-control" value="<?=$contact->mobile?>" placeholder="<?=Yii::t('app', 'Enter Mobile')?>" required>
                </div>
            </div>

        <?php endif; ?>

        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label"><?=Yii::t('app', $entity_type != 'lead.job' ? 'Phone' : 'Phone Job')?></label>
                <input type="text" name="contact<?=$entity_type?>[phone]" class="form-control" value="<?=$contact->phone?>" placeholder="<?=Yii::t('app', 'Enter Phone')?>" required>
            </div>
        </div>
</div>