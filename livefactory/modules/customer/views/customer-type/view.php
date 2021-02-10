<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\CustomerType $model
 */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=$this->title." ".Yii::t('app', 'is Added')?> </div>
<?php	}
?>
<div class="customer-type-view">
    <!--<div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>'Customer Type - '.$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
  //          'id',
            'type',
            'label',
         //   'sort_order',
            //'status',
			['attribute'=>'active','value' => $model->active?Yii::t('app', 'Active'):Yii::t('app', 'Inactive')],
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
        'enableEditMode'=>false,
    ]) ?>

</div>
