<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
$type_appointment = [
    0 => 'En Oficina' ,
    1 => 'En llamada'
];
?>

<div class="modal fade bs-example-modal-lg appointmentae">
    <?php if (empty($appointment_model)) { ?>
    <form method="post" id="appointmentform" action="index.php?r=sales/lead/appointmentinsert&id=<?php echo $_REQUEST['id'];?>" enctype="multipart/form-data">
    <?php
    } else { ?>
    <form method="post" id="appointmentform" action="index.php?r=sales/lead/appointmentupdate&id=<?php echo $_REQUEST['id'];?>"  enctype="multipart/form-data">
    <?php } ?>

        <?php Yii::$app->request->enableCsrfValidation = true; ?>

        <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

        <input type="hidden" name="appointment_id" value="<?=$appointment_model->id?>">

        <input type="hidden" name="appointment" value="true">

        <input type="hidden" name="lead_id" value="<?=$model->id?>">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title"><?=Yii::t('app', 'Appointment')?></h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-4">

                            <div class="form-group">

                                <label class="control-label"><?=Yii::t('app', 'Description')?></label>

                                <input type="text" name="description" data-validation="required" class="form-control" value="<?=$appointment_model->description?>" required>

                            </div>

                        </div>

                        <div class="col-sm-4">

                            <div class="form-group">

                                <label class="control-label">Promesa</label>

                                <input type="text" name="amount" data-validation="required" class="form-control" value="<?=$appointment_model->amount?>" required>

                            </div>

                        </div>

                        <div class="col-sm-4">

                            <div class="form-group">

                                <label class="control-label"><?=Yii::t('app', 'Date')?></label>
                                <input type="date" name="date" class="form-control" value="<?=$appointment_model->date ? date('Y-m-d',strtotime($appointment_model->date)) : ''?>" required>

                            </div>

                        </div>

                        <div class="col-sm-4">

                            <div class="form-group">

                                <label class="control-label"><?=Yii::t('app', 'Time')?></label>
                                <input type="time" name="time" class="form-control" value="<?=$appointment_model->time ? date('H:i:s',strtotime($appointment_model->time)) : ''?>" required>

                            </div>

                        </div>


                        <?php if ($appointment_model->id): ?>
                        <div class="col-sm-4">

                            <div class="form-group">

                                <label class="control-label"><?=Yii::t('app', 'Status')?></label>

                                <select name="status" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1"<?=$appointment_model->status == 1 ? 'selected' : ''?>><?=Yii::t('app', 'Concreted')?></option>
                                    <option value="2"<?=$appointment_model->status == 2 ? 'selected' : ''?>><?=Yii::t('app', 'Not Concreted')?></option>
                                </select>

                            </div>

                        </div>
                        <?php endif; ?>
                        <div class="col-sm4 text-center ">
                            <div class="form-group">
                                <h4><strong>Tipo de cita</strong></h4>
                                <?php foreach ($type_appointment as $value => $text): ?>
                                    <input type="radio"  name="type_appointment"  value="<?=$value ?>" <?php if($value === $appointment_model->type): ?> checked="checked" <?php  endif; ?> <?php if($value == 1 && !isset($appointment_model->type)): ?> checked="checked" <?php  endif; ?><?=$text?> required> <label> <?=$text?>&nbsp; </label>
                                <?php endforeach;?>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary add_appointmentae btn-sm">

                        <i class="fa fa-road"></i><?=Yii::t('app', 'Save')?> </button>

                    <a type="button" class="btn btn-default btn-sm"   href="index.php?r=sales/lead/view&id= <?=$model->id ?>"><i class="fa fa-remove"></i><?=Yii::t('app', 'Close')?> </a>

                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </form>

</div><!-- /.modal -->



