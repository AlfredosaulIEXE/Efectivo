<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use livefactory\models\User;
use livefactory\models\Payment;
?>
<?php

$paymentTypes = Payment::getTypes(true);
$paymentOrigins = [
    1 => 'Efectivo',
    2 => 'Transferencia',
    3 => 'Depósito Bancario',
    4 => 'Tarjeta de Crédito/Débito',
    5 => 'Cheque',
];
$agents = ArrayHelper::map(User::find()->where('office_id = ' . $model->office_id)->orderBy('first_name')->asArray()->all(), 'id', 'first_name');
$generator = isset($payment_model) ? User::findOne($payment_model->generator_id) : User::findOne($model->lead_owner_id);
$can_change_generator = Yii::$app->user->can('Payment.Update') && $payment_model;
?>

<div class="modal fade bs-example-modal-lg paymentab">
    <?php if (empty($payment_model)) { ?>
    <form method="post" id="paymentform" action="index.php?r=sales/lead/paymentinsert&id=<?php echo $_REQUEST['id'];?>" enctype="multipart/form-data">
        <?php
        } else { ?>
        <form method="post" id="paymentform" action="index.php?r=sales/lead/paymentupdate&id=<?php echo $_REQUEST['id'];?>"  enctype="multipart/form-data">
            <?php } ?>

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

            <input type="hidden" name="payment_id" value="<?=$payment_model->id?>">

            <input type="hidden" name="payment" value="true">

            <input type="hidden" name="lead_id" value="<?=$model->id?>">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <a class="close"  href="index.php?r=sales/lead/view&id=<?php echo $_REQUEST['id'] ?>">&times;</a> <!--check-->

                        <h4 class="modal-title">Registrar movimiento</h4>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="" class="control-label">Tipo de Movimiento</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1">Cargo</option>
                                        <option value="2">Abono</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Payment Type')?></label>
                                        <select name="type" class="form-control" required>
                                            <option><?='--' . Yii::t('app', 'Payment Type') . '--'?></option>
                                            <?php
                                            foreach ($paymentTypes as $type_id => $type) {
                                                if ($type_id == $payment_model->type ){
                                                    echo "<option value='$type_id' selected>$type</option>";
                                                }else {
                                                    echo "<option value='$type_id'>$type</option>";
                                                }
                                            }?>
                                        </select>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Amount')?></label>

                                    <input name="amount" step="any" data-validation="required" class="form-control currency" value="<?=$payment_model->amount?>" required>

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Date')?></label>
                                    <input type="date" name="date" class="form-control" value="<?= empty($payment_model->date) ? '' : (date('Y-m-d',strtotime($payment_model->date)))?>" max="<?=date('Y-m-d')?>" required>

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Note')?></label>

                                    <input type="text" name="note" data-validation="required" class="form-control" value="<?=$payment_model->note?>">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Code')?></label>
                                    <input type="text" name="code" class="form-control" value="<?=$payment_model->code?>">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label">Generó</label>
                                    <select name="generator_id" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($agents as $a_id => $name): ?>
                                            <option value="<?= $a_id ?>"<?php if($a_id == $generator->id): ?> selected<?php endif; ?>><?=$name?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Payment Origin')?></label>
                                    <select name="origin" class="form-control" required>
                                        <option value=""><?='--' . Yii::t('app', 'Payment Origin') . '--'?></option>
                                        <?php
                                        for ($i=1; $i<5; $i++) {
                                            if ($i == $payment_model->origin){
                                                echo "<option value='$i' selected>$paymentOrigins[$i]</option>";

                                            }else {
                                                echo "<option value='$i'>$paymentOrigins[$i]</option>";
                                            }
                                        }?>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <?php
                                    if(Yii::$app->params['FILE_SIZE']=="0")
                                        $limit = "No Limit";
                                    else
                                        $limit = Yii::$app->params['FILE_SIZE']."MB";
                                    ?>
                                    <label><?=Yii::t('app', 'Attachment (Max allowed size:').' '.$limit.')'?></label>
                                    <input type="file" name="attach" class="form-control" value="">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label">Co-generador</label>
                                    <select name="co_generator_id" class="form-control" id="co_generator_id" <?php /*if ( ! $can_change_generator) echo ' disabled="disabled"'*/?>>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($agents as $a_id => $name): ?>
                                            <option value="<?= $a_id ?>"<?php if(isset($payment_model) && $a_id == $payment_model->co_generator_id): ?> selected<?php endif; ?>><?=$name?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary add_appointmentae btn-sm">

                            <i class="fa fa-road"></i><?=Yii::t('app', 'Save')?> </button>

                        <a class="btn btn-default btn-sm" href="index.php?r=sales/lead/view&id=<?php echo $_REQUEST['id'] ?>">Cerrar</a> <!--check -->
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </form>

</div><!-- /.modal -->



