<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Estimate',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index','entity_type'=>$model->entity_type]];
$this->params['breadcrumbs'][] = $this->title;
include_once('script.php');
?>
<div class="estimate-create">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?></h5>

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
								'taxList'=>$taxList,
							]) ?>

                    </div>

                </div>
</div>
