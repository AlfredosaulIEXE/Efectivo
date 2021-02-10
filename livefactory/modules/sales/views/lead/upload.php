<?php

use yii\helpers\ArrayHelper;
use livefactory\models\Office;
use livefactory\models\LeadSource;
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
?>
<?php if ($error): ?>
<div class="alert alert-danger">
    <?=$error;?>
</div>
<?php endif; ?>
<?php if ($registers > 0 || $repeats > 0): ?>
    <div class="alert alert-success">
        El archivo se cargó correctamente con <strong><?=$registers?></strong> leads registrados de los cuales <strong><?=$repeats;?></strong> son repetidos.
    </div>
<?php endif; ?>
<div class="ibox">
    <div class="ibox-title"><h5>Carga de leads por excel</h5></div>
    <div class="ibox-content">
        <div class="alert alert-warning">Esta herramienta funciona únicamente con los archivos de excel que descarga Facebook, no intentarlo con otro tipo de formato.</div>
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
                        <select class="form-control" name="loan_amount" required>
                            <option value="" selected="">Monto a solicitar...</option>
                            <option value="50000">$50,000</option>
                            <option value="60000">$60,000 a $120,000</option>
                            <option value="120000">$120,000 a $600,000</option>
                            <option value="600000">$600,000 a $1,200,000</option>
                            <option value="1200000">$1,200,000 a $3,000,000</option>
                            <option value="3000000">$3,000,000 a $6,000,000</option>
                            <option value="6000000">Más de $6,000,000</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" name="type_csv" required>
                            <option value="" selected="">Tipo de Archivo a cargar</option>
                            <option value="1">Archivo de Facebook</option>
                            <option value="2">Archivo de Google Drive</option>
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
