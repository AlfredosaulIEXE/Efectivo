<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Task $model
 */
?>
<option value="">--<?=Yii::t ('app','Select Project')?>--</option>
<?php
foreach($dataReader as $project){?>
<option value="<?=$project['id']?>" <?=$project['id']==$project_id?'selected':''?>><?=$project['project_name']?></option>
<?php
}?>