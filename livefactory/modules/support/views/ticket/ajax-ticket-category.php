<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Queue $model
 */
?>
<option value="">--<?=Yii::t ('app','Ticket Category 1')?>--</option>
<?php
foreach($dataReader as $category){?>
<option value="<?=$category['id']?>" <?=$category['id']==$ticket_category_id_1?'selected':''?>><?=$category['label']?> </option>
<?php
}?>