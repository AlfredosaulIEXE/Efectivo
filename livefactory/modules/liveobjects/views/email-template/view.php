<?php



use yii\helpers\Html;

use kartik\detail\DetailView;

use kartik\datecontrol\DateControl;



/**

 * @var yii\web\View $this

 * @var livefactory\models\EmailTemplate $model

 */



$this->title = $model->template_name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=$this->title." ".Yii::t('app', 'is Added')?> </div>
<?php	}
?>
<div class="email-template-view">

	<!--

    <div class="page-header">

        <h1><?= Html::encode($this->title) ?></h1>

    </div>

	-->





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

    //        'id',

            'template_name',

            'template_subject:ntext',

            'template_body:ntext',

     //       'added_at',

     //       'updated_at',

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

