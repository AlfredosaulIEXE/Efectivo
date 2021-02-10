<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Currency $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Currency',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5> <?php echo $this->title;?></h5>
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
        <div class="currency-create">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        
        </div>
   </div>
</div>
