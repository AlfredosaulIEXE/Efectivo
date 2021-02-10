<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\TicketStatus $model
 */

$this->title = Yii::t('app', 'Create Ticket Status', [
    'modelClass' => 'Ticket Status',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-status-create">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5> <?=$this->title ?></h5>

            <div class="ibox-tools">

                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
               
                <a class="close-link" href="index.php?r=support/ticket-status/index">
                    <i class="fa fa-times"></i>
                </a>
            </div>
</div>
         <div class="ibox-content">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div></div></div>
