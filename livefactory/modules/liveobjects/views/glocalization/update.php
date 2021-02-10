<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model livefactory\models\Glocalization */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Glocalization',
]) . ' ' . $model->language;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Glocalizations'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->language, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="glocalization-update">

     <div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5> <?=$this->title ?></h5>



            <div class="ibox-tools">



                <a class="collapse-link">

                    <i class="fa fa-chevron-up"></i>

                </a>

               

                <a class="close-link">

                    <i class="fa fa-times"></i>

                </a>

            </div>

</div>

         <div class="ibox-content">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div></div></div>
