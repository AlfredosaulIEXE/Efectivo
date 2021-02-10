<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Queue $model
 */
?>
<option value="">--<?=Yii::t ('app','Ticket Category 2')?>--</option>
<?php
foreach($dataReader as $category){?>
<option value="<?=$category['id']?>" <?=$category['id']==$ticket_category_id_2?'selected':''?>><?=$category['label']?> </option>
<?php
}?>