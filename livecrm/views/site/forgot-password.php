<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
 <link href="css/bootstrap.css" rel="stylesheet">
 <link href="css/style.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">

        <div>
            <div>
                <h1 class="logo-name"><?= Html::encode("Live") ?></h1>
            </div>
            <!--<h3>Welcome to LiveCRM</h3>-->
            <?php if($error){?>
				<div class="alert alert-danger" role="alert" style="color:red"><?=$error?></div>
			<?php }	?>
            <?php if($msg){?>
				<div class="alert alert-success" role="alert"><?=$msg?></div>
			<?php }	?>
            <p><?=Yii::t('app', 'Please enter your registered email:')?></p>
            <?php $form = ActiveForm::begin(['id' => 'forgot-form', 'class' => 'm-t']); ?>

	
                <?= $form->field($model, 'email') ?>
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?><br/><br/>
                <a href="index.php?r=site/login" class="btn btn-sm btn-block" style="text-decoration:none"><?=Yii::t('app', 'Back to login')?></a>
		

            <?php ActiveForm::end(); ?>
            <p class="m-t"> <small>LiveCRM <?=Yii::t('app', 'framework based on')?> Yii <?=Yii::t('app', 'and')?> Bootstrap 3 &copy; 2015</small> </p>
        </div>
</div>
