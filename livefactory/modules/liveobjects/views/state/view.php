<?php



use yii\helpers\Html;

use kartik\detail\DetailView;

use kartik\datecontrol\DateControl;
use livefactory\models\Country;
use yii\helpers\ArrayHelper;


/**

 * @var yii\web\View $this

 * @var livefactory\models\State $model

 */



$this->title = $model->state;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=$this->title." ".Yii::t('app', 'is Added')?> </div>
<?php	}
?>
<div class="state-view">

    <!--<div class="page-header">

        <h1><?= Html::encode($this->title) ?></h1>

    </div>-->





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

           // 'id',

            'state',

            'state_code',

           // 'country_id',
['attribute'=>'country_id','type'=>DetailView::INPUT_DROPDOWN_LIST,'items'=>ArrayHelper::map(Country::find()->orderBy('country')->asArray()->all(), 'id', 'country'),'options' => [ 
                                                'prompt' => '--Select Country--'
                                        ] ],
            //'active',

			['attribute'=>'active','value' => $model->active?Yii::t('app', 'Yes'):Yii::t('app', 'No'), 'type'=>DetailView::INPUT_DROPDOWN_LIST,'items'=>array(''=>'--Select--','0'=>Yii::t('app', 'No'),'1'=>Yii::t('app', 'Yes'))]

           // 'added_at',

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

