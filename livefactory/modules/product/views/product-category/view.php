<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\CustomerType $model
 */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
	$(document).ready(function(e) {
		if(<?=$_GET['id']?>){
        	$('#productcategory-name').attr('readonly',true);
		} 
    });
</script>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=$this->title." ".Yii::t('app', 'is Added')?> </div>
<?php	}
?>
<div class="product-category-view">
    <!--<div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>'Product Category - '.$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
  //          'id',
            'name',
            'label',
          //  'sort_order',
            //'status',
			['attribute'=>'active','value' => $model->active?Yii::t('app', 'Yes'): Yii::t('app', 'No'), 'type'=>DetailView::INPUT_DROPDOWN_LIST,'items'=>array(''=>'--'.Yii::t('app', 'Select').'--','0'=> Yii::t('app', 'No'),'1'=>  Yii::t('app', 'Yes'))],
           // 'created_at',
           // 'updated_at',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
