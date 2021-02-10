<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Queue $model
 */

$this->title = Yii::t('app', 'Create Queue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Queue'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-create">
   <div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= $this->title?></h5>

                        <div class="ibox-tools">

						    <a class="collapse-link" >

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

                            <a class="close-link" href="index.php?r=liveobjects/queue/index">

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
