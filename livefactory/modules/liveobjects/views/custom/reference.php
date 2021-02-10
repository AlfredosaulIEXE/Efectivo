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