<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\User;
/**
 * @var yii\web\View $this
 * @var livefactory\models\AuthAssignment $model
 */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<div class="auth-assignment-view">


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
            'item_name',
            //'user_id',
			[
				'attribute'=>'user_id',
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>ArrayHelper::map(User::find()->where("active=1")->asArray()->all(), 'id',function ($user, $defaultValue) {
       		 $username=$user['username']?$user['username']:$user['email'];
       		 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    }),
				'value'=>$model->user->first_name. ' ' .$model->user->last_name.' ('.$model->user->username.')',
		]
            //'created_at',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->item_name],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
