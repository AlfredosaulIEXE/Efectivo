<?php
use yii\helpers\Html;

use kartik\widgets\ActiveForm;
/**
 * @var yii\web\View $this
 * @var livefactory\models\UnitGenerate $model
 */

$this->title = Yii::t('app', 'Modificar Unidad Generadora', [
    'modelClass' => 'UnitGenerate',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'UnitGenerate'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="office-create">
        <div class="policy-cover-type-create">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> <?=$this->title ?></h5>

                    <div class="ibox-tools">

                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>

                        <a class="close-link" href="index.php?r=liveobjects/unit/index">
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
    </div>

<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])." ";
if (Yii::$app->user->can('Office.Delete')) {
    ?>
    <a href="index.php?r=liveobjects/unit/update&id=<?=$model->id?>&edit=t&active=<?=$model->active !='1'?'yes':'no'?>" onClick="return confirm('Are you Sure?')" class="btn <?=$model->active !='1'?'btn-primary btn-sm':'btn-danger btn-sm'?>"><?=Yii::t('app', $model->active !='1'?'Activar Unidad':'Desactivar Unidad')?></a>
<?php }
ActiveForm::end(); ?>