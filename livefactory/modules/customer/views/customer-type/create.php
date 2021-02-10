<?php



use yii\helpers\Html;



/**

 * @var yii\web\View $this

 * @var livefactory\models\CustomerType $model

 */



$this->title = Yii::t('app', 'Create Customer Type');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Type'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="customer-type-create">

     <div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5> <?=$this->title ?></h5>



            <div class="ibox-tools">



                <a class="collapse-link">

                    <i class="fa fa-chevron-up"></i>

                </a>

               

                <a class="close-link" href="index.php?r=customer/customer-type/index">

                    <i class="fa fa-times"></i>

                </a>

            </div>

</div>

         <div class="ibox-content">

    <?= $this->render('_form', [

        'model' => $model,

    ]) ?>



</div></div></div>

