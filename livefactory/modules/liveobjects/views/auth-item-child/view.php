<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\AuthItem;

use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var livefactory\models\AuthItemChild $model
 */

$this->title = $model->parent;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Item Children'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<div class="auth-item-child-view">


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
           // 'parent',
		   [
				'attribute'=>'parent',
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>ArrayHelper::map(AuthItem::find()->asArray()->all(), 'name','name'),
		],
           // 'child',
		   [
				'attribute'=>'child',
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>ArrayHelper::map(AuthItem::find()->asArray()->all(), 'name','name')
		]
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->parent],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
