<?php
use yii\helpers\ArrayHelper;
use livefactory\models\Office;
use livefactory\models\LeadSource;
use livefactory\models\Loan;
/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel
 *

 */
$this->title = 'Carga de leads por excel';
$this->params ['breadcrumbs'] [] = $this->title;

$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$sources = ArrayHelper::map(LeadSource::find()->orderBy('sort_order')->asArray()->all(), 'id', 'label');
$credit = ArrayHelper::map();
$leadLoans = array();
foreach (ArrayHelper::map(Loan::find()->where("active=1")->orderBy('id')->asArray()->all(), 'id', 'description') as $key => $ld) {
    $leadLoans[$key] = $ld;
}
?>
<?php if ($error): ?>
    <div class="alert alert-danger">
        <?=$error;?>
    </div>
<?php endif; ?>
<?php if ($registers > 0 || $repeats > 0): ?>
    <div class="alert alert-success">
        <span>El archivo se cargó correctamente con <strong><?=$registers?></strong> leads registrados de los cuales <strong><?=$repeats;?></strong> son repetidos.</span>
        <?php if ($fails > 0): ?>
        <span>Errores de carga  <?= $fails?></span>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="ibox">
    <div class="ibox-title">
        <h5>Carga de leads por excel</h5>
    </div>
    <div class="ibox-content">
        <div class="container">
            <div class="row">
                <div >
                    <form action="" method="post">
                        <input type="hidden" value="upload-lead" name="lead-import">
                        <button type="submit" class="btn btn-success"  name="lead">Descarga de Plantilla de Carga de Leads</button>

                    </form>
                </div>
                <br>
                <div >
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="file" name="excel" accept=".csv" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="office_id" class="form-control" required>
                                        <option value="">Seleccionar sucursal...</option>
                                        <?php foreach ($offices as $o_id => $name): ?>
                                            <option value="<?= $o_id ?>"<?php if($o_id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="source_id" class="form-control" required>
                                        <option value="">Seleccionar medio...</option>
                                        <?php foreach ($sources as $id => $name): ?>
                                            <option value="<?= $id ?>"<?php if($id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control form-arrow" name="creditType" required="">
                                        <optgroup label="">
                                            <option value="" selected="">Tipo de Crédito</option>
                                            <?php foreach ($leadLoans as $loans => $name): ?>
                                            <option value="<?= $loans?>"><?= $name?></option>
                                            <?php endforeach;?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">¡Cargar ahora!</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>
