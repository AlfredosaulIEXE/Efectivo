<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\EmailTemplate $model
 */

$this->title = Yii::t('app', 'Create Email Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-template-create">
    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= $this->title ?> <small class="m-l-sm"><?=Yii::t('app', 'Enter Email Template Name, Subject & Body')?></small></h5>
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
