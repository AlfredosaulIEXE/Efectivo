<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Queue $model
 */
?>
<option value="">--<?=Yii::t ('app','Queue')?>--</option>
<?php
foreach($dataReader as $queue){?>
<option value="<?=$queue['id']?>" <?=$queue['id']==$queue_id?'selected':''?>><?=$queue['queue_title']?> </option>
<?php
}?>