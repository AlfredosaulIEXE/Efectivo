<?php



use yii\helpers\Html;

use kartik\detail\DetailView;

use kartik\datecontrol\DateControl;

use kartik\widgets\ActiveForm;



/**

 * @var yii\web\View $this

 * @var common\models\UserRole $model

 */



$this->title = $model->label;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Roles'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script>

	$(document).ready(function(e) {

		if(<?=$_GET['id']?>){

        	$('#userrole-role').attr('readonly',true);

		} 

    });

</script>

<div class="user-role-view">

   <!-- <div class="page-header">

        <h1><?= Html::encode($this->title) ?></h1>

    </div>-->





    <?= DetailView::widget([

            'model' => $model,

            'condensed'=>false,

            'hover'=>true,

            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,

            'panel'=>[

            'heading'=> Yii::t('app', 'User Role').' - '.$this->title,

            'type'=>DetailView::TYPE_INFO,

        ],

        'attributes' => [

    //        'id',

            'role',

            'label',

           // 'status',

			['attribute'=>'active','value'=> $model->active?Yii::t('app', 'Active'):Yii::t('app', 'Inactive'), 'type'=>DetailView::INPUT_DROPDOWN_LIST,'items'=>array(''=>'--Select--','0'=>Yii::t('app', 'Inactive'),'1'=>Yii::t('app', 'Active'))]

  //          'created_at',

   //         'updated_at',

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

