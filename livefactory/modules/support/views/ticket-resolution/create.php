<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketResolution $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ticket Resolution',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Resolutions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-resolution-create">
	<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= $this->title?></h5>

                        <div class="ibox-tools">

						    <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

							<!--

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                                <i class="fa fa-wrench"></i>

                            </a>

							

                            <ul class="dropdown-menu dropdown-user">

                                <li><a href="#">Config option 1</a>

                                </li>

                                <li><a href="#">Config option 2</a>

                                </li>

                            </ul>

							-->

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

                    <div class="ibox-content">

							 <?= $this->render('_form', [
        						'model' => $model,
    						]) ?>

                    </div>

                </div>
   

</div>
